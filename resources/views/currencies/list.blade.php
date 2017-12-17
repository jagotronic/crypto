
                <table class="table table-striped task-table">

                    <!-- Table Headings -->
                    <thead>
                        <th>Name</th>
                        <th>Symbol</th>
                        <th>USD</th>
                        <th>CAD</th>
                        <th>BTC</th>
                        <th>&nbsp;</th>
                    </thead>

                    <!-- Table Body -->
                    <tbody>
                        @foreach ($currencies as $currency)
                            <tr>
                                <!-- Task Name -->
                                <td class="table-text">
                                    <div>{{ $currency->name }}</div>
                                </td>
                                <td>
                                    <div>{{ $currency->symbol }}</div>
                                </td>
                                <td>
                                    <div>{{ $currency->usd_value }}</div>
                                </td>
                                <td>
                                    <div>{{ $currency->cad_value }}</div>
                                </td>
                                <td>
                                    <div>{{ $currency->btc_value }}</div>
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
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>