{{--
	This is the form Breeder Users use
	for completing
	their profile

	Input Fields include:
		Office Address Line 1
		Office Address Line 2
		Office Address Province
		Office Address Zip Code
		Office Address Landline
		Office Address Mobile
		Contact Person Name
		Contact Person Mobile
		Website
		Produce
		Farm Address Line 1
		Farm Address Line 2
		Farm Address Province
		Farm Address Zip Code
		Farm type
		Farm landline
		Farm mobile
 --}}

<div class="row">
	<div class="col s12">
		<ul class="tabs z-depth-1">
			<li id="personal-tab" class="tab col s6"><a class="active" href="#personal-information"><i class="material-icons">domain</i>Office Information</a></li>
			<li id="farm-tab" class="tab col s6"><a href="#farm-information"><i class="material-icons">store</i>Farm Information</a></li>
		</ul>
	</div>
	<div class="col s12">
		<div id="personal-information" class="card-panel">

			<div class="row">
			{{-- Office Address: Address Line 1 --}}
				<div class="input-field col s10 push-s1">
					{!! Form::text('officeAddress_addressLine1', null, ['autofocus' => 'autofocus'])!!}
					{!! Form::label('officeAddress_addressLine1', 'Address Line 1* : Street, Road, Subdivision') !!}
				</div>
			</div>


			<div class="row">
			{{-- Office Address: Address Line 2 --}}
				<div class="input-field col s10 push-s1">
					{!! Form::text('officeAddress_addressLine2', null)!!}
					{!! Form::label('officeAddress_addressLine2', 'Address Line 2* : Barangay, Town, City') !!}
				</div>
			</div>


			<div class="row">
			{{-- Office Address: Province --}}
				<div class="input-field col s5 push-s1">
					{!! Form::text('officeAddress_province', null)!!}
					{!! Form::label('officeAddress_province', 'Province*') !!}
				</div>

			{{-- Office Address: Zip Code --}}
			<div class="input-field col s5 push-s1">
					{!! Form::text('officeAddress_zipCode', null)!!}
					{!! Form::label('officeAddress_zipCode', 'Postal/ZIP Code*') !!}
				</div>
			</div>

			<div class="row">
			{{-- Office Landline --}}
				<div class="input-field col s5 push-s1">
					{!! Form::text('office_landline', null)!!}
					{!! Form::label('office_landline', 'Landline') !!}
				</div>

			{{-- Office Mobile --}}
			<div class="input-field col s5 push-s1">
					{!! Form::text('office_mobile', null)!!}
					{!! Form::label('office_mobile', 'Mobile*') !!}
				</div>
			</div>

			<div class="row">
				<div class="col s10 offset-s1">
					<h5 class="center-align">Contact Person Details</h5>
				</div>
			</div>

			<div class="row">
			{{-- Contact Person: Name --}}
				<div class="input-field col s5 push-s1">
					{!! Form::text('contactPerson_name', null)!!}
					{!! Form::label('contactPerson_name', 'Name*') !!}
				</div>

			<!-- Contact Person: Mobile -->
			<div class="input-field col s5 push-s1">
					{!! Form::text('contactPerson_mobile', null)!!}
					{!! Form::label('contactPerson_mobile', 'Mobile*') !!}
				</div>
			</div>

			<div class="row">
				<div class="col s10 offset-s1">
					<h5 class="center-align">Other information</h5>
				</div>
			</div>

			<div class="row">
			{{-- Website --}}
				<div class="input-field col s5 push-s1">
					{!! Form::text('website', null)!!}
					{!! Form::label('website', 'Website') !!}
				</div>

			{{-- Produce --}}
			<div class="input-field col s5 push-s1">
					{!! Form::text('produce', null)!!}
					{!! Form::label('produce', 'Produce') !!}
				</div>
			</div>


			<div class="row">
			  <div class="col s10 offset-s1">
				  <div class="col right">
					  <a href="#" id="next" class="btn-floating btn-medium waves-effect waves-light teal darken-1 tooltipped" data-position="left" data-delay="50" data-tooltip="Next">
						  <i class="material-icons">chevron_right</i>
					  </a>
				  </div>
			  </div>
			</div>
		</div>

		<div id="farm-information" class="card-panel">
			<div id="farm-address-body">
				<div class="row add-farm">
				<div class="col s10 offset-s1">
					<div id="farm-1" class="card-panel hoverable">
						<h5 class="center-align"> Farm 1 </h5>

						<div class="row">
						{{-- Farm Address: Name --}}
							<div class="input-field col s10 push-s1">
								{!! Form::text('farmAddress[1][name]', null)!!}
								{!! Form::label('farmAaddress[1][name]', 'Name*') !!}
							</div>
						</div>

						<div class="row">
						{{-- Farm Address: Address Line 1 --}}
							<div class="input-field col s10 push-s1">
								{!! Form::text('farmAddress[1][addressLine1]', null)!!}
								{!! Form::label('farmAaddress[1][addressLine1]', 'Address Line 1* : Street, Road, Subdivision') !!}
							</div>
						</div>

						<div class="row">
						{{-- Farm Address: Address Line 2 --}}
							<div class="input-field col s10 push-s1">
								{!! Form::text('farmAddress[1][addressLine2]', null)!!}
								{!! Form::label('farmAddress[1][addressLine2]', 'Address Line 2* : Barangay, Town, City') !!}
							</div>
						</div>

						<div class="row">
							{{-- Farm Address: Province --}}
							<div class="input-field col s5 push-s1">
								{!! Form::text('farmAddress[1][province]', null)!!}
								{!! Form::label('farmAddress[1][province]', 'Province*') !!}
							</div>

							{{-- Farm Address: Zip Code --}}
							<div class="input-field col s5 push-s1">
								{!! Form::text('farmAddress[1][zipCode]', null)!!}
								{!! Form::label('farmAddress[1][zipCode]', 'Postal/ZIP Code*') !!}
							</div>
						</div>

						<div class="row">
							{{-- Farm Type --}}
							<div class="input-field col s5 push-s1">
								{!! Form::text('farmAddress[1][farmType]', null)!!}
								{!! Form::label('farmAddress[1][farmType]', 'Farm Type*') !!}
							</div>
						</div>

						<div class="row">
							{{-- Farm Landline --}}
							<div class="input-field col s5 push-s1">
								{!! Form::text('farmAddress[1][landline]', null)!!}
								{!! Form::label('farmAddress[1][landline]', 'Farm Landline') !!}
							</div>

							{{-- Farm Mobile --}}
							<div class="input-field col s5 push-s1">
								{!! Form::text('farmAddress[1][mobile]', null)!!}
								{!! Form::label('farmAddress[1][mobile]', 'Farm Mobile*') !!}
							</div>
						</div>
					</div>
				</div>
				</div>
			</div>

			<div class="row">
				<div class="col s10 offset-s1">
					<div class="col left">
						<a href="#" id="previous" class="btn-floating btn-medium waves-effect waves-light teal darken-1 tooltipped" data-position="right" data-delay="50" data-tooltip="Previous">
							<i class="material-icons">chevron_left</i>
						</a>
					</div>
					<div class="col right">
						<a href="#" id="add-farm" class="btn-floating btn-medium waves-effect waves-light blue tooltipped" data-position="left" data-delay="50" data-tooltip="Add another Farm">
							<i class="material-icons">add</i>
						</a>
					</div>
				</div>
			</div>

			{{-- Submit Button --}}
			<div class="row">
			  <button type="submit" class="btn waves-effect waves-light col s3 push-s8"> Submit
				  <i class="material-icons right">send</i>
			  </button>
			</div>
		</div>
	</div>
</div>
