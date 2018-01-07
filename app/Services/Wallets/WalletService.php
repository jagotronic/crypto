<?php

namespace App\Services\Wallets;

use App\Wallet;
use App\Currency;

abstract class WalletService {

	public $name = '';

	protected $fields = [];

    public $validation = [];

    static $currencies = [];

	abstract protected function handle(Wallet $wallet);

	public function getName() {
		return !empty($this->name) ? $this->name : __CLASS__;
	}

	public function getFields() {
		$fields = $this->fields;

		foreach ($fields as $key => $value) {
			if (is_string($value)) {
				$fields[$key] = [
					'type' => $value
				];
			}

			if (!empty($value['data'])) {
				$fields[$key]['data'] = $this->getData();
			}
		}

		return $fields;
	}

	protected function findCurrencyBySymbol($symbol)
	{
		if (!array_key_exists($symbol, self::$currencies)) {
			$currency = Currency::ofSymbol($symbol)->first();

			if (is_null($currency)) {
				throw new \Exception('unknown currency symbol "'.$symbol.'"');
			}

			$currency->refresh();

			self::$currencies[$symbol] = $currency;
		}

		return self::$currencies[$symbol];
	}

	protected function findCurrencyById($id)
	{
		$currency = Currency::find($id);

		if (is_null($currency)) {
			throw new \Exception('unknown currency id "'.$symbol.'"');
		}

		if (!array_key_exists($currency->symbol, self::$currencies)) {
			self::$currencies[$currency->symbol] = $currency;
		}

		return self::$currencies[$currency->symbol];
	}
}
