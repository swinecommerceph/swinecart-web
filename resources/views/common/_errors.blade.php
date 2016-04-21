{{--
	Display error messages on user requests such as filling up forms
--}}

@if (count($errors) > 0)
	{{-- Form Error List --}}
	<div class="card-panel">
		<strong> <span class="red-text text-darken-1"> Whoops! Something went wrong! </span> </strong>
		<br>
		<ul>
			@foreach ($errors->all() as $error)
				<li class="red-text">{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif
