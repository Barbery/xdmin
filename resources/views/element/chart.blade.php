<div>
    <div id="app">
        {!! $chart->container() !!}
    </div>
</div>

@section('componentsScript')
    @parent
    <script src="https://unpkg.com/vue" charset="utf-8"></script>
@endsection

@section('inlineJs')
    @parent
    <script>
    var app = new Vue({
        el: '#app',
    });
    </script>
@endsection

@section('componentsScript')
    @parent
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
    @parent
    <script src=https://cdnjs.cloudflare.com/ajax/libs/echarts/4.0.2/echarts-en.min.js charset=utf-8></script>
@endsection

@section('inlineJs')
    @parent
    {!! $chart->script() !!}
@endsection

@section('inlineJs')
    @parent
    <script type="text/javascript">
        $(function () {
            document.getElementById('search-btn').addEventListener('click',function () {
                refreshChart()
            });
        });

        function refreshChart() {
            $.ajax({
                url: '/chart/api',
                dataType: 'json',
                data: {
                  'sDate':$('input[name="day"]:first').val(),
                  'eDate':$('input[name="day"]:last').val()
                },
                success:function (data) {
                    if (data.code == 0) {
                        {{--removeData(window.{{  $chart->id  }})--}}
                        $('#{{  $chart->id  }}').remove();
                        $('#app').append('<canvas id="{{  $chart->id  }}"></canvas>');
                        var ctx = document.getElementById("{{  $chart->id  }}");
                        // 初始化一个新的折线图
                        var myLineChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: data.data['labels'],
                                datasets: JSON.parse(data.data['data'])
                            },
                            options: {"maintainAspectRatio":false,"scales":{"xAxes":[],"yAxes":[{"ticks":{"beginAtZero":true}}]}}
                        });
                    }
                }
            });
        }

    </script>


@endsection