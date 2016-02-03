{{--
	View for Customer and Breeder Login
--}}

@extends('layouts.default')

@section('content')
	<div class="row">
		<div class="col s12 m6 offset-m3">
			<div class="card-panel">
				<div class="row s12">
					<h4 class="center-align"> Login </h4>
					<!-- Display Validation Errors -->
					@include('common._errors')

					<!-- Login Form -->
					<form action="{{ route('postLogin_path') }}" method="POST" class="col s12">
						{{ csrf_field() }}

						<!-- E-Mail Address -->
						<div class="row">
							<div class="input-field col s12">
								<input type="email" id="email" name="email" value="{{ old('email') }}" autofocus>
								<label for="email">E-mail</label>
							</div>
						</div>

						<!-- Password -->
						<div class="row">
							<div class="input-field col s12">
								<input type="password" id="password" name="password">
								<label for="password">Password</label>
							</div>
						</div>

						<!-- Login Button -->
						<div class="row">
							<div class="">
								<button type="submit" class="btn waves-effect waves-light col s4 push-s8"> Login
									<i class="material-icons right">send</i>
								</button>
							</div>
						</div>

					</form>

					<div class="row">
						<h5 class="center-align"> OR </h5>
						<!-- Facebook Button -->
						<div class="col s12">
							<a href="/login/facebook" class="btn-large waves-effect waves-light blue col s12"> Login with Facebook </a>
						</div>
					</div>

					<div class="row">
						<!-- Google Button -->
						<div class="col s12">
							<a href="/login/google" class="btn-large waves-effect waves-light red col s12"> Login with Google </a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
