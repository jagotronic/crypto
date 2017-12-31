@extends('layouts.app')

@section('content')
<div class="Summary">
    <div class="Summary-balances">
        <div class="panel panel-default">
            <div class="panel-heading">Balances</div>
            <table class="table table-condensed">
                <colgroup>
                    <col width="" />
                    <col width="150px" />
                    <col width="150px" />
                    <col width="150px" />
                    <col width="150px" />
                </colgroup>
                <tbody>
                    <tr class="Summary-totals">
                        <td></td>
                        <td></td>
@foreach($totals['summary'] as $symbol => $total)
                        <td class="superTotal">
                            <div class="card">
                              <div class="card-body">
                                <h5 class="card-title text-center">{{ $symbol }}</h5>
                                <p class="card-text text-center">
                                    {{ $symbol == 'BTC' ? sprintf('%s', number_format($total, 7)) : sprintf('$ %s', number_format($total, 3)) }}
                                </p>
                              </div>
                            </div>
                        </td>
@endforeach
                    </tr>
                </tbody>
            </table>
@foreach($balances as $symbol => $currency_balances)
            <div class="panel-body">
                <h2>{{ $symbol }}</h2>
            </div>
            <!-- Table -->
            <table class="table table-condensed table-bordered">
                <colgroup>
                    <col width="" />
                    <col width="150px" />
                    <col width="150px" />
                    <col width="150px" />
                    <col width="150px" />
                </colgroup>
                <tfoot>
                    <tr>
                        <td align="right" style="border-bottom: 1px solid #fff;">Total</td>
                        <th class="total">{{ sprintf('%s', number_format($totals['currencies'][$symbol]['value'], 5)) }}</th>
                        <th class="total">{{ sprintf('$ %s', number_format($totals['currencies'][$symbol]['USD'], 3)) }}</th>
                        <th class="total">{{ sprintf('$ %s', number_format($totals['currencies'][$symbol]['CAD'], 3)) }}</th>
                        <th class="total">{{ sprintf('%s', number_format($totals['currencies'][$symbol]['BTC'], 7)) }}</th>
                    </tr>
                </tfoot>
                <tbody>
@foreach($currency_balances['balances'] as $balance)
                    <tr>
                        <td class="name">{{ $balance['wallet'] }}</td>
                        <td>{{ sprintf('%s', number_format($balance['value'], 5)) }}</td>
                        <td{{ (!empty($balance['error']) ? ' class=danger' : '') }}>{{ !empty($balance['error']) ? $balance['error'] : sprintf('$ %s', number_format($balance['values']['USD'], 3)) }}</td>
                        <td{{ (!empty($balance['error']) ? ' class=danger' : '') }}>{{ !empty($balance['error']) ? $balance['error'] : sprintf('$ %s', number_format($balance['values']['CAD'], 3)) }}</td>
                        <td{{ (!empty($balance['error']) ? ' class=danger' : '') }}>{{ !empty($balance['error']) ? $balance['error'] : sprintf('%s', number_format($balance['values']['BTC'], 7)) }}</td>
                    </tr>
@endforeach
                </tbody>
            </table>
@endforeach
        </div>
    </div>
    <div class="Summary-currencies">
@foreach($currencies as $currency)
        <a class="card" href="https://digitalcoinprice.com/{{ $currency['api_path'] }}" target="_blank">
          <img class="card-img-top" src="https://digitalcoinprice.com/application/modules/assets/images/coins/64x64/{{ $currency['api_path'] }}.png" alt="{{ $currency['name'] }}">
          <div class="card-body">
            <h5 class="card-title">{{ $currency['symbol'] }}</h5>
            <p class="card-text align-center">
                <strong>USD</strong>: {{ sprintf('$ %s', number_format($currency['usd_value'], 3)) }} <br>
                <strong>CAD</strong>: {{ sprintf('$ %s', number_format($currency['cad_value'], 3)) }} <br>
                <strong>BTC</strong>: {{ $currency['btc_value'] }}
            </p>
          </div>
        </a>
@endforeach
    </div>
</div>
<pre>{{  print_r($response) }}</pre>
@endsection
@section('scripts')
<script>
    $(function() {
        
    })
</script>
@endsection
