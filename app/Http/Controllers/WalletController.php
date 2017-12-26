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
        return view('wallets.create', ['handlers' => Wallet::getHandlers()]);
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
        return view('wallets.edit', ['wallet' => $wallet, 'handlers' => Wallet::getHandlers()]);
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
    public function destroy(Wallet $wallet)
    {
        $wallet->delete();
        $request->session()->flash('message', 'Wallet successfully deleted!');

        return redirect('wallets');
    }

    private function validateMe(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:191|unique:wallets,name,'.$request->get('id'),
            'handler' => 'required|string|min:15',
        ];

        $handler = $request->get('handler');

        if (strlen($handler)) {
            $classPath = '\\App\\Helpers\\WalletHandlers\\' . $request->get('handler');
            $a = new $classPath();

            foreach ($a->params as $key => $value) {
                $rules['data.' . $request->get('handler') . '.' . $key] = $value;
            }
        }

        $request->validate($rules);
    }
}
