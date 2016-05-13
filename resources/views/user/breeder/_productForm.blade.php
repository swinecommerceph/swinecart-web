{{--
	Forms regarding creation and showcasing of Breeder's products

	General Info and other product details
	Media (Images and Videos)
--}}

<!-- Modal Structure for adding a Product -->
<div id="add-product-modal" class="modal modal-fixed-footer">
	{!! Form::open(['route' => 'products.store', 'class' => 's12', 'id' => 'create-product']) !!}
	<div class="modal-content">
		<h4>Add Product <i class="material-icons right modal-action modal-close">close</i> </h4>
		<div class="row">
			<div id="tabs-container" class="col s12">
				<ul class="tabs grey lighten-5">
					<li class="tab col s4"><a href="#swine-information">Swine Information</a></li>
					<li class="tab col s4"><a href="#breed-information">Breed Information</a></li>
					<li class="tab col s4"><a href="#other-details">Other Details</a></li>
				</ul>
			</div>

			{{-- Swine Information --}}
			<div id="swine-information" class="col s12 m12 l10 offset-l1">
				<div class="row">
					<br>
					{{-- Name --}}
					<div class="input-field col s6">
						{!! Form::text('name', null)!!}
						{!! Form::label('name', 'Name*') !!}
					</div>

					{{-- Type --}}
					<div class="input-field col s6">
						<select id="select-type">
					      <option value="" disabled selected>Choose Type</option>
					      <option value="boar">Boar</option>
					      <option value="sow">Sow</option>
					      <option value="semen">Semen</option>
					    </select>
					    <label>Type*</label>
					</div>
				</div>

				<div class="row">
					{{-- Farm From --}}
					<div class="input-field col s6">
						<select id="select-farm">
					    	<option value="" disabled selected>Choose Farm</option>
							@foreach($farms as $farm)
								<option value="{{$farm->id}}">{{$farm->name}}, {{$farm->province}}</option>
							@endforeach
					    </select>
					    <label>Farm From*</label>
					</div>
				</div>

				<div class="row">
					{{-- Price --}}
					<div class="input-field col s6">
						{!! Form::text('price', null)!!}
						{!! Form::label('price', 'Price') !!}
					</div>

					{{-- Quantity --}}
					<div id="input-quantity-container" class="input-field col s6">
						{!! Form::text('quantity', null)!!}
						{!! Form::label('quantity', 'Quantity') !!}
					</div>
				</div>
			</div>

			{{-- Breed Information --}}
			<div id="breed-information" class="col s12 m12 l10 offset-l1">
				<div class="row">
					 {{-- Breed --}}
					<div class="input-field col s7">
						<p>
							<input name="radio-breed" type="radio" value="purebreed" id="purebreed" class="with-gap" checked/>
		      				<label for="purebreed">Purebreed</label>
						</p>
						<p>
							<input name="radio-breed" type="radio" value="crossbreed" id="crossbreed" class="with-gap"/>
		      				<label for="crossbreed">Crossbreed</label>
						</p>
					</div>
				</div>

				<div class="row">
					<div id="input-purebreed-container">
						{{-- If pure breed --}}
						<div class="input-field col s6">
							{!! Form::text('breed', null)!!}
							{!! Form::label('breed', 'Breed*') !!}
						</div>
					</div>
					<div id="input-crossbreed-container">
						{{-- If crossbreed --}}
						<div class="input-field col s6">
							{!! Form::text('fbreed', null)!!}
							{!! Form::label('fbreed', 'Father\'s Breed*') !!}
						</div>
						<div class="input-field col s6">
							{!! Form::text('mbreed', null)!!}
							{!! Form::label('mbreed', 'Mother\'s Breed*') !!}
						</div>
					</div>
				</div>

				<div class="row">
					{{-- Age --}}
					<div class="input-field col s6">
						{!! Form::text('age', null)!!}
						{!! Form::label('age', 'Age (days)') !!}
					</div>

					{{-- ADG --}}
					<div class="input-field col s6">
						{!! Form::text('adg', null)!!}
						{!! Form::label('adg', 'Average Daily Gain (grams)') !!}
					</div>
				</div>

				<div class="row">
					{{-- FCR --}}
					<div class="input-field col s6">
						{!! Form::text('fcr', null)!!}
						{!! Form::label('fcr', 'Feed Conversion Ratio') !!}
					</div>

					{{-- Backfat thickness --}}
					<div class="input-field col s6">
						{!! Form::text('backfat_thickness', null)!!}
						{!! Form::label('backfat_thickness', 'Backfat thickness (mm)') !!}
					</div>
				</div>

			</div>

			{{-- Other Details --}}
			<div id="other-details" class="col s12 m12 l10 offset-l1">
				<div class="row">
					<br>
					{{-- Other Details --}}
					<div class="col s12">
						<a href="#" id="add-other-details" class="left tooltipped" data-position="right" data-delay="50" data-tooltip="Add detail"><i class="material-icons teal-text text-lighten-2">add_circle</i></a>
					</div>

					<div id="other-details-container">
						<div class="detail-container">
							<div class="input-field col s6">
								{!! Form::text('characteristic[]', null)!!}
								{!! Form::label('characteristic[]', 'Characteristic') !!}
							</div>
							<div class="input-field col s5">
								{!! Form::text('value[]', null)!!}
								{!! Form::label('value[]', 'Value') !!}
							</div>
							<div class="input-field col s1 remove-button-container">
								<a href="#" class="tooltipped remove-detail grey-text text-lighten-1" data-position="top" data-delay="50" data-tooltip="Remove detail">
						            <i class="material-icons">remove_circle</i>
						        </a>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button id="submit-button" type="submit" class="btn waves-effect waves-light modal-action"> Submit
			<i class="material-icons right">send</i>
		</button>
		{{-- <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Close</a> --}}
	</div>
	{!! Form::close() !!}
