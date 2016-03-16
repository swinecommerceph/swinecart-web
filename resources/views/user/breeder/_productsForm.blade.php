<!--
	This is the form Breeder Users use
	for adding products

	Input Fields include:
		Name
		Type
		Age
		Breed
		Price
		Quantity (only for semen type)
		Other Product Details
-->

<div class="row">

	<div class="col s12">
		<div class="card-panel">

			<div class="row">
			<!-- Name -->
				<div class="input-field col s10 push-s1">
					{!! Form::text('name', null, ['autofocus' => 'autofocus'])!!}
					{!! Form::label('name', 'Name*') !!}
				</div>
			</div>


			<div class="row">
				<!-- Type -->
				<div class="input-field col s10 push-s1">
					<select class="select">
				      <option value="" disabled selected>Choose your option</option>
				      <option value="boar">Boar</option>
				      <option value="sow">Sow</option>
				      <option value="semen">Semen</option>
				    </select>
				    <label>Materialize Select</label>
				</div>
			</div>


			<div class="row">
				<!-- Age -->
				<div class="input-field col s5 push-s1">
					{!! Form::text('age', null)!!}
					{!! Form::label('age', 'Age*') !!}
				</div>

				<!-- Breed -->
				<div class="input-field col s5 push-s1">
					{!! Form::text('breed', null)!!}
					{!! Form::label('breed', 'Breed*') !!}
				</div>
			</div>

			<div class="row">
				<!-- Price -->
				<div class="input-field col s5 push-s1">
					{!! Form::text('price', null)!!}
					{!! Form::label('price', 'Price') !!}
				</div>

				<!-- Quantity -->
				<div class="input-field col s5 push-s1">
					{!! Form::text('quantity', null)!!}
					{!! Form::label('quantity', 'Quantity*') !!}
				</div>
			</div>

			<div class="row">
				<div class="col s10 offset-s1">
					<h5 class="center-align">Other Details</h5>
				</div>
			</div>

			<div class="row">
				<!-- Other Product Details -->
				<div class="input-field col s5 push-s1">
					{!! Form::textarea('other_details', null, ['class' => 'materialize-textarea'])!!}
					{!! Form::label('other_details', 'Other Details*') !!}
				</div>
			</div>

			<div class="row">
				<!-- Images -->
				<div class="input-field col s5 push-s1">
					{!! Form::textarea('other_details', null, ['class' => 'materialize-textarea'])!!}
					{!! Form::label('other_details', 'Other Details*') !!}
				</div>
			</div>

			<div class="row">
				<!-- Videos -->
				<div class="input-field col s5 push-s1">
					{!! Form::textarea('other_details', null, ['class' => 'materialize-textarea'])!!}
					{!! Form::label('other_details', 'Other Details*') !!}
				</div>
			</div>

			<!-- Submit Button -->
			<div class="row">
			  <button type="submit" class="btn waves-effect waves-light col s3 push-s8"> Submit
				  <i class="material-icons right">send</i>
			  </button>
			</div>
		</div>
	</div>
</div>
