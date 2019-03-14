@section('beforeAceLink')
    @parent

<link href="https://cdn.staticfile.org/chosen/1.8.7/chosen.min.css" rel="stylesheet">
@endsection

<div class="form-group form-item">
    <label class="col-sm-3 control-label no-padding-right form-label" >
      {{ $label }} {!! isset($is_required) && $is_required ? '<span class="red">*</span>' : '' !!}
    </label>
    <div class="col-sm-5 form-val">
        <select class="chosen-select form-control" style="width: 100%" id="chosen-select-{{ $name }}">
            <option value=""> 请选择 </option>
            <?php $val = $val ?? '';?>
            @foreach($items as $id => $item)
                <option {{ (isset($key) ? $item[$key] : $id) == $val ? 'selected' : '' }} value="{{ isset($key) ? $item[$key] : $id }}">{{ isset($value) ? $item[$value] : $item }}</option>
            @endforeach
        </select>
        <input id="{{ $name }}" type="hidden" name="{{ $name }}" value="{{ $val ?? ''}}"/>
    </div>
</div>


@section('componentsScript')
    @parent

    <script src="https://cdn.staticfile.org/chosen/1.8.7/chosen.jquery.min.js"></script>
@endsection


@section('inlineJs')
    @parent

    <script type="text/javascript">
        $("#chosen-select-{{ $name }}").chosen().change(function(e, params){
            $("#{{ $name }}").val(params.selected);
        });
    </script>
@endsection
