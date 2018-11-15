{{--
	Forms regarding creation and showcasing of Breeder's products

	General Info and other product details
	Media (Images and Videos)
--}}

{{--  Add Product Modal --}}
<div id="add-product-modal" class="modal modal-fixed-footer" style="max-height: 90%; height: 80vh !important;">
	{!! Form::open(['route' => 'products.store', 'class' => 's12', 'id' => 'create-product']) !!}
	<div class="modal-content">
		<h4>Add Product <i class="material-icons right modal-action modal-close">close</i> </h4>
		<div class="row">
			<div id="tabs-container" class="col s12">
				<ul class="tabs tabs-fixed-width grey lighten-5">
					<li id="swine-info-tab" class="tab col s4"><a href="#swine-information">Swine Information</a></li>
					<li id="breed-info-tab" class="tab col s4"><a href="#breed-information">Breed Information</a></li>
					<li id="other-details-tab" class="tab col s4"><a href="#other-details">Other Details</a></li>
				</ul>
			</div>

			{{-- Swine Information --}}
			<div id="swine-information" class="col s12 m12 l10 offset-l1">
				<div class="row">
					<br>
					{{-- Name --}}
					<div class="input-field col s6">
						{!! Form::text('name', null, ['id' => 'name', 'class' => 'validate'])!!}
						{!! Form::label('name', 'Name*') !!}
					</div>

					{{-- Type --}}
					<div class="input-field col s6">
						<select id="select-type" data-form="add">
					      <option value="" disabled selected>Choose Type</option>
					      <option value="boar">Boar</option>
					      <option value="sow">Sow</option>
						  <option value="gilt">Gilt</option>
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
						{!! Form::text('price', null, ['class' => 'validate'])!!}
						{!! Form::label('price', 'Price') !!}
					</div>
				</div>
			</div>

			{{-- Breed Information --}}
			<div id="breed-information" class="col s12 m12 l10 offset-l1">
				<div class="row">
					 {{-- Breed --}}
					<div class="input-field col s7">
						<p>
							<input name="radio-breed" type="radio" value="purebreed" id="purebreed" class="with-gap purebreed" checked/>
		      				<label for="purebreed">Purebreed</label>
						</p>
						<p>
							<input name="radio-breed" type="radio" value="crossbreed" id="crossbreed" class="with-gap crossbreed"/>
		      				<label for="crossbreed">Crossbreed</label>
						</p>
					</div>
				</div>

				<div class="row">
					<div class="input-purebreed-container">
						{{-- If pure breed --}}
						<div class="input-field col s6">
							{!! Form::text('breed', null, ['id' => 'breed'])!!}
							{!! Form::label('breed', 'Breed*') !!}
						</div>
					</div>
					<div class="input-crossbreed-container">
						{{-- If crossbreed --}}
						<div class="input-field col s6">
							{!! Form::text('fbreed', null, ['id' => 'fbreed'])!!}
							{!! Form::label('fbreed', 'Father\'s Breed*') !!}
						</div>
						<div class="input-field col s6">
							{!! Form::text('mbreed', null, ['id' => 'mbreed'])!!}
							{!! Form::label('mbreed', 'Mother\'s Breed*') !!}
						</div>
					</div>
				</div>

				<div class="row">
					{{-- Birthdate --}}
					<div class="input-field col s6">
						<input type="date" id="birthdate" name="birthdate" class="datepicker validate"/>
						<label for="birthdate">Birth Date</label>
					</div>

					{{-- ADG --}}
					<div class="input-field col s6">
						{!! Form::text('adg', null, ['class' => 'validate'])!!}
						{!! Form::label('adg', 'Average Daily Gain (grams)') !!}
					</div>
				</div>

				<div class="row">
					{{-- FCR --}}
					<div class="input-field col s6">
						{!! Form::text('fcr', null, ['class' => 'validate'])!!}
						{!! Form::label('fcr', 'Feed Conversion Ratio') !!}
					</div>

					{{-- Backfat thickness --}}
					<div class="input-field col s6">
						{!! Form::text('backfat_thickness', null, ['class' => 'validate'])!!}
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
						<a href="#" id="add-other-details" class="left tooltipped add-other-details" data-position="right" data-delay="50" data-tooltip="Add detail"><i class="material-icons teal-text text-lighten-2">add_circle</i></a>
					</div>

					<div class="other-details-container">
						<div class="detail-container">
							<div class="input-field col s6">
								{!! Form::text('characteristic[]', null, ['class' => 'validate'])!!}
								{!! Form::label('characteristic[]', 'Characteristic') !!}
							</div>
							<div class="input-field col s5">
								{!! Form::text('value[]', null, ['class' => 'validate'])!!}
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
		<button id="submit-button" type="submit" class="btn waves-effect waves-light modal-action" style="display:none;"> Add </button>
	</div>
	{!! Form::close() !!}
