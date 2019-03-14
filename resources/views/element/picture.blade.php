<div id="{{ $name ?? '' }}" class="form-group form-item {{ $class or '' }}">
    <label class="col-sm-3 control-label no-padding-right form-label">
        {{ $label }} {!! isset($is_required) && $is_required ? '<span class="red">*</span>' : '' !!}
    </label>
    <div class="col-sm-5 form-val">
        <div class="well well-sm"> <img width="{{ $width ?? 200 }}" height="{{ $height ?? 160 }}" src="{{ $val }}" /> </div>
    </div>
</div>
