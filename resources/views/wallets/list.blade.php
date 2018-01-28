
                <table class="table task-table Wallet-list">

                    <!-- Table Headings -->
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Handler</th>
                            <th>Status</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>

                    <!-- Table Body -->
                    <tbody>
@foreach ($walletsGroup as $type => $wallets)
                        <tr class="Wallet-typeRow"><td colspan="4"><h4>{{ ucfirst($type) }}</h4></td></tr>
@foreach ($wallets as $wallet)
                        <tr class="wallet{{ !empty($wallet->message) ? ' text-danger' : '' }}">
                            <!-- Task Name -->
                            <td class="table-text">
                                <div>{{ $wallet->name }}</div>
                            </td>
                            <td>
                                <div>{{ $wallet->handler }}</div>
                            </td>
                            <td>
                                <a href="javascript:;" class="js-status{{ !empty($wallet->message) ? ' text-danger' : '' }}" data-link="{{ URL::route('wallets.message', ['id' => $wallet->id], false) }}">{{ !empty($wallet->message) ? 'error' : 'OK' }}</a>
                            </td>
                            <td class="text-right">
						        <form style="display: inline-block;" action="{{ url('wallets/' . $wallet->id) }}" method="POST">
						            {{ csrf_field() }}
						            {{ method_field('DELETE') }}

						            <button type="submit" class="btn btn-danger btn-xs">
						                <i class="fa fa-trash"></i> Delete
						            </button>
						        </form>
                                <a class="btn btn-success btn-xs" href="{{ url('wallets/'.$wallet->id.'/edit') }}">
                                    <i class="fa fa-pencil"></i> Edit
                                </a>
                                <button class="btn btn-info btn-xs js-refresh" data-link="{{ URL::route('wallets.refresh', ['id' => $wallet->id], false) }}">
                                    <i class="fa fa-refresh"></i> Refresh
                                </button>
                            </td>
                        </tr>
                            <tr class="balance balance-wallet-{{ $wallet->id }}">
                                <td colspan="4" class="Wallet-balanceRow">
    @forelse ($wallet->balances as $balance)
                                    <small class="Wallet-balance">{{ $balance->value }} / <strong>{{ $balance->symbol }}</strong></small>
    @empty
                                    <small class="Wallet-balance text-muted">- - - -</small>
    @endforelse
                                </td>
                            </tr>
@endforeach
@endforeach
                    </tbody>
                </table>
                <div class="modal fade" id="cryptoModal" tabindex="-1" role="dialog" aria-labelledby="cryptoModalLabel">
                  <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="cryptoModalLabel">Error message</h4>
                      </div>
                      <div class="modal-body">
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>
@section('scripts')
        <script>
            $(function() {
                $('.js-refresh').click(function() {
                    var $link = $(this).prop('disabled', true);
                    var $tr = $link.closest('tr').addClass('info').removeClass('text-danger');
                    var $status = $tr.find('.js-status').html('loading...').removeClass('text-danger');

                    $.get($link.data('link'), {}, function(data) {
                        $tr.removeClass('info');
                        $status.html(data.message !== null ? 'error' : 'OK');
                        $link.prop('disabled', false);

                        if (data.message !== null) {
                            $tr.addClass('text-danger');
                            $status.addClass('text-danger');
                        } else {
                            var $container = $tr.nextAll('.balance-wallet-' + data.id).find('.Wallet-balanceRow').empty();

                            $(data.balances).each(function() {
                                $('<small class="Wallet-balance">'+ this.value +' / <strong>'+ this.symbol +'</strong></small>').appendTo($container);
                            });
                        }
                    });
                });

                $('.js-status').click(function() {
                    var link = $(this).data('link');

                    $.get(link, {}, function(html) {
                        $('#cryptoModal .modal-body').html(html);
                        $('#cryptoModal').modal({
                            show: true
                        });
                    });
                });

                $('.js-refresh-all').click(function() {
                    $('.btn').prop('disabled', true);
                    $('#Wallet-container').imarcomLoader();

                    $.get($(this).data('link'), {}, function(response) {
                        window.location.reload();
                    });

                });
            });
        </script>
@endsection
