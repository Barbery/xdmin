@section('beforeAceLink')
    @parent

<link href="https://cdn.staticfile.org/chosen/1.8.7/chosen.min.css" rel="stylesheet">
@endsection
<div class="search-item search-select">
    <label for="{{ $name }}" class="search-label">{{ $label }}：</label>
    <select data-operator="{{ $operator ?? '' }}" class="chosen-select form-control" name="{{ $name }}" id="chosen-select-{{ $name }}">
        <option value=""> 请选择 </option>
        @foreach($items as $id => $item)
            <option value="{{ isset($key) ? $item[$key] : $id }}">{{ isset($value) ? $item[$value] : $item }}</option>
        @endforeach
    </select>
</div>

@section('componentsScript')
    @parent

    <script src="https://cdn.staticfile.org/chosen/1.8.7/chosen.jquery.min.js"></script>
@endsection

@section('inlineJs')
    @parent

    <script type="text/javascript">
        $("#chosen-select-{{ $name }}").chosen();
    </script>
@endsection
