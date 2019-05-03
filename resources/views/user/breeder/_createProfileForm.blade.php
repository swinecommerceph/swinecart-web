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
			<li id="personal-tab" class="tab col s6"><a class="active" href="#personal-information">Office Information</a></li>
			<li id="farm-tab" class="tab col s6"><a href="#farm-information">Farm Information</a></li>
		</ul>
	</div>
	<div class="col s12">
		<div id="personal-information" class="card-panel">
			<div class="row">
			{{-- Office Address: Address Line 1 --}}
				<div class="input-field col s10 push-s1">
					{!! Form::text('officeAddress_addressLine1', null, ['autofocus' => 'autofocus', 'id' => 'officeAddress_addressLine1'])!!}
					{!! Form::label('officeAddress_addressLine1', 'Address Line 1* : Street, Road, Subdivision') !!}
				</div>
			</div>


			<div class="row">
			{{-- Office Address: Address Line 2 --}}
				<div class="input-field col s10 push-s1">
					{!! Form::text('officeAddress_addressLine2', null, ['id' => 'officeAddress_addressLine2'])!!}
					{!! Form::label('officeAddress_addressLine2', 'Address Line 2* : Barangay, Town, City') !!}
				</div>
			</div>


			<div class="row">
			{{-- Office Address: Province --}}
				<div class="input-field col s5 push-s1">
					{!! Form::select('officeAddress_province', $provinces, null, ['id' => 'office_provinces']); !!}
					<label>Province*</label>
				</div>

			{{-- Office Address: Zip Code --}}
			<div class="input-field col s5 push-s1">
					{!! Form::text('officeAddress_zipCode', null, ['id' => 'officeAddress_zipCode'])!!}
					{!! Form::label('officeAddress_zipCode', 'Postal/ZIP Code*') !!}
				</div>
			</div>

			<div class="row">
			{{-- Office Landline --}}
				<div class="input-field col s5 push-s1">
					{!! Form::text('office_landline', null, ['id' => 'office_landline'])!!}
					{!! Form::label('office_landline', 'Landline') !!}
				</div>

			{{-- Office Mobile --}}
			<div class="input-field col s5 push-s1">
					{!! Form::text('office_mobile', null, ['id' => 'office_mobile'])!!}
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
					{!! Form::text('contactPerson_name', null, ['id' => 'contactPerson_name'])!!}
					{!! Form::label('contactPerson_name', 'Name*') !!}
				</div>

			<!-- Contact Person: Mobile -->
			<div class="input-field col s5 push-s1">
					{!! Form::text('contactPerson_mobile', null, ['id' => 'contactPerson_mobile'])!!}
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
					{!! Form::text('website', null, ['id' => 'website'])!!}
					{!! Form::label('website', 'Website') !!}
				</div>

			{{-- Produce --}}
			<div class="input-field col s5 push-s1">
					{!! Form::text('produce', null, ['id' => 'produce'])!!}
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
        <?php $farmNumber = 1; ?>
				@foreach($farmAddresses as $farmAddress)
					<div class="row add-farm">
					<div class="col s10 offset-s1">
						<div id="farm-1" class="card-panel hoverable">
							<h5 class="center-align"> {{ $farmAddress->name }} </h5>
							{!! Form::hidden('farmAddress[' . $loop->iteration .'][id]', $farmAddress->id) !!}
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
                  <input
                    type="checkbox"
                    id="check"
                    class="same-address-checker farm-{{ $farmNumber }}">
                  <label for="check" class="teal-text text-darken-4"><b>Address is same as Office Information</b></label>
                </div>
                <br>

							{{-- Farm Address: Address Line 1 --}}
								<div class="input-field col s10 push-s1">
									{!! Form::text('farmAddress[' . $loop->iteration . '][addressLine1]', null, ['id' => 'farmAddress[' . $loop->iteration . '][addressLine1]', 'class' => 'farm-' . $loop->iteration . '-addressLine1'])!!}
									{!! Form::label('farmAddress[' . $loop->iteration . '][addressLine1]', 'Address Line 1* : Street, Road, Subdivision') !!}
								</div>
							</div>

							<div class="row">
							{{-- Farm Address: Address Line 2 --}}
								<div class="input-field col s10 push-s1">
									{!! Form::text('farmAddress[' . $loop->iteration . '][addressLine2]', null, ['id' => 'farmAddress[' . $loop->iteration . '][addressLine2]', 'class' => 'farm-' . $loop->iteration . '-addressLine2'])!!}
									{!! Form::label('farmAddress[' . $loop->iteration . '][addressLine2]', 'Address Line 2* : Barangay, Town, City') !!}
								</div>
							</div>

							<div class="row">
								{{-- Farm Address: Province --}}
								<div class="input-field col s5 push-s1">
									{!! Form::select('farmAddress[' . $loop->iteration . '][province]', $provinces, null); !!}
									<label>Province*</label>
								</div>

								{{-- Farm Address: Zip Code --}}
								<div class="input-field col s5 push-s1">
									{!! Form::text('farmAddress[' . $loop->iteration . '][zipCode]', null, ['id' => 'farmAddress[' . $loop->iteration . '][zipCode]', 'class' => 'farm-' . $loop->iteration . '-zipCode'])!!}
									{!! Form::label('farmAddress[' . $loop->iteration . '][zipCode]', 'Postal/ZIP Code*') !!}
								</div>
							</div>

							<div class="row">
								{{-- Farm Type --}}
								<div class="input-field col s5 push-s1">
									{!! Form::text('farmAddress[' . $loop->iteration . '][farmType]', null, ['id' => 'farmAddress[' . $loop->iteration . '][farmType]'])!!}
									{!! Form::label('farmAddress[' . $loop->iteration . '][farmType]', 'Farm Type*') !!}
								</div>
							</div>

							<div class="row">
								{{-- Farm Landline --}}
								<div class="input-field col s5 push-s1">
									{!! Form::text('farmAddress[' . $loop->iteration . '][landline]', null, ['id' => 'farmAddress[' . $loop->iteration . '][landline]', 'class' => 'farm-' . $loop->iteration . '-landline'])!!}
									{!! Form::label('farmAddress[' . $loop->iteration . '][landline]', 'Farm Landline') !!}
								</div>

								{{-- Farm Mobile --}}
								<div class="input-field col s5 push-s1">
									{!! Form::text('farmAddress[' . $loop->iteration . '][mobile]', null, ['id' => 'farmAddress[' . $loop->iteration . '][mobile]', 'class' => 'farm-' . $loop->iteration . '-mobile'])!!}
									{!! Form::label('farmAddress[' . $loop->iteration . '][mobile]', 'Farm Mobile*') !!}
								</div>
							</div>
						</div>
					</div>
          </div>
          <?php $farmNumber++; ?>
				@endforeach
			</div>

			<div class="row">
				<div class="col s10 offset-s1">
					<div class="col left">
						<a href="#" id="previous" class="btn-floating btn-medium waves-effect waves-light teal darken-1 tooltipped" data-position="right" data-delay="50" data-tooltip="Previous">
							<i class="material-icons">chevron_left</i>
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