</div>

{{-- Add Media Modal --}}
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
		<button id="next-button" type="submit" class="btn waves-effect waves-light modal-action"> Product Summary </button>
		<a href="#!" class="modal-action waves-effect waves-green btn-flat back-button">Back</a>
	</div>
</div>

{{-- Product Summary Modal --}}
<div id="product-summary-modal" class="modal modal-fixed-footer">
	<div class="modal-content">
		<h4>Product Summary</h4>
		<div class="row">
			<ul id="product-summary-collection" class="collection with-header">
				<li class="collection-header">
					<h5>Product Name</h5>
					<h6>Province</h6>
				</li>
				<div></div>
			</ul>
		</div>
		<div class="row">
	        <div class="col s12">
	            <div id="other-details-summary" class="card">
	                <div class="card-content black-text">
	                    <span class="card-title">Other Details</span>
						<div></div>
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
		<div class="from-add-process">
			{!! Form::open(['route' => 'products.display', 'class' => 's12', 'id' => 'display-product-form']) !!}
				<button id="display-button" class="btn waves-effect waves-light modal-action"> Display </button>
			{!! Form::close() !!}
			<button id="save-draft-button" class="btn waves-effect waves-light modal-action"> Save as Draft </button>
		</div>
		<div class="from-edit-process">
			<button id="save-button" class="btn waves-effect waves-light modal-action"> Save </button>
		</div>
		<a href="#!" class="modal-action waves-effect waves-green btn-flat back-button">Back</a>
	</div>
</div>

{{-- Edit Product Modal --}}
<div id="edit-product-modal" class="modal modal-fixed-footer">
	{!! Form::open(['route' => 'products.update', 'class' => 's12', 'id' => 'edit-product']) !!}
	<div class="modal-content">
		<h4>Edit Product <i class="material-icons right modal-action modal-close">close</i> </h4>
		<div class="row">
			<div id="tabs-container" class="col s12">
				<ul class="tabs tabs-fixed-width grey lighten-5">
					<li class="tab col s4"><a href="#edit-swine-information">Swine Information</a></li>
					<li class="tab col s4"><a href="#edit-breed-information">Breed Information</a></li>
					<li class="tab col s4"><a href="#edit-other-details">Other Details</a></li>
				</ul>
			</div>

			{{-- Swine Information --}}
			<div id="edit-swine-information" class="col s12 m12 l10 offset-l1">
				<div class="row">
					<br>
					{{-- Name --}}
					<div class="input-field col s6">
						{!! Form::text('edit-name', null, ['id' => 'edit-name'])!!}
						{!! Form::label('edit-name', 'Name*') !!}
					</div>

					{{-- Type --}}
					<div class="input-field col s6">
						<select id="edit-select-type" data-form="add">
					      <option value="" disabled selected>Choose Type</option>
					      <option value="boar">Boar</option>
					      <option value="sow">Sow</option>
						  <option value="gilt">Gilt</option>
					      <option value="semen">Semen</option>
					    </select>
					    <label>Type*</label>
					</div>
				</div>

				<div class="row">
					{{-- Farm From --}}
					<div class="input-field col s6">
						<select id="edit-select-farm">
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
						{!! Form::text('edit-price', null, ['class' => 'validate'])!!}
						{!! Form::label('edit-price', 'Price') !!}
					</div>
				</div>
			</div>

			{{-- Breed Information --}}
			<div id="edit-breed-information" class="col s12 m12 l10 offset-l1">
				<div class="row">
					 {{-- Breed --}}
					<div class="input-field col s7">
						<p>
							<input name="radio-breed" type="radio" value="purebreed" id="edit-purebreed" class="with-gap purebreed" checked/>
		      				<label for="edit-purebreed">Purebreed</label>
						</p>
						<p>
							<input name="radio-breed" type="radio" value="crossbreed" id="edit-crossbreed" class="with-gap crossbreed"/>
		      				<label for="edit-crossbreed">Crossbreed</label>
						</p>
					</div>
				</div>

				<div class="row">
					<div class="input-purebreed-container">
						{{-- If pure breed --}}
						<div class="input-field col s6">
							{!! Form::text('edit-breed', null, ['id' => 'edit-breed'])!!}
							{!! Form::label('edit-breed', 'Breed*') !!}
						</div>
					</div>
					<div class="input-crossbreed-container">
						{{-- If crossbreed --}}
						<div class="input-field col s6">
							{!! Form::text('edit-fbreed', null, ['id' => 'edit-fbreed'])!!}
							{!! Form::label('edit-fbreed', 'Father\'s Breed*') !!}
						</div>
						<div class="input-field col s6">
							{!! Form::text('edit-mbreed', null, ['id' => 'edit-mbreed'])!!}
							{!! Form::label('edit-mbreed', 'Mother\'s Breed*') !!}
						</div>
					</div>
				</div>

				<div class="row">
					{{-- Birhtdate --}}
					<div class="input-field col s6">
						<input type="date" id="edit-birthdate" name="edit-birthdate" class="datepicker"/>
						<label for="edit-birthdate">Birth Date</label>
					</div>

					{{-- ADG --}}
					<div class="input-field col s6">
						{!! Form::text('edit-adg', null, ['class' => 'validate'])!!}
						{!! Form::label('edit-adg', 'Average Daily Gain (grams)') !!}
					</div>
				</div>

				<div class="row">
					{{-- FCR --}}
					<div class="input-field col s6">
						{!! Form::text('edit-fcr', null, ['class' => 'validate'])!!}
						{!! Form::label('edit-fcr', 'Feed Conversion Ratio') !!}
					</div>

					{{-- Backfat thickness --}}
					<div class="input-field col s6">
						{!! Form::text('edit-backfat_thickness', null, ['class' => 'validate'])!!}
						{!! Form::label('edit-backfat_thickness', 'Backfat thickness (mm)') !!}
					</div>
				</div>

			</div>

			{{-- Other Details --}}
			<div id="edit-other-details" class="col s12 m12 l10 offset-l1">
				<div class="row">
					<br>
					{{-- Other Details --}}
					<div class="col s12">
						<a href="#" id="add-other-details" class="left tooltipped add-other-details" data-position="right" data-delay="50" data-tooltip="Add detail"><i class="material-icons teal-text text-lighten-2">add_circle</i></a>
					</div>

					<div class="other-details-container">
						<div class="detail-container">
							<div class="input-field col s6">
								{!! Form::text('characteristic[]', null, ['class' => 'validate'])!!}
								{!! Form::label('characteristic[]', 'Characteristic') !!}
							</div>
							<div class="input-field col s5">
								{!! Form::text('value[]', null, ['class' => 'validate'])!!}
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
		<div class="from-add-process" style="display:none;">
			<button id="add-media-button" class="btn waves-effect waves-light modal-action"> Add Media </button>
		</div>
		<div class="from-edit-process">
			<button class="btn waves-effect waves-light modal-action update-button"> Update Product </button>
			<button id="edit-media-button" class="btn waves-effect waves-light modal-action"> Edit Media </button>
		</div>
	</div>
	{!! Form::close() !!}
