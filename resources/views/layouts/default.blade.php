<!--
	Template for the default layout of a page
	It is a whole one column page layout
-->

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Swine E-Commerce PH @yield('title') </title>

	{{-- <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,700" rel="stylesheet" type="text/css"> --}}
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

	<!-- Compiled and minified CSS -->
	{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css">  --}}

	<link href="/css/materialize.min.css" rel="stylesheet" type="text/css">
	<link href="/css/style.css" rel="stylesheet" type="text/css">
</head>

<body @yield('page-id')>

	<div class="navbar-fixed">
		<nav class="teal darken-3">
		    <div class="nav-wrapper container">
		      	@if (Auth::guest())
					<a class="brand-logo" href="{{ route('index_path') }}">Swine E-Commerce PH</a>
			  	@else
					<a class="brand-logo" href="{{ route('home_path') }}">Swine E-Commerce PH</a>
			  	@endif

		      	<ul id="nav-mobile" class="right hide-on-med-and-down">
		        @if(Auth::guest())
					<li><a href="{{ route('home_path') }}"> Products </a></li>
					<li><a href="{{ route('home_path') }}"> ASBAP </a></li>
					@if(!Request::is('/'))
						@if(!Request::is('login'))
							<li><a href="{{ route('getLogin_path') }}" class="waves-effect waves-light btn">Login</a></li>
						@elseif(!Request::is('register'))
							<li><a href="{{ route('getRegister_path') }}" class="waves-effect waves-light btn">Register</a></li>
						@endif
					@endif
				@else
					<li> <span>{{ Auth::user()->name }}</span> </li>
					@yield('navbar_head')
					<li>
						<a class="dropdown-button" data-beloworigin="true" data-activates="nav-dropdown">
							 <i class="material-icons">arrow_drop_down</i>
						</a>
						<ul id="nav-dropdown" class="dropdown-content">
					        @yield('navbar_dropdown')
					        <li class="divider"></li>
					        <li><a href="{{ route('logout_path') }}">Logout</a></li>
					    </ul>
					</li>


				@endif
		      	</ul>
		    </div>
			<div id="progress" class="progress" style="display:none;">
				<div class="indeterminate"></div>
			</div>
			<div id="search-field" class="nav-wrapper white z-depth-1" style="display:none;">
	            <div style="height:1px;">
	            </div>
	            <form>
	                <div class="input-field">
	                    <input id="search" type="search" placeholder="Search for a product" required>
	                    <label for="search"><i class="material-icons teal-text">search</i></label>
	                    <i class="material-icons">close</i>
	                </div>
	            </form>
	        </div>
		</nav>
	</div>

	@if(Auth::check() && !Request::is('*/home'))
		<div class="grey lighten-3">
	        <div class="container">
	            <div class="row">
	                <div class="col s12">
	                    <h4 class="breadcrumb-title"> @yield('breadcrumb-title') </h4>
	                </div>
	                <div id="breadcrumb" class="col s12">
	                    @yield('breadcrumb')
	                </div>
	            </div>
	        </div>
	    </div>
	@endif

	@yield('static')
	@yield('homeContent')
	@if(!Request::is('/'))
		<div class="container">
			@yield('content')
		</div>
	@endif

	{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-alpha1/jquery.min.js"></script> --}}
	{{-- <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script> --}}
	<!-- Compiled and minified JavaScript -->
	<!--script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js"></script-->

	<script src="/js/jquery.min.js"></script>
	<script src="/js/materialize.min.js"></script>
	<script src="/js/config.js"></script>
	<script src="/js/custom.js"></script>
	@yield('customScript')

</body>
</html>
