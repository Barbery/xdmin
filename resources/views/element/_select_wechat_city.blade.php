<script>
    var name = '{{ $name }}';
    var cityJSelect = $('select[name="'+ name +'"]');
    var cityId = '{{ $city_id or 0 }}';

    initCitySelect(function() {
        cityId = 0;
        cityJSelect.chosen('destroy').chosen();
    });

    function initCitySelect(callback)
    {
        var defOption = '<option value="">请选择</option>';

        cityJSelect.html(defOption);

        var options = defOption;
        $.get('{{ route('common.wechat_cities', ['pageRouteName' => \Illuminate\Support\Facades\Request::route()->getName()]) }}', function (data) {
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
