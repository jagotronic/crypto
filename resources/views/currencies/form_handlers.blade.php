
@foreach ($handlers as $h)
    @foreach ($h['fields'] as $name => $config)

    @include('wallets.form_inputs.'. $config['type'], [
        'class' => 'hidden handler-'. $h['id'],
        'label' => trans($name),
        'name' => 'data['. $h['id'] .']['. $name .']',
        'value' => old('data.' . $h['id'] . '.' . $name, !empty($wallet) && !empty($wallet->data[$h['id']][$name]) ? $wallet->data[$h['id']][$name] : ''),
        'config' => $config
    ])

    @endforeach
@endforeach
