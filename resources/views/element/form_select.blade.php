<div id="{{ $name }}" class="form-group form-item">
    <label class="col-sm-3 control-label no-padding-right form-label" >
      {{ $label }} {!! isset($is_required) && $is_required ? '<span class="red">*</span>' : '' !!}
    </label>
    <div class="col-sm-5 form-val">
        <select class="form-control" name="{{ $name }}">
            @foreach($items as $_k => $_name)
                @if ((isset($val) && $val == $_k))
                    <option value="{{ isset($key) ? $_name[$key] : $_k }}" selected>{{ isset($value) ? $_name[$value] : $_name }}</option>
                @else
                    <option value="{{ isset($key) ? $_name[$key] : $_k }}">{{ isset($value) ? $_name[$value] : $_name }}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>
