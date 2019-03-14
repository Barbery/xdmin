@section('componentsLink')
    @parent

    <link rel="stylesheet" href="{{ asset('admin-assets/jstree/themes/default/style.min.css') }}" />
@endsection

<div id="js-tree-wrapper">
</div>

@section('componentsJs')
    @parent

    <script>
        $(function () {
          var $jsTree = $('#js-tree-wrapper');
          $jsTree.jstree({
            "plugins" : ["checkbox"],
            'core' : {
                "themes":{
                    "icons":false
                },
                'data' : {!! json_encode($tree) !!}
            }
          });
        });
    </script>
@endsection

@section('componentsScript')
    @parent

    <script src="{{ asset('admin-assets/jstree/jstree.min.js') }}"></script>
@endsection
