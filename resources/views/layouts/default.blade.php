<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Swine E-Commerce PH @yield('title') </title>

	<link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,700" rel="stylesheet" type="text/css">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet" type="text/css">

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-alpha1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

	<style>
		body {
			font-family: 'Raleway';
			margin-top: 25px;
		}

		.fa-btn {
			margin-right: 6px;
		}

		.table-text div {
			padding-top: 6px;
		}
	</style>

	<script>
		(function () {
			$('#task-name').focus();
		}());
	</script>
</head>

<body>

	<div class="container">
		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
                    <a class="navbar-brand" href="#">
                        <img alt="Brand" src="/images/logo.png" height="30" width="30">
                    </a>
					@if (Auth::guest())
						<a class="navbar-brand" href="{{ route('index_path') }}">Swine E-Commerce PH</a>
					@else
						<a class="navbar-brand" href="{{ route('home_path') }}">Swine E-Commerce PH</a>
					@endif
				</div>

				<div id="navbar" class="navbar-collapse collapse">
					<ul class="nav navbar-nav">
						&nbsp;
					</ul>

					<ul class="nav navbar-nav navbar-right">						
						@if(Auth::guest())
							<li><a href="{{ route('home_path') }}"><i class="fa fa-btn fa-binoculars"></i> Products </a></li>
							<li><a href="{{ route('getRegister_path') }}"><i class="fa fa-btn fa-pencil"></i>Register</a></li>
							<li><a href="{{ route('getLogin_path') }}"><i class="fa fa-btn fa-sign-in"></i>Login</a></li>
						@else		
							@yield('navbar_head')	
							<li class="dropdown">
					          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-btn fa-user"></i>{{ Auth::user()->name }} <span class="caret"></span></a>
					          <ul class="dropdown-menu">
					            <li><a href="#">Action</a></li>
					            <li><a href="#">Another action</a></li>
					            <li><a href="#">Something else here</a></li>
					            <li role="separator" class="divider"></li>
					            <li><a href="{{ route('logout_path') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
					          </ul>
					        </li>
						@endif
					</ul>
				</div>
			</div>
		</nav>
	</div>

	@yield('content')
</body>
</html>
