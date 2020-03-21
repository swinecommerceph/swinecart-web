{{--
    Displays details of a chosen product
--}}

@extends('user.breeder.home')

@section('title')
| {{$product->name}}
@endsection

@section('pageId')
id="page-breeder-view-product-details"
@endsection

@section('breadcrumbTitle')
<div class="breadcrumb-container">
  Product: {{$product->name}}
</div>
@endsection

@section('breadcrumb')
<div class="breadcrumb-container">
  <a href="{{ route('home_path') }}" class="breadcrumb">Home</a>
  <a href="{{ route('products') }}" class="breadcrumb">Products</a>
  <a href="#!" class="breadcrumb">{{$product->name}}</a>
</div>
@endsection

@section('breeder-content')
<div class="row">
  <div class="col s12 m7">
    {{-- Primary Image --}}
    <div class="row">
      <div class="card">
        <div class="card-image">
          <img style="width: 40vw; height: 50vh;" src="{{$product->img_path}}"
            data-imagezoom="{{ $product->def_img_path }}">
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col s12">
        <ul class="tabs tabs-fixed-width">
          <li id="image-carousel-tab" class="tab col s3"><a class="active" href="#images-carousel">Images</a></li>
          <li id="video-carousel-tab" class="tab col s3"><a href="#videos-carousel">Videos</a></li>
        </ul>
      </div>
      {{-- Image Carousel --}}
      <div id="images-carousel" class="col s12">
        <div class="carousel" style="height:14rem;">
          <a class="carousel-item" href="#!"><img src="{{$product->img_path}}"></a>
          @foreach($product->imageCollection as $image)
          <a class="carousel-item" href="#!"><img src="/images/product/{{$image->name}}"></a>
          @endforeach
        </div>
      </div>
      {{--  Video Carousel --}}
      <div id="videos-carousel" class="col s12">
        @if(count($product->videoCollection) > 0)
        <div class="carousel" style="height:14rem;">
          @foreach($product->videoCollection as $video)
          <a class="carousel-item" href="#!">
            <video class="responsive-video" controls>
              <source src="/videos/product/{{$video->name}}" type="{{$video->type}}">
            </video>
          </a>
          @endforeach
        </div>
        @endif
      </div>
    </div>

  </div>

  <div id="product-details-table" class="col s12 m5">
    <h3 style="color: hsl(0, 0%, 13%); font-weight: 700">{{ $product->name }}</h3>
    <h5 style="color: hsl(0, 0%, 29%);">{{$product->type}} - {{$product->breed}}</h5>
    @if($product->birthdate === "November 30, -0001")
    <p style="color: hsl(0, 0%, 45%);"><i>No age information</i></p>
    @else
    <p style="color: hsl(0, 0%, 45%);">Birthdate {{$product->birthdate}} ({{$product->age}} days old)</p>
    @endif


    {{-- SwineCart Information --}}
    <p style="font-weight:600; margin-top: 4vh; font-size: 1.4rem;" class="teal-text text-darken-4">Swine Information
    </p>

    <li style="color: hsl(0, 0%, 29%);">Average Daily Gain:
      <span style="color: hsl(0, 0%, 13%);">
        @if ( $product->adg === 0)
        <i class="grey-text">Not Indicated</i>
        @else
        {{$product->adg}} g
        @endif
      </span>
    </li>

    <li style="color: hsl(0, 0%, 29%);">Feed Conversion Ratio:
      <span style="color: hsl(0, 0%, 13%);">
        @if ( $product->fcr === 0.0)
        <i class="grey-text">Not Indicated</i>
        @else
        {{$product->fcr}} g
        @endif
      </span>
    </li>

    <li style="color: hsl(0, 0%, 29%);">Backfat Thickness:
      <span style="color: hsl(0, 0%, 13%);">
        @if ( $product->backfat_thickness === 0.0)
        <i class="grey-text">Not Indicated</i>
        @else
        {{$product->backfat_thickness}} mm
        @endif
      </span>
    </li>

    <li style="color: hsl(0, 0%, 29%);">Litter size born alive:
      <span style="color: hsl(0, 0%, 13%);">
        @if ( $product->lsba === 0)
        <i class="grey-text">Not Indicated</i>
        @else
        {{$product->lsba}}
        @endif
      </span>
    </li>

    <li style="color: hsl(0, 0%, 29%);">Birth weight:
      <span style="color: hsl(0, 0%, 13%);">
        @if ( $product->birthweight === 0.0)
        <i class="grey-text">Not Indicated</i>
        @else
        {{$product->birthweight}} g
        @endif
      </span>
    </li>

    @if ( $product->type === "Gilt" || $product->type === "Sow")
    <li style="color: hsl(0, 0%, 29%);">Number of teats:
      <span style="color: hsl(0, 0%, 13%);">
        @if ( $product->left_teats === 0 || $product->right_teats === 0)
        <i class="grey-text">Not Indicated</i>
        @else
        {{$product->left_teats}} (left) | {{$product->right_teats}} (right)
        @endif
      </span>
    </li>
    @endif

    <li style="color: hsl(0, 0%, 29%);">House type:
      <span style="color: hsl(0, 0%, 13%);">
        @if ( $product->house_type === "")
        <i class="grey-text">Not Indicated</i>
        @else
        <span style="color: hsl(0, 0%, 13%);">
          @if($product->house_type === "tunnelventilated")
          Tunnel ventilated
          @else
          Open sided
          @endif
        </span>
        @endif
      </span>
    </li>

    {{-- Other Information --}}
    <p style="font-weight:600; margin-top: 4vh; font-size: 1.4rem;" class="teal-text text-darken-4">Other Information
    </p>
    @if ( $product->other_details === "")
    <i class="grey-text">Not Indicated</i>
    @else
    <p>{!! $product->other_details !!}</p>
    @endif


  </div>

</div>

<script type="text/x-template" id="average-star-rating">
  <div class="ratings-container" style="padding:0; position:relative; display:inline-block">
    <div class="star-ratings-top" style="position:absolute; z-index:1; overflow:hidden; display:block; white-space:nowrap;" :style="{ width: ratingToPercentage + '%' }">
      <i class="material-icons yellow-text"> star </i>
      <i class="material-icons yellow-text"> star </i>
      <i class="material-icons yellow-text"> star </i>
      <i class="material-icons yellow-text"> star </i>
      <i class="material-icons yellow-text"> star </i>
    </div>
    <div class="star-ratings-bottom" style="padding:0; z-index:0; display:block;">
      <i class="material-icons yellow-text"> star_border </i>
      <i class="material-icons yellow-text"> star_border </i>
      <i class="material-icons yellow-text"> star_border </i>
      <i class="material-icons yellow-text"> star_border </i>
      <i class="material-icons yellow-text"> star_border </i>
    </div>
  </div>
  </script>
@endsection

@section('customScript')
<script src="{{ elixir('/js/breeder/viewProductDetail.js') }}"></script>
@endsection