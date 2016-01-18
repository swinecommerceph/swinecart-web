@extends('layouts.twoColumn')

@section('left_column')
	<h4 class="center-align"> Register </h4>
	<!-- Display Validation Errors -->
	@include('common._errors')

	<!-- Registration Form -->
	<form action="{{ route('postRegister_path') }}" method="POST" class="col s12">
		{{ csrf_field() }}

		<!-- Name -->
		<div class="row">
			<div class="input-field col s12">
				<input type="text" id="name" name="name" value="{{ old('name') }}" autofocus>
				<label for="name">Name</label>
			</div>
		</div>

		<!-- E-Mail Address -->
		<div class="row">
			<div class="input-field col s12">
				<input type="email" id="email" name="email" value="{{ old('email') }}">
				<label for="email">E-Mail</label>
			</div>
		</div>

		<!-- Password -->
		<div class="row">
			<div class="input-field col s12">
				<input type="password" id="password" name="password">
				<label for="password">Password</label>
			</div>
		</div>

		<!-- Confirm Password -->
		<div class="row">
			<div class="input-field col s12">
				<input type="password" id="password_confirmation" name="password_confirmation">
				<label for="password_confirmation">Re-Type Password</label>
			</div>
		</div>

		<!-- Register Button -->
		<div class="col s6 push-s6">
			<button type="submit" class="btn waves-effect waves-light"> Register
				<i class="material-icons right">send</i>
			</button>
		</div>

	</form>
@endsection

@section('right_column')
	<h4>Downloadable Forms</h4>
	<p> Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.
        In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium.
    </p>
@endsection
