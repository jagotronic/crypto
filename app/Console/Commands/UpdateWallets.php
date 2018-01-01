<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\UpdateWallets as UpdateWalletsJob;

class UpdateWallets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wallets:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update wallets value';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Starting UpdateWalletsJob job');
        UpdateWalletsJob::dispatch();
    }
}