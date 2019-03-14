@section('componentsJs')
    @parent

    <script>
        var cityJSelect = $('select[name="city_id"]');
        var districtJSelect = $('select[name="district_id"]');
        var cityId = '{{ $city_id or 0 }}';
        var districtId = '{{ $district_id or 0 }}';

        $(function () {
            districtJSelect.chosen();

            if (cityId > 0) {
                initDistrictSelect(cityId, function() {
                    districtId = 0;
                    districtJSelect.chosen('destroy').chosen();
                });
            }

            cityJSelect.change(function () {
                if ($(this).val() > 0) {
                    initDistrictSelect($(this).val(), function() {
                        districtJSelect.chosen('destroy').chosen();
                    });
                }
            });
        });

        function initDistrictSelect(cityId, callback)
        {
            var defOption = '<option value="">请选择</option>';

            districtJSelect.html(defOption);

            var options = defOption;
            $.get('/common/areas/' + cityId + '/{{ pageRouteName() }}', function (data) {
                for (var i in data.data) {
                    if (i == districtId) {
                        options += '<option value="' + i + '" selected>' + data.data[i] + '</option>';
                    } else {
                        options += '<option value="' + i + '">' + data.data[i] + '</option>';
                    }
                }

                districtJSelect.html(options);

                if (callback) {
                    callback();
                }
            });
        }
    </script>
@endsection