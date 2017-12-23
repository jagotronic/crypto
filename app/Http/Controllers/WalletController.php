<?php

namespace App\Http\Controllers;

use App\Wallet;
use App\Currency;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wallets = Wallet::all();
        return view('wallets.index', ['wallets'=>$wallets]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('wallets.create', ['currencies' => Currency::all()]);
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

        $wallet = new Wallet();
        $wallet->name = request('name');
        $wallet->address = request('address');
        $wallet->currency_id = request('currency_id');
        $wallet->type = request('type');
        $wallet->amount = request('amount');
        $wallet->description = request('description', '');
        $wallet->save();

        $request->session()->flash('message', 'Currency successfully created!');
        return redirect('wallets');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function show(Wallet $wallet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function edit(Wallet $wallet)
    {
        return view('wallets.edit', ['wallet' => $wallet, 'currencies' => Currency::all()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wallet $wallet)
    {
        $this->validateMe($request);

        $wallet->name = request('name');
        $wallet->address = request('address');
        $wallet->currency_id = request('currency_id');
        $wallet->type = request('type');
        $wallet->amount = request('amount');
        $wallet->description = request('description', '');
        $wallet->save();

        $request->session()->flash('message', 'Wallet successfully updated!');
        return redirect('wallets');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wallet $wallet)
    {
        $wallet->delete();
        $request->session()->flash('message', 'Wallet successfully deleted!');

        return redirect('wallets');
    }

    private function validateMe(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191|unique:wallets,name,'.$request->get('id'),
            'address' => 'required|string|min:26|max:34',
            'currency_id' => 'required|exists:currencies,id',
            'type' => 'required|in:wallet,pool,exchange',
            'amount' => 'required|regex:/[\d]{0,8}.[\d]{0,8}/',
        ]);
    }
}
