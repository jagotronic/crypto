<?php

namespace App\Services\Wallets;

use App\Services\YimpPoolService;

class AltminerPool extends YimpPoolService {

	public $name = 'Altminer.net pool';

    protected function getApiPath()
    {
        return 'https://altminer.net/api/wallet?address=';
    }
}
