# jagotronic/crypto

## Currencies Seeding
File path to create. This file is `.gitignore`.
```
database/seeds/mySeeds/currencies.php
```

example of content:
```
<?php

DB::table('currencies')->insert([
    'name' => 'BitCoin',
    'handler' => 'Coinmarketcap',
    'data' => json_encode([
        'api_path' => 'bitcoin'
    ]),
    'icon_src' => '', // will be fetch next refresh
    'webpage_url' => '', // will be fetch next refresh
    'symbol' => 'BTC',
]);
```

## Users Seeding
File path to create. This file is `.gitignore`.
```
database/seeds/mySeeds/users.php
```
example of content:
```
<?php

DB::table('users')->insert([
    'name' => 'My Name',
    'email' => 'my@email.com',
    'password' => bcrypt('ma_passphrase')
]);
```

## Wallets Seeding
File path to create. This file is `.gitignore`.
```
database/seeds/mySeeds/wallets.php
```
example of content:
```
<?php

// EXCHANGES
DB::table('wallets')->insert([
    'name' => 'My Bittrex account',
    'handler' => 'BittrexExchange',
    'data' => json_encode([
        'apikey' => env('BITTREX_APIKEY', null),
        'apisecret' => env('BITTREX_APISECRET', null),
    ]),
    'description' => ''
]);

// WALLETS
DB::table('wallets')->insert([
    'name' => 'Gobyte wallet',
    'handler' => 'GobyteWallet',
    'data' => json_encode([
        'address' => env('GOBYTE_WALLET_ADDRESS', null),
    ]),
    'description' => ''
]);

// Pools
DB::table('wallets')->insert([
    'name' => 'Unimining.ca pool (GBX)',
    'handler' => 'UniminingPool',
    'data' => json_encode([
        'address' => env('GOBYTE_WALLET_ADDRESS', null),
    ]),
    'description' => ''
]);

// Manual wallets (no API to read balances)
DB::table('wallets')->insert([
    'name' => 'mercatox ETH',
    'handler' => 'Manual',
    'data' => json_encode([
        'value' => 0.20887129,
        'currency' => 'ETH'
    ]),
    'description' => ''
]);
```