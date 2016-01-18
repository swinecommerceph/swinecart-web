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

<body>

	<nav class="teal darken-3">
	    <div class="nav-wrapper container">
	      	@if (Auth::guest())
				<a class="brand-logo" href="{{ route('index_path') }}">Swine E-Commerce PH</a>
		  	@else
				<a class="brand-logo" href="{{ route('home_path') }}">Swine E-Commerce PH</a>
		  	@endif

	      	<ul id="nav-mobile" class="right hide-on-med-and-down">
	        @if(Auth::guest())
				<li><a href="{{ route('home_path') }}"> <i class="material-icons left">shop_two</i> Products </a></li>
				<li><a href="{{ route('getRegister_path') }}" class="waves-effect waves-light btn">Register</a>
				</li>
				<li><a href="{{ route('getLogin_path') }}" class="waves-effect waves-light btn">Login</a></li>
			@else
				@yield('navbar_head')
				<li>
					<a class="dropdown-button" data-beloworigin="true" data-activates="nav-dropdown">
						{{ Auth::user()->name }} <i class="material-icons right">arrow_drop_down</i>
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
	</nav>


	<div class="container">

		@yield('content')

	</div>

	{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-alpha1/jquery.min.js"></script> --}}

	<!-- Compiled and minified JavaScript -->
	<!--script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js"></script-->

	<script src="/js/jquery.min.js"></script>
	<script src="/js/materialize.min.js"></script>
	<script src="/js/custom.js"></script>

</body>
</html>