</div>

<div id="add-media-modal" class="modal modal-fixed-footer">

	<div class="modal-content">
		<h4>Add Media</h4>
		<div class="row">
			{!! Form::open(['route' => 'products.mediaUpload', 'class' => 's12 dropzone', 'id' => 'media-dropzone', 'enctype' => 'multipart/form-data']) !!}
				<div class="fallback">
					<input type="file" name="media[]" accept="image/png, image/jpeg, image/jpg, video/avi, video/mp4, video/flv, video/mov" multiple>
				</div>
			{!! Form::close() !!}
		</div>
	</div>
	<div class="modal-footer">
		<button id="next-button" type="submit" class="btn waves-effect waves-light modal-action"> Next
			<i class="material-icons right">send</i>
		</button>
		{{-- <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Close</a> --}}
	</div>
</div>

<div id="product-summary-modal" class="modal modal-fixed-footer">
	<div class="modal-content">
		<h4>Product Summary</h4>
		<div class="row">
			<ul id="product-summary-collection" class="collection with-header">
				<li class="collection-header">
					<h5>Product Name</h5>
					<h6>Province</h6>
				</li>
			</ul>
		</div>
		<div class="row">
	        <div class="col s12">
	            <div id="other-details-summary" class="card">
	                <div class="card-content black-text">
	                    <span class="card-title">Other Details</span>
	                </div>
	            </div>
	        </div>
	    </div>
		<div class="row">
	        <div class="col s12">
	            <div id="images-summary" class="card">
	                <div class="card-content black-text">
	                    <span class="card-title">Images</span>
						{!! Form::open(['route' => 'products.setPrimaryPicture', 'class' => 's12']) !!}
						<div class="row"></div>
						{!! Form::close() !!}
	                </div>
	            </div>
	        </div>
	    </div>
		<div class="row">
	        <div class="col s12">
	            <div id="videos-summary" class="card">
	                <div class="card-content black-text">
	                    <span class="card-title">Videos</span>
						<div class="row"></div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>

	<div class="modal-footer">
		{!! Form::open(['route' => 'products.showcase', 'class' => 's12', 'id' => 'showcase-product-form']) !!}
			<button id="showcase-button" class="btn waves-effect waves-light modal-action"> Showcase
				<i class="material-icons right">publish</i>
			</button>
		{!! Form::close() !!}
		<a href="#!" id="save-draft-button" class="modal-action waves-effect waves-green btn-flat ">Save as Draft</a>
	</div>
</div>

