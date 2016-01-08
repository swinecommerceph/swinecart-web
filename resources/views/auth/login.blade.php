@extends('layouts.default')

@section('content')
	<div class="row">
		<div class="col s12 m6 offset-m3">
			<h4 class="center-align"> Login </h4>
			<!-- Display Validation Errors -->
			@include('common._errors')

			<!-- New Task Form -->
			<form action="{{ route('postLogin_path') }}" method="POST" class="col s12">
				{{ csrf_field() }}

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

				<!-- Login Button -->
				<div class="col s6 push-s6">
					<button type="submit" class="btn waves-effect waves-light"> Login 
						<i class="material-icons right">send</i>
					</button>
				</div>
				
			</form>
		</div>
	</div>
@endsection
