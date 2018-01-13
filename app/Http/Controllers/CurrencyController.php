<?php

namespace App\Http\Controllers;

use App\Currency;
use App\Factories\CurrencyServiceFactory;
use App\Helpers\CurrenciesUpdater;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currencies = Currency::all();
        return view('currencies.index', ['currencies'=>$currencies]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('currencies.create', ['handlers' => $this->getHandlers()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateMe($request);

        $currency = new Currency();
        $currency->name = request('name');
        $currency->symbol = request('symbol');
        $currency->handler = request('handler');
        $currency->usd_value = request('usd_value', null);
        $currency->cad_value = request('cad_value', null);
        $currency->btc_value = request('btc_value', null);
        $currency->description = request('description', '');
        $currency->data = request('data', []);
        $currency->save();

        $request->session()->flash('message', 'Currency successfully created!');
        return redirect('currencies');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function show(Currency $currency)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Currency $currency)
    {
        return view('currencies.edit', ['currency' => $currency, 'handlers' => $this->getHandlers()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Currency $currency)
    {
        $this->validateMe($request);

        $currency->name = request('name');
        $currency->symbol = request('symbol');
        $currency->handler = request('handler');
        $currency->usd_value = request('usd_value', null);
        $currency->cad_value = request('cad_value', null);
        $currency->btc_value = request('btc_value', null);
        $currency->description = request('description', '');
        $currency->data = request('data', []);
        $currency->save();

        $request->session()->flash('message', 'Currency successfully updated!');
        return redirect('currencies');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Currency $currency)
    {
        $currency->delete();
        $request->session()->flash('message', 'Currency successfully deleted!');

        return redirect('currencies');
    }

    /**
     * Refresh the specified resource.
     *
     * @param  \App\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function refresh(Currency $currency)
    {
        $currency->message = null;

        try {
            CurrenciesUpdater::update($currency);
        } catch (\Exception $e) {
            $message = json_decode($e->getMessage(), true);

            if (is_null($message)) {
                $message = $e->getMessage();
            }

            if (!is_array($message)) {
                $message = ['message' => $message];
            }

            $message['trace'] = $e->getTraceAsString();
            $currency->message = json_encode($message);
        }

        $currency->save();

        return $currency->fresh();
    }

    /**
     * Show the specified resource message.
     *
     * @param  \App\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function message(Currency $currency)
    {
        return view('currencies.message', ['currency' => $currency]);
    }

    /**
     * Model validation method
     * @param  Request $request
     * @return [void]
     */
    private function validateMe(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:191|unique:currencies,name,'.$request->get('id'),
            'symbol' => 'required|string|max:191|unique:currencies,symbol,'.$request->get('id'),
            'handler' => 'required|valid_currency_handler|string|min:2',
            'usd_value' => 'nullable|regex:/[\d]{0,8}.[\d]{0,8}/',
            'cad_value' => 'nullable|regex:/[\d]{0,8}.[\d]{0,8}/',
            'btc_value' => 'nullable|regex:/[\d]{0,8}.[\d]{0,8}/'
        ];

        $handlerClassName = $request->get('handler');

        if (strlen($handlerClassName)) {
            $handler = CurrencyServiceFactory::get($handlerClassName);

            if ($handlerClassName === 'Manual') {
                $rules['usd_value'] = 'required|regex:/^[\d]{0,8}.[\d]{0,8}$/';
                $rules['cad_value'] = 'required|regex:/^[\d]{0,8}.[\d]{0,8}$/';
                $rules['btc_value'] = 'required|regex:/^[\d]{0,8}.[\d]{0,8}$/';
            }

            foreach ($handler->validation as $attribute => $rule) {
                $rules['data.' . $handlerClassName . '.' . $attribute] = $rule;
            }
        }

        $request->validate($rules);
    }

    private function getHandlers()
    {
        return Currency::getHandlers();
    }
}
