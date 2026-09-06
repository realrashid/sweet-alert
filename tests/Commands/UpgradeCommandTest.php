<?php

use Illuminate\Support\Facades\File;

/*
 * These run the command against a throwaway application tree, because the
 * risky part is not the rewriting — that is covered in UpgraderTest — but the
 * file handling: which files it opens, and whether --dry-run really writes
 * nothing.
 */
beforeEach(function () {
    $this->tree = sys_get_temp_dir().'/sweet-alert-upgrade-'.bin2hex(random_bytes(4));

    File::makeDirectory($this->tree.'/app/Http/Controllers', 0755, true);
    File::makeDirectory($this->tree.'/resources/views', 0755, true);
    File::makeDirectory($this->tree.'/config', 0755, true);
    File::makeDirectory($this->tree.'/vendor/acme/pkg/src', 0755, true);

    app()->setBasePath($this->tree);
});

afterEach(function () {
    File::deleteDirectory($this->tree);
});

function writeFile(string $path, string $contents): string
{
    File::put($path, $contents);

    return $path;
}

it('rewrites a v7 controller', function () {
    $file = writeFile($this->tree.'/app/Http/Controllers/OrderController.php', <<<'PHP'
    <?php

    namespace App\Http\Controllers;

    class OrderController
    {
        public function ship()
        {
            alert()->html('Order shipped', '<b>#42</b> is on its way', 'success');

            return back();
        }
    }
    PHP);

    $this->artisan('alert:upgrade', ['--force' => true])->assertSuccessful();

    expect(File::get($file))
        ->toContain("alert()->title('Order shipped')->html('<b>#42</b> is on its way')->icon('success')")
        ->not->toContain("html('Order shipped'");
});

it('writes nothing on a dry run', function () {
    $original = <<<'PHP'
    <?php

    alert()->html('Title', '<b>Body</b>');
    PHP;

    $file = writeFile($this->tree.'/app/legacy.php', $original);

    $this->artisan('alert:upgrade', ['--dry-run' => true])->assertSuccessful();

    expect(File::get($file))->toBe($original);
});

it('shows the change it would make on a dry run', function () {
    writeFile($this->tree.'/app/legacy.php', "<?php\n\nalert()->html('Title', '<b>Body</b>');\n");

    $this->artisan('alert:upgrade', ['--dry-run' => true])
        ->expectsOutputToContain("title('Title')")
        ->expectsOutputToContain('Dry run')
        ->assertSuccessful();
});

it('never touches vendor', function () {
    $original = "<?php\n\nalert()->html('Vendor', '<b>Body</b>');\n";
    $file = writeFile($this->tree.'/vendor/acme/pkg/src/Thing.php', $original);

    $this->artisan('alert:upgrade', ['--force' => true])->assertSuccessful();

    expect(File::get($file))->toBe($original);
});

it('upgrades the blade include to the directive', function () {
    $file = writeFile($this->tree.'/resources/views/app.blade.php', <<<'BLADE'
    <body>
        @include('sweetalert::alert')
    </body>
    BLADE);

    $this->artisan('alert:upgrade', ['--force' => true])->assertSuccessful();

    expect(File::get($file))->toContain('@sweetAlert')->not->toContain('sweetalert::alert');
});

it('nulls the themable defaults in a published config', function () {
    $file = writeFile($this->tree.'/config/sweetalert.php', <<<'PHP'
    <?php

    return [
        'background' => env('SWEET_ALERT_BACKGROUND', '#fff'),
    ];
    PHP);

    $this->artisan('alert:upgrade', ['--force' => true])->assertSuccessful();

    expect(File::get($file))->toContain("env('SWEET_ALERT_BACKGROUND')")->not->toContain("'#fff'");
});

it('reports what it will not change', function () {
    writeFile($this->tree.'/app/Legacy.php', "<?php\n\nuse RealRashid\SweetAlert\Toaster;\n");

    $this->artisan('alert:upgrade', ['--dry-run' => true])
        ->expectsOutputToContain('Needs a human')
        ->assertSuccessful();
});

it('can be pointed at a single path', function () {
    $untouched = writeFile($this->tree.'/app/A.php', "<?php\n\nalert()->html('A', '<b>1</b>');\n");
    $target = writeFile($this->tree.'/resources/views/b.php', "<?php\n\nalert()->html('B', '<b>2</b>');\n");

    $this->artisan('alert:upgrade', ['--path' => ['resources/views'], '--force' => true])->assertSuccessful();

    expect(File::get($target))->toContain("title('B')")
        ->and(File::get($untouched))->toContain("html('A', '<b>1</b>')");
});

it('is a no-op on a codebase that is already on v8', function () {
    $original = <<<'PHP'
    <?php

    use RealRashid\SweetAlert\Facades\Alert;

    Alert::title('Hi')->html('<b>Body</b>')->icon('success')->flash();
    PHP;

    $file = writeFile($this->tree.'/app/Modern.php', $original);

    $this->artisan('alert:upgrade', ['--force' => true])
        ->expectsOutputToContain('Nothing to rewrite')
        ->assertSuccessful();

    expect(File::get($file))->toBe($original);
});
