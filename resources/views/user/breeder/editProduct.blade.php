{{--
    Add Product Page of Breeder
--}}

@extends('user.breeder.home')

@section('title')
  | Breeder - Edit Product
@endsection

@section('breadcrumbTitle')
  <div class="breadcrumb-container">    
    Edit Product
  </div>
@endsection

@section('breadcrumb')
  <div class="breadcrumb-container">    
      <a href="{{route('products',['type' => 'all-type', 'status' => 'all-status', 'sort' => 'none'])}}" class="breadcrumb">Products</a>
      <a href="#!" class="breadcrumb">Edit the product: <b>{{ $product->name }}</b></a>
  </div>
@endsection

@section('breeder-content')


  {{-- Edit Product Modal --}}
  <div id="edit-product-modal" class="row">
    <div class="col s1"></div>
    <div class="col s11">
      @include('common._errors')
      {!! Form::open(['route' => 'products.update', 'class' => 's12', 'id' => 'edit-product']) !!}

      {{-- Swine Information --}}
      <p style="font-weight: 600; margin-bottom: 2vh; font-size: 1.2rem;" class="teal-text text-darken-4">Product Information</p> 
      <div class="row">
        <div class="col s0.5"></div>
        <div class="col s6">
          
          {{-- Name --}}
          <div style="margin-bottom: 2vh; width: 20vw;" class="input-field">
            {!! Form::text('edit-name', null, ['id' => 'edit-name', 'class' => 'validate input-manage-products'])!!}
            {!! Form::label('edit-name', 'Name', ['class' => 'teal-text text-darken-4', 'style' => 'font-size: 1rem;']) !!}
          </div>

          {{-- Type --}}
          <div style="margin-bottom: 2vh; width: 10vw;" class="input-field">
            <select id="edit-select-type" data-form="add">
              <option value="" disabled selected>Choose Type</option>
              <option value="boar">Boar</option>
              <option value="sow">Sow</option>
              <option value="gilt">Gilt</option>
              <option value="semen">Semen</option>
            </select>
            <label style="font-size: 1rem;" class="teal-text text-darken-4">Type</label>
          </div>

          {{-- Price --}}
          <p style="margin-bottom: 1vh;" class="teal-text text-darken-4">
            Price (range)
            <span class="grey-text">
              <i> - Optional
              </i>
            </span>
          </p>
          
          {{-- <div style="margin-bottom: 4vh;" class="input-field">
            {!! Form::text('edit-price', null, ['id' => 'edit-price', 'class' => 'validate input-manage-products price-field'])!!}
            {!! Form::label('edit-price', 'Price', ['class' => 'teal-text text-darken-4', 'style' => 'font-size: 1rem;']) !!}
          </div> --}}
          {{-- Price (from) --}}
          <div class="col s4 input-field" style="padding-left: 0vw !important; margin-top: 0vh !important; width: 7vw;">
            {!! Form::text('edit-min_price', null, ['id' => 'edit-min_price', 'class' => 'validate input-manage-products'])!!}
          </div>
          
          <div class="col s1" style="padding-top: 1vh;">
            to
          </div>

          {{-- Price (to) --}}
          <div class="col s4 input-field" style="margin-top: 0vh !important; width: 8vw;">
            {!! Form::text('edit-max_price', null, ['id' => 'edit-max_price', 'class' => 'validate input-manage-products'])!!}
          </div>

        </div>
      </div>
      <br>

      {{-- Prompt for semen type product --}}
        <blockquote 
          id="semen-blockquote"
          class="info"
          style="display: none !important;">
          <b>Product with type 'Semen' will have no quantity and will not be unique</b>
        </blockquote>

        {{-- Checkbox if the product is unique --}}
        <p style="font-weight: 600; margin-bottom: 2vh; font-size: 1.2rem;" class="teal-text text-darken-4">
          Is this product unique?
          <span style="font-size: 1rem; font-weight: 400" class="grey-text">
            <i> - If any customer buys a unique product, it will disappear upon being sold
            </i>
          </span>
        </p>

        <div>
          <input type="checkbox" id="check" class="edit-product-unique-checker">
          <label for="check">Yes, this product is unique</label>
        </div>

        <br>

        {{-- Checkbox if the product is unique --}}
        <p style="font-weight: 600; margin-bottom: 2vh; font-size: 1.2rem;" class="teal-text text-darken-4">
          Quantity of Product to be Added
          <span style="font-size: 1rem; font-weight: 400" class="grey-text">
            <i> - Unique products will always have a quantity of one (1).
            </i>
          </span>
        </p>
        
        {{-- Product Quantity --}}
        <div class="s6 col">
          <span class="col s2 center-align" style="padding:0; margin-left: 1rem; margin-right: 0.5rem;">
              <span class="col s12" style="padding:0;">
                <input 
                    type="number"
                    ref="input"
                    value="{{ $product->quantity }}"
                    min="1"
                    onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 49 && event.charCode <= 57"
                    class="edit-product-quantity center-align"
                    style="margin:0;"
                >
                </span>
          </span>
        </div>

        <br><br><br><br>

      {{-- Breed Information --}}
      <p style="font-weight: 600; margin-bottom: 2vh; font-size: 1.2rem;" class="teal-text text-darken-4">Swine Information</p>
      
      <div class="row">
        <div class="col s0.5"></div>
        <div class="col s6">
          
          {{-- Breed Type --}}
          <label style="font-size: 1rem;" class="teal-text text-darken-4">Breed Type</label>
          <div class="row">
            <div class="input-field col s7">
              <p>
                <input name="radio-breed" type="radio" value="purebreed" id="edit-purebreed" class="with-gap purebreed" checked/>
                <label class="teal-text text-darken-4" for="purebreed">Purebreed</label>
              </p>
              <p>
                <input name="radio-breed" type="radio" value="crossbreed" id="edit-crossbreed" class="with-gap crossbreed"/>
                <label class="teal-text text-darken-4" for="crossbreed">Crossbreed</label>
              </p>
            </div>
          </div>

          {{-- Breed --}}
          <div class="row">
            <div class="input-purebreed-container">
              {{-- If pure breed --}}
              <div class="input-field" style="width: 20vw;">
                {!! Form::text('edit-breed', null, ['id' => 'edit-breed', 'class' => 'validate input-manage-products'])!!}
                {!! Form::label('edit-breed', 'Breed', ['class' => 'teal-text text-darken-4', 'style' => 'font-size: 1rem;']) !!}
              </div>
            </div>
            <div class="input-crossbreed-container">
              {{-- If crossbreed --}}
              <div class="input-field" style="width: 20vw;">
                {!! Form::text('edit-fbreed', null, ['id' => 'edit-fbreed', 'class' => 'validate input-manage-products'])!!}
                {!! Form::label('edit-fbreed', 'Father\'s Breed', ['class' => 'teal-text text-darken-4', 'style' => 'font-size: 1rem;']) !!}
              </div>
              <div class="input-field" style="width: 20vw;">
                {!! Form::text('edit-mbreed', null, ['id' => 'edit-mbreed', 'class' => 'validate input-manage-products'])!!}
                {!! Form::label('edit-mbreed', 'Mother\'s Breed', ['class' => 'teal-text text-darken-4', 'style' => 'font-size: 1rem;']) !!}
              </div>
            </div>
          </div>

          <div class="row">
            {{-- Birthdate --}}
            <div class="input-field" style="width: 13vw; display: flex !important;">
              <input style="cursor: pointer;" type="date" id="edit-birthdate" name="edit-birthdate" class="datepicker"/>
              <i 
                class="material-icons teal-text text-darken-2"
                style="font-size: 3rem; z-index: -1 !important; left: 10.5vw; !important; position: absolute;"
              >date_range</i>
              <label style="font-size: 1rem;" class="teal-text text-darken-4" for="edit-birthdate">
                  Birth Date
              </label>
            </div>

            {{-- Birth weight --}}
            <div class="input-field">
              {!! Form::text('edit-birthweight', null, ['id' => 'edit-birthweight', 'class' => 'validate input-manage-products', 'style' => 'width: 7vw;'])!!}
              {!! Html::decode(Form::label('edit-birthweight','<p style="font-size:1rem;" class="teal-text text-darken-4">Birth weight <span class="grey-text"><i>- Optional</i></span></p>')) !!}
            </div>

            {{-- Farm From --}}
            <div style="margin-bottom: 4vh; width: 20vw;" class="input-field">
              <select id="edit-select-farm">
                  <option value="" disabled selected>Choose Farm</option>
                  @foreach($farms as $farm)
                    <option value="{{$farm->id}}">{{$farm->name}}, {{$farm->province}}</option>
                  @endforeach
                </select>
                <label style="font-size: 1rem;" class="teal-text text-darken-4">Farm From</label>
            </div>

            {{-- House type --}}
            <div style="margin-bottom: 8vh; width: 12vw;" class="input-field">
              <select id="edit-select-housetype">
                <option value="" disabled selected>Choose house type</option>
                <option value="tunnelventilated">Tunnel ventilated</option>
                <option value="opensided">Open sided</option>
              </select>
              <label style="font-size: 1rem;" class="teal-text text-darken-4">
                House type
                <span class="grey-text">
                  <i>
                    - Optional
                  </i>
                </span>
              </label>
            </div>

            {{-- ADG --}}
            <div class="input-field">
              {!! Form::text('edit-adg', null, ['id' => 'edit-adg', 'class' => 'validate input-manage-products', 'style' => 'width: 7vw;'])!!}
              {!! Html::decode(Form::label('edit-adg','<p style="font-size:1rem;" class="teal-text text-darken-4">Average Daily Gain (grams) <span class="grey-text"><i>- Optional</i></span></p>')) !!}
            </div>
          </div>

          <div class="row">
            {{-- FCR --}}
            <div class="input-field">
              {!! Form::text('edit-fcr', null, ['id' => 'edit-fcr', 'class' => 'validate input-manage-products', 'style' => 'width: 7vw;'])!!}
              {!! Html::decode(Form::label('edit-fcr','<p style="font-size:1rem;" class="teal-text text-darken-4">Feed Conversion Ratio <span class="grey-text"><i>- Optional</i></span></p>')) !!}
            </div>

            {{-- Backfat thickness --}}
            <div class="input-field">
              {!! Form::text('edit-backfat_thickness', null, ['id' => 'edit-backfat_thickness', 'class' => 'validate input-manage-products', 'style' => 'width: 7vw;'])!!}
              {!! Html::decode(Form::label('edit-backfat_thickness','<p style="font-size:1rem;" class="teal-text text-darken-4">Backfat thickness (mm) <span class="grey-text"><i>- Optional</i></span></p>')) !!}
            </div>

            {{-- Litter size born alive --}}
            <div class="input-field">
              {!! Form::text('edit-lsba', null, ['id' => 'edit-lsba', 'class' => 'validate input-manage-products', 'style' => 'width: 7vw;'])!!}
              {!! Html::decode(Form::label('edit-lsba','<p style="font-size:1rem;" class="teal-text text-darken-4">Litter size born alive <span class="grey-text"><i>- Optional</i></span></p>')) !!}
            </div>

            {{-- Number of teats --}}
            <div id="edit-number-of-teats-container" style="display: none;>
              <p style="margin-bottom: 3vh;" class="teal-text text-darken-4">
                Number of teats
                <span class="grey-text">
                  <i> - Optional
                  </i>
                </span>
              </p>
              
              {{-- Number of teats (left) --}}
              <div class="col s4 input-field" style="padding-left: 0vw !important; margin-top: 0vh !important;">
                {!! Form::text('edit-left_teats', null, ['id' => 'edit-left_teats', 'class' => 'validate input-manage-products', 'style' => 'width: 4vw;'])!!}
                {!! Form::label('edit-left_teats', '(left)', ['class' => 'teal-text text-darken-4', 'style' => 'font-size: 1rem; padding-left: 0vw;']) !!}
              </div>
              
              {{-- Number of teats (right) --}}
              <div class="col s4 input-field" style="margin-top: 0vh !important;">
                {!! Form::text('edit-right_teats', null, ['id' => 'edit-right_teats', 'class' => 'validate input-manage-products', 'style' => 'width: 4vw;'])!!}
                {!! Form::label('edit-right_teats', '(right)', ['class' => 'teal-text text-darken-4', 'style' => 'font-size: 1rem;']) !!}
              </div>
            </div>
            
          </div>
        </div>
      </div>

      {{-- Other Details --}}
      <p style="font-weight: 600; margin-bottom: 2vh; font-size: 1.2rem;" class="teal-text text-darken-4">Other Details</p>
      

      {{-- Has a default value, no need to make it work since this will be changed --}}
      <div class="row">
        <div class="col s0.5"></div>
        <div class="col s6">
          <textarea id="edit-other_details" class="materialize-textarea"></textarea>
        </div>
      </div>

      {{-- Add Media --}}
      {{-- <div class="row">
        <div class="col s3">
          <div id="add-media-button" class="btn blue">
            Add Media
          </div>
        </div>
      </div> --}}

      </div>
    <div class="row">
      <button id="edit-media-button" style="font-weight: 900; width: 15vw; font-size: 1.4rem" type="submit" class="right btn-large waves-effect waves-light teal darken-4">Edit Media</button>
      <button style="font-weight: 900; width: 15vw; font-size: 1.4rem; margin-right: 2vw;" type="submit" class="right btn-large waves-effect waves-light teal darken-4 update-button">Edit Product</button>

    </div>
      
    {!! Form::close() !!}
  </div>

  {{-- Product Summary Modal --}}
  <div id="product-summary-modal" class="modal modal-fixed-footer" style="max-height: 90%; height: 80vh !important; width: 60vw !important;">
    <div class="modal-content">
      <h4>Product Summary</h4>
      <div class="row">
        <ul id="product-summary-collection" class="collection with-header">
          <li class="collection-header">
            <h5 style="font-weight: 700;">Product Name</h5>
            <h6>Province</h6>
          </li>
          <div></div>
        </ul>
      </div>
      <div class="row">
            <div class="col s12">
                <div id="other-details-summary" class="card" style="box-shadow: 0px 0px !important; border: 1px solid #DFDFDF;">
                    <div class="card-content black-text">
                        <span class="card-title">Other Details</span>
              <div></div>
                    </div>
                </div>
            </div>
        </div>
      <div class="row">
            <div class="col s12">
                <div id="images-summary" class="card grey lighten-5" style="box-shadow: 0px 0px !important; border: none;">
                    <div class="card-content black-text">
                        <span class="card-title">List of Images</span>
              {!! Form::open(['route' => 'products.setPrimaryPicture', 'class' => 's12']) !!}
              <div class="image-contents"></div>
              {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        
      <div class="row">
            <div class="col s12">
                <div id="videos-summary" class="card grey lighten-5" style="box-shadow: 0px 0px !important; border: none;">
                    <div class="card-content black-text">
                        <span class="card-title">List of Videos</span>
              <div class="video-contents"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer" style="background: hsl(0, 0%, 97%); border: none;">
      <div class="from-add-process">
        {!! Form::open(['route' => 'products.display', 'class' => 's12', 'id' => 'display-product-form']) !!}
          <button id="display-button" class="btn waves-effect waves-light modal-action teal darken-3"> Display Product</button>
          <button id="save-draft-button" class="btn waves-effect waves-light modal-action teal darken-3"> Save as Draft </button>
        {!! Form::close() !!}
      </div>
      <div class="from-edit-process">
        <a href="#!" style="text-transform: none !important;" class="modal-action waves-effect waves-green btn-flat back-button">Back</a>
        <button id="save-button" class="btn waves-effect waves-light modal-action teal darken-3"> Save </button>
      </div>
    </div>
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
      
    </div>

    <div class="modal-footer" style="background: hsl(0, 0%, 97%); border: none;">
      <button class="btn waves-effect waves-light modal-action update-button teal darken-4"> Update Product </button>
      <a href="#!" class="modal-action waves-effect waves-green btn-flat back-button">Back</a>
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
      <div class="dz-error-mark"><span><i class='medium material-icons red-text text-darken-4'>error</i></span></div>
      {{-- <div class="dz-error-message"><span data-dz-errormessage></span></div> --}}
      <a><i class="dz-remove material-icons red-text text-lighten-1 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Remove this media" data-dz-remove>cancel</i></a>
    </div>
  </div>
@endsection

@section('customScript')
    <script type="text/javascript">
      var product_data = {!! json_encode($product) !!};
    </script>
    <script src="{{ elixir('/js/breeder/editProducts.js') }}"></script>
@endsection