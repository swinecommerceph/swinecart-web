{{--
	View for Registration Page which includes form for Customer registration
--}}

@extends('layouts.default')

@section('pageId')
    id="page-register"
@endsection

@section('content')
  <br>
  {!! Form::open([
    //'route' => 'breeder.store',
    //'method' => 'PATCH',
    'class' => 's12',
    'id' => 'breeder-register'
  ]) !!}
    <div class="row container">
      <h4 class="left-align">Register as Breeder</h4>

      <div class="col s12">
        <ul class="tabs z-depth-1">
          <li id="personal-tab" class="tab col s6">
            <a href="#personal-information">Office Information</a>
          </li>
          <li id="farm-tab" class="tab col s6">
            <a href="#farm-information">Farm Information</a>
          </li>
        </ul>
      </div>

      <!-- Office/Personal Information -->
      <div class="col s12">
        <div id="personal-information" class="card-panel">
          
          <!-- Office Address 1 -->
          <div class="row">
            <div class="input-field col s10 push-s1">
              {!! 
                Form::text('officeAddress_addressLine1', null, [
                  'autofocus' => 'autofocus', 'id' => 'officeAddress_addressLine1'
                ]) 
              !!}
              {!! Form::label(
                'officeAddress_addressLine1',
                'Address Line 1* : Street,
                Road,
                Subdivision'
              )!!}
            </div>
          </div>

          <!-- Office Address 2 -->
          <div class="row">
            <div class="input-field col s10 push-s1">
              {!! Form::text('officeAddress_addressLine2', null, [
                'id' => 'officeAddress_addressLine2'
              ])!!}
              {!! Form::label(
                'officeAddress_addressLine2',
                'Address Line 2* : Barangay,
                Town,
                City'
              ) !!}
            </div>
          </div>

          <div class="row">

            <!-- Office Province -->
            <div id="select-province" class="input-field col s5 push-s1">
              {!! Form::select('officeAddress_province', $provinces, null, [
                'id' => 'office_provinces'
              ]) !!}
              <label>Province*</label>
            </div>
            
            <!-- Office Postal/Zip Code -->
            <div class="input-field col s5 push-s1">
              {!! Form::text('officeAddress_zipCode', null, ['id' => 'officeAddress_zipCode'])!!}
              {!! Form::label('officeAddress_zipCode', 'Postal/ZIP Code*') !!}
            </div>

          </div>
          
          <div class="row">

            <!-- Office Landline Number -->
            <div class="input-field col s5 push-s1">
              {!! Form::text('office_landline', null, ['id' => 'office_landline'])!!}
              {!! Form::label('office_landline', 'Landline') !!}
            </div>

            <!-- Office Mobile Number -->
            <div class="input-field col s5 push-s1">
              {!! Form::text('office_mobile', null, ['id' => 'office_mobile'])!!}
              {!! Form::label('office_mobile', 'Mobile*') !!}
            </div>

          </div>

          <!-- Contact Person Header -->
          <div class="row">
            <div class="col s10 offset-s1">
              <h5 class="center-align">Contact Person Details</h5>
            </div>
          </div>

          <div class="row">

            <!-- Contact Person Name -->
            <div class="input-field col s5 push-s1">
              {!! Form::text('contactPerson_name', null, ['id' => 'contactPerson_name'])!!}
              {!! Form::label('contactPerson_name', 'Name*') !!}
            </div>

            <!-- Contact Person's Mobile Number -->
            <div class="input-field col s5 push-s1">
              {!! Form::text('contactPerson_mobile', null, ['id' => 'contactPerson_mobile'])!!}
              {!! Form::label('contactPerson_mobile', 'Mobile*') !!}
            </div>

          </div>

          <!-- Other Information Header -->
          <div class="row">
            <div class="col s10 offset-s1">
              <h5 class="center-align">Other information</h5>
            </div>
          </div>

          <div class="row">

            <!-- Office Website -->
            <div class="input-field col s5 push-s1">
              {!! Form::text('website', null, ['id' => 'website'])!!}
              {!! Form::label('website', 'Website') !!}
            </div>

            <!-- What the Office Produce -->
            <div class="input-field col s5 push-s1">
              {!! Form::text('produce', null, ['id' => 'produce'])!!}
              {!! Form::label('produce', 'Produce') !!}
            </div>
          </div>

          <!-- Next button -->
          <div class="row">
            <div class="col s10 offset-s1">
              <div class="col right">
                <a 
                  href="#"
                  id="next"
                  class="btn-floating btn-medium waves-effect waves-light teal darken-1 tooltipped"
                  data-position="left"
                  data-delay="50"
                  data-tooltip="Next">
                  <i class="material-icons">chevron_right</i>
                </a>
              </div>
            </div>
          </div>

        </div>

        <!-- Farm Information -->
        <div id="farm-information" class="card-panel">

          <!-- Accreditation Information Header -->
          <div class="row">
            <div class="col s10 offset-s1">
              <h5 class="center-align">Accreditation Information</h5>
            </div>
          </div>

          <!-- Accreditation Number -->
          <div class="row">
            <div class="input-field col s10 push-s1">
              {!! Form::text('farm_accreditation_number', null, [
                'id' => 'farm_accreditation_number'
              ])!!}
              {!! Form::label('farm_accreditation_number', 'Accreditation Number of farm*') !!}
            </div>
          </div>

          <!-- Accreditation Dates -->
          <div class="row">
            <!-- Date evaludated -->
            <div class="input-field col s5 push-s1">
              <input 
                style="cursor: pointer;"
                type="date"
                id="acc-date-evaluated"
                name="acc-date-evaluated"
                class="datepicker"/>
              <label for="acc-date-evaluated">Date evaluated*</label>
            </div>

            <!-- End -->
            <div class="input-field col s5 push-s1">
              <input 
                style="cursor: pointer;" 
                type="date"
                id="acc-expiry-date"
                name="acc-expiry-date"
                class="datepicker" />
              <label for="acc-expiry-date">Expiry date*</label>
            </div>
          </div>

          <!-- Farm Information Header -->
          <div class="row">
            <div class="col s10 offset-s1">
              <h5 class="center-align">Farm Information</h5>
            </div>
          </div>

          <!-- Farm Address 1 -->
          <div class="row">
            <div class="input-field col s10 push-s1">
              {!! Form::text('farmAddress[1][addressLine1]', null, [
                'id' => 'farmAddress[1][addressLine1]',
                'class' => 'farm-1-addressLine1'
              ])!!}
              {!! Form::label(
                'farmAddress[1][addressLine1]',
                'Address Line 1* : Street, Road, Subdivision'
              ) !!}
            </div>
          </div>

          <!-- Farm Address 2 -->
          <div class="row">
            <div class="input-field col s10 push-s1">
              {!! Form::text('farmAddress[1][addressLine2]', null, [
                'id' => 'farmAddress[1][addressLine2]',
                'class' => 'farm-1-addressLine2'
              ])!!}
              {!! Form::label(
                'farmAddress[1][addressLine2]',
                'Address Line 2* : Barangay, Town, City'
              ) !!}
            </div>
          </div>

          <div class="row">
            <!-- Farm Address: Province -->
            <div class="input-field col s5 push-s1">
              {!! Form::select('farmAddress[1][province]', $provinces, null); !!}
              <label>Province*</label>
            </div>

            <!-- Farm Address: Zip Code -->
            <div class="input-field col s5 push-s1">
              {!! Form::text('farmAddress[1][zipCode]', null, [
                'id' => 'farmAddress[1][zipCode]',
                'class' => 'farm-1-zipCode'
              ])!!}
              {!! Form::label('farmAddress[1][zipCode]', 'Postal/ZIP Code*') !!}
            </div>
          </div>

          <!-- Farm Type -->
          <div class="row">
            <div class="input-field col s5 push-s1">
              {!! Form::text('farmAddress[1][farmType]', null, [
                'id' => 'farmAddress[1][farmType]'
              ])!!}
              {!! Form::label('farmAddress[1][farmType]', 'Farm Type*') !!}
            </div>
          </div>

          <div class="row">
            <!-- Farm Landline -->
            <div class="input-field col s5 push-s1">
              {!! Form::text('farmAddress[1][landline]', null, [
                'id' => 'farmAddress[1][landline]',
                'class' => 'farm-1-landline'
              ])!!}
              {!! Form::label('farmAddress[1][landline]', 'Farm Landline') !!}
            </div>

            <!-- Farm Mobile -->
            <div class="input-field col s5 push-s1">
              {!! Form::text('farmAddress[1][mobile]', null, [
                'id' => 'farmAddress[1][mobile]',
                'class' => 'farm-1-mobile'
              ])!!}
              {!! Form::label('farmAddress[1][mobile]', 'Farm Mobile*') !!}
            </div>
          </div>

          <!-- Previous Button -->
          <div class="row">
            <div class="col s10 offset-s1">
              <div class="col left">
                <a href="#" id="previous" class="btn-floating btn-medium waves-effect waves-light teal darken-1 tooltipped" data-position="right" data-delay="50" data-tooltip="Previous">
                  <i class="material-icons">chevron_left</i>
                </a>
              </div>
            </div>
          </div>

          <!-- Submit Button -->
          <div class="row">
            {{-- For Terms and Policy --}}
            <div class="terms-and-policy-container">
              <p class="terms-and-policy">
                By clicking Submit, you agree to our
                <a href="{{ route('breeder.getTermsOfAgreement') }}" target="_blank">Terms</a>
                and
                <a href="{{ route('breeder.privacyPolicy') }}" target="_blank">Data Policy</a>.
              </p>
            </div>

            <button 
              type="submit"
              class="primary btn waves-effect waves-light col s3 push-s9"> 
              Submit
              <i class="material-icons right">send</i>
            </button>
			    </div>

        </div>

      </div>

    </div>
  {!! Form::close() !!}
@endsection

@section('customScript')
  <script src="{{ elixir('/js/breeder/breederRegister.js') }}"></script>
@endsection