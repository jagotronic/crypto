@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Currencies</div>
			    <div class="panel-body">

				@include('commun.message')

			    @if (count($currencies) > 0)
			        <h2>List of currencies</h2>
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
    </div>
</div>
@endsection
