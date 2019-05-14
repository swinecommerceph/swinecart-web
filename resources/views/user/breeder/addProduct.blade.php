{{--
    Add Product Page of Breeder
--}}

@extends('user.breeder.home')

@section('title')
  | Breeder - Add Products
@endsection

@section('breadcrumbTitle')
  <div class="breadcrumb-container">    
    Add Product
  </div>
@endsection

@section('breadcrumb')
  <div class="breadcrumb-container">    
      <a href="{{ route('home_path') }}" class="breadcrumb">Home</a>
      <a href="#!" class="breadcrumb">Add Product</a>
  </div>
@endsection

@section('breeder-content')
  <div id="add-product-modal" class="row">
    <div class="col s1"></div>
    <div class="col s11">
      @include('common._errors')
      {!! Form::open(['route' => 'products.store', 'class' => 's12', 'id' => 'create-product']) !!}

        {{-- Product Information --}}
        <p style="font-weight: 600; margin-bottom: 2vh; font-size: 1.2rem;" class="teal-text text-darken-4">Product Information</p> 
        
        <div class="row">
          <div class="col s0.5"></div>
          <div class="col s6">
            
            {{-- Name --}}
            <div style="margin-bottom: 2vh; width: 20vw;" class="input-field">
              {!! Form::text('name', null, ['id' => 'name', 'class' => 'validate input-manage-products'])!!}
              {!! Form::label('name', 'Name', ['class' => 'teal-text text-darken-4', 'style' => 'font-size: 1rem;']) !!}
            </div>

            {{-- Type --}}
            <div class="row">
                <div class="col s4.5" style="padding-left: 0px !important">
                  <div style="margin-bottom: 2vh; width: 10vw;" class="input-field">
                    <select id="select-type" data-form="add">
                      <option value="" disabled selected>Choose Type</option>
                      <option value="boar" >Boar</option>
                      <option value="sow">Sow</option>
                      <option value="gilt">Gilt</option>
                      <option value="semen">Semen</option>
                    </select>
                    <label id="select-type-label" for="type" style="font-size: 1rem; margin-top: 2rem;" class="teal-text text-darken-4">Type</label>
                  </div>
                </div>              
              
                <div id="select-type-data-error" style="display:none;" class="col s5">
                  <p style="margin-top: 3vh;" class="red-text">Please choose a product type</p> 
                </div>
              
            </div>
            
            <p style="margin-bottom: 1vh;" class="teal-text text-darken-4">
              Price (range)
              <span class="grey-text">
                <i> - Optional
                </i>
              </span>
            </p>

            {{-- Price (from) --}}
            <div class="col s4 input-field" style="padding-left: 0vw !important; margin-top: 0vh !important; width: 7vw;">
              {!! Form::text('min_price', null, ['class' => 'validate input-manage-products'])!!}
            </div>
            
            <div class="col s1" style="padding-top: 1vh;">
              to
            </div>

            {{-- Price (to) --}}
            <div class="col s4 input-field" style="margin-top: 0vh !important; width: 8vw;">
              {!! Form::text('max_price', null, ['class' => 'validate input-manage-products'])!!}
            </div>

          </div>

        </div>
        <br>

        {{-- Prompt for semen type product --}}
        <blockquote 
          id="semen-blockquote"
          class="info-two"
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

        <div class="row">
          <div class="col s0.5"></div>
          <div class="col s6">
            <input type="checkbox" id="check" class="product-unique-checker filled-in">
            <label for="check">Yes, this product is unique</label>
          </div>
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
                    value="1"
                    min="1"
                    onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 49 && event.charCode <= 57"
                    class="product-quantity center-align"
                    style="margin:0;"
                >
              </span>
          </span>
        </div>

        <br><br><br><br>

        {{-- Swine Information --}}
        <p style="font-weight: 600; margin-bottom: 2vh; font-size: 1.2rem;" class="teal-text text-darken-4">Swine Information</p>
        <div class="row">
          <div class="col s0.5"></div>
          <div class="col s6">
            
            {{-- Breed Type --}}
            <label style="font-size: 1rem;" class="teal-text text-darken-4">Breed Type</label>
            <div class="row">
              <div class="input-field col s7">
                <p>
                  <input name="radio-breed" type="radio" value="purebreed" id="purebreed" class="with-gap purebreed" checked/>
                  <label class="teal-text text-darken-4" for="purebreed">Purebreed</label>
                </p>
                <p>
                  <input name="radio-breed" type="radio" value="crossbreed" id="crossbreed" class="with-gap crossbreed"/>
                  <label class="teal-text text-darken-4" for="crossbreed">Crossbreed</label>
                </p>
              </div>
            </div>

            {{-- Breed --}}
            <div class="row">
              <div class="input-purebreed-container">
                {{-- If pure breed --}}
                <div class="input-field" style="width: 20vw;">
                  {!! Form::text('breed', null, ['id' => 'breed', 'class' => 'validate input-manage-products'])!!}
                  {!! Form::label('breed', 'Breed', ['class' => 'teal-text text-darken-4', 'style' => 'font-size: 1rem;']) !!}
                </div>
              </div>
              <div class="input-crossbreed-container">
                {{-- If crossbreed --}}
                <div class="input-field" style="width: 20vw;">
                  {!! Form::text('fbreed', null, ['id' => 'fbreed', 'class' => 'validate input-manage-products'])!!}
                  {!! Form::label('fbreed', 'Father\'s Breed', ['class' => 'teal-text text-darken-4', 'style' => 'font-size: 1rem;']) !!}
                </div>
                <div class="input-field" style="width: 20vw;">
                  {!! Form::text('mbreed', null, ['id' => 'mbreed', 'class' => 'validate input-manage-products'])!!}
                  {!! Form::label('mbreed', 'Mother\'s Breed', ['class' => 'teal-text text-darken-4', 'style' => 'font-size: 1rem;']) !!}
                </div>
              </div>
            </div>

            <div class="row">
              
              {{-- Birthdate --}}
              <div class="col s5.5" style="padding-left: 0px !important">
                <div class="input-field" style="width: 13vw; display: flex !important;">
                  <input style="cursor: pointer;" type="date" id="birthdate" name="birthdate" class="datepicker validate"/>
                  <i 
                    class="material-icons teal-text text-darken-2"
                    style="font-size: 3rem; z-index: -1 !important; left: 10.5vw; !important; position: absolute;"
                  >date_range</i>
                  <label style="font-size: 1rem;" class="teal-text text-darken-4" for="birthdate">
                    Birth Date
                  </label>
                </div>
              </div>
  
              <div id="birthdate-data-error" style="display:none;" class="col s5">
                <p style="margin-top: 3vh;" class="red-text">Please choose swine's birthdate</p> 
              </div>

            </div>

             
            {{-- Birth weight --}}
            <div class="input-field">
              {!! Form::text('birthweight', null, ['class' => 'validate input-manage-products', 'style' => 'width: 7vw;'])!!}
              {!! Html::decode(Form::label('birth_weight','<p style="font-size:1rem;" class="teal-text text-darken-4">Birth weight <span class="grey-text"><i>- Optional</i></span></p>')) !!}
            </div>

            {{-- Farm From --}}
            <div class="row">
              <div class="col s6" style="padding-left: 0px !important">
                <div style="margin-bottom: 4vh; width: 15vw;" class="input-field">
                  <select id="select-farm">
                    <option value="" disabled selected>Choose farm</option>
                    @foreach($farms as $farm)
                      {{-- @if($farm->name === "aliquid, Siquijor")
                        <option value="{{$farm->id}}" >{{$farm->name}}, {{$farm->province}}</option>
                      @endif --}}
                      <option value="{{$farm->id}}">{{$farm->name}}, {{$farm->province}}</option>
                    @endforeach
                  </select>
                  <label style="font-size: 1rem;" class="teal-text text-darken-4">Farm From</label>
                </div>
              </div>

              <div id="select-farm-data-error" style="display: none;" class="col s5">
                <p style="margin-top: 3vh;" class="red-text">Please choose a farm</p> 
              </div>
            </div>

            {{-- House type --}}
            <div style="margin-bottom: 8vh; width: 12vw;" class="input-field">
              <select id="select-housetype">
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
              {!! Form::text('adg', null, ['class' => 'validate input-manage-products', 'style' => 'width: 7vw;'])!!}
              {!! Html::decode(Form::label('adg','<p style="font-size:1rem;" class="teal-text text-darken-4">Average Daily Gain (grams) <span class="grey-text"><i>- Optional</i></span></p>')) !!}
            </div>
    
            <div class="row">
              {{-- FCR --}}
              <div class="input-field">
                {!! Form::text('fcr', null, ['class' => 'validate input-manage-products', 'style' => 'width: 7vw;'])!!}
                {!! Html::decode(Form::label('fcr','<p style="font-size:1rem;" class="teal-text text-darken-4">Feed Conversion Ratio <span class="grey-text"><i>- Optional</i></span></p>')) !!}
              </div>
    
              {{-- Backfat thickness --}}
              <div class="input-field">
                {!! Form::text('backfat_thickness', null, ['class' => 'validate input-manage-products', 'style' => 'width: 7vw;'])!!}
                {!! Html::decode(Form::label('backfat_thickness','<p style="font-size:1rem;" class="teal-text text-darken-4">Backfat thickness (mm) <span class="grey-text"><i>- Optional</i></span></p>')) !!}
              </div>

              {{-- Litter size born alive --}}
              <div class="input-field">
                {!! Form::text('lsba', null, ['class' => 'validate input-manage-products', 'style' => 'width: 7vw;'])!!}
                {!! Html::decode(Form::label('lsba','<p style="font-size:1rem;" class="teal-text text-darken-4">Litter size born alive <span class="grey-text"><i>- Optional</i></span></p>')) !!}
              </div>
            </div>

            {{-- Number of teats --}}
            <div id="number-of-teats-container" style="display: none;">
              <p style="margin-bottom: 3vh;" class="teal-text text-darken-4">
                Number of teats
                <span class="grey-text">
                  <i> - Optional
                  </i>
                </span>
              </p>
              
              {{-- Number of teats (left) --}}
              <div class="col s4 input-field" style="padding-left: 0vw !important; margin-top: 0vh !important;">
                {!! Form::text('left_teats', null, ['class' => 'validate input-manage-products', 'style' => 'width: 4vw;'])!!}
                {!! Form::label('left_teats', '(left)', ['class' => 'teal-text text-darken-4', 'style' => 'font-size: 1rem; padding-left: 0vw;']) !!}
              </div>
              
              {{-- Number of teats (right) --}}
              <div class="col s4 input-field" style="margin-top: 0vh !important;">
                {!! Form::text('right_teats', null, ['class' => 'validate input-manage-products', 'style' => 'width: 4vw;'])!!}
                {!! Form::label('right_teats', '(right)', ['class' => 'teal-text text-darken-4', 'style' => 'font-size: 1rem;']) !!}
              </div>
            </div>


          </div>
        </div>

        {{-- Other Details --}}
        <p style="font-weight: 600; margin-bottom: 2vh; font-size: 1.2rem;" class="teal-text text-darken-4">
          Other Details
          <span style="font-size: 1rem; font-weight: 400" class="grey-text">
            <i> - Optional
            </i>
          </span>
        </p>
        {{-- Has a default value, no need to make it work since this will be changed --}}
        <div class="row">
          <div class="col s0.5"></div>
          <div class="col s6">
            <textarea id="other_details" class="materialize-textarea"></textarea>
          </div>
        </div>

        <div class="row"></div>
        <div class="row"></div>
        <div class="row"></div>
        <div class="row"></div>
        {{-- Add Product button --}}
        <div>
          <button style="font-weight: 900; width: 15vw; font-size: 1.4rem" id="submit-button" type="submit" class="right btn-large waves-effect waves-light teal darken-4"> Add Product</button>
        </div>
      {!! Form::close() !!}
    </div>
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

      <blockquote
        class="info-two"
      >
        <b>Note:</b> Adding media (images or videos) is not required in Adding a Product. You can add them later when editing a product.
      </blockquote>
    </div>
    <div class="modal-footer">
      <button id="next-button" type="submit" class="btn waves-effect waves-light modal-action teal darken-3"> Product Summary </button>
    </div>
  </div>

  {{-- Product Summary Modal --}}
  <div id="product-summary-modal" class="modal modal-fixed-footer" style="max-height: 90%; height: 80vh !important; width: 60vw !important;">
    <div class="modal-content">
      <h4>Product Summary</h4>
      <div class="row">
        <div 
          id="product-summary-collection"
          style="
            background-color: white; 
            padding-top: 1vh;
            padding-left: 1vw;
            border: solid 1px #eeeeee;
          "
        >
          
          <h3 style="color: hsl(0, 0%, 13%); font-weight: 700; margin-left: -3px !important;">Product Name</h3>
          <h5 style="color: hsl(0, 0%, 29%); margin-left: -3px !important;">Product Type</h5>
          <p id="product-summary-province" style="color: hsl(0, 0%, 45%); margin-bottom: 0 !important;">Province</p>
          <p id="product-summary-birthdate" style="color: hsl(0, 0%, 45%); margin-top: 0 !important; margin-bottom: 0 !important;">Birthdate</p>
          
          {{-- SwineCart Information --}}
          <p style="font-weight:600; margin-top: 4vh; font-size: 1.4rem;" class="teal-text text-darken-4">Swine Information</p>
          <div id="swine-information"></div>

          {{-- Other Information --}}
          <p style="font-weight:600; margin-top: 4vh; font-size: 1.4rem;" class="teal-text text-darken-4">Other Information</p>
          <div id="other-information"></div>
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
@endsection

@section('customScript')
    <script src="{{ elixir('/js/breeder/showProducts.js') }}"></script>
@endsection