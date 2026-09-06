<?php

namespace RealRashid\SweetAlert\Commands;

use Illuminate\Console\Command;

/**
 * Install Command - Sets up SweetAlert in the Laravel application.
 *
 * This command publishes the configuration file, views, and assets,
 * providing a convenient one-step setup process for the package.
 */
class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'alert:install {--force : Overwrite any existing files}';

    /**
     * The console command description.
     */
    protected $description = 'Install all of the SweetAlert resources';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Installing SweetAlert...');

        $this->call('vendor:publish', [
            '--tag' => 'sweetalert-config',
            '--force' => $this->option('force'),
        ]);

        $this->call('vendor:publish', [
            '--tag' => 'sweetalert-views',
            '--force' => $this->option('force'),
        ]);

        $this->call('vendor:publish', [
            '--tag' => 'sweetalert-asset',
            '--force' => $this->option('force'),
        ]);

        $this->newLine();
        $this->info('SweetAlert installed successfully!');
        $this->info('Configuration file: config/sweetalert.php');
        $this->info('Views: resources/views/vendor/sweetalert');
        $this->info('Assets: public/vendor/sweetalert');
        $this->newLine();
        $this->info('Include the alert view in your layout: @sweetAlert');

        return self::SUCCESS;
    }
}
