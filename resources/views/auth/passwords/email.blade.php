{{--
	View for sending of Password Reset Link
--}}

@extends('layouts.default')

@section('page-id')
    page-send-password-reset-link
@endsection

@section('content')
    <div class="row">
        <div class="col s12 m6 offset-m3">
            <div class="card">
                <div class="card-content row">
                    <span class="card-title">Reset Password</span>
                    <blockquote class="info">
                        Type your e-mail address and we will send a password reset link to it.
                    </blockquote>
                    {{-- Display Validation Errors --}}
					@include('common._errors')

                    @if (session('status'))
                        <div class="card-panel green-text">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{-- Login Form --}}
					<form action="{{ url('/password/email') }}" method="POST" class="col s12">
						{{ csrf_field() }}

                        {{-- E-Mail Address --}}
						<div class="row">
							<div class="input-field col s12">
								<input type="email" id="email" name="email" value="{{ old('email') }}" class="validate" autofocus required>
                                @if ($errors->has('email'))
		                            <label for="email" data-error="{{ $errors->first('email') }}">E-mail</label>
                                @else
                                    <label for="email">E-mail</label>
                                @endif
							</div>
						</div>

                        {{-- Send Password Reset Link button --}}
						<div class="row">
							<div class="">
								<button type="submit" class="btn waves-effect waves-light col s6 push-s6"> Send Password Reset Link
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
