
                <table class="table table-striped task-table">

                    <!-- Table Headings -->
                    <thead>
                        <th>Name</th>
                        <th>Symbol</th>
                        <th>Status</th>
                        <th>USD</th>
                        <th>CAD</th>
                        <th>BTC</th>
                        <th>&nbsp;</th>
                    </thead>

                    <!-- Table Body -->
                    <tbody>
                        @foreach ($currencies as $currency)
                            <tr class="{{ !empty($currency->message) ? 'danger' : '' }}">
                                <!-- Task Name -->
                                <td class="table-text">
                                    <div>{{ $currency->name }}</div>
                                </td>
                                <td>
                                    <div>{{ $currency->symbol }}</div>
                                </td>
                                <td>
                                    <a href="javascript:;" class="js-status{{ !empty($currency->message) ? ' text-danger' : '' }}" data-link="{{ URL::route('currencies.message', ['id' => $currency->id], false) }}">{{ !empty($currency->message) ? 'error' : 'OK' }}</a>
                                </td>
                                <td>
                                    <div class="js-usd">{{ $currency->usd_value }}</div>
                                </td>
                                <td>
                                    <div class="js-cad">{{ $currency->cad_value }}</div>
                                </td>
                                <td>
                                    <div class="js-btc">{{ $currency->btc_value }}</div>
                                </td>
                                <td class="text-right">
							        <form style="display: inline-block;" action="{{ url('currencies/'.$currency->id) }}" method="POST">
							            {{ csrf_field() }}
							            {{ method_field('DELETE') }}

							            <button type="submit" class="btn btn-danger btn-xs">
							                <i class="fa fa-trash"></i> Delete
							            </button>
							        </form>
                                    <a class="btn btn-success btn-xs" href="{{ url('currencies/'.$currency->id.'/edit') }}">
                                        <i class="fa fa-pencil"></i> Edit
                                    </a>
                                    <button class="btn btn-info btn-xs js-refresh" data-link="{{ URL::route('currencies.refresh', ['id' => $currency->id], false) }}">
                                        <i class="fa fa-refresh"></i> Refresh
                                    </button>
                                </td>
                            </tr>
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
                    var $tr = $link.closest('tr').addClass('info').removeClass('danger');
                    var $status = $tr.find('.js-status').html('loading...').removeClass('text-danger');;
                    var $usd = $tr.find('.js-usd');
                    var $cad = $tr.find('.js-cad');
                    var $btc = $tr.find('.js-btc');

                    $.get($link.data('link'), {}, function(data) {
                        $tr.removeClass('info');
                        $status.html(data.message !== null ? 'error' : 'OK');
                        $link.prop('disabled', false);

                        if (data.message !== null) {
                            $tr.addClass('danger');
                            $status.addClass('text-danger');
                        }

                        $usd.html(data.usd_value);
                        $cad.html(data.cad_value);
                        $btc.html(data.btc_value);
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
                    $('.js-refresh').prop('disabled', true);
                    $.get($(this).data('link'), {}, function(response) {
                        window.location.reload();
                    });
                });
            });
        </script>
@endsection
