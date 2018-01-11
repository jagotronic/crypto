<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function($router) {


	$router->resources([
		'gpus' => 'GpuController',
		'currencies' => 'CurrencyController',
		'balances' => 'BalanceController',
		'wallets' => 'WalletController',
	]);

	Route::get('/home', 'HomeController@index')->name('home');
	Route::get('/summary', 'SummaryController@index')->name('summary');
	Route::get('/summary/refresh', 'SummaryController@refresh')->name('summary.refresh');
	Route::get('/currencies/{currency}/refresh', 'CurrencyController@refresh')->name('currencies.refresh');
	Route::get('/wallets/{wallet}/refresh', 'WalletController@refresh')->name('wallets.refresh');
});

