
                <table class="table table-striped task-table" style="table-layout: fixed;">

                    <!-- Table Headings -->
                    <thead>
                        <th>Name</th>
                        <th>Handler</th>
                        <th>&nbsp;</th>
                    </thead>

                    <!-- Table Body -->
                    <tbody>
                        @foreach ($wallets as $wallet)
                            <tr>
                                <!-- Task Name -->
                                <td class="table-text">
                                    <div>{{ $wallet->name }}</div>
                                </td>
                                <td>
                                    <div>{{ $wallet->handler }}</div>
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
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>