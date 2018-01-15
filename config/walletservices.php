<?php

return [
	// Exchanges
	App\Services\Wallets\BittrexExchange::class,
	App\Services\Wallets\CoinsmarketsExchange::class,
	App\Services\Wallets\CryptopiaExchange::class,
	App\Services\Wallets\KucoinExchange::class,
	App\Services\Wallets\QuadrigacxExchange::class,
	App\Services\Wallets\YobitExchange::class,
	// Wallets
	App\Services\Wallets\BitcoinWallet::class,
	App\Services\Wallets\DesireWallet::class,
	App\Services\Wallets\EthereumWallet::class,
	App\Services\Wallets\GobyteWallet::class,
	App\Services\Wallets\MineprojectPool::class,
	App\Services\Wallets\SolarisWallet::class,
	App\Services\Wallets\ZcashWallet::class,
	App\Services\Wallets\CrowdcoinWallet::class,
	App\Services\Wallets\GoaWallet::class,
	// Pools
	App\Services\Wallets\AltminerPool::class,
	App\Services\Wallets\CryptohubPool::class,
	App\Services\Wallets\UniminingPool::class,
	App\Services\Wallets\ZecNanopoolPool::class,

	// Others
	App\Services\Wallets\Manual::class,
	
];
