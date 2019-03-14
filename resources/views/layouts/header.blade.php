<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8" />
    <title>{{ $appConfig['appName'] ?? '管理系统' }}</title>

    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.staticfile.org/font-awesome/4.7.0/css/font-awesome.min.css" />

    @yield('beforeAceLink')

    <link rel="stylesheet" href="{{ asset('admin-assets/ace/css/ace.min.css') }}" class="ace-main-stylesheet" id="main-ace-style" />
    <!--[if lte IE 9]>
    <link rel="stylesheet" href="{{ asset('admin-assets/ace/css/ace-part2.min.css') }}" class="ace-main-stylesheet" />
    <![endif]-->
    <!--[if lte IE 9]>
    <link rel="stylesheet" href="{{ asset('admin-assets/ace/css/ace-ie.min.css') }}" />
    <![endif]-->

    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!--[if !IE]> -->
    <script src="https://cdn.staticfile.org/jquery/2.2.4/jquery.min.js"></script>
    <!-- <![endif]-->
    <!--[if IE]>
    <script src="https://cdn.staticfile.org/jquery/1.12.4/jquery.min.js"></script>
    <![endif]-->
    <!--[if IE]>
    <script type="text/javascript">
        window.jQuery || document.write("<script src='{{ asset('admin-assets/ace/js/jquery1x.min.js') }}'>"+"<"+"/script>");
    </script>
    <![endif]-->

    <link rel="stylesheet" href="https://cdn.staticfile.org/AlertifyJS/1.7.1/css/alertify.min.css" />
    <link rel="stylesheet" href="https://cdn.staticfile.org/AlertifyJS/1.7.1/css/themes/default.rtl.min.css" />

    <script src="https://cdn.staticfile.org/AlertifyJS/1.7.1/alertify.min.js"></script>

    <script src="{{ asset('admin-assets/pub.js') }}"></script>

    @yield('extraLink')

    @yield('componentsLink')

    <script type="text/javascript">
        if ('ontouchstart' in document.documentElement)
            document.write("<script src='{{ asset('admin-assets/ace/js/jquery.mobile.custom.min.js') }}'>"+"<"+"/script>");
        try {
            ace.settings.check('navbar' , 'fixed');
            ace.settings.check('main-container' , 'fixed');
            ace.settings.check('sidebar' , 'fixed');
            ace.settings.check('breadcrumbs' , 'fixed');
        } catch(e) {}

        // 在初始化左边菜单后根据选中的菜单设置页面的title
        function initPageTitle() {
            var appName = '{{ $appConfig['appName'] ?? '管理系统' }}';
            var name = $('#left-menu li.active span:first').text();
            // if (name) {
            //     document.title = name + '-' + appName;
            //     $('#page-title').text(name);
            // } else {
            //     document.title = appName;
            // }
        }
    </script>

    <style>
        .upload-section {
            position: relative;
        }

        .upload-section .upload-area {
            width: 200px;
            border: 1px solid #dddddd;
        }

        .upload-section .status-bar {
            width: 200px;
            height: 24px;
            position: absolute;
            z-index: 100;
            line-height: 24px;
            font-size: 12px;
            color: white;
            background-color: yellow;
            bottom: 32px;
            text-align: center;
            display: none;
            opacity: 0.8;
            filter: alpha(opacity=0.8);
        }

        .upload-section .btn-group {
            margin-top: 5px;
        }

        .upload-section .thumbmail {
            width: 200px;
        }

        .export-bar {
            text-align: right;
        }

        .chosen-container>.chosen-single, [class*=chosen-container]>.chosen-single {
            background: white;
        }
    </style>

    @yield('componentsStyle')

    @yield('inlineStyle')
</head>
