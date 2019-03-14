@section('componentsLink')
    @parent

    <link rel="stylesheet" href="{{ asset('admin-assets/jstree/themes/default/style.min.css') }}" />
@endsection

<div id="js-tree-wrapper">
    <ul id="js-tree">
    </ul>
</div>

@section('componentsJs')
    @parent

    <script>
        $(function () {
            request('{{ $menu_url }}', 'GET', {}, function (data) {
                var treeHtml = renderTree(data.data);

                $('#js-tree').html(treeHtml);

                $('#js-tree-wrapper').jstree({
                    "conditionalselect" : function (node, event) {
                        return false;
                    },
                    "plugins": ["conditionalselect"]
                });
            });

            function renderTree(menus) {
                var treeHtml = '';
                for (var k in menus) {
                    var menu = menus[k];

                    var id = menu['id'];
                    var name = menu['display_name'];
                    var icon = menu['icon'];
                    var url = menu['url'];
                    var subMenus = menu['sub'] == undefined ? false : menu['sub'];

                    var treeAttr = {"opened" : true, "icon":"fa fa-bookmark"};
                    var treeAttrStr = JSON.stringify(treeAttr);
                    treeHtml += '<li data-jstree=\''+ treeAttrStr +'\'>' + name;
                    treeHtml += ' <span class="edit-btn-group"><a class="edit-btn" href="/permission/'+ id +'/edit">编辑</a> </span>'

                    if (subMenus) {
                        treeHtml += '<ul>' + renderTree(subMenus) + '</ul>';
                    }
                    treeHtml += '</li>';
                }

                return treeHtml;
            }
        });
    </script>
@endsection

@section('componentsScript')
    @parent

    <script src="{{ asset('admin-assets/jstree/jstree.min.js') }}"></script>
@endsection