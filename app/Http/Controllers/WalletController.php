<?php

namespace App\Http\Controllers;

use App\Factories\WalletServiceFactory;
use App\Helpers\WalletsUpdater;
use App\Wallet;
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
        $wallets = $wallets->sortBy('type')->groupBy('type');

        return view('wallets.index', ['walletsGroup' => $wallets]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('wallets.create', ['handlers' => $this->getHandlers()]);
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
        $wallet->handler = request('handler');
        $wallet->description = request('description', '');
        $wallet->data = request('data', []);
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
        return view('wallets.edit', ['wallet' => $wallet, 'handlers' => $this->getHandlers()]);
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
        $wallet->handler = request('handler');
        $wallet->description = request('description', '');
        $wallet->data = request('data', []);
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
    public function destroy(Wallet $wallet, Request $request)
    {
        $wallet->delete();
        $request->session()->flash('message', 'Wallet successfully deleted!');

        return redirect('wallets');
    }

    /**
     * Refresh the specified resource.
     *
     * @param  \App\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function refresh(Wallet $wallet)
    {
        $wallet->refresh();
        return $wallet->fresh(['balances']);
    }

    /**
     * Refresh all resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function refreshAll()
    {
        return WalletsUpdater::updateAll();
    }

    /**
     * Show the specified resource message.
     *
     * @param  \App\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function message(Wallet $wallet)
    {
        return view('wallets.message', ['wallet' => $wallet]);
    }

    /**
     * Model validation method
     * @param  Request $request
     * @return [void]
     */
    private function validateMe(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:191|unique:wallets,name,'.$request->get('id'),
            'handler' => 'required|valid_wallet_handler|string|min:2',
        ];

        $handlerClassName = $request->get('handler');

        if (strlen($handlerClassName)) {
            $handler = WalletServiceFactory::get($handlerClassName);

            foreach ($handler->validation as $attribute => $rule) {
                $rules['data.' . $handlerClassName . '.' . $attribute] = $rule;
            }
        }

        $request->validate($rules);
    }

    private function getHandlers()
    {
        return Wallet::getHandlers();
    }

}
