{{--
	View for Registration Page which includes form for Customer registration
--}}

@extends('layouts.default')

@section('pageId')
    id="page-register"
@endsection

@section('content')
	<div class="row container">
    <div class="col s12 m6 offset-m3">
      <div class="card-panel">
        <div class="row s12">
          <h4 class="center-align"> Register as Breeder </h4>  

          <br>
          {{-- Display Validation Errors --}}
          @include('common._errors')

          {{-- Registration Form --}}
          <form id="registration-form" action="{{ url('register') }}" method="POST" class="col s12">
            {{ csrf_field() }}

            {{-- Accreditation Number --}}
            <div class="row">
              <div class="input-field col s12">
                <input 
                  class="validate"
                  id="accreditation-number"
                  name="accreditation-number"
                  value="{{ old('accreditation-number') }}"
                  autofocus
                >
                <label for="accreditation-number">Accreditation number</label>
              </div>
            </div>

            {{-- E-Mail Address --}}
    					<div class="row">
    						<div class="input-field col s12">
                  <input 
                    class="validate"
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                  >
    							<label for="email">E-mail Address</label>
    						</div>
    					</div>

            {{-- For Terms and Policy --}}
            <div class="terms-and-policy-container">
              <p class="terms-and-policy">
                By clicking Register, you agree to our
              <a href="{{ route('termsOfAgreement') }}" target="_blank">Terms</a>
                and
                <a href="{{ route('breederPrivacyPolicy') }}" target="_blank">Data Policy</a>.
              </p>
            </div>

            {{-- Register Button --}}
            <div class="row">
              <div class="">
                <button 
                  type="submit"
                  class="btn primary primary-hover col s5 push-s7"
                >
                  Register as Breeder
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
