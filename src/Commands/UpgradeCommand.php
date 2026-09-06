<?php

namespace RealRashid\SweetAlert\Commands;

use Illuminate\Console\Command;
use RealRashid\SweetAlert\Support\UpgradeChange;
use RealRashid\SweetAlert\Support\Upgrader;
use Symfony\Component\Finder\Finder;

/**
 * Upgrade Command - Migrates a v7 codebase to the v8 API.
 *
 * Most of what changed between v7 and v8 is additive, but a few call sites
 * changed shape. The dangerous one is html(): PHP ignores surplus arguments
 * to a userland method, so a v7 call keeps running and quietly renders the
 * title as the alert body. This command finds those and rewrites them.
 *
 * It writes nothing without being asked. --dry-run prints the diff and stops,
 * and a normal run asks for confirmation first.
 */
class UpgradeCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'alert:upgrade
                            {--dry-run : Show what would change without writing anything}
                            {--path=* : Limit the scan to these paths, relative to the project root}
                            {--force : Write the changes without asking}';

    /**
     * The console command description.
     */
    protected $description = 'Migrate v7 SweetAlert calls to the v8 API';

    /**
     * Where a Laravel application keeps code that might call this package.
     *
     * vendor/ and storage/ are never scanned: one is not yours to edit, and
     * the other is compiled output that is regenerated anyway.
     */
    protected array $defaultPaths = ['app', 'routes', 'resources/views', 'config', 'database'];

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $files = $this->filesToScan();

        if ($files === []) {
            $this->components->warn('No PHP or Blade files found to scan.');

            return self::SUCCESS;
        }

        $this->components->info(sprintf(
            '%s %d file%s for v7 SweetAlert calls...',
            $dryRun ? 'Checking' : 'Scanning',
            count($files),
            count($files) === 1 ? '' : 's'
        ));

        $planned = [];
        $warnings = [];

        foreach ($files as $file) {
            [$new, $changes] = $this->upgradeFile($file);

            if ($changes === []) {
                continue;
            }

            $applied = array_values(array_filter($changes, fn (UpgradeChange $c) => $c->applied));
            $notApplied = array_values(array_filter($changes, fn (UpgradeChange $c) => ! $c->applied));

            if ($applied !== []) {
                $planned[$file] = [$new, $applied];
            }

            if ($notApplied !== []) {
                $warnings[$file] = $notApplied;
            }
        }

        $this->report($planned, $warnings);

        if ($planned === []) {
            $this->newLine();
            $this->components->info('Nothing to rewrite.'.($warnings !== [] ? ' See the notes above.' : ''));

            return self::SUCCESS;
        }

        if ($dryRun) {
            $this->newLine();
            $this->components->info('Dry run — nothing was written. Re-run without --dry-run to apply.');

            return self::SUCCESS;
        }

        if (! $this->option('force') && ! $this->confirm('Write these changes?', false)) {
            $this->components->warn('Nothing was written.');

            return self::SUCCESS;
        }

        foreach ($planned as $file => [$new, $changes]) {
            file_put_contents($file, $new);
        }

        $this->newLine();
        $this->components->info(sprintf('Updated %d file%s.', count($planned), count($planned) === 1 ? '' : 's'));
        $this->components->warn('Review the diff and run your test suite before committing.');

        return self::SUCCESS;
    }

    /**
     * Run the right set of rules for the kind of file this is.
     *
     * @return array{0:string,1:list<UpgradeChange>}
     */
    protected function upgradeFile(string $path): array
    {
        $source = (string) file_get_contents($path);
        $upgrader = new Upgrader;

        $new = match (true) {
            str_ends_with($path, '.blade.php') => $upgrader->upgradeBlade($source),
            $this->isPackageConfig($path) => $upgrader->upgradeConfig($source),
            default => $upgrader->upgradePhp($source),
        };

        return [$new, $upgrader->collect()];
    }

    protected function isPackageConfig(string $path): bool
    {
        return str_ends_with($path, 'config'.DIRECTORY_SEPARATOR.'sweetalert.php')
            || str_ends_with($path, 'config'.DIRECTORY_SEPARATOR.'sweet-alert.php');
    }

    /**
     * @return list<string>
     */
    protected function filesToScan(): array
    {
        $roots = [];

        foreach ($this->option('path') ?: $this->defaultPaths as $path) {
            $absolute = $this->laravel->basePath($path);

            if (is_file($absolute)) {
                $roots[] = $absolute;
            } elseif (is_dir($absolute)) {
                $roots[] = $absolute;
            }
        }

        $files = [];

        foreach ($roots as $root) {
            if (is_file($root)) {
                $files[] = $root;

                continue;
            }

            $finder = (new Finder)
                ->files()
                ->in($root)
                ->name('*.php')
                ->exclude(['vendor', 'node_modules', 'storage', 'cache']);

            foreach ($finder as $file) {
                $files[] = $file->getRealPath();
            }
        }

        sort($files);

        return array_values(array_unique($files));
    }

    /**
     * @param  array<string, array{0:string,1:list<UpgradeChange>}>  $planned
     * @param  array<string, list<UpgradeChange>>  $warnings
     */
    protected function report(array $planned, array $warnings): void
    {
        foreach ($planned as $file => [, $changes]) {
            $this->newLine();
            $this->line('  <fg=cyan>'.$this->relative($file).'</>');

            foreach ($changes as $change) {
                $this->line(sprintf('    <fg=gray>%d</> <fg=red>- %s</>', $change->line, $this->oneLine($change->before)));
                $this->line(sprintf('      <fg=green>+ %s</>', $this->oneLine((string) $change->after)));
            }
        }

        if ($warnings === []) {
            return;
        }

        $this->newLine();
        $this->components->warn('Needs a human — these were found but not changed:');

        foreach ($warnings as $file => $changes) {
            foreach ($changes as $change) {
                $this->line(sprintf(
                    '  <fg=yellow>%s:%d</> %s',
                    $this->relative($file),
                    $change->line,
                    $this->oneLine($change->before)
                ));
                $this->line('    <fg=gray>'.$change->note.'</>');
            }
        }
    }

    protected function relative(string $path): string
    {
        return str_replace($this->laravel->basePath().DIRECTORY_SEPARATOR, '', $path);
    }

    protected function oneLine(string $code): string
    {
        return trim((string) preg_replace('/\s+/', ' ', $code));
    }
}
