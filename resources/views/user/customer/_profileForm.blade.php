<!--
	This is the form Customer Users use
	for completing/updating
	their profile

	Input fields inlcude:
		Address Line 1
		Address Line 2
		Province
		Zip Code
		Landline
		Mobile
		Farm Address Line 1
		Farm Address Line 2
		Farm Address Province
		Farm Address Zip Code
		Farm type
		Farm landline
		Farm mobile
-->

<ul class="collapsible" data-collapsible="accordion">
	<li>
		<div class="collapsible-header active"><i class="material-icons">person_outline</i>Personal Information</div>
		<div class="collapsible-body">

		  <div class="row">
			<!-- Address: Address Line 1 -->
		  	<div class="input-field col s10 push-s1">
		  		{!! Form::text('address_addressLine1', null, ['autofocus' => 'autofocus'])!!}
		  		{!! Form::label('address_addressLine1', 'Address Line 1* : Street, Road, Subdivision') !!}
		  	</div>
		  </div>


		  <div class="row">
			<!-- Address: Address Line 2 -->
		  	<div class="input-field col s10 push-s1">
		  		{!! Form::text('address_addressLine2', null)!!}
		  		{!! Form::label('address_addressLine2', 'Address Line 2* : Barangay, Town, City') !!}
		  	</div>
		  </div>


		  <div class="row">
			<!-- Address: Province -->
		  	<div class="input-field col s5 push-s1">
		  		{!! Form::text('address_province', null)!!}
		  		{!! Form::label('address_province', 'Province*') !!}
		  	</div>

			<!-- Address: Zip Code -->
			<div class="input-field col s5 push-s1">
		  		{!! Form::text('address_zipCode', null)!!}
		  		{!! Form::label('address_zipCode', 'Postal/ZIP Code*') !!}
		  	</div>
		  </div>

		  <div class="row">
			<!-- Landline -->
		  	<div class="input-field col s5 push-s1">
		  		{!! Form::text('landline', null)!!}
		  		{!! Form::label('landline', 'Landline') !!}
		  	</div>

			<!-- Mobile -->
			<div class="input-field col s5 push-s1">
		  		{!! Form::text('mobile', null)!!}
		  		{!! Form::label('mobile', 'Mobile*') !!}
		  	</div>
		  </div>
		</div>
	</li>
	<li>
	  	<div class="collapsible-header"><i class="material-icons">store</i>Farm Information</div>
	  	<div class="collapsible-body">
			<div id="farmAddress-body">
				<?php $farmCount = 1 ?>
				@forelse($farmAddresses as $farmAddress)
					<div class="row add-farm">
					<div class="col s10 offset-s1">
						<div id="farm-1" class="card-panel z-depth-1">
							<p> Farm {{ $farmCount }} </p>
							<div class="row">
							<!-- Farm Address: Address Line 1 -->
								<div class="input-field col s10 push-s1">
									{!! Form::text('farmAddress['.$farmCount.'][addressLine1]', $farmAddress->addressLine1)!!}
									{!! Form::label('farmAaddress['.$farmCount.'][addressLine1]', 'Address Line 1* : Street, Road, Subdivision') !!}
								</div>
							</div>

							<div class="row">
							<!-- Farm Address: Address Line 2 -->
								<div class="input-field col s10 push-s1">
									{!! Form::text('farmAddress['.$farmCount.'][addressLine2]', $farmAddress->addressLine2)!!}
									{!! Form::label('farmAddress['.$farmCount.'][addressLine2]', 'Address Line 2* : Barangay, Town, City') !!}
								</div>
							</div>

							<div class="row">
								<!-- Farm Address: Province -->
								<div class="input-field col s5 push-s1">
									{!! Form::text('farmAddress['.$farmCount.'][province]', $farmAddress->province)!!}
									{!! Form::label('farmAddress['.$farmCount.'][province]', 'Province*') !!}
								</div>

								<!-- Farm Address: Zip Code -->
								<div class="input-field col s5 push-s1">
									{!! Form::text('farmAddress['.$farmCount.'][zipCode]', $farmAddress->zipCode)!!}
									{!! Form::label('farmAddress['.$farmCount.'][zipCode]', 'Postal/ZIP Code*') !!}
								</div>
							</div>

							<div class="row">
								<!-- Farm Type -->
								<div class="input-field col s5 push-s1">
									{!! Form::text('farmAddress['.$farmCount.'][farmType]', $farmAddress->farmType)!!}
									{!! Form::label('farmAddress['.$farmCount.'][farmType]', 'Farm Type*') !!}
								</div>
							</div>

							<div class="row">
								<!-- Farm Landline -->
								<div class="input-field col s5 push-s1">
									{!! Form::text('farmAddress['.$farmCount.'][landline]', $farmAddress->landline)!!}
									{!! Form::label('farmAddress['.$farmCount.'][landline]', 'Farm Landline') !!}
								</div>

								<!-- Farm Mobile -->
								<div class="input-field col s5 push-s1">
									{!! Form::text('farmAddress['.$farmCount.'][mobile]', $farmAddress->mobile)!!}
									{!! Form::label('farmAddress['.$farmCount.'][mobile]', 'Farm Mobile*') !!}
								</div>
							</div>

							@if($farmCount == count($farmAddresses))
								<div class="row ">
				                    <div class="col offset-s10 removeButton-field">
				                        <a href="#" id="remove-farmAddress" class="btn-floating btn-medium waves-effect waves-light deep-orange tooltipped" data-position="left" data-delay="50" data-tooltip="Remove this Farm">
				                            <i class="material-icons">remove</i>
				                        </a>
				                    </div>
				                </div>
							@endif

							<?php $farmCount++ ?>
						</div>
					</div>
					</div>
				@empty
					<div class="row add-farm">
					<div class="col s10 offset-s1">
						<div id="farm-1" class="card-panel z-depth-1">
							<p> Farm 1 </p>
							<div class="row">
							<!-- Farm Address: Address Line 1 -->
								<div class="input-field col s10 push-s1">
									{!! Form::text('farmAddress[1][addressLine1]', null)!!}
									{!! Form::label('farmAaddress[1][addressLine1]', 'Address Line 1* : Street, Road, Subdivision') !!}
								</div>
							</div>

							<div class="row">
							<!-- Farm Address: Address Line 2 -->
								<div class="input-field col s10 push-s1">
									{!! Form::text('farmAddress[1][addressLine2]', null)!!}
									{!! Form::label('farmAddress[1][addressLine2]', 'Address Line 2* : Barangay, Town, City') !!}
								</div>
							</div>

							<div class="row">
								<!-- Farm Address: Province -->
								<div class="input-field col s5 push-s1">
									{!! Form::text('farmAddress[1][province]', null)!!}
									{!! Form::label('farmAddress[1][province]', 'Province*') !!}
								</div>

								<!-- Farm Address: Zip Code -->
								<div class="input-field col s5 push-s1">
									{!! Form::text('farmAddress[1][zipCode]', null)!!}
									{!! Form::label('farmAddress[1][zipCode]', 'Postal/ZIP Code*') !!}
								</div>
							</div>

							<div class="row">
								<!-- Farm Type -->
								<div class="input-field col s5 push-s1">
									{!! Form::text('farmAddress[1][farmType]', null)!!}
									{!! Form::label('farmAddress[1][farmType]', 'Farm Type*') !!}
								</div>
							</div>

							<div class="row">
								<!-- Farm Landline -->
								<div class="input-field col s5 push-s1">
									{!! Form::text('farmAddress[1][landline]', null)!!}
									{!! Form::label('farmAddress[1][landline]', 'Farm Landline') !!}
								</div>

								<!-- Farm Mobile -->
								<div class="input-field col s5 push-s1">
									{!! Form::text('farmAddress[1][mobile]', null)!!}
									{!! Form::label('farmAddress[1][mobile]', 'Farm Mobile*') !!}
								</div>
							</div>
						</div>
					</div>
					</div>
				@endforelse
			</div>

			<div class="row">
				<div class="col offset-s10">
					<a href="#" id="add-farmAddress" class="btn-floating btn-medium waves-effect waves-light blue tooltipped" data-position="left" data-delay="50" data-tooltip="Add another Farm">
						<i class="material-icons">add</i>
					</a>
				</div>
			</div>
	  	</div>

	</li>
</ul>

<!-- Submit Button -->
<div class="row">
  <button type="submit" class="btn waves-effect waves-light col s3 push-s8"> Submit
	  <i class="material-icons right">send</i>
  </button>
</div>
