{{--
    Displays details of a chosen product
--}}

@extends('user.customer.home')

@section('title')
    | {{$product->name}}
@endsection

@section('pageId')
    id="page-customer-view-product-details"
@endsection

@section('breadcrumbTitle')
    Product: {{$product->name}}
@endsection

@section('breadcrumb')
    <a href="{{ route('home_path') }}" class="breadcrumb">Home</a>
    <a href="{{ route('products.view') }}" class="breadcrumb">Products</a>
    <a href="#!" class="breadcrumb">{{$product->name}}</a>
@endsection

@section('content')
    <div class="row container">
        <div class="col s12 m7">
            {{-- Primary Image --}}
            <div class="row">
                <div class="card">
                    <div class="card-image">
                        <img style="width: 39vw; height: 50vh;" src="{{$product->img_path}}" data-imagezoom="{{ $product->def_img_path }}">
                    </div>
                </div>
            </div>

            {{-- Images and Videos Headers--}}
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
        {{-- Product Details --}}
        <div class="col s12 m5">
            <ul class="collection with-header">
                <li class="collection-header">
                    <h4 class="row">
                        <div class="col" style="color: hsl(0, 0%, 13%); font-weight: 700">
                            {{ $product->name }}
                        </div>

                        {{-- Shopping Cart Icon--}}
                        <div class="col right">
                            @if ($product->quantity)
                                {!! Form::open(['route' => 'cart.add', 'data-product-id' => $product->id, 'data-type' => $product->type]) !!}
                                    <a href="#" class="right tooltipped add-to-cart"  data-position="left" data-delay="50" data-tooltip="Add to Swine Cart">
                                        <i class="material-icons blue-text" style="font-size:35px;">add_shopping_cart</i>
                                    </a>
                                {!! Form::close() !!}
                            @endif
                        </div>
                    </h4>

                    {{-- Breeder and Farm Province--}}
                    <div class="row">
                        <div class="col">
                            Breeder: 
                            <a class="blue-text" style="font-weight: 700;" href="{{ route('viewBProfile', ['breeder' => $product->breeder_id]) }}">
                                {{ $product->breeder }}
                            </a><br>
                            <span>Farm Province: {{ $product->farm_province }}</span>
                        </div>
                    </div>
                </li>

                {{-- Product Details --}}
                <div style="margin-left: 1vw;">
                  {{-- SwineCart Information --}}
                  <p style="font-weight:600; font-size: 1.4rem;" class="teal-text text-darken-4">Swine Information</p>

                  <ul style="margin-left: 1vw;">
                    <li style="color: hsl(0, 0%, 13%); list-style-type: disc;">Average Daily Gain:
                      @if($product->adg === 0)
                        <span style="color: hsl(0, 0%, 29%);">
                          <i class="text-grey">Not indicated</i>
                        </span>
                      @else
                        <span style="color: hsl(0, 0%, 13%);">
                          {{$product->adg}} g
                        </span>
                      @endif
                    </li>

                    <li style="color: hsl(0, 0%, 13%); list-style-type: disc;">Feed Conversion Ratio:
                      @if($product->fcr === 0.0)
                        <span style="color: hsl(0, 0%, 29%);">
                          <i class="text-grey">Not indicated</i>
                        </span>
                      @else
                        <span style="color: hsl(0, 0%, 13%);">
                          {{$product->fcr}}
                        </span>
                      @endif
                    </li>

                    <li style="color: hsl(0, 0%, 13%); list-style-type: disc;">Backfat Thickness:
                      @if($product->backfat_thickness === 0.0)
                        <span style="color: hsl(0, 0%, 29%);">
                          <i class="text-grey">Not indicated</i>
                        </span>
                      @else
                        <span style="color: hsl(0, 0%, 13%);">
                          {{$product->backfat_thickness}}
                        </span>
                      @endif
                    </li>

                    <li style="color: hsl(0, 0%, 13%); list-style-type: disc;">Litter size born alive:
                      @if($product->lsba === 0)
                        <span style="color: hsl(0, 0%, 29%);">
                          <i class="text-grey">Not indicated</i>
                        </span>
                      @else
                        <span style="color: hsl(0, 0%, 13%);">
                          {{$product->lsba}}
                        </span>
                      @endif
                    </li>

                    <li style="color: hsl(0, 0%, 13%); list-style-type: disc;">Birth weight:
                      @if($product->birthweight === 0.0)
                        <span style="color: hsl(0, 0%, 29%);">
                          <i class="text-grey">Not indicated</i>
                        </span>
                      @else
                        <span style="color: hsl(0, 0%, 13%);">
                          {{$product->birthweight}}
                        </span>
                      @endif
                    </li>

                    @if ( $product->type === "Gilt" || $product->type === "Sow")
                      <li style="color: hsl(0, 0%, 13%); list-style-type: disc;">Number of teats: 
                          @if ( $product->left_teats === 0 || $product->right_teats === 0)
                            <span style="color: hsl(0, 0%, 29%);">
                              <i class="text-grey">Not Indicated</i>
                            </span>
                          @else
                            <span style="color: hsl(0, 0%, 13%);">
                              {{$product->left_teats}} (left) | {{$product->right_teats}} (right)
                            </span>
                          @endif
                        </span>
                      </li>
                    @endif

                    <li style="color: hsl(0, 0%, 13%); list-style-type: disc;">House type: 
                      @if ( $product->house_type === "")
                        <span style="color: hsl(0, 0%, 29%);">
                          <i class="text-grey">Not Indicated</i>
                        </span>
                      @else
                        <span style="color: hsl(0, 0%, 13%);">
                          @if($product->house_type)
                            Tunnel ventilated
                          @else
                            Open sided
                          @endif
                        </span>
                      @endif
                    </li>
                  </ul>

                  {{-- Other Information --}}
                  <p style="font-weight:600; margin-top: 4vh; font-size: 1.4rem;" class="teal-text text-darken-4">Other Information</p>
                  <p>{!! $product->other_details !!}</p>
                </div>

            </ul>
        </div>
    </div>

    <div class="row container" style="margin-top: 0px !important;">
      <div class="col s7">
        <ul>
          <li id="stars-container" class="collection-item grey lighten-4" >
              <span style="color: hsl(0, 0%, 13%); font-weight: 600;">
                Breeder Ratings
              </span>
              {{-- Tool tip will not work because of the overriding of JQuery UI in the stars-container id --}}
              <a  href="/customer/messages/{{ $product->userid }}" 
                  class="right tooltipped"
                  data-position="left"
                  data-delay="50"
                  data-tooltip="Send Message to Breeder"
              >
                  <i class="material-icons blue-text" style="font-size:35px;">message</i>
              </a><br><br>
              <span class="row">
                  <i class="col s6" style="color: hsl(0, 0%, 45%);">Delivery</i>
                  <span class="col s6">
                      <average-star-rating :rating="{{ $breederRatings['deliveryRating'] }}"> </average-star-rating>
                  </span>
              </span>
              <span class="row">
                  <i class="col s6" style="color: hsl(0, 0%, 45%);">Transaction</i>
                  <span class="col s6">
                      <average-star-rating :rating="{{ $breederRatings['transactionRating'] }}"> </average-star-rating>
                  </span>
              </span>
              <span class="row">
                  <i class="col s6" style="color: hsl(0, 0%, 45%);">Product Quality</i>
                  <span class="col s6">
                      <average-star-rating :rating="{{ $breederRatings['productQualityRating'] }}"> </average-star-rating>
                  </span>
              </span>
          </li>
        </ul>
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
    <script src="{{ elixir('/js/customer/viewProductDetail.js') }}"></script>
@endsection
