<?php

namespace App\Services\Wallets;

use App\Services\YimpPoolService;

class UniminingPool extends YimpPoolService {

	public $name = 'unimining.ca pool';

    protected function getApiPath()
    {
        return 'https://www.unimining.net/api/wallet?address=';
    }
}
