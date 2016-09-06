{{--
	Template for email layouts
 --}}

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Swine E-Commerce PH @yield('title') </title>

	{{-- <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,700" rel="stylesheet" type="text/css"> --}}
	{{-- <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> --}}

	{{-- Compiled and minified CSS --}}
	{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css">  --}}

	<link href="/css/materialize.min.css" rel="stylesheet" type="text/css">
	<link href="/css/icon.css" rel="stylesheet" type="text/css">
	<link href="/css/style.css" rel="stylesheet" type="text/css">
	{{-- @yield('email-css') --}}
</head>

<body>

	<div class="navbar-fixed">
		<nav class="teal darken-3">
		    <div class="nav-wrapper container">
			    <a class="brand-logo center" href="{{ route('home_path') }}">Swine E-Commerce PH</a
		    </div>
		</nav>
	</div>

	<div class="container">
		@yield('content')
	</div>

	{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-alpha1/jquery.min.js"></script> --}}

	{{-- Compiled and minified JavaScript --}}
	{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js"></script> --}}

	<script src="/js/vendor/jquery.min.js"></script>
	<script src="/js/vendor/materialize.min.js"></script>

</body>
</html>
