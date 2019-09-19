{{--
	View for Registration Page which includes form for Customer registration
--}}

@extends('layouts.default')

@section('pageId')
    id="page-register"
@endsection

@section('content')
  <br><br>
	<div class="row container center-align">
    <div class="row">
      <a 
        href="{{ route('customerRegister') }}"
        class="waves-effect waves-light btn primary register-button"
      >
        Register as Customer
      </a>  
    </div>

    <div class="row">
      <a 
        href="{{ route('breederRegister') }}"
        class="waves-effect waves-light btn primary register-button"
      >
        Register as Breeder
      </a>  
    </div>
    
	</div>
@endsection
