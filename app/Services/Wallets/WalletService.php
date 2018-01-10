<?php

namespace App\Services\Wallets;

use App\Services\ApiService;
use App\Currency;
use App\Wallet;

abstract class WalletService extends ApiService {

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
}
