<div id="{{ $name }}" class="form-group form-item {{ $class or '' }}">
    <label class="col-sm-3 control-label no-padding-right form-label" >
      {{ $label }} {!! isset($is_required) && $is_required ? '<span class="red">*</span>' : '' !!}
    </label>
    <div class="col-sm-5 form-val">
        @include('components.upload_picture', ['name' => $name, 'value' => $val ?? ''])
        @if(isset($help_block) && !empty($help_block))
            <span class="help-block">{{ $help_block }}</span>
        @endif
    </div>
</div>
