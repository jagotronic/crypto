
                <table class="table table-striped task-table">

                    <!-- Table Headings -->
                    <thead>
                        <th>Name</th>
                        <th>Handler</th>
                        <th>Status</th>
                        <th>&nbsp;</th>
                    </thead>

                    <!-- Table Body -->
                    <tbody>
                        @foreach ($wallets as $wallet)
                            <tr class="{{ !empty($wallet->message) ? 'danger' : '' }}">
                                <!-- Task Name -->
                                <td class="table-text">
                                    <div>{{ $wallet->name }}</div>
                                </td>
                                <td>
                                    <div>{{ $wallet->handler }}</div>
                                </td>
                                <td>
                                    <div class="js-status">{{ !empty($wallet->message) ? 'error' : 'OK' }}</div>
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
                                    <button class="btn btn-info btn-xs js-wallet-refresh" data-link="{{ url('wallets/'.$wallet->id.'/refresh') }}">
                                        <i class="fa fa-refresh"></i> Refresh
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
@section('scripts')
        <script>
            $(function() {
                $('.js-wallet-refresh').click(function() {
                    var $link = $(this);
                    var $tr = $link.closest('tr').addClass('info').removeClass('danger');
                    var $status = $tr.find('.js-status').html('loading...');

                    $.get($link.data('link'), {}, function(data) {
                        $tr.removeClass('info');
                        $status.html(data.message !== null ? 'error' : 'OK');

                        if (data.message !== null) {
                            $tr.addClass('danger');
                        }
                    });
                });
            });
        </script>
@endsection
