@extends('layouts.app')

@section('content')
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">Wallets</div>
	    <div class="panel-body">

			@include('commun.message')

@if (count($wallets) > 0)
	        <h2>List of wallets</h2>
		</div>
		@include('wallets.list')
        <div class="panel-body">
@else
	    	<p>No wallets yet</p>
@endif
        	<div class="text-right"><a href="{{ route('wallets.create') }}" class="btn btn-primary btm-sm">Add wallet <i class="fa fa-plus"></i> </a></div>
        </div>
    </div>
</div>
@endsection
