<div class="form-group form-item">
    <label class="col-sm-3 control-label no-padding-right form-label" >
        {{ $label }} {!! isset($is_required) && $is_required ? '<span class="red">*</span>' : '' !!}
    </label>
    <div class="col-sm-5 form-val">
        <select name="{{ $name }}" class="chosen-select form-control">
        </select>

        @include('element._select_wechat_city', ['city_id' => isset($city_id) ? $city_id : '', 'name' => $name])
    </div>
</div>
