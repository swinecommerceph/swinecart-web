<!-- 
	All String
	Address 
	Landline
	Mobile
	Farm Address
	Farm type
	Farm landline
	Farm mobile
 -->

<!-- Address -->
<div class="row">
	<div class="input-field col s12">
		{!! Form::text('address', null)!!}
		{{-- <input type="text" name="address" class="form-control" value="{{ old('address') }}"> --}}
		{!! Form::label('address', 'Address') !!}
		{{-- <label for="address" class="col-sm-3 control-label">Address</label> --}}
	</div>
</div>

<!-- Landline -->
<div class="row">
	<div class="input-field col s12">
		{!! Form::text('landline', null)!!}
		{{-- <input type="text" name="landline" class="form-control" value="{{ old('landline') }}"> --}}
		{!! Form::label('landline', 'Landline') !!}
		{{-- <label for="landline" class="col-sm-3 control-label">Landline</label> --}}
	</div>
</div>

<!-- Mobile -->
<div class="row">
	<div class="input-field col s12">
		{!! Form::text('mobile', null)!!}
		{{-- <input type="text" name="mobile" class="form-control" value="{{ old('mobile') }}"> --}}
		{!! Form::label('mobile', 'Mobile') !!}
		{{-- <label for="mobile" class="col-sm-3 control-label">Mobile</label> --}}
	</div>
</div>

<!-- Farm Address -->
<div class="row">
	<div class="input-field col s12">
		{!! Form::text('farm_address', null)!!}
		{{-- <input type="text" name="farm_address" class="form-control" value="{{ old('farm_address') }}"> --}}
		{!! Form::label('farm_address', 'Farm Address') !!}
		{{-- <label for="farm_address" class="col-sm-3 control-label">Farm Address</label> --}}
	</div>
</div>

<!-- Farm Type -->
<div class="row">
	<div class="input-field col s12">
		{!! Form::text('farm_type', null)!!}
		{{-- <input type="text" name="farm_type" class="form-control" value="{{ old('farm_type') }}"> --}}
		{!! Form::label('farm_type', 'Farm Type') !!}
		{{-- <label for="farm_type" class="col-sm-3 control-label">Farm Type</label> --}}
	</div>
</div>

<!-- Farm Landline -->
<div class="row">
	<div class="input-field col s12">
		{!! Form::text('farm_landline', null)!!}
		{{-- <input type="text" name="farm_landline" class="form-control" value="{{ old('farm_landline') }}"> --}}
		{!! Form::label('farm_landline', 'Farm Landline') !!}
		{{-- <label for="farm_landline" class="col-sm-3 control-label">Farm Landline</label> --}}
	</div>
</div>

<!-- Farm Mobile -->
<div class="row">
	<div class="input-field col s12">
		{!! Form::text('farm_mobile', null)!!}
		{{-- <input type="text" name="farm_mobile" class="form-control" value="{{ old('farm_mobile') }}"> --}}
		{!! Form::label('farm_mobile', 'Farm Mobile') !!}
		{{-- <label for="farm_mobile" class="col-sm-3 control-label">Farm Mobile</label> --}}
	</div>
</div>

<!-- Submit Button -->
<div class="col s6 push-s6">
	<button type="submit" class="btn waves-effect waves-light"> Submit
		<i class="material-icons right">send</i>
	</button>
</div>
