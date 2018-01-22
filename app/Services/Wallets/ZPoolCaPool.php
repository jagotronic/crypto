<?php

namespace App\Services\Wallets;

use App\Services\Wallets\Type\PoolService;
use App\Services\YimpPoolService;

class ZPoolCaPool extends YimpPoolService implements PoolService {

	public $name = 'zpool.ca pool';

    protected function getApiPath()
    {
        return 'http://www.zpool.ca/api/wallet?address=';
    }
}
