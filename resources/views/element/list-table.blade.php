<!-- div.dataTables_borderWrap -->
<div>
    <table id="list-table"
           data-toolbar="#toolbar"
           data-url="{{ $data_url ?? route("{$route}.search") }}"
           data-id-field="id"
           data-toggle="table"
           data-side-pagination="server"
           data-page-list="[10, 30, 50, 100]"
           data-response-handler="responseHandler"
           data-toolbar-align="top"
           data-query-params="queryParams"
           data-pagination="true"
           data-striped="true"
           >
        <thead>
          {!! $th_header !!}
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

@section('componentsScript')
  @parent
  <script src="https://cdn.staticfile.org/bootstrap-table/1.12.1/bootstrap-table.min.js"></script>
@endsection


@section('inlineJs')
  @parent
  <script type="text/javascript">
    var $table = $('#list-table'),
        $inputs = $('#toolbar').find('input[name]'),
        $selects = $('#toolbar').find('select'),
        $ok = $('#search-btn');

    $(function () {
      $ok.click(function () {
        $table.bootstrapTable('refresh');
      });
    });

    function responseHandler(sourceData)
    {
      if (sourceData.code == 0) {
        var pageData = sourceData.data;
        return {
            "total": pageData.total,
            "rows": pageData.data
        }
      } else {
        alertify.notify(sourceData.msg, 'error', 5);
        return {
            "total": 0,
            "rows": []
        }
      }
    }

    var lastParams = [];
    function queryParams(options) {
      var params = [];
      $inputs.each(function () {
        obj = $(this);
        var operator = obj.attr('data-operator')
        operator = operator === "" ? "=" : operator;
        if (obj.val() !== "") {
          params.push([obj.attr('name'), operator, obj.val()]);
        }
      });

      $selects.each(function () {
        obj = $(this);
        var operator = obj.attr('data-operator')
        operator = operator === "" ? "=" : operator;
        if (obj.val() !== "") {
          params.push([obj.attr('name'), operator, obj.val()]);
        }
      });

      var queryParams = {filter: JSON.stringify(params), per_page:options.limit, page: JSON.stringify(lastParams) === JSON.stringify(params) ? (options.offset/options.limit)+1 : 1};
      lastParams = params;
      return queryParams;
    }

      function formatter(value, row, index)
      {
        if (maps[this.field] === undefined || maps[this.field][value] === undefined) {
          return;
        }

        return maps[this.field][value];
      }

      function formatMoney(value, row, index)
      {
        return parseFloat(value/100, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString();
      }

      function formatDate(value, row, index)
      {
        if (value == 0) {
          return '-';
        }

        var date = new Date(value * 1000),
            month = date.getMonth()+1,
            day = date.getDate(),
            hours = date.getHours(),
            minutes = date.getMinutes(),
            seconds = date.getSeconds();

        month = month > 9 ? month : '0' + month;
        day = day > 9 ? day : '0' + day;
        hours = hours > 9 ? hours : '0' + hours;
        minutes = minutes > 9 ? minutes : '0' + minutes;
        seconds = seconds > 9 ? seconds : '0' + seconds;
        return date.getFullYear() + '-' + month + '-' + day + ' ' + hours + ':' + minutes + ':' + seconds;
      }

      function delItem(obj, msg) {
        Operation.confirm_submit(msg || '确认删除？', $(obj), $(obj).data('href'), 'delete', {}, function () {
          alertify.success('操作成功');
          setTimeout(function() {
              Operation.refreshTable();
          }, 200);
        });
      }

      function toggleItem(obj, msg, data, method) {
        Operation.confirm_submit(msg || '是否确认该操作？', $(obj), $(obj).data('href'), method, eval('('+data+')'), function () {
          alertify.success('操作成功');
          setTimeout(function() {
              Operation.refreshTable();
          }, 200);
        });
      }

      function getButton(title, href, confirmMsg, data, method)
      {
        if (confirmMsg == '') {
          confirmMsg = '是否确认' + title;
        }

        return '<button class="btn btn-default btn-sm" data-href="'+href+'" onclick="toggleItem(this, \''+confirmMsg+'\', \''+JSON.stringify(data).replace(/["']/g, '\\\'')+'\',\''+method+'\')">'+title+'</button>';
      }
  </script>
@endsection
