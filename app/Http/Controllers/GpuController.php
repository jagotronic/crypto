<?php

namespace App\Http\Controllers;

use App\Gpu;
use Illuminate\Http\Request;

class GpuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('gpus.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Gpu  $gpu
     * @return \Illuminate\Http\Response
     */
    public function show(Gpu $gpu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Gpu  $gpu
     * @return \Illuminate\Http\Response
     */
    public function edit(Gpu $gpu)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Gpu  $gpu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gpu $gpu)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Gpu  $gpu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gpu $gpu)
    {
        //
    }
}
