{{--
    View for reseting the User's password after clicking
    the Password Reset Link with the token
 --}}

@extends('layouts.default')

@section('page-id')
    page-password-reset
@endsection

@section('content')
    <div class="row">
        <div class="col s12 m6 offset-m3">
            <div class="card">
                <div class="card-content row">
                    <span class="card-title">Reset Password</span>
                    {{-- Display Validation Errors --}}
					@include('common._errors')

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{-- Login Form --}}
					<form action="{{ url('/password/reset') }}" method="POST" class="col s12">
						{{ csrf_field() }}

                        <input type="hidden" name="token" value="{{ $token }}">

                        {{-- E-Mail Address --}}
						<div class="row">
							<div class="input-field col s12">
								<input type="email" id="email" name="email" value="{{ old('email') }}" autofocus required>
                                @if ($errors->has('email'))
		                            <label for="email" data-error="{{ $errors->first('email') }}">E-mail</label>
                                @else
                                    <label for="email">E-mail</label>
                                @endif
							</div>
						</div>

                        {{-- Password --}}
						<div class="row">
							<div class="input-field col s12">
								<input type="password" id="password" name="password" class="validate" required>
                                @if ($errors->has('password'))
		                            <label for="password" data-error="{{ $errors->first('password') }}">Password</label>
                                @else
                                    <label for="password">Password</label>
                                @endif
							</div>
						</div>

                        {{-- Password Confirmation --}}
						<div class="row">
							<div class="input-field col s12">
								<input type="password" id="password-confirm" name="password_confirmation" class="validate" required>
                                @if ($errors->has('password_confirmation'))
		                            <label for="password-confirm" data-error="{{ $errors->first('password_confirmation') }}">Confirm Password</label>
                                @else
                                    <label for="password-confirm">Confirm Password</label>
                                @endif
							</div>
						</div>

                        {{-- Send Password Reset Link button --}}
						<div class="row">
							<div class="">
								<button type="submit" class="btn waves-effect waves-light col s6 push-s6"> Reset Password
									<i class="material-icons right">send</i>
								</button>
							</div>
						</div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
