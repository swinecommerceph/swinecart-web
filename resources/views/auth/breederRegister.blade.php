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
    'route' => 'breeder.register',
    'method' => 'POST',
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
          
          <div class="row">
            <!-- Name -->
            <div class="input-field col s12 m5 push-m1">
              {!! Form::text('breederName', null, ['autofocus' => 'autofocus', 'id' => 'breederName']) !!}
              {!! Form::label('breederName', 'Name*')!!}
            </div>

            <!-- Email -->
            <div class="input-field col s12 m5 push-m1">
              {!! Form::text('email', null, ['id' => 'email']) !!}
              {!! Form::label('email', 'Email*')!!}
            </div>

          </div>

          <!-- Office Address 1 -->
          <div class="row">
            <div class="input-field col s12 m10 push-m1">
              {!! 
                Form::text('officeAddress_addressLine1', null, [
                  'id' => 'officeAddress_addressLine1'
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
            <div class="input-field col s12 m10 push-m1">
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
            <div id="select-province" class="input-field col s12 m5 push-m1">
              {!! Form::select('officeAddress_province', $provinces, null, [
                'id' => 'office_provinces'
              ]) !!}
              <label>Province*</label>
            </div>
            
            <!-- Office Postal/Zip Code -->
            <div class="input-field col s12 m5 push-m1">
              {!! Form::text('officeAddress_zipCode', null, ['id' => 'officeAddress_zipCode'])!!}
              {!! Form::label('officeAddress_zipCode', 'Postal/ZIP Code*') !!}
            </div>

          </div>
          
          <div class="row">

            <!-- Office Landline Number -->
            <div class="input-field col s12 m5 push-m1">
              {!! Form::text('office_landline', null, ['id' => 'office_landline'])!!}
              {!! Form::label('office_landline', 'Landline') !!}
            </div>

            <!-- Office Mobile Number -->
            <div class="input-field col s12 m5 push-m1">
              {!! Form::text('office_mobile', null, ['id' => 'office_mobile'])!!}
              {!! Form::label('office_mobile', 'Mobile*') !!}
            </div>

          </div>

          <!-- Contact Person Header -->
          <div class="row">
            <div class="col s12 m10 offset-m1">
              <h5 class="center-align">Contact Person Details</h5>
            </div>
          </div>

          <div class="row">

            <!-- Contact Person Name -->
            <div class="input-field col s12 m5 push-m1">
              {!! Form::text('contactPerson_name', null, ['id' => 'contactPerson_name'])!!}
              {!! Form::label('contactPerson_name', 'Name*') !!}
            </div>

            <!-- Contact Person's Mobile Number -->
            <div class="input-field col s12 m5 push-m1">
              {!! Form::text('contactPerson_mobile', null, ['id' => 'contactPerson_mobile'])!!}
              {!! Form::label('contactPerson_mobile', 'Mobile*') !!}
            </div>

          </div>

          <!-- Other Information Header -->
          <div class="row">
            <div class="col s12 m10 offset-m1">
              <h5 class="center-align">Other information</h5>
            </div>
          </div>

          <div class="row">

            <!-- Office Website -->
            <div class="input-field col s12 m5 push-m1">
              {!! Form::text('website', null, ['id' => 'website'])!!}
              {!! Form::label('website', 'Website') !!}
            </div>

            <!-- What the Office Produce -->
            <div class="input-field col s12 m5 push-m1">
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

          <!-- Farm Name -->
          <div class="row">
            <div class="input-field col s12 m5 push-m1">
              {!! Form::text('farm_name', null, [
                'id' => 'farm_name'
              ])!!}
              {!! Form::label('farm_name', 'Farm Name*') !!}
            </div>
          </div>

          <!-- Accreditation Information Header -->
          <div class="row">
            <div class="col s12 m10 offset-m1">
              <h5 class="center-align">Accreditation Information</h5>
            </div>
          </div>

          <!-- Accreditation Number -->
          <div class="row">
            <div class="input-field col s12 m10 push-m1">
              {!! Form::text('farm_accreditation_number', null, [
                'id' => 'farm_accreditation_number'
              ])!!}
              {!! Form::label('farm_accreditation_number', 'Accreditation Number of farm*') !!}
            </div>
          </div>

          <!-- Accreditation Dates -->
          <div class="row">
            <!-- Date evaludated -->
            <div class="input-field col s12 m5 push-m1">
              <input 
                style="cursor: pointer;"
                type="date"
                id="acc_date_evaluated"
                name="acc_date_evaluated"
                class="datepicker"/>
              <label for="acc_date_evaluated">Date evaluated*</label>
            </div>

            <!-- End -->
            <div class="input-field col s12 m5 push-m1">
              <input 
                style="cursor: pointer;" 
                type="date"
                id="acc_expiry_date"
                name="acc_expiry_date"
                class="datepicker" />
              <label for="acc_expiry_date">Expiry date*</label>
            </div>
          </div>

          <!-- Farm Information Header -->
          <div class="row">
            <div class="col s12 m10 offset-m1">
              <h5 class="center-align">Farm Information</h5>
            </div>
          </div>

          <!-- Farm Address 1 -->
          <div class="row">
            <div class="input-field col s12 m10 push-m1">
              {!! Form::text('farmAddress_1_addressLine1', null, [
                'id' => 'farmAddress_1_addressLine1',
                'class' => 'farm-1-addressLine1'
              ])!!}
              {!! Form::label(
                'farmAddress_1_addressLine1',
                'Address Line 1* : Street, Road, Subdivision'
              ) !!}
            </div>
          </div>

          <!-- Farm Address 2 -->
          <div class="row">
            <div class="input-field col s12 m10 push-m1">
              {!! Form::text('farmAddress_1_addressLine2', null, [
                'id' => 'farmAddress_1_addressLine2',
                'class' => 'farm-1-addressLine2'
              ])!!}
              {!! Form::label(
                'farmAddress_1_addressLine2',
                'Address Line 2* : Barangay, Town, City'
              ) !!}
            </div>
          </div>

          <div class="row">
            <!-- Farm Address: Province -->
            <div class="input-field col s12 m5 push-m1">
              {!! Form::select('farmAddress_1_province', $provinces, null); !!}
              <label>Province*</label>
            </div>

            <!-- Farm Address: Zip Code -->
            <div class="input-field col s12 m5 push-m1">
              {!! Form::text('farmAddress_1_zipCode', null, [
                'id' => 'farmAddress_1_zipCode',
                'class' => 'farm-1-zipCode'
              ])!!}
              {!! Form::label('farmAddress_1_zipCode', 'Postal/ZIP Code*') !!}
            </div>
          </div>

          <!-- Farm Type -->
          <div class="row">
            <div class="input-field col s12 m5 push-m1">
              {!! Form::text('farmAddress_1_farmType', null, [
                'id' => 'farmAddress_1_farmType'
              ])!!}
              {!! Form::label('farmAddress_1_farmType', 'Farm Type*') !!}
            </div>
          </div>

          <div class="row">
            <!-- Farm Landline -->
            <div class="input-field col s12 m5 push-m1">
              {!! Form::text('farmAddress_1_landline', null, [
                'id' => 'farmAddress_1_landline',
                'class' => 'farm-1-landline'
              ])!!}
              {!! Form::label('farmAddress_1_landline', 'Farm Landline') !!}
            </div>

            <!-- Farm Mobile -->
            <div class="input-field col s12 m5 push-m1">
              {!! Form::text('farmAddress_1_mobile', null, [
                'id' => 'farmAddress_1_mobile',
                'class' => 'farm-1-mobile'
              ])!!}
              {!! Form::label('farmAddress_1_mobile', 'Farm Mobile*') !!}
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
              id="submit-button"
              type="submit"
              disabled
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