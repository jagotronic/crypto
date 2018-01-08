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

	function initCurl(string $url = null)
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		return $ch;
	}
}
