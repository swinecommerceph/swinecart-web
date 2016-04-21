{{--
	Template for the two-column layout of a page
	It is a same size two-column page layout
 --}}

@extends('layouts.default')

@section('content')
	<div class="row">
		<div class="col s12 m6">
			@yield('left_column')
		</div>

		<div class="col s12 m6">
			@yield('right_column')
		</div>
	</div>
@endsection
