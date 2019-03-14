@section('componentsJs')
    @parent

    <script>
        var provinceJSelect = $('select[name="province_id"]');
        var cityJSelect = $('select[name="city_id"]');
        var districtJSelect = $('select[name="district_id"]');
        var provinceId = '{{ $province_id or 0 }}';
        var cityId = '{{ $city_id or 0 }}';

        $(function () {
            cityJSelect.chosen();

            if (provinceId > 0) {
                initCitySelect(provinceId, function() {
                    cityId = 0;
                    cityJSelect.chosen('destroy').chosen();
                });
            }
        });

        provinceJSelect.change(function () {
            if ($(this).val() > 0) {
                initCitySelect($(this).val(), function() {
                    districtJSelect.html('<option value="">请选择</option>');
                    districtJSelect.chosen('destroy').chosen();
                    cityJSelect.chosen('destroy').chosen();
                });
            }
        });

        function initCitySelect(provinceId, callback)
        {
            var defOption = '<option value="">请选择</option>';

            cityJSelect.html(defOption);

            var options = defOption;
            $.get('/common/areas/' + provinceId + '/{{ pageRouteName() }}', function (data) {
                for (var i in data.data) {
                    if (i == cityId) {
                        options += '<option value="' + i + '" selected>' + data.data[i] + '</option>';
                    } else {
                        options += '<option value="' + i + '">' + data.data[i] + '</option>';
                    }
                }

                cityJSelect.html(options);

                if (callback) {
                    callback();
                }
            });
        }
    </script>
@endsection