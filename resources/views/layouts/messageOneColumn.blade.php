<!--
	Template for email layouts
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

<body>

	<div class="navbar-fixed">
		<nav class="teal darken-3">
		    <div class="nav-wrapper container">
			    <a class="brand-logo center" href="{{ route('home_path') }}">Swine E-Commerce PH</a
		    </div>
		</nav>
	</div>
	{{-- <nav id="breadcrumb" class="teal lighten-5">
		<div class="nav-wrapper container">
			@yield('breadcrumb')
		</div>
	</nav> --}}


	<div class="container">

		@yield('content')

	</div>

	{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-alpha1/jquery.min.js"></script> --}}

	<!-- Compiled and minified JavaScript -->
	<!--script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js"></script-->

	<script src="/js/jquery.min.js"></script>
	<script src="/js/materialize.min.js"></script>

</body>
</html>
