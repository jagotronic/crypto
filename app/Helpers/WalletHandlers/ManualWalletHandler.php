<?php

namespace App\Helpers\WalletHandlers;
use App\Wallet;
use App\Currency;

class ManualWalletHandler extends WalletHandler {

	public $name = 'Manual';
	protected $fields = [
        'currency_id' => [
        	'type' => 'select',
        	'data' => 'getData'
        ],
        'value' => 'text',
	];
    public $validation = [
        'currency_id' => 'required|exists:currencies,id',
        'value' => 'required|regex:/^[\d]{0,8}.[\d]{0,8}$/'
    ];

	public function handle(Wallet $wallet)
	{
		$currency = $this->findCurrencyById($wallet->raw_data['currency_id']);

		// Create balance here
		// dd($currency->toArray());
	}

	public static function getData()
	{
		return array_map(function($currency) {
			return [
				'value' => $currency['id'],
				'label' => $currency['name']
			];
		}, Currency::all()->toArray());
	}
}