</div>

{{-- Edit Media Modal --}}
<div id="edit-media-modal" class="modal modal-fixed-footer">
	<div class="modal-content">
		<h4>Edit Media </h4>
		<div class="row">
			{!! Form::open(['route' => 'products.mediaUpload', 'class' => 's12 dropzone', 'id' => 'edit-media-dropzone', 'enctype' => 'multipart/form-data']) !!}
				<div class="fallback">
					<input type="file" name="media[]" accept="image/png, image/jpeg, image/jpg, video/avi, video/mp4, video/flv, video/mov" multiple>
				</div>
			{!! Form::close() !!}
		</div>
		<div class="row">
	        <div class="col s12">
	            <div id="edit-images-summary" class="card">
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
	            <div id="edit-videos-summary" class="card">
	                <div class="card-content black-text">
	                    <span class="card-title">Videos</span>
						<div class="row"></div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>

	<div class="modal-footer">
		<button class="btn waves-effect waves-light modal-action update-button"> Update Product </button>
		<a href="#!" class="modal-action waves-effect waves-green btn-flat back-button">Back</a>
	</div>
</div>

{{-- Confirmation Modal --}}
<div id="confirmation-modal" class="modal">
	<div class="modal-content">
	  <h5>Remove product/s from your database?</h5>
	</div>
	<div class="modal-footer">
	  <a
	  	href="#!"
	  	id="confirm-remove"
	  	class=" modal-action modal-close waves-effect waves-green btn-flat red darken-4 white-text"
	  	style="text-transform: none;  font-weight: 700;"
	  >
			Remove
		</a>
	  
	  <a
	  	href="#!"
	  	class="modal-action modal-close waves-effect waves-green btn-flat grey-text"
	  	style="text-transform: none; font-weight: 700;"
	  >
			No
		</a>
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
