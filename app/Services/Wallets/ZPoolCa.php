<?php

namespace App\Services\Wallets;

use App\Services\YimpPoolService;

class ZPoolCa extends YimpPoolService {

	public $name = 'zpool.ca pool';

    protected function getApiPath()
    {
        return 'http://www.zpool.ca/api/wallet?address=';
    }
}
