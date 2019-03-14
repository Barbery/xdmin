@section('componentsLink')
    @parent

    <link rel="stylesheet" href="{{ asset('admin-assets/ace/css/bootstrap-datetimepicker.min.css') }}" />
@endsection

<div class="form-group form-item">
    <label class="col-sm-3 control-label no-padding-right form-label">
        {{ $label }} {!! isset($is_required) && $is_required ? '<span class="red">*</span>' : '' !!}
    </label>
    <div class="col-sm-5 form-val">
        <input type="{{ $type or 'text' }}" id="{{ $name }}" name="{{ $name }}" class="form-control"
               placeholder="{{ isset($placeholder) ? $placeholder : (isset($is_required) && $is_required ? '必填' : $label) }}"
               value="{{ $val or '' }}" autocomplete=false />
        @if(isset($help_block) && !empty($help_block))
            <span class="help-block">{!! $help_block !!}</span>
        @endif
    </div>
</div>

@section('componentsScript')
    @parent
    <script src="/admin-assets/ace/js/moment.min.js"></script>
    <script src="{{ asset('admin-assets/ace/js/bootstrap-datetimepicker.min.js') }}"></script>
@endsection

@section('componentsJs')
    @parent

    <script>
        $(function() {
            $('#{{ $name }}').datetimepicker({
             format: 'YYYY-MM-DD HH:mm',//use this option to display seconds
             sideBySide: true,
             icons: {
                time: 'fa fa-clock-o',
                date: 'fa fa-calendar',
                up: 'fa fa-chevron-up',
                down: 'fa fa-chevron-down',
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
                today: 'fa fa-arrows ',
                clear: 'fa fa-trash',
                close: 'fa fa-times'
             }
            });
        });


    </script>
@endsection
