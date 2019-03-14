<div class="form-group">
    <label class="col-sm-3 control-label no-padding-right" > {{ $label }} {{ isset($require) ?  '<span class="red">*</span>' : ''}} </label>
    <div class="col-xs-9 col-sm-5">
        <input type="text" name="{{ $name }}" class="form-control" placeholder="{{ isset($require) ?  '必填' : ''}}" value="{{ $value }}" />
    </div>
</div>