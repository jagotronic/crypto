
            <div class="form-group {{ $class }}">
                <label for="task" class="col-sm-3 control-label">{{ $label }}</label>

                <div class="col-sm-6">
                    <select class="form-control" name="{{ $name }}" id="handler">
                        <option value="">Choose</option>
@foreach ($config['data'] as $option)
                        <option value="{{ $option['value'] }}" {{ $option['value'] == $value ? "selected" : "" }}>
                            {{ $option['label'] }}
                        </option>
@endforeach
                    </select>
                </div>
            </div>