<div class="form-group form-item {{ $class or '' }}" style="margin-bottom: 0px;">
    <label class="col-sm-3 control-label no-padding-right form-label">
        {{ $label }} {!! isset($is_required) && $is_required ? '<span class="red">*</span>' : '' !!}
    </label>
    <div class="col-sm-5 form-val">
        <?php $val = $val ?? '';?>
        <div class="well well-sm" id="{{$name}}">&nbsp;{!! isset($notFilter) ? $val : htmlspecialchars($val) !!} </div>
        @if(isset($help_block) && !empty($help_block))
            <span class="help-block">{!! $help_block !!}</span>
        @endif
    </div>
</div>
