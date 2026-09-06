<?php

namespace RealRashid\SweetAlert\Commands;

use Illuminate\Console\Command;

/**
 * Publish Command - Publishes individual SweetAlert assets.
 */
class PublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'alert:publish
        {--config : Publish the configuration file}
        {--views : Publish the Blade views}
        {--assets : Publish the JavaScript assets}
        {--force : Overwrite any existing files}';

    /**
     * The console command description.
     */
    protected $description = 'Publish SweetAlert resources individually';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $force = $this->option('force');

        if ($this->option('config')) {
            $this->publishConfig($force);
        }

        if ($this->option('views')) {
            $this->publishViews($force);
        }

        if ($this->option('assets')) {
            $this->publishAssets($force);
        }

        if (! $this->option('config') && ! $this->option('views') && ! $this->option('assets')) {
            $this->publishConfig($force);
            $this->publishViews($force);
            $this->publishAssets($force);
        }

        $this->info('SweetAlert resources published successfully!');

        return self::SUCCESS;
    }

    /**
     * Publish the configuration file.
     */
    protected function publishConfig(bool $force): void
    {
        $this->call('vendor:publish', [
            '--tag' => 'sweetalert-config',
            '--force' => $force,
        ]);
    }

    /**
     * Publish the Blade views.
     */
    protected function publishViews(bool $force): void
    {
        $this->call('vendor:publish', [
            '--tag' => 'sweetalert-views',
            '--force' => $force,
        ]);
    }

    /**
     * Publish the JavaScript assets.
     */
    protected function publishAssets(bool $force): void
    {
        $this->call('vendor:publish', [
            '--tag' => 'sweetalert-asset',
            '--force' => $force,
        ]);
    }
}
