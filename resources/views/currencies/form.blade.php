
        @include('commun.errors')
        
        <!-- New Task Form -->
        <form action="{{ !empty($currency) ? url('currencies/'. $currency->id) : url('currencies') }}" method="POST" class="form-horizontal">
            {{ csrf_field() }}
            @if(!empty($currency))
            <input type="hidden" name="_method" value="PUT">
            @endif
            <input type="hidden" name="id" value="{{ !empty($currency) ? $currency->id : '' }}">

            <!-- Currency Name -->
            <div class="form-group">
                <label for="task" class="col-sm-3 control-label">Nom</label>

                <div class="col-sm-6">
                    <input type="text" name="name" value="{{ old('name', !empty($currency) ? $currency->name : '') }}" id="task-name" class="form-control">
                </div>
            </div>

            <!-- Handler -->
            <div class="form-group">
                <label for="task" class="col-sm-3 control-label">Handler</label>

                <div class="col-sm-6">
                    <select class="form-control" name="handler" id="handler">
                        <option value="">Choose</option>
@foreach ($handlers as $h)
                        <option value="{{ $h['id'] }}" {{ old('handler', !empty($currency) ? $currency->handler : '') == $h['id'] ? "selected" : "" }}>
                            {{ trans($h['name']) }}
                        </option>
@endforeach
                    </select>
                </div>
            </div>

            @include('forms.services', [
                'handlers' => $handlers,
                'model' => !empty($currency) ? $currency : null,
            ])

            <!-- Currency Symbol -->
            <div class="form-group">
                <label for="task" class="col-sm-3 control-label">Symbol</label>

                <div class="col-sm-6">
                    <input type="text" name="symbol" value="{{ old('symbol', !empty($currency) ? $currency->symbol : '') }}" id="task-name" class="form-control">
                </div>
            </div>

            <!-- USD value -->
            <div class="form-group">
                <label for="task" class="col-sm-3 control-label">USD</label>

                <div class="col-sm-6">
                    <input type="text" name="usd_value" value="{{ old('usd_value', !empty($currency) ? $currency->usd_value : '') }}" id="task-name" class="form-control">
                </div>
            </div>

            <!-- CAD value -->
            <div class="form-group">
                <label for="task" class="col-sm-3 control-label">CAD</label>

                <div class="col-sm-6">
                    <input type="text" name="cad_value" value="{{ old('cad_value', !empty($currency) ? $currency->cad_value : '') }}" id="task-name" class="form-control">
                </div>
            </div>

            <!-- BTC value -->
            <div class="form-group">
                <label for="task" class="col-sm-3 control-label">BTC</label>

                <div class="col-sm-6">
                    <input type="text" name="btc_value" value="{{ old('btc_value', !empty($currency) ? $currency->btc_value : '') }}" id="task-name" class="form-control">
                </div>
            </div>

            <!-- BTC value -->
            <div class="form-group">
                <label for="task" class="col-sm-3 control-label">Description</label>

                <div class="col-sm-6">
                    <textarea name="description" class="form-control">{{ old('description', !empty($currency) ? $currency->description : '') }}</textarea>
                </div>
            </div>

            <!-- Add Task Button -->
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-6">
                    <a href="{{ url('currencies') }}" class="btn btn-default">
                        <i class="fa fa-ban"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary pull-right">
                        <i class="fa fa-floppy-o"></i> Save
                    </button>
                </div>
            </div>
        </form>
@section('scripts')
        <script>
            var $hiddens = $('.hidden');

            function updateHandlers() {
                let handler = $('#handler').val();
                $hiddens.addClass('hidden');

                if (handler) {
                    $hiddens.filter('.handler-' + handler).removeClass('hidden');
                }
            }

            $('#handler').on('change', updateHandlers)

            updateHandlers();
        </script>
@endsection
