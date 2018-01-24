<?php

namespace App\Services\Wallets;

use App\Services\Wallets\Type\PoolService;
use App\Services\YimpPoolService;

class UniminingPool extends YimpPoolService implements PoolService {

	public $name = 'unimining.ca pool';

    protected function getApiPath()
    {
        return 'https://www.unimining.net/api/wallet?address=';
    }
}
