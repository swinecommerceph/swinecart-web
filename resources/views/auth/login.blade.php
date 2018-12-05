{{--
	View for Customer and Breeder Login
--}}

@extends('layouts.default')

@section('pageId')
    id="page-login"
@endsection

@section('content')
	<br>
	<div class="row">
		<div class="col s12 m4 offset-m4">
			<div class="card-panel">
				<div class="row s12">
					<p class="left-align" style="font-size: 1.125rem; line-height: 1.2; margin-left: 0.75rem;"> Login to your account</p>
					{{-- Display Validation Errors --}}
					@include('common._errors')

					{{-- Login Form --}}
					<form action="{{ url('login') }}" method="POST" class="col s12">
						{!! csrf_field() !!}

						{{-- E-Mail Address --}}
						<div class="row">
							<div class="input-field col s12">
								<label for="email" style="font-weight: 700; color: hsl(0, 0%, 45%);">E-mail address</label>
								<input type="email" id="email" name="email" value="{{ old('email') }}" autofocus placeholder="Enter email" required>
							</div>
						</div>

						{{-- Password --}}
						<div class="row">
							<div class="input-field col s12">
								<label for="password" style="font-weight: 700; color: hsl(0, 0%, 45%);">Password</label>
								<input type="password" id="password" name="password" placeholder="Password" required>
							</div>
						</div>

						{{-- Login Button --}}
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
						{{-- Facebook Button --}}
						<div class="col s12">
							<a href="/login/facebook" class="btn-large waves-effect waves-light indigo darken-2 col s12 social-button"> Login with Facebook </a>
						</div>
					</div>

					<div class="row">
						{{-- Google Button --}}
						<div class="col s12">
							<a href="/login/google" class="btn-large waves-effect waves-light red col s12 social-button"> Login with Google </a>
						</div>
					</div>

                    <div class="row">
						{{-- Forgot Password --}}
						<div class="col s12 center-align">
							<a href="/password/reset"> Forgot Password </a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
