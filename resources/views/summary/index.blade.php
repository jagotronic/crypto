@extends('layouts.app')

@section('content')
<div class="Summary">
    <div class="Summary-balances">
        <div class="panel panel-default">
            <div class="panel-heading">Balances</div>
            <table class="table table-condensed">
                <colgroup>
                    <col width="" />
                    <col width="15%" />
                    <col width="15%" />
                    <col width="15%" />
                    <col width="15%" />
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
@foreach($balances as $symbol => $item)
            <div class="panel-body Summary-balanceTitle">
                <h2>
                    <img src="{{ $currencies[$symbol]['icon_src'] }}" alt="{{ $currencies[$symbol]['name'] }}">
                    {{ $currencies[$symbol]['name'] }}
                </h2>
            </div>
            <!-- Table -->
            <table class="table table-condensed table-bordered Summary-balanceTable">
                <colgroup>
                    <col width="" />
                    <col width="15%" />
                    <col width="15%" />
                    <col width="15%" />
                    <col width="15%" />
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
@foreach($item['balances'] as $balance)
                    <tr>
                        <td class="name">{{ $balance['wallet'] }}</td>
                        <td class="value">{{ sprintf('%s', number_format($balance['value'], 5)) }}</td>
                        <td class="value{{ (!empty($balance['error']) ? ' danger' : '') }}">{{ !empty($balance['error']) ? $balance['error'] : sprintf('$ %s', number_format($balance['values']['USD'], 3)) }}</td>
                        <td class="value{{ (!empty($balance['error']) ? ' danger' : '') }}">{{ !empty($balance['error']) ? $balance['error'] : sprintf('$ %s', number_format($balance['values']['CAD'], 3)) }}</td>
                        <td class="value{{ (!empty($balance['error']) ? ' danger' : '') }}">{{ !empty($balance['error']) ? $balance['error'] : sprintf('%s', number_format($balance['values']['BTC'], 7)) }}</td>
                    </tr>
@endforeach
                </tbody>
            </table>
@endforeach
        </div>
    </div>
    <div class="Summary-currencies">
@foreach($currencies as $currency)
    @if(empty($currency['webpage_url']))
        <span class="card">
    @else
        <a class="card" href="{{ $currency['webpage_url'] }}" target="_blank">
    @endif
          <img class="card-img-top" src="{{ $currency['icon_src'] }}" alt="{{ $currency['name'] }}">
          <div class="card-body">
            <h5 class="card-title">{{ $currency['symbol'] }}</h5>
            <p class="card-text align-center">
                <strong>USD</strong>: {{ sprintf('$ %s', number_format($currency['usd_value'], 3)) }} <br>
                <strong>CAD</strong>: {{ sprintf('$ %s', number_format($currency['cad_value'], 3)) }} <br>
                <strong>BTC</strong>: {{ $currency['btc_value'] }}
            </p>
            <div class="Summary-currencyPercentRow">
                <div class="Summary-currencyPercentCol{{ $currency['percent_change_1h'] < 0 ? ' minus' : '' }}">
                    <i class="Summary-currencyArrow"></i>
                    1h <br>
                    {{ sprintf('%s', number_format($currency['percent_change_1h'], 2)) }}%
                </div>
                <div class="Summary-currencyPercentCol{{ $currency['percent_change_24h'] < 0 ? ' minus' : '' }}">
                    <i class="Summary-currencyArrow"></i>
                    24h <br>
                    {{ sprintf('%s', number_format($currency['percent_change_24h'], 2)) }}%
                </div>
                <div class="Summary-currencyPercentCol{{ $currency['percent_change_7d'] < 0 ? ' minus' : '' }}">
                    <i class="Summary-currencyArrow"></i>
                    7d <br>
                    {{ sprintf('%s', number_format($currency['percent_change_7d'], 2)) }}%
                </div>
            </div>
          </div>
    @if(empty($currency['webpage_url']))
        </span>
    @else
        </a>
    @endif
@endforeach
    </div>
</div>
<!-- <pre>{{  print_r($response) }}</pre> -->
@endsection
@section('scripts')
<script>
    $(function() {
        
    })
</script>
@endsection
