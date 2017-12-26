<?php

namespace App\Helpers\WalletHandlers;
use App\Wallet;

class ManualWalletHandler {

	public $name = 'Manual';
    public $params = [];

	public function handle (Wallet $wallet)
	{
		// nothing - must update manualy
	}
}