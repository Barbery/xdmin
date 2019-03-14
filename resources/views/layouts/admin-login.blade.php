<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<meta charset="utf-8" />
		<title>{{ $pageName }} - {{ $appConfig['appName'] ?? '' }}</title>

		<meta name="description" content="User login page" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="{{ asset('admin-assets/ace/css/bootstrap.min.css') }}" />
		<link rel="stylesheet" href="{{ asset('admin-assets/ace/font-awesome/4.7.0/css/font-awesome.min.css') }}" />

		<!-- ace styles -->
		<link rel="stylesheet" href="{{ asset('admin-assets/ace/css/ace.min.css') }}" />

		<!--[if lte IE 9]>
		<link rel="stylesheet" href="{{ asset('admin-assets/ace/css/ace-part2.min.css') }}" />
		<![endif]-->
		<link rel="stylesheet" href="{{ asset('admin-assets/ace/css/ace-rtl.min.css') }}" />

		<!--[if lte IE 9]>
		<link rel="stylesheet" href="{{ asset('admin-assets/ace/css/ace-ie.min.css') }}" />
		<![endif]-->

		<link rel="stylesheet" href="{{ asset('admin-assets/alertifyjs/css/alertify.min.css') }}" />
		<link rel="stylesheet" href="{{ asset('admin-assets/alertifyjs/css/themes/default.css') }}" />
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

		<!--[if lt IE 9]>
		<script src="{{ asset('admin-assets/ace/js/html5shiv.min.js') }}"></script>
		<script src="{{ asset('admin-assets/ace/js/respond.min.js') }}"></script>
		<![endif]-->
	</head>
	<body class="login-layout light-login">
		<div class="main-container">
			<div class="main-content">
				@yield('content')
			</div>
		</div>

		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script src="{{ asset('admin-assets/ace/js/jquery.2.1.1.min.js') }}"></script>

		<!-- <![endif]-->

		<!--[if IE]>
		<script src="{{ asset('admin-assets/ace/js/jquery.1.11.1.min.js') }}"></script>
		<![endif]-->

		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='{{ asset('admin-assets/ace/js/jquery.min.js') }}'>"+"<"+"/script>");
		</script>

		<!-- <![endif]-->

		<!--[if IE]>
		<script type="text/javascript">
			window.jQuery || document.write("<script src='{{ asset('admin-assets/ace/js/jquery1x.min.js') }}'>"+"<"+"/script>");
		</script>
		<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='{{ asset('admin-assets/ace/js/jquery.mobile.custom.min.js') }}'>"+"<"+"/script>");
		</script>

		<script src="{{ asset('admin-assets/alertifyjs/alertify.min.js') }}"></script>
		<script src="{{ asset('admin-assets/pub.js') }}"></script>

		@yield('inlineJs')
	</body>
</html>
