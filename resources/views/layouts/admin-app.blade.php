<!DOCTYPE html>
<html lang="en">

@include('layouts.header')

<body class="no-skin">
@includeFirst(["{$module}::layouts.navbar", 'layouts.navbar'])

<div class="main-container" id="main-container">
	<div id="sidebar" class="sidebar responsive ace-save-state sidebar-fixed">
		@include('components.left_menu')
	</div>
	<div class="main-content">
		<div class="main-content-inner">
			<div class="breadcrumbs ace-save-state breadcrumbs-fixed" id="breadcrumbs">
				@include('components.breadcrumb')
			</div>
			<div class="page-content">

				@section('pageTitle')
					<div class="page-header">
						<h1 id="page-title"></h1>
					</div>
				@show

				@yield('content')
			</div>
		</div>
	</div>

	@include('layouts.footer')
</div>

<script src="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="{{ asset('admin-assets/ace/js/ace-elements.min.js') }}"></script>
<script src="{{ asset('admin-assets/ace/js/ace.min.js') }}"></script>

@yield('extraScript')

@yield('componentsScript')

@yield('componentsJs')

@yield('inlineJs')
</body>
</html>
