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
			<div class="card-panel" style="border: solid 1px #E1E4EC !important; box-shadow: 0px 0px !important; border-radius: 3px !important;">
				<div class="row s12">
					<p class="left-align" style="font-size: 1.125rem; line-height: 1.2; color: hsl(0, 0%, 45%);"> Login to your account</p>
					{{-- Display Validation Errors --}}
					

					{{-- Login Form --}}
					<form action="{{ url('login') }}" method="POST" class="col s12">
						{!! csrf_field() !!}

						{{-- E-Mail Address --}}
            <div class="row input-field">
              <input class="validate" type="email" id="email" name="email" value="{{ old('email') }}" autofocus placeholder="  Enter Email">
              <label for="email">E-mail Address</label>
            </div>

						{{-- Password --}}
            <div id="password-container" class="row input-field">
              <!-- <span style="display: inline-block; width: 4rem;"></span>
              <span><a style="font-size: 0.9rem;" href="/password/reset"> I forgot my password </a></span> -->
              
              {{-- Input field for password --}}
              <input class="validate col s11 login-password" type="password" id="password" name="password" placeholder="  Enter Password">
              <label for="password">Password</label>

              {{-- Show/Hide Password Button --}}
              <div class="col s1 show-hide-password"
                  style="cursor: pointer;">
                <i id="show-hide-password-icon" class="grey-text text-lighten-1 material-icons center-align">visibility</i>
              </div>
            </div>

						{{-- Login Button --}}
						<div class="row">
                <button type="submit" class="btn waves-effect waves-light col s12 teal darken-3">Login with Email</button>
            </div>

					</form>

					{{-- Social Login --}}
          <p class="center-align"> OR </p>

          {{-- Facebook Button --}}
          <a href="/login/facebook" class="waves-effect waves-light col s12 btn facebook">
            <i style="border-right: solid 1px rgba(0, 0, 0, 0.2); margin-right: 1rem; padding: 0 1.3rem;" class="fa fa-facebook"></i>
            Login with Facebook
          </a>

          <br><br>

          {{-- Google Button --}}
          <a href="/login/google" class="waves-effect waves-light red col s12 btn google">
            <i style="border-right: solid 1px rgba(0, 0, 0, 0.2); margin-right: 1rem; padding-right: 1rem; padding-left: 0.1rem;" class="fa fa-google"></i>
            Login with Google
          </a>
                   
				</div>
			</div>
		</div>
	</div>
@endsection

@section('customScript')
  <script src="{{ elixir('/js/login.js') }}"></script>
@endsection
