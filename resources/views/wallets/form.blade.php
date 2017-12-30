
        @include('commun.errors')
        
        <!-- New Task Form -->
        <form id="WalletForm" action="{{ !empty($wallet) ? url('wallets/'. $wallet->id) : url('wallets') }}" method="POST" class="form-horizontal">
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

            <!-- Handler -->
            <div class="form-group">
                <label for="task" class="col-sm-3 control-label">Handler</label>

                <div class="col-sm-6">
                    <select class="form-control" name="handler" id="handler">
                        <option value="">Choose</option>
@foreach ($handlers as $h)
                        <option value="{{ $h['id'] }}" {{ old('handler', !empty($wallet) ? $wallet->handler : '') == $h['id'] ? "selected" : "" }}>
                            {{ trans($h['name']) }}
                        </option>
@endforeach
                    </select>
                </div>
            </div>

            @include('wallets.form_handlers', [
                'handlers' => $handlers,
                'wallet' => !empty($wallet) ? $wallet : null,
            ])

            <!-- Description -->
            <div class="form-group">
                <label for="task" class="col-sm-3 control-label">Description</label>

                <div class="col-sm-6">
                    <textarea name="description" class="form-control">{{ old('description', !empty($wallet) ? $wallet->description : '') }}</textarea>
                </div>
            </div>

            <!-- Buttons -->
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

@section('scripts')
        <script>
            var $hiddens = $('.hidden');

            function updateHandlers() {
                let handler = $('#handler').val();
                $hiddens.addClass('hidden');

                if (handler) {
                    console.log('.handler-' + handler)
                    $hiddens.filter('.handler-' + handler).removeClass('hidden');
                }
            }

            // $('#WalletForm').on('update.handler_params', updateHandlers);

            $('#handler').on('change', updateHandlers)

            updateHandlers();
        </script>
@endsection
