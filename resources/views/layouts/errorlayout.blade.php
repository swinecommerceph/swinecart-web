{{--
	Template for the default layout of a page
	It is a whole one column page layout
 --}}

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>SwineCart @yield('title') </title>

	{{-- <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,700" rel="stylesheet" type="text/css"> --}}
	{{-- <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> --}}

	{{-- Compiled and minified CSS --}}
	{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/css/materialize.min.css">  --}}
	{{-- <link href="http://vjs.zencdn.net/5.9.2/video-js.min.css" rel="stylesheet"> --}}

	<link href="/css/materialize.min.css" rel="stylesheet" type="text/css">
	<link href="/css/dropzone.css" rel="stylesheet" type="text/css">
	<link href="/css/icon.css" rel="stylesheet" type="text/css">
	<link href="/css/style.css" rel="stylesheet" type="text/css">
	<link href="/js/vendor/VideoJS/video-js.min.css" rel="stylesheet">
	<link href="/js/vendor/DataTables/datatables.min.css" rel="stylesheet">

	<!-- If you'd like to support IE8 -->
  	{{-- <script src="http://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script> --}}

	<script src="/js/vendor/VideoJS/ie8/videojs-ie8.min.js"></script>
</head>

<body @yield('pageId')>
	{{-- Navbar --}}
	<div class="navbar-fixed">
		<nav class="teal darken-3">
		    <div class="nav-wrapper container">
	      		<img src="/images/logowhite.png" height=65 style="padding:.4rem 0 .4rem 0;"/>
				&nbsp&nbsp<a class="brand-logo" href="{{ route('index_path') }}">SwineCart</a>
		    </div>
			<ul id="nav-mobile" class="right">
				<li><a class="waves-effect waves-light btn" href="{{ url('logout') }}">Logout</a></li>
			@if(Auth::guest())

			@else
				<li> <a>{{ Auth::user()->name }}</a> </li>
				@yield('navbarHead')
				<li>
					<a class="dropdown-button" data-beloworigin="true" data-hover="true" data-alignment="right" data-activates="nav-dropdown">
						<i class="material-icons">arrow_drop_down</i>
					</a>
					<ul id="nav-dropdown" class="dropdown-content">
						@yield('navbarDropdown')
						<li class="divider"></li>
						<li><a href="{{ url('logout') }}">Logout</a></li>
					</ul>
				</li>
			@endif
			</ul>
		</nav>
	</div>

	{{-- For static elements such as add product and back to top --}}
	@yield('static')

	{{-- Home view for unauthenticated users --}}
	@yield('homeContent')


	{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-alpha1/jquery.min.js"></script> --}}
	{{-- <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script> --}}
	{{-- Compiled and minified JavaScript --}}
	{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/js/materialize.min.js"></script> --}}
	{{-- <script src="http://vjs.zencdn.net/5.9.2/video.js"></script> --}}

	<script src="/js/vendor/jquery.min.js"></script>
	<script src="/js/vendor/materialize.min.js"></script>
	<script src="/js/vendor/dropzone.js"></script>
	<script src="/js/vendor/VideoJS/video.min.js"></script>
	<script src="/js/config.js"></script>
	<script src="/js/custom.js"></script>
	{{-- For user-specific initialization scripts --}}
	@yield('initScript')
	{{-- Custom scripts for certain pages/functionalities --}}
	@yield('customScript')

</body>
</html>
