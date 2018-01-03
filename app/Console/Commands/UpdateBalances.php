<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\UpdateCurrencies;
use App\Jobs\UpdateWallets;

class UpdateBalances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'balances:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all balances';

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
        $this->info('Starting UpdateCurrencies job');
        UpdateCurrencies::dispatch();

        $this->info('Starting UpdateWallets job');
        UpdateWallets::dispatch();

        $this->info('Should update history here');
    }
}
