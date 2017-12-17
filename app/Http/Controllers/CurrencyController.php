<?php

namespace App\Http\Controllers;

use App\Currency;
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
        return view('currencies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191|unique:currencies,name,'.$request->get('id'),
            'symbol' => 'required|string|max:191|unique:currencies,symbol,'.$request->get('id'),
            'usd_value' => 'nullable|regex:/[\d]{0,8}.[\d]{0,8}/',
            'cad_value' => 'nullable|regex:/[\d]{0,8}.[\d]{0,8}/',
            'btc_value' => 'nullable|regex:/[\d]{0,8}.[\d]{0,8}/'
        ]);

        $currency = new Currency();
        $currency->name = request('name');
        $currency->symbol = request('symbol');
        $currency->usd_value = request('usd_value', null);
        $currency->cad_value = request('cad_value', null);
        $currency->btc_value = request('btc_value', null);
        $currency->description = request('description', '');
        $currency->save();

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
        print_r($request->all());
        return view('currencies.edit', ['currency' => $currency]);
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
        $currency->name = request('name');
        $currency->symbol = request('symbol');
        $currency->usd_value = request('usd_value', null);
        $currency->cad_value = request('cad_value', null);
        $currency->btc_value = request('btc_value', null);
        $currency->description = request('description', '');
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
}
