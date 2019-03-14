<div class="pull-right">
    <div class="btn-group mr10">
        <a href="javascript:0" id="export" data-href="{{ route($route_name ?? "{$route}.export") }}" target="_blank" class="btn btn-primary btn-sm">
            <i class="glyphicon glyphicon-plus"></i> {{ $btn_label ?? '导出' }}</a>
    </div>
</div><!-- pull-right -->

<script type="text/javascript">
    $(function() {
        const exportBtn = $('#export');
        exportBtn.click(function(){
            var url = exportBtn.data("href") + '?filter=' + JSON.stringify(lastParams) + '&format=excel';
            window.open(url);
        })
    })
</script>
