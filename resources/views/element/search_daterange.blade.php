@section('componentsLink')
    @parent

    <link rel="stylesheet" href="https://cdn.staticfile.org/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css" />
@endsection

<div class="search-daterange search-item">
    <label class="search-label center">{{ $label }}</label>
    <div class="input-group input-daterange">
        <input type="text" style="width: 100%" class="input-sm form-control search-val" data-operator=">=" autocomplete="off" data-date-format="yyyy-mm-dd" name="{{ $name }}">
        <div class="input-group-addon">to</div>
        <input type="text" style="width: 100%" class="input-sm form-control search-val" data-operator="<=" autocomplete="off" data-date-format="yyyy-mm-dd" name="{{ $name }}">
    </div>
</div>

@section('componentsScript')
    @parent

    <script src="https://cdn.staticfile.org/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
@endsection

@section('componentsJs')
    @parent

    <script>
        $(function() {
            $('.search-daterange input').datepicker({
                autoclose:true
            });
        });
    </script>
@endsection
