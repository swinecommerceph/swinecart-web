{{--
    Displays Home page of Customer User
--}}

@extends('layouts.default')

@section('title')
    | Customer
@endsection

@section('pageId')
    id="page-customer-swine-cart"
@endsection

@section('breadcrumbTitle')
    Swine Cart
@endsection

@section('breadcrumb')
    <a href="{{ route('home_path') }}" class="breadcrumb">Home</a>
    <a href="#!" class="breadcrumb">Swine Cart</a>
@endsection

@section('navbarHead')
    <li><a href="{{ route('products.view') }}"> Products </a></li>
    <li><a href="{{ route('home_path') }}"> <i class="material-icons">message</i></a></li>
    @if(!Auth::user()->update_profile)
        <li><a id="cart-icon" class="dropdown-button" data-beloworigin="true" data-activates="cart-dropdown">
                <i class="material-icons">shopping_cart</i>
                <span></span>
            </a>
            <ul id="cart-dropdown" class="dropdown-content collection">
                <div id="preloader-circular" class="row">
                    <div class="center-align">
                        <div class="preloader-wrapper small active">
                            <div class="spinner-layer spinner-blue-only">
                                <div class="circle-clipper left">
                                    <div class="circle"></div>
                                </div>
                                <div class="gap-patch">
                                    <div class="circle"></div>
                                </div>
                                <div class="circle-clipper right">
                                    <div class="circle"></div>
                                </div>
                            </div>

                            {{-- <div class="spinner-layer spinner-red">
                                <div class="circle-clipper left">
                                    <div class="circle"></div>
                                </div><div class="gap-patch">
                                    <div class="circle"></div>
                                </div><div class="circle-clipper right">
                                    <div class="circle"></div>
                                </div>
                            </div>

                            <div class="spinner-layer spinner-yellow">
                                <div class="circle-clipper left">
                                    <div class="circle"></div>
                                </div><div class="gap-patch">
                                    <div class="circle"></div>
                                </div><div class="circle-clipper right">
                                    <div class="circle"></div>
                                </div>
                            </div>

                            <div class="spinner-layer spinner-green">
                                <div class="circle-clipper left">
                                    <div class="circle"></div>
                                </div><div class="gap-patch">
                                    <div class="circle"></div>
                                </div><div class="circle-clipper right">
                                    <div class="circle"></div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
                <li>
                    <ul id="item-container" class="collection">
                    </ul>
                </li>

                <li>
                    <a href="{{ route('view.cart') }}" class="left">Go to Cart</a>
                    <a href="{{ route('home_path') }}" class="right">Request items</a>
                </li>
            </ul>
        </li>
    @endif
@endsection

@section('navbarDropdown')
    <li><a href="{{ route('customer.edit') }}"> <i class="material-icons left">people</i> Update Profile</a></li>
    <li class="divider"></li>
    <li><a href="{{ route('home_path') }}"> <i class="material-icons left">shopping_cart</i> Swine Cart </a> </li>
@endsection

@section('static')
    <div class="fixed-action-btn" style="bottom: 30px; right: 24px;">
      <a id="back-to-top" class="btn-floating btn-large red tooltipped" style="display:none;" data-position="left" data-delay="50" data-tooltip="Back To Top">
        <i class="material-icons">keyboard_arrow_up</i>
      </a>
    </div>
@endsection

@section('content')
    {{-- Swine Cart --}}
    <div class="container">
      <ul class="collection">
      {{-- @if($products->length === 0)
        <h4>No product in your cart</h4>
      @else --}}
        @forelse($products as $product)
            <li class="collection-item">
              <div class="row">
                <div class="col s2">
                  Status
                </div>
                <div class="col s2">
                  Images
                </div>
                <div class="col s4">
                  Info
                </div>
                <div class="col s2">
                  Quantity
                </div>
                <div class="col s2">
                  Action
                </div>
              </div>
            </li>

            {{-- Original Content --}}
            <li class="collection-item avatar">
              <div class="row">
                <div class="col s8">
                  <a href="#"><img src="{{$product->img_path}}" class="circle"></a>
                  <a href="#" class="anchor-title teal-text"><span>{{$product->product_name}}</span></a>
                  <p>
                    {{ucfirst($product->product_type)}} -
                    {{-- @if(strrchr($product->product_breed, '+') !== FALSE)
                      {{$breed = explode('+',''$product->product_breed')}}
                    @else
                      {{ucfirst($product->product_breed)}}
                    @endif --}}
                  </p>
                  <p>
                    {{$product->breeder}}
                  </p>
                </div>
                <div class="col right">
                  <form method="POST" action="http://localhost:8000/customer/swine-cart/delete" accept-charset="UTF-8" data-item-id="{{$product->item_id}}">
                    {{-- <input name="_method" type="hidden" value="DELETE"> --}}
                    <input name="_token" type="hidden" value="{{$product->token}}">
                    <a href="#" class="delete-from-swinecart"><i class="material-icons teal-text">clear</i></a>
                  </form>
                </div>
                @if($product->request_status === '0')
                <div class="col right">
                  <a href="#"><i class="material-icons teal-text">play_for_work</i></a>
                </div>
                @endif
              </div>
            </li>
        @empty
          <div class="row">
            <div class="col s12 offset-s1">
              <h5>Your swine cart is empty.</h5>
            </div>
          </div>
        @endforelse
      {{-- @endif --}}
      </ul>
    </div>


@endsection

@section('initScript')
    <script src="/js/customer/swinecart.js"> </script>
    <script src="/js/customer/customer_custom.js"> </script>
@endsection
