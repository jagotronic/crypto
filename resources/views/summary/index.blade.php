@extends('layouts.app')

@section('content')
<div class="Summary">
    <div class="panel panel-default">
        <div class="panel-heading">Balances</div>
        <table class="table table-condensed" style="table-layout: fixed;">
            <colgroup>
                <col width="" />
                <col width="15%" />
                <col width="15%" />
                <col width="15%" />
                <col width="15%" />
            </colgroup>
            <tbody>
                <tr class="Summary-myTotals">
                    <td></td>
                    <td></td>
@foreach($totals as $symbol => $total)
                    <td class="superTotal">
                        <div class="card">
                          <div class="card-body">
                            <p class="card-text text-center">
                                {{ $symbol == 'BTC' ? sprintf('%s', number_format($total, 7)) : sprintf('%s', number_format($total, 3)) }}
                            </p>
                            <h5 class="card-title text-center">{{ $symbol . ($symbol == 'BTC' ? '' : ' $')}}</h5>
                          </div>
                        </div>
                    </td>
@endforeach
                </tr>
            </tbody>
        </table>
@foreach($balances as $symbol => $item)
        <!-- Table -->
        <table class="table table-condensed table-bordered Summary-balanceTable">
            <colgroup>
                <col width="" />
                <col width="15%" />
                <col width="15%" />
                <col width="15%" />
                <col width="15%" />
            </colgroup>
            <thead>
                <tr>
                    <th rowspan="2" colspan="2" class="Summary-currency">
                        <a href="{{ $item['currency']['webpage_url'] }}" target="_blank">
                            <h2 class="Summary-currencyTitle">
                                <img src="{{ $item['currency']['icon_src'] }}" alt="{{ $item['currency']['name'] }} ({{ $symbol }})">
                                <span>{{ $item['currency']['name'] }} ({{ $symbol }})</span>
                            </h2>
                            <div class="Summary-currencyPercents">
                                <div class="Summary-currencyPercentValue{{ $item['currency']['percent_change_1h'] < 0 ? ' minus' : '' }}">
                                    <i class="Summary-currencyArrow"></i> 
                                    {{ sprintf('%s', number_format($item['currency']['percent_change_1h'], 2)) }}% 
                                    <small>(1h)</small>
                                </div>
                                <div class="Summary-currencyPercentValue{{ $item['currency']['percent_change_24h'] < 0 ? ' minus' : '' }}">
                                    <i class="Summary-currencyArrow"></i>
                                    {{ sprintf('%s', number_format($item['currency']['percent_change_24h'], 2)) }}% 
                                    <small>(24h)</small>
                                </div>
                                <div class="Summary-currencyPercentValue{{ $item['currency']['percent_change_7d'] < 0 ? ' minus' : '' }}">
                                    <i class="Summary-currencyArrow"></i>
                                    {{ sprintf('%s', number_format($item['currency']['percent_change_7d'], 2)) }}%
                                    <small>(7d)</small>
                                </div>
                            </div>
                        </a>
                    </th>
                    <th colspan="3" class="Summary-spacer"></th>
                </tr>
                <tr>
                    <th class="Summary-currencyValue">{{ sprintf('$ %s', number_format($item['currency']['usd_value'], 3)) }}/<sub>{{ $symbol }}</sub></th>
                    <th class="Summary-currencyValue">{{ sprintf('$ %s', number_format($item['currency']['cad_value'], 3)) }}/<sub>{{ $symbol }}</sub></th>
                    <th class="Summary-currencyValue">{{ $item['currency']['btc_value'] }}/<sub>{{ $symbol }}</sub></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td align="right" style="border-bottom: 1px solid #fff;">Total</td>
                    <th class="total">{{ sprintf('%s', number_format($item['totals']['value'], 5)) }}</th>
                    <th class="total">{{ sprintf('$ %s', number_format($item['totals']['USD'], 3)) }}</th>
                    <th class="total">{{ sprintf('$ %s', number_format($item['totals']['CAD'], 3)) }}</th>
                    <th class="total">{{ sprintf('%s', number_format($item['totals']['BTC'], 7)) }}</th>
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
<!-- <pre>{{  print_r($response) }}</pre> -->
@endsection
@section('scripts')
<script>
    $(function() {
        
    })
</script>
@endsection
