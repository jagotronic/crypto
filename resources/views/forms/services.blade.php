
@foreach ($handlers as $h)
    @foreach ($h['fields'] as $name => $config)

    @include('forms.form_inputs.'. $config['type'], [
        'class' => 'hidden handler-'. $h['id'],
        'label' => trans($name),
        'name' => 'data['. $h['id'] .']['. $name .']',
        'value' => old('data.' . $h['id'] . '.' . $name, !empty($model) && !empty($model->data[$h['id']][$name]) ? $model->data[$h['id']][$name] : ''),
        'config' => $config
    ])

    @endforeach
@endforeach
