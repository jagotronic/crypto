
        @include('commun.errors')
        
        <!-- New Task Form -->
        <form action="{{ !empty($wallet) ? url('wallets/'. $wallet->id) : url('wallets') }}" method="POST" class="form-horizontal">
            {{ csrf_field() }}
            @if(!empty($wallet))
            <input type="hidden" name="_method" value="PUT">
            @endif
            <input type="hidden" name="id" value="{{ !empty($wallet) ? $wallet->id : '' }}">

            <!-- Wallet Name -->
            <div class="form-group">
                <label for="task" class="col-sm-3 control-label">Nom</label>

                <div class="col-sm-6">
                    <input type="text" name="name" value="{{ old('name', !empty($wallet) ? $wallet->name : '') }}" class="form-control">
                </div>
            </div>

            <!-- Wallet address -->
            <div class="form-group">
                <label for="task" class="col-sm-3 control-label">Address</label>

                <div class="col-sm-6">
                    <input type="text" name="address" value="{{ old('address', !empty($wallet) ? $wallet->address : '') }}" class="form-control">
                </div>
            </div>

            <!-- Wallet currency -->
            <div class="form-group">
                <label for="task" class="col-sm-3 control-label">Currency Symbol</label>

                <div class="col-sm-6">
                    <select class="form-control" name="currency_id">
{{ $currency_id = old('currency_id', !empty($wallet) ? $wallet->currency_id : '') }}
                        <option value="">Choose</option>
@foreach ($currencies as $currency)
                        <option value="{{ $currency->id }}" {{ $currency_id == $currency->id ? "selected" : "" }}>
                            {{ $currency->name }}
                        </option>
@endforeach
                    </select>
                </div>
            </div>

            <!-- Amount -->
            <div class="form-group">
                <label for="task" class="col-sm-3 control-label">Amount</label>

                <div class="col-sm-6">
                    <input type="text" name="amount" value="{{ old('amount', !empty($wallet) ? $wallet->amount : '') }}" id="task-name" class="form-control">
                </div>
            </div>

            <!-- USD value -->
            <div class="form-group">
                <label for="task" class="col-sm-3 control-label">Type</label>

                <div class="col-sm-6">
{{ $type = old('type', !empty($wallet) ? $wallet->type : '') }}
                    <select class="form-control" name="type">
                        <option value="">Choose</option>
@foreach (['wallet','exchange','pool'] as $t)
                        <option value="{{ $t }}" {{ $type == $t ? "selected" : "" }}>
                            {{ trans($t) }}
                        </option>
@endforeach
                    </select>
                </div>
            </div>

            <!-- BTC value -->
            <div class="form-group">
                <label for="task" class="col-sm-3 control-label">Description</label>

                <div class="col-sm-6">
                    <textarea name="description" class="form-control">{{ old('description', !empty($wallet) ? $wallet->description : '') }}</textarea>
                </div>
            </div>

            <!-- Add Task Button -->
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-6">
                    <a href="{{ url('wallets') }}" class="btn btn-default">
                        <i class="fa fa-ban"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary pull-right">
                        <i class="fa fa-floppy-o"></i> Save
                    </button>
                </div>
            </div>
        </form>
