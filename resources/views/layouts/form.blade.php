@extends('layouts.admin-app')

@section('extraLink')
  <link rel="stylesheet" href="{{ asset('admin-assets/dist/css/form.css') }}" />
@endsection

@section('content')
  <div class="row">
    <div class="col-xs-12">
      @yield('content')
    </div>
  </div>
@endsection

@section('inlineJs')
    <script>
        $(function() {
            var method = '';
            $('#save-btn').click(function() {
                var url = '';
                var method = '';
                @if (isset($Model) && $Model->id > 0)
                    url = '{{ route("{$route}.update", ['id' => isset($Model) ? $Model->id : 0]) }}';
                    method = 'put';
                @else
                    url = '{{ route("{$route}.store") }}';
                    method = 'post';
                @endif

                Operation.submit($(this), url, method, $('#form').serialize(), function() {
                    alertify.success('保存成功');
                    setTimeout(function() {
                        location.href = '{{ route("{$route}.index") }}';
                    }, 500);
                });
            });
        });
    </script>
@endsection
