@extends('layouts.app')

@section('content')
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">Currencies</div>
	    <div class="panel-body">

		@include('commun.message')

	    @if (count($currencies) > 0)
	        <h2>List of currencies
                <button class="pull-right btn btn-info btn-xs js-refresh-all">
                    <i class="fa fa-refresh"></i> Refresh All
                </button>
            </h2>
		</div>
		@include('currencies.list')
        <div class="panel-body">
	    @else
	    	<p>No currencies yet</p>
	    @endif
        	<div class="text-right"><a href="{{ route('currencies.create') }}" class="btn btn-primary btm-sm">Add Currency <i class="fa fa-plus"></i> </a></div>
        </div>
    </div>
</div>
@endsection