<div id="edit-product-modal" class="modal modal-fixed-footer">
	{!! Form::open(['route' => 'products.updateProduct', 'class' => 's12', 'id' => 'create-product']) !!}
	<div class="modal-content">
		<h4>Add Product <i class="material-icons right modal-action modal-close">close</i> </h4>
		<div class="row">
			<div id="tabs-container" class="col s12">
				<ul class="tabs grey lighten-5">
					<li class="tab col s4"><a href="#swine-information">Swine Information</a></li>
					<li class="tab col s4"><a href="#breed-information">Breed Information</a></li>
					<li class="tab col s4"><a href="#other-details">Other Details</a></li>
				</ul>
			</div>

			{{-- Swine Information --}}
			<div id="swine-information" class="col s12 m12 l10 offset-l1">
				<div class="row">
					<br>
					{{-- Name --}}
					<div class="input-field col s6">
						{!! Form::text('name', null)!!}
						{!! Form::label('name', 'Name*') !!}
					</div>

					{{-- Type --}}
					<div class="input-field col s6">
						<select id="select-type">
					      <option value="" disabled selected>Choose Type</option>
					      <option value="boar">Boar</option>
					      <option value="sow">Sow</option>
					      <option value="semen">Semen</option>
					    </select>
					    <label>Type*</label>
					</div>
				</div>

				<div class="row">
					{{-- Farm From --}}
					<div class="input-field col s6">
						<select id="select-farm">
					    	<option value="" disabled selected>Choose Farm</option>
							@foreach($farms as $farm)
								<option value="{{$farm->id}}">{{$farm->name}}, {{$farm->province}}</option>
							@endforeach
					    </select>
					    <label>Farm From*</label>
					</div>
				</div>

				<div class="row">
					{{-- Price --}}
					<div class="input-field col s6">
						{!! Form::text('price', null)!!}
						{!! Form::label('price', 'Price') !!}
					</div>

					{{-- Quantity --}}
					<div id="input-quantity-container" class="input-field col s6">
						{!! Form::text('quantity', null)!!}
						{!! Form::label('quantity', 'Quantity') !!}
					</div>
				</div>
			</div>

			{{-- Breed Information --}}
			<div id="breed-information" class="col s12 m12 l10 offset-l1">
				<div class="row">
					 {{-- Breed --}}
					<div class="input-field col s7">
						<p>
							<input name="radio-breed" type="radio" value="purebreed" id="purebreed" class="with-gap" checked/>
		      				<label for="purebreed">Purebreed</label>
						</p>
						<p>
							<input name="radio-breed" type="radio" value="crossbreed" id="crossbreed" class="with-gap"/>
		      				<label for="crossbreed">Crossbreed</label>
						</p>
					</div>
				</div>

				<div class="row">
					<div id="input-purebreed-container">
						{{-- If pure breed --}}
						<div class="input-field col s6">
							{!! Form::text('breed', null)!!}
							{!! Form::label('breed', 'Breed*') !!}
						</div>
					</div>
					<div id="input-crossbreed-container">
						{{-- If crossbreed --}}
						<div class="input-field col s6">
							{!! Form::text('fbreed', null)!!}
							{!! Form::label('fbreed', 'Father\'s Breed*') !!}
						</div>
						<div class="input-field col s6">
							{!! Form::text('mbreed', null)!!}
							{!! Form::label('mbreed', 'Mother\'s Breed*') !!}
						</div>
					</div>
				</div>

				<div class="row">
					{{-- Age --}}
					<div class="input-field col s6">
						{!! Form::text('age', null)!!}
						{!! Form::label('age', 'Age (days)') !!}
					</div>

					{{-- ADG --}}
					<div class="input-field col s6">
						{!! Form::text('adg', null)!!}
						{!! Form::label('adg', 'Average Daily Gain (grams)') !!}
					</div>
				</div>

				<div class="row">
					{{-- FCR --}}
					<div class="input-field col s6">
						{!! Form::text('fcr', null)!!}
						{!! Form::label('fcr', 'Feed Conversion Ratio') !!}
					</div>

					{{-- Backfat thickness --}}
					<div class="input-field col s6">
						{!! Form::text('backfat_thickness', null)!!}
						{!! Form::label('backfat_thickness', 'Backfat thickness (mm)') !!}
					</div>
				</div>

			</div>

			{{-- Other Details --}}
			<div id="other-details" class="col s12 m12 l10 offset-l1">
				<div class="row">
					<br>
					{{-- Other Details --}}
					<div class="col s12">
						<a href="#" id="add-other-details" class="left tooltipped" data-position="right" data-delay="50" data-tooltip="Add detail"><i class="material-icons teal-text text-lighten-2">add_circle</i></a>
					</div>

					<div id="other-details-container">
						<div class="detail-container">
							<div class="input-field col s6">
								{!! Form::text('characteristic[]', null)!!}
								{!! Form::label('characteristic[]', 'Characteristic') !!}
							</div>
							<div class="input-field col s5">
								{!! Form::text('value[]', null)!!}
								{!! Form::label('value[]', 'Value') !!}
							</div>
							<div class="input-field col s1 remove-button-container">
								<a href="#" class="tooltipped remove-detail grey-text text-lighten-1" data-position="top" data-delay="50" data-tooltip="Remove detail">
						            <i class="material-icons">remove_circle</i>
						        </a>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">

	</div>
	{!! Form::close() !!}

</div>

{{-- Modal Structure --}}
<div id="confirmation-modal" class="modal">
	<div class="modal-content">
	  <p>Are you sure you want to remove the products chosen?</p>
	</div>
	<div class="modal-footer">
	  <a href="#!" id="confirm-remove" class=" modal-action modal-close waves-effect waves-green btn-flat"><i class="material-icons">done</i></a>
	  <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat"><i class="material-icons">clear</i></a>
	</div>
</div>

{{--  Custom preview for dropzone --}}
<div id="custom-preview" style="display:none;">
	<div class="dz-preview dz-file-preview">
		<div class="dz-image">
			<img data-dz-thumbnail alt="" src=""/>
		</div>
		<div class="dz-details">
			<div class="dz-filename"><span data-dz-name></span></div>
			<div class="dz-size" data-dz-size></div>
		</div>
		<div class="dz-progress progress red lighten-4"><div class="determinate green" style="width:0%" data-dz-uploadprogress></div></div>
		<div class="dz-success-mark"><span><i class='medium material-icons green-text'>check_circle</i></span></div>
		<div class="dz-error-mark"><span><i class='medium material-icons orange-text text-lighten-1'>error</i></span></div>
		<div class="dz-error-message"><span data-dz-errormessage></span></div>
		<a><i class="dz-remove material-icons red-text text-lighten-1 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Remove this media" data-dz-remove>cancel</i></a>
	</div>
</div>
