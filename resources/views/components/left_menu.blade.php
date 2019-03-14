<ul id="left-menu" class="nav nav-list">
</ul>

@section('componentsJs')
    @parent

    <script>
        $(function () {
            var menuCacheKey = 'left_menu:' + window.location.host;
            var urlInfo = window.location.href.split('#')[0].split('/');
            var menuDataUrl = '{{ route("{$module}.getAccessResources") }}';

            var data = Operation.getSessionCache(menuCacheKey);
            if (data === null || data === "") {
                request(menuDataUrl, 'GET', {}, function (response) {
                    if (response.code != 0) {
                        return;
                    }

                    var data = {}
                    for (var i in response.data) {
                        var arr = response.data[i];
                        if (data[arr['pid']] === undefined) {
                            data[arr['pid']] = [arr];
                        } else {
                            data[arr['pid']].push(arr);
                        }
                    }

                    Operation.setSessionCache(menuCacheKey, JSON.stringify(data));
                    render(data);
                });
            } else {
                render(JSON.parse(data));
            }


            function render(data)
            {
                $('#left-menu').html(getMenuHtml(data, 0));

                $('#left-menu .active').parents('li').addClass('open');

                initPageTitle();
            }

            function getMenuHtml(menus, id) {
                if (menus[id] === undefined) {
                    return '';
                }

                var menuHtml = '';
                for (var k in menus[id]) {
                    var menu = menus[id][k];
                    var pid = menu['pid'];
                    var name = menu['display_name'];
                    var icon = menu['icon'];
                    var path = menu['path'];
                    var isCurrent = ('/'+urlInfo[3] === path);
                    var hasSubMenu = menus[menu['id']] !== undefined;

                    menuHtml +=
                        '<li class="' + (isCurrent ? 'active': '') + '">' +
                            '<a class="' + (hasSubMenu && id == 0 ? 'dropdown-toggle' : '') + '" href="'+ path +'">' +
                                '<i class="menu-icon fa fa-' + icon + '"></i>' +
                                '<span class="menu-text">'+ name +'</span>' +
                            '</a>' +
                            '<b class="arrow"></b>';
                    if (hasSubMenu) {
                        menuHtml += '<ul class="submenu ' + (path === '' ? '' : 'hide') + '">' + getMenuHtml(menus, menu['id']) + '</ul>';
                    } else {
                        menuHtml += '</li>';
                    }
                }

                return menuHtml;
            }
        });
    </script>
@endsection
