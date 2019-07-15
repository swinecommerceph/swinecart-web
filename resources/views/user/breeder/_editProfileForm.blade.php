{{--
	This is the form Breeder Users use
	for updating
	their profile

	Input fields inlcude:
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
			<li id="personal-tab" class="tab col s4">
				<a class="active" href="#personal-information">Office Information</a>
			</li>
			<li id="farm-tab" class="tab col s4">
				<a href="#farm-information">Farm Information</a>
			</li>
			<li id="password-tab" class="tab col s4">
				<a href="#password-information">Change Password</a>
			</li>
		</ul>
	</div>
	<div class="col s12">
		{!! Form::model($breeder,['route' => 'breeder.updatePersonal', 'method' => 'PUT', 'data-personal-id' => $breeder->id]) !!}
		<div id="personal-information" class="card-panel">

			<div class="row">
			<!-- Address: Address Line 1 -->
				<div class="input-field col s10 push-s1">
					{!! Form::text('officeAddress_addressLine1', null, ['disabled' => 'disabled', 'id' => 'officeAddress_addressLine1'])!!}
					{!! Form::label('officeAddress_addressLine1', 'Address Line 1* : Street, Road, Subdivision') !!}
				</div>
			</div>


			<div class="row">
			<!-- Address: Address Line 2 -->
				<div class="input-field col s10 push-s1">
					{!! Form::text('officeAddress_addressLine2', null, ['disabled' => 'disabled', 'id' => 'officeAddress_addressLine2'])!!}
					{!! Form::label('officeAddress_addressLine2', 'Address Line 2* : Barangay, Town, City') !!}
				</div>
			</div>


			<div class="row">
			<!-- Address: Province -->
				<div class="input-field col s5 push-s1">
					{!! Form::select('officeAddress_province', $provinces, null, ['disabled' => 'disabled', 'id' => 'office_provinces']); !!}
					<label>Province*</label>
				</div>

			<!-- Address: Zip Code -->
			<div class="input-field col s5 push-s1">
					{!! Form::text('officeAddress_zipCode', null, ['disabled' => 'disabled', 'id' => 'officeAddress_zipCode'])!!}
					{!! Form::label('officeAddress_zipCode', 'Postal/ZIP Code*') !!}
				</div>
			</div>

			<div class="row">
			<!-- Landline -->
				<div class="input-field col s5 push-s1">
					{!! Form::text('office_landline', null, ['disabled' => 'disabled', 'id' => 'office_landline'])!!}
					{!! Form::label('office_landline', 'Landline') !!}
				</div>

			<!-- Mobile -->
			<div class="input-field col s5 push-s1">
					{!! Form::text('office_mobile', null, ['disabled' => 'disabled', 'id' => 'office_mobile'])!!}
					{!! Form::label('office_mobile', 'Mobile*') !!}
				</div>
			</div>

			<div class="row">
				<div class="col s10 offset-s1">
					<h5 class="center-align">Contact Person Details</h5>
				</div>
			</div>

			<div class="row">
			<!-- Contact Person: Name -->
				<div class="input-field col s5 push-s1">
					{!! Form::text('contactPerson_name', null, ['disabled' => 'disabled', 'id' => 'contactPerson_name'])!!}
					{!! Form::label('contactPerson_name', 'Name*') !!}
				</div>

			<!-- Contact Person: Mobile -->
			<div class="input-field col s5 push-s1">
					{!! Form::text('contactPerson_mobile', null, ['disabled' => 'disabled', 'id' => 'contactPerson_mobile'])!!}
					{!! Form::label('contactPerson_mobile', 'Mobile*') !!}
				</div>
			</div>

			<div class="row">
				<div class="col s10 offset-s1">
					<h5 class="center-align">Other information</h5>
				</div>
			</div>

			<div class="row">
			<!-- Contact Person: Name -->
				<div class="input-field col s5 push-s1">
					{!! Form::text('website', null, ['disabled' => 'disabled', 'id' => 'website'])!!}
					{!! Form::label('website', 'Website') !!}
				</div>

			<!-- Contact Person: Mobile -->
			<div class="input-field col s5 push-s1">
					{!! Form::text('produce', null, ['disabled' => 'disabled', 'id' => 'produce'])!!}
					{!! Form::label('produce', 'Produce') !!}
				</div>
			</div>

			<div class="row">
			  <div class="col s10 offset-s1 content-section">
				  <div class="col right">
					  <button  id="" class="btn-floating btn-medium waves-effect waves-light teal darken-1 tooltipped edit-button" data-position="left" data-delay="50" data-tooltip="Edit">
						  <i class="material-icons">mode_edit</i>
					  </button>
				  </div>
				  <div class="col right">
					  <a href="#!" id="" class="btn-floating btn-medium waves-effect waves-light red lighten-1 tooltipped cancel-button" style="display:none;" data-position="top" data-delay="50" data-tooltip="Cancel">
						  <i class="material-icons">clear</i>
					  </a>
				  </div>
			  </div>
			</div>
		</div>
		{!! Form::close() !!}

		<div id="farm-information" class="card-panel">
			<div id="farm-address-body">
        <?php $farmNumber = 1; ?>
				@foreach($farmAddresses as $farmAddress)
					<div class="row add-farm">
						<div class="col s10 offset-s1">
            <div id="{{ $farmAddress->name }}" class="card-panel hoverable">
								{!! Form::open(['route' => 'breeder.updateFarm', 'method' => 'PUT', 'class' => 'edit-farm', 'data-farm-id' => $farmAddress->id, 'data-farm-order' => $loop->iteration]) !!}
								<h5 class="center-align farm-title"> {{ $farmAddress->name }} </h5>
								<div class="row">
									<div class="col s6 offset-s3">
										<table>
											<thead></thead>
											<tbody>
												<tr>
													<td class="" style="padding:0;"> Accreditation No. </td>
													<td class="right-align" style="padding:0;"> {{ $farmAddress->accreditation_no }} </td>
												</tr>
												<tr>
													<td class="" style="padding:0;"> Date Evaluated </td>
													<td class="right-align" style="padding:0;"> {{ date_format(date_create($farmAddress->accreditation_date), 'F Y') }} </td>
												</tr>
												<tr>
													<td class="" style="padding:0;"> Expiry Date </td>
													<td class="right-align" style="padding:0;"> {{ date_format(date_create($farmAddress->accreditation_expiry), 'F Y') }} </td>
												</tr>
											</tbody>
										</table>
									</div>
                </div>
                
                
								<div class="row">
                  
                  {{-- Checkbox if Farm Address is same as Office Address --}}
                  <div>
                    <input type="checkbox" id="check" class="same-address-checker farm-{{ $farmNumber }}" disabled>
                    <label for="check" class="teal-text text-darken-4"><b>Address is same as Office Information</b></label>
                  </div>
                  <br>

								<!-- Farm Address: Address Line 1 -->
									<div class="input-field col s10 push-s1">
										{!! Form::text('addressLine1', $farmAddress->addressLine1, ['disabled' => 'disabled', 'id' => 'farm-' . $loop->iteration . '-addressLine1'])!!}
										{!! Form::label('addressLine1', 'Address Line 1* : Street, Road, Subdivision') !!}
									</div>
								</div>

								<div class="row">
								<!-- Farm Address: Address Line 2 -->
									<div class="input-field col s10 push-s1">
										{!! Form::text('addressLine2', $farmAddress->addressLine2, ['disabled' => 'disabled', 'id' => 'farm-' . $loop->iteration . '-addressLine2'])!!}
										{!! Form::label('addressLine2', 'Address Line 2* : Barangay, Town, City') !!}
									</div>
								</div>

								<div class="row">
									<!-- Farm Address: Province -->
									<div class="input-field col s5 push-s1" id="farm-{{ $farmNumber }}">
										{!! Form::select('province', $provinces, $farmAddress->province, ['disabled' => 'disabled']); !!}
										<label>Province*</label>
									</div>

									<!-- Farm Address: Zip Code -->
									<div class="input-field col s5 push-s1">
										{!! Form::text('zipCode', $farmAddress->zipCode, ['disabled' => 'disabled', 'id' => 'farm-' . $loop->iteration . '-zipCode'])!!}
										{!! Form::label('zipCode', 'Postal/ZIP Code*') !!}
									</div>
								</div>

								<div class="row">
									<!-- Farm Type -->
									<div class="input-field col s5 push-s1">
										{!! Form::text('farmType', $farmAddress->farmType, ['disabled' => 'disabled', 'id' => 'farm-' . $loop->iteration . '-farmType'])!!}
										{!! Form::label('farmType', 'Farm Type*') !!}
									</div>
								</div>

								<div class="row">
									<!-- Farm Landline -->
									<div class="input-field col s5 push-s1">
										{!! Form::text('landline', $farmAddress->landline, ['disabled' => 'disabled', 'id' => 'farm-' . $loop->iteration . '-landline'])!!}
										{!! Form::label('landline', 'Farm Landline') !!}
									</div>

									<!-- Farm Mobile -->
									<div class="input-field col s5 push-s1">
										{!! Form::text('mobile', $farmAddress->mobile, ['disabled' => 'disabled', 'id' => 'farm-' . $loop->iteration . '-mobile'])!!}
										{!! Form::label('mobile', 'Farm Mobile*') !!}
									</div>
								</div>

								<div class="row">
								  <div class="col s10 offset-s1 content-section">
									  <div class="col right">
										  <button  class="btn-floating btn-medium waves-effect waves-light teal darken-1 tooltipped edit-button" data-position="left" data-delay="50" data-tooltip="Edit {{$farmAddress->name}}">
											  <i class="material-icons">mode_edit</i>
										  </button>
									  </div>
									  <div class="col right">
										  <a href="#!" class="btn-floating btn-medium waves-effect waves-light red lighten-1 tooltipped cancel-button" style="display:none;" data-position="top" data-delay="50" data-tooltip="Cancel">
											  <i class="material-icons">clear</i>
										  </a>
									  </div>
								  </div>
							  	</div>

								{!! Form::close() !!}

								{!! Form::open(['route' => 'breeder.deleteFarm', 'method' => 'DELETE', 'class' => 'delete-farm', 'data-farm-id' => $farmAddress->id]) !!}
									<div class="row ">
										<div class="col offset-s10 remove-button-field">
											<a href="#!" class="btn-floating btn-medium waves-effect waves-light grey tooltipped remove-farm" data-position="left" data-delay="50" data-tooltip="Remove {{$farmAddress->name}}">
												<i class="material-icons">remove</i>
											</a>
										</div>
									</div>
								{!! Form::close() !!}


							</div>
						</div>
          </div>
          <?php $farmNumber++; ?>
				@endforeach
			</div>

		</div>

		<div id="password-information" class="card-panel">

			<blockquote id="password-error-container" class="error" style="display:none;"> </blockquote>

			{!! Form::open(['route' => 'breeder.changePassword', 'method' => 'PATCH', 'id' => 'change-password-form']) !!}

      {{-- Current Password --}}
			<div class="row">
        <div class="col s3"></div>				
	
        {{-- Show/Hide Password Button --}}
        <div class="col s1 show-hide-password">
          <i style="cursor: pointer;" id="show-hide-password-icon" class="grey-text text-lighten-1 material-icons center-align">visibility</i>
        </div>

				<div class="input-field col s4">
					<input type="password" id="currentpassword" name="current_password" class="validate login-password" required>
					@if ($errors->has('current_password'))
						<label for="currentpassword" data-error="{{ $errors->first('current_password') }}">Current Password</label>
					@else
						<label for="currentpassword">Current Password</label>
					@endif
				</div>
			</div>

			{{-- New Password --}}
			<div class="row">
				<div class="input-field col s4 offset-s4">
					<input type="password" id="newpassword" name="new_password" class="validate login-password" required>
					@if ($errors->has('new_password'))
						<label for="newpassword" data-error="{{ $errors->first('new_password') }}">New Password</label>
					@else
						<label for="newpassword">New Password</label>
					@endif
				</div>
			</div>

			{{-- Password Confirmation --}}
			<div class="row">
				<div class="input-field col s4 offset-s4">
					<input type="password" id="newpasswordconfirm" name="new_password_confirmation" class="validate login-password" required>
					@if ($errors->has('new_password_confirmation'))
						<label for="newpasswordconfirm" data-error="{{ $errors->first('new_password_confirmation') }}">Confirm Password</label>
					@else
						<label for="newpasswordconfirm">Confirm Password</label>
					@endif
				</div>
			</div>

			<div class="row">
				<div class="col s4 offset-s4">
						<a href="#" style="width: 17vw;" id="change-password-button" class="btn teal darken-3 btn-medium waves-effect waves-light">
							Change Password
						</a>
					</div>
			</div>
			{!! Form::close() !!}
		</div>

	</div>
</div>
