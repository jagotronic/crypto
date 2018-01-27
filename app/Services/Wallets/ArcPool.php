<?php

namespace App\Services\Wallets;

use App\Services\Wallets\Type\PoolService;
use App\Services\YimpPoolService;

class ArcPool extends YimpPoolService implements PoolService {

	public $name = 'arcpool.com pool';

    protected function getApiPath()
    {
        return 'https://arcpool.com/api/wallet?address=';
    }
}
