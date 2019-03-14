@section('componentsJs')
    @parent

    <script>
        var provinceJSelect = $('select[name="province_id"]');
        var provinceId = '{{ $province_id or 0 }}';

        $(function () {
            provinceJSelect.chosen();

            initProvinceSelect(function() {
                provinceId = 0;
                provinceJSelect.chosen('destroy').chosen();
            });
        });

        function initProvinceSelect(callback)
        {
            var defOption = '<option value="">请选择</option>';

            provinceJSelect.html(defOption);

            var options = defOption;
            $.get('/common/areas/1/{{ pageRouteName() }}', function (data) {
                for (var i in data.data) {
                    if (i == provinceId) {
                        options += '<option value="' + i + '" selected>' + data.data[i] + '</option>';
                    } else {
                        options += '<option value="' + i + '">' + data.data[i] + '</option>';
                    }
                }

                provinceJSelect.html(options);

                if (callback) {
                    callback();
                }
            });
        }
    </script>
@endsection