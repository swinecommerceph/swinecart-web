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
    {{-- Rating Modal --}}

    <div id="rate" class="modal">
      <div class="modal-content">
        <h4>Rating</h4>
        <div class="divider"></div>
        <div>
          <br>
          <span class="row">
              <span class="col s6">Delivery</span>
              <span id="delivery" class="col s6 right-align">
                  <a href="#" class="delivery" data-value=1><i class="material-icons grey-text text-darken-2">star_border</i></a>
                  <a href="#" class="delivery" data-value=2><i class="material-icons grey-text text-darken-2">star_border</i></a>
                  <a href="#" class="delivery" data-value=3><i class="material-icons grey-text text-darken-2">star_border</i></a>
                  <a href="#" class="delivery" data-value=4><i class="material-icons grey-text text-darken-2">star_border</i></a>
                  <a href="#" class="delivery" data-value=5><i class="material-icons grey-text text-darken-2">star_border</i></a>
              </span>
          </span>
          <span class="row">
              <span class="col s6">Transaction</span>
              <span id="transaction" class="col s6 right-align">
                <a href="#" class="transaction" data-value=1><i class="material-icons grey-text text-darken-2">star_border</i></a>
                <a href="#" class="transaction" data-value=2><i class="material-icons grey-text text-darken-2">star_border</i></a>
                <a href="#" class="transaction" data-value=3><i class="material-icons grey-text text-darken-2">star_border</i></a>
                <a href="#" class="transaction" data-value=4><i class="material-icons grey-text text-darken-2">star_border</i></a>
                <a href="#" class="transaction" data-value=5><i class="material-icons grey-text text-darken-2">star_border</i></a>
              </span>
          </span>
          <span class="row">
              <span class="col s6">Product Quality</span>
              <span id="productQuality" class="col s6 right-align">
                <a href="#" class="productQuality" data-value=1><i class="material-icons grey-text text-darken-2">star_border</i></a>
                <a href="#" class="productQuality" data-value=2><i class="material-icons grey-text text-darken-2">star_border</i></a>
                <a href="#" class="productQuality" data-value=3><i class="material-icons grey-text text-darken-2">star_border</i></a>
                <a href="#" class="productQuality" data-value=4><i class="material-icons grey-text text-darken-2">star_border</i></a>
                <a href="#" class="productQuality" data-value=5><i class="material-icons grey-text text-darken-2">star_border</i></a>
              </span>
          </span>
            <div class="input-field col s12 center-align">
            <textarea id="comment" class="materialize-textarea"></textarea>
            <label for="comment">Comment</label>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <form class="" action="{{route('rate.breeder')}}" method="post" data-breeder-id= data-customer-id= data-delivery= data-transaction= data-productQuality=>
          <input type="hidden" name="_token" value="">
          <a href="#!" id="submit-rate" class="modal-action modal-close waves-effect waves-green btn-flat">Submit</a>
        </form>
      </div>
    </div>

    {{-- Swine Cart --}}
    <div class="row">
      <span class="col s2 left-align">
        STATUS
      </span>
      <div class="col s6">
        ITEM(S)
      </div>
      <div class="col s2">
        QUANTITY
      </div>
      <div class="col s2">
        ACTION
      </div>
    </div>
      <ul class="collection cart">
        @forelse($products as $product)
            {{-- Original Content --}}
            <li class="collection-item swineyswine">
              <div class="row swine-cart-item valign-wrapper">
                {{-- Product Status Icons --}}
                @if($product->status === 'requested')
                  <div class="col s2 verticalLine valign-wrapper">
                      <div class="">
                        <span class="col s6 center-align">
                          <i class="material-icons status-icons yellow-text text-darken-2 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Delete product">queue</i>
                        </span>
                        <span class="col s6 center-align">
                          <i class="material-icons">save</i>
                        </span>
                        <span class="col s6 center-align">
                          <i class="material-icons">motorcycle</i>
                        </span>
                        <span class="col s6 center-align">
                          <i class="material-icons">payment</i>
                        </span>
                      </div>
                  </div>
                  <div class="info col s2 center-align">
                    <a href="#"><img src="{{$product->img_path}}" width="75" height="75" class="circle"></a>
                  </div>
                @elseif($product->status === 'reserved')
                  <div class="col s2 verticalLine valign-wrapper">
                      <div class="">
                        <span class="col s6 center-align">
                          <i class="material-icons  yellow-text text-darken-2">queue</i>
                        </span>
                        <span class="col s6 center-align">
                          <i class="material-icons  orange-text text-darken-2">save</i>
                        </span>
                        <span class="col s6 center-align">
                          <i class="material-icons">motorcycle</i>
                        </span>
                        <span class="col s6 center-align">
                          <i class="material-icons">payment</i>
                        </span>
                      </div>
                  </div>
                  <div class="info col s2 center-align">
                    <a href="#"><img src="{{$product->img_path}}" width="75" height="75" class="circle"></a>
                  </div>
                @elseif($product->status === 'paid')
                  <div class="col s2 verticalLine valign-wrapper">
                      <div class="">
                        <span class="col s6 center-align">
                          <i class="material-icons yellow-text text-darken-2">queue</i>
                        </span>
                        <span class="col s6 center-align">
                          <i class="material-icons  orange-text text-darken-2">save</i>
                        </span>
                        <span class="col s6 center-align">
                          <i class="material-icons">motorcycle</i>
                        </span>
                        <span class="col s6 center-align">
                          <i class="material-icons  green-text text-darken-2">payment</i>
                        </span>
                      </div>
                  </div>
                  <div class="info col s2 center-align">
                    <a href="#"><img src="{{$product->img_path}}" width="75" height="75" class="circle"></a>
                  </div>
                @elseif($product->status === 'on_delivery')
                  <div class="col s2 verticalLine valign-wrapper">
                      <div class="">
                        <span class="col s6 center-align">
                          <i class="material-icons yellow-text text-darken-2">queue</i>
                        </span>
                        <span class="col s6 center-align">
                          <i class="material-icons orange-text text-darken-2">save</i>
                        </span>
                        <span class="col s6 center-align">
                          <i class="material-icons green-text text-darken-2">motorcycle</i>
                        </span>
                        <span class="col s6 center-align">
                          <i class="material-icons">payment</i>
                        </span>
                      </div>
                  </div>
                  <div class="info col s2 center-align">
                    <a href="#"><img src="{{$product->img_path}}" width="75" height="75" class="circle"></a>
                  </div>
                @elseif($product->status === 'sold')
                  <div class="col s2 verticalLine valign-wrapper">
                      <div class="">
                        <span class="col s6 center-align">
                          <a href="#">
                            <i class="material-icons yellow-text text-darken-2 tooltipped" data-position="top" data-delay="50" data-tooltip="Requested">queue</i>
                          </a>
                        </span>
                        <span class="col s6 center-align">
                          <i class="material-icons orange-text text-darken-2">save</i>
                        </span>
                        <span class="col s6 center-align">
                          <i class="material-icons red-text text-darken-2">motorcycle</i>
                        </span>
                        <span class="col s6 center-align">
                          <i class="material-icons green-text text-darken-2">payment</i>
                        </span>
                      </div>
                  </div>
                  <div class="info col s2 center-align">
                    <a href="#"><img src="{{$product->img_path}}" width="75" height="75" class="circle"></a>
                  </div>
                @else
                  <div class="col s2 verticalLine valign-wrapper">
                      <div>
                        <span class="col s6 center-align">
                          <i class="material-icons status-icons">queue</i>
                        </span>
                        <span class="col s6 center-align">
                          <i class="material-icons">save</i>
                        </span>
                        <span class="col s6 center-align">
                          <i class="material-icons">motorcycle</i>
                        </span>
                        <span class="col s6 center-align">
                          <i class="material-icons">payment</i>
                        </span>
                      </div>
                  </div>
                  <div class="info col s2 center-align">
                    <a href="#"><img src="{{$product->img_path}}" width="75" height="75" class="circle"></a>
                  </div>
                @endif
                {{-- Product Info --}}
                <div class="col s4 verticalLine">
                  <a href="#" class="anchor-title teal-text"><span class="col s12">{{$product->product_name}}</span></a>
                  <span class="col s12">
                    {{ucfirst($product->product_type)}} - {{ucfirst($product->product_breed)}}
                  </span>
                  <span class="col s12">
                    {{$product->breeder}}
                  </span>
                </div>
                {{-- Quantity Check --}}
                @if($product->product_type === 'semen')
                  <div class="col s2 left-align verticalLine valign-wrapper">
                      <div class="center-align">{{$product->product_quantity}}</div>
                  </div>
                  <div class="col s2">
                    @if($product->status === 'showcased')
                      <form method="POST" action="{{route('cart.delete')}}" accept-charset="UTF-8" data-item-id="{{$product->item_id}}">
                        <input name="_method" type="hidden" value="DELETE">
                        <input name="_token" type="hidden" value="{{$product->token}}">
                        <a href="#" class="delete-from-swinecart btn">Remove</a>
                      </form>
                      <form method="PUT" class="request-icon" action="{{route('cart.request')}}" accept-charset="UTF-8" data-item-id="{{$product->item_id}}" data-product-id="{{$product->product_id}}">
                        <input name="_token" type="hidden" value="{{$product->token}}">
                        <a href="#" class="request-product tooltipped" data-position="bottom" data-delay="50" data-tooltip="Request product"><i class="material-icons teal-text">play_for_work</i></a>
                      </form>
                    @elseif($product->status === 'paid' or $product->status === 'on_delivery')

                    @elseif($product->status === 'sold')
                      <span class="col s12">
                        <a class="rate-button btn" data-customer-id="{{$product->customer_id}}" data-breeder-id="{{$product->breeder_id}}" data-token="{{$product->token}}" class="modal-trigger">
                          Rate
                        </a>
                      </span>
                    @endif
                  </div>
                @else
                  <div class="col s2 offset-s2">
                    <form method="POST" action="{{route('cart.delete')}}" accept-charset="UTF-8" data-item-id="{{$product->item_id}}">
                      <input name="_method" type="hidden" value="DELETE">
                      <input name="_token" type="hidden" value="{{$product->token}}">
                      <a href="#" class="delete-from-swinecart btn">Remove</a>
                    </form>

                    @if($product->status === 'showcased')
                      <form method="PUT" class="request-icon" action="{{route('cart.request')}}" accept-charset="UTF-8" data-item-id="{{$product->item_id}}" data-product-id="{{$product->product_id}}">
                        <input name="_token" type="hidden" value="{{$product->token}}">
                        <a href="#" class="request-product tooltipped" data-position="bottom" data-delay="50" data-tooltip="Request product"><i class="material-icons teal-text">play_for_work</i></a>
                      </form>
                    @endif
                  </div>
                @endif

              </div>
        @empty
          <div class="row">
            <div class="col s12 center-align">
              <h5>Your swine cart is empty.</h5>
            </div>
          </div>
        @endforelse
      {{-- @endif --}}
      </ul>


@endsection

@section('initScript')
    <script src="/js/customer/swinecart.js"> </script>
    <script src="/js/customer/customer_custom.js"> </script>
    <script src="/js/customer/swinecart_script.js"> </script>
@endsection
