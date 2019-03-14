@section('beforeAceLink')
    @parent

    <link rel="stylesheet" href="{{ asset('admin-assets/ace/css/chosen.min.css') }}" />
@endsection

<div id="{{ $name }}" class="form-group form-item">
    <label class="col-sm-3 control-label no-padding-right" > {{ $label }} <span class="red">*</span> </label>
    <div class="col-sm-9 form-val">
        <select name="province_id" class="chosen-select form-control">
            <option value="">请选择</option>
        </select>

        <select name="city_id" class="chosen-select form-control">
            <option value="">请选择</option>
        </select>

        <select name="district_id" class="chosen-select form-control">
            <option value="">请选择</option>
        </select>

        @include('element._select_province', ['province_id' => $province_id ?? 0])
        @include('element._select_city', ['province_id' => $province_id ?? 0, 'city_id' => $city_id ?? 0])
        @include('element._select_district', ['city_id' => $city_id ?? 0, 'district_id' => $district_id ?? 0])
    </div>
</div>

@section('componentsScript')
    @parent

    <script src="{{ asset('admin-assets/ace/js/chosen.jquery.min.js') }}"></script>
@endsection

@section('componentsStyle')
    @parent

    <style>
        #{{$name}} .form-val select {
            width: 160px;
        }
    </style>
@endsection
