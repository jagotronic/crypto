<?php

namespace App\Services\Wallets;

use App\Services\Wallets\Type\PoolService;
use App\Services\YimpPoolService;

class AltminerPool extends YimpPoolService implements PoolService {

	public $name = 'Altminer.net pool';

    protected function getApiPath()
    {
        return 'https://altminer.net/api/wallet?address=';
    }
}
