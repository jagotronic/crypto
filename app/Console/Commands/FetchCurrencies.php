<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\FetchCurrencies as FetchCurrenciesJob;

class FetchCurrencies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currencies:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update currencies value';

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
        $this->info('Starting FetchCurrenciesJob job');
        FetchCurrenciesJob::dispatch();
    }
}
