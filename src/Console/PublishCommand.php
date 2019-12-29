<?php

namespace RealRashid\SweetAlert\Console;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sweetalert:publish {--force : Overwrite any existing files}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish all of the SweetAlert resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->call('vendor:publish', [
            '--tag' => 'sweetalert-config',
            '--force' => $this->option('force'),
        ]);
        $this->call('vendor:publish', [
            '--tag' => 'sweetalert-view',
            '--force' => $this->option('force'),
        ]);
        $this->call('vendor:publish', [
            '--tag' => 'sweetalert-asset',
            '--force' => true,
        ]);
    }
}
