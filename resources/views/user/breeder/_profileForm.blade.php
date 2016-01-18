<!--
	All String
	Office Address Line 1
	Office Address Line 2
	Office Address Province
	Office Address Zip Code
	Office Address Landline
	Office Address Mobile
	Farm Address Line 1
	Farm Address Line 2
	Farm Address Province
	Farm Address Zip Code
	Farm type
	Farm landline
	Farm mobile
	Contact Person Name
	Contact Person Mobile
-->

<ul class="collapsible" data-collapsible="accordion">
	<li>
	  <div class="collapsible-header active"><i class="material-icons">domain</i>Office Information</div>
	  	<div class="collapsible-body">

		  <div class="row">
			<!-- Address: Street Address -->
		  	<div class="input-field col s10 push-s1">
		  		{!! Form::text('officeAddress_addressLine1', null, ['autofocus' => 'autofocus'])!!}
		  		{!! Form::label('officeAddress_addressLine1', 'Address Line 1* : Street, Road, Subdivision') !!}
		  	</div>
		  </div>


		  <div class="row">
			<!-- Address: Address Line 2 -->
		  	<div class="input-field col s10 push-s1">
		  		{!! Form::text('officeAddress_addressLine2', null)!!}
		  		{!! Form::label('officeAddress_addressLine2', 'Address Line 2* : Barangay, Town, City') !!}
		  	</div>
		  </div>


		  <div class="row">
			<!-- Address: Province -->
		  	<div class="input-field col s5 push-s1">
		  		{!! Form::text('officeAddress_province', null)!!}
		  		{!! Form::label('officeAddress_province', 'Province*') !!}
		  	</div>

			<!-- Address: Zip Code -->
			<div class="input-field col s5 push-s1">
		  		{!! Form::text('officeAddress_zipCode', null)!!}
		  		{!! Form::label('officeAddress_zipCode', 'Postal/ZIP Code*') !!}
		  	</div>
		  </div>

		  <div class="row">
			<!-- Landline -->
		  	<div class="input-field col s5 push-s1">
		  		{!! Form::text('office_landline', null)!!}
		  		{!! Form::label('office_landline', 'Landline') !!}
		  	</div>

			<!-- Mobile -->
			<div class="input-field col s5 push-s1">
		  		{!! Form::text('office_mobile', null)!!}
		  		{!! Form::label('office_mobile', 'Mobile*') !!}
		  	</div>
		  </div>
	  </div>
	</li>
	<li>
	  <div class="collapsible-header"><i class="material-icons">store</i>Farm Information</div>
	  <div class="collapsible-body">
		  <div class="row">
			<!-- Farm Address: Street Address -->
		  	<div class="input-field col s10 push-s1">
		  		{!! Form::text('farmAddress_addressLine1', null)!!}
		  		{!! Form::label('farmAaddress_addressLine1', 'Address Line 1* : Street, Road, Subdivision') !!}
		  	</div>
		  </div>


		  <div class="row">
			<!-- Farm Address: Address Line 2 -->
		  	<div class="input-field col s10 push-s1">
		  		{!! Form::text('farmAddress_addressLine2', null)!!}
		  		{!! Form::label('farmAddress_addressLine2', 'Address Line 2* : Barangay, Town, City') !!}
		  	</div>
		  </div>


		  <div class="row">
			<!-- Farm Address: Province -->
		  	<div class="input-field col s5 push-s1">
		  		{!! Form::text('farmAddress_province', null)!!}
		  		{!! Form::label('farmAddress_province', 'Province*') !!}
		  	</div>

			<!-- Farm Address: Zip Code -->
			<div class="input-field col s5 push-s1">
		  		{!! Form::text('farmAddress_zipCode', null)!!}
		  		{!! Form::label('farmAddress_zipCode', 'Postal/ZIP Code*') !!}
		  	</div>
		  </div>

		  <!-- Farm Type -->
		  <div class="row">
		  	<div class="input-field col s5 push-s1">
		  		{!! Form::text('farm_type', null)!!}
		  		{!! Form::label('farm_type', 'Farm Type*') !!}
		  	</div>
		  </div>


		  <div class="row">
			<!-- Farm Landline -->
		  	<div class="input-field col s5 push-s1">
		  		{!! Form::text('farm_landline', null)!!}
		  		{!! Form::label('farm_landline', 'Farm Landline') !!}
		  	</div>

			<!-- Farm Mobile -->
			<div class="input-field col s5 push-s1">
		  		{!! Form::text('farm_mobile', null)!!}
		  		{!! Form::label('farm_mobile', 'Farm Mobile*') !!}
		  	</div>
		  </div>

	  </div>
	</li>
	<li>
		<div class="collapsible-header active"><i class="material-icons">contacts</i>Contact Person</div>
  	  		<div class="collapsible-body">
				<div class="row">
		  			<!-- Contact Person: Name -->
		  		  	<div class="input-field col s10 push-s1">
		  		  		{!! Form::text('contactPerson_name', null)!!}
		  		  		{!! Form::label('contactPerson_name', 'Name*') !!}
		  		  	</div>
		  		</div>

		  		<div class="row">
		  			<!-- Contact Person: Mobile -->
		  		  	<div class="input-field col s10 push-s1">
		  		  		{!! Form::text('contactPerson_mobile', null)!!}
		  		  		{!! Form::label('contactPerson_mobile', 'Mobile*') !!}
		  		  	</div>
		  		</div>
			</div>
	</li>
</ul>

<!-- Submit Button -->
<div class="col s6 push-s6">
  <button type="submit" class="btn waves-effect waves-light"> Submit
	  <i class="material-icons right">send</i>
  </button>
</div>
