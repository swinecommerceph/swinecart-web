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

    {{-- Info Modal --}}
    <div id="info-modal" class="modal">
      <div class="modal-content">
        <div class="row">
          <div class="image col s12 m7">
            <div class="row">
              <div class="col s12">
                <div class="row">
                    <div class="card">
                        <div class="card-image">
                            <img id="modal-img" src=>
                        </div>
                    </div>
                </div>
              </div>
              <div class="other-details col s12">
                  <div class="card">
                      <div class="card-content">
                          <span class="card-title">Other Details</span>
                          <p></p>
                      </div>
                  </div>
              </div>
            </div>
          </div>
          {{-- Info in modal --}}
          <div class="cart-details col s12 m5 ">
            <ul class="collection with-header">
                <li class="collection-header">
                    <h4 class="row">
                        <div class="col product-name">
                            {{-- productName --}}
                        </div>
                        <div class="col right">
                          <a href="#"><i class="material-icons">shopping_basket</i></a>
                        </div>
                    </h4>
                    <div class="row">
                        <div class="col breeder product-farm">
                          {{-- Breeder and farm_province --}}
                        </div>
                    </div>
                </li>
                <li class="collection-item product-type">{{--{{$product->type}} - {{$product->breed}} --}}
                  <span></span>
                </li>
                <li class="collection-item product-age">{{--{{$product->age}} days old --}}
                  <span></span> days old
                </li>
                <li class="collection-item product-adg">{{--Average Daily Gain: {{$product->adg}} g--}}
                  Average Daily Gain: <span></span>
                </li>
                <li class="collection-item product-fcr">{{--Feed Conversion Ratio: {{$product->fcr}}--}}
                  Feed Conversion Ratio: <span></span>
                </li>
                <li class="collection-item product-backfat_thickness">{{--Backfat Thickness: {{$product->backfat_thickness}} --}}
                  Backfat Thickness: <span></span>
                </li>
                <li>
                      <i>Delivery</i>
                      <span class="left-align">
                          <i class="material-icons yellow-text">star</i>
                          <i class="material-icons yellow-text">star</i>
                          <i class="material-icons yellow-text">star</i>
                          <i class="material-icons yellow-text">star_half</i>
                          <i class="material-icons yellow-text">star_border</i>
                      </span>
                      <i>Transaction</i>
                      <span>
                          <i class="material-icons yellow-text">star</i>
                          <i class="material-icons yellow-text">star</i>
                          <i class="material-icons yellow-text">star</i>
                          <i class="material-icons yellow-text">star</i>
                          <i class="material-icons yellow-text">star_border</i>
                      </span>
                      <i>Product Quality</i>
                      <span>
                          <i class="material-icons yellow-text">star</i>
                          <i class="material-icons yellow-text">star</i>
                          <i class="material-icons yellow-text">star</i>
                          <i class="material-icons yellow-text">star</i>
                          <i class="material-icons yellow-text">star_half</i>
                      </span>
                </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

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
        <form class="" action="{{route('rate.breeder')}}" method="post" data-status= data-product-id= data-breeder-id= data-customer-id= data-delivery= data-transaction= data-productQuality=>
          <input type="hidden" name="_token" value="">
          <a href="#!" id="submit-rate" class="modal-action modal-close waves-effect waves-green btn-flat">Submit</a>
        </form>
      </div>
    </div>

    <ul class="tabs">
      <li class="tab col s6"><a href="#swine-cart">Orders</a></li>
      <li class="tab col s6 teal-text"><a href="#transaction-history">Transaction History</a></li>
    </ul>

    {{-- Transaction History --}}
    <div id="transaction-history">
      <div class="row">
        <div class="col s4 offset-s2">
          ITEM
        </div>
        <div class="col s4">
          BREEDER
        </div>
        <div class="col s2">
          TIME
        </div>
      </div>
      <ul id="cart" class="collection cart">
        @forelse($history as $log)
          <li class="collection-item swineyswine">
            <div class="row">
              <div class="col s2 center-align">
                <a href="#"><img src="{{$log->img_path}}" width="75" height="75" class="circle"></a>
              </div>
              <div class="col s4 verticalLine">
                <h5 class="col s12">{{$log->product_name}}</h5>
                <span class="col s12">
                  {{ucfirst($log->product_type)}}
                </span>
                @if($log->product_type === 'semen')
                  <span class="col s12">
                    {{$log->product_quantity}}
                  </span>
                @endif
              </div>
              <div class="col s4 verticalLine">
                {{$log->breeder}}
              </div>
              <div class="col s2">
                {{$log->timestamp}}
              </div>
            </div>
          </li>
        @empty
          <div class="row">
            <div class="col s12 center-align">
              <h5>Your history is empty.</h5>
            </div>
          </div>
        @endforelse
      </ul>
    </div>



    {{-- Swine Cart --}}
    <div id="swine-cart">
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
        <ul id="cart" class="collection cart">
          @forelse($products as $product)
              {{-- Original Content --}}
              <li class="collection-item swineyswine">
                <div class="row swine-cart-item valign-wrapper">
                  {{-- Product Status Icons --}}
                  @if($product->status === 'requested')
                    <div class="status col s2 verticalLine valign-wrapper">
                      <div class="">
                        <span class="col s6 right-align">
                          <a href="#">
                            <i class="request material-icons teal-text tooltipped" data-position="top" data-delay="50" data-tooltip="Requested">queue</i>
                          </a>
                        </span>
                        <span class="col s6 left-align">
                          <a href="#">
                            <i class="reserved material-icons tooltipped grey-text text-darken-4" data-position="top" data-delay="50" data-tooltip="Not Reserved">save</i>
                          </a>
                        </span>
                        <span class="col s6 right-align">
                          <a href="#">
                            <i class="on-delivery material-icons tooltipped grey-text text-darken-4" data-position="bottom" data-delay="50" data-tooltip="Not on Delivery">local_shipping</i>
                          </a>
                        </span>
                        <span class="col s6 left-align">
                          <a href="#">
                            <i class="paid material-icons tooltipped grey-text text-darken-4" data-position="bottom" data-delay="50" data-tooltip="Not Paid">payment</i>
                          </a>
                        </span>
                      </div>
                    </div>
                  @elseif($product->status === 'reserved')
                    <div class="status col s2 verticalLine valign-wrapper">
                      <div class="">
                        <span class="col s6 right-align">
                          <a href="#">
                            <i class="request material-icons teal-text tooltipped" data-position="top" data-delay="50" data-tooltip="Requested">queue</i>
                          </a>
                        </span>
                        <span class="col s6 left-align">
                          <a href="#">
                            <i class="reserved material-icons teal-text tooltipped" data-position="top" data-delay="50" data-tooltip="Reserved">save</i>
                          </a>
                        </span>
                        <span class="col s6 right-align">
                          <a href="#">
                            <i class="on-delivery material-icons grey-text text-darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Not on Delivery">local_shipping</i>
                          </a>
                        </span>
                        <span class="col s6 left-align">
                          <a href="#">
                            <i class="paid material-icons grey-text text-darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Not Paid">payment</i>
                          </a>
                        </span>
                      </div>
                    </div>
                  @elseif($product->status === 'paid')
                    <div class="status col s2 verticalLine valign-wrapper">
                      <div class="">
                        <span class="col s6 right-align">
                          <a href="#">
                            <i class="request material-icons teal-text tooltipped" data-position="top" data-delay="50" data-tooltip="Requested">queue</i>
                          </a>
                        </span>
                        <span class="col s6 left-align">
                          <a href="#">
                            <i class="reserved material-icons teal-text tooltipped" data-position="top" data-delay="50" data-tooltip="Reserved">save</i>
                          </a>
                        </span>
                        <span class="col s6 right-align">
                          <a href="#">
                            <i class="on-delivery material-icons teal-text tooltipped" data-position="bottom" data-delay="50" data-tooltip="Not on Delivery">local_shipping</i>
                          </a>
                        </span>
                        <span class="col s6 left-align">
                          <a href="#">
                            <i class="paid material-icons teal-text tooltipped" data-position="bottom" data-delay="50" data-tooltip="Paid">payment</i>
                          </a>
                        </span>
                      </div>
                    </div>
                  @elseif($product->status === 'on_delivery')
                    <div class="status col s2 verticalLine valign-wrapper">
                      <div class="">
                        <span class="col s6 right-align">
                          <a href="#">
                            <i class="request material-icons teal-text tooltipped" data-position="top" data-delay="50" data-tooltip="Requested">queue</i>
                          </a>
                        </span>
                        <span class="col s6 left-align">
                          <a>
                            <i class="reserved material-icons teal-text tooltipped" data-position="top" data-delay="50" data-tooltip="Reserved">save</i>
                          </a>
                        </span>
                        <span class="col s6 right-align">
                          <a href="#">
                            <i class="on-delivery material-icons teal-text tooltipped" data-position="bottom" data-delay="50" data-tooltip="On Delivery">local_shipping</i>
                          </a>
                        </span>
                        <span class="col s6 left-align">
                          <a href="#">
                            <i class="paid material-icons grey-text text-darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Not Paid">payment</i>
                          </a>
                        </span>
                      </div>
                    </div>
                  @elseif($product->status === 'sold')
                    <div class="status col s2 verticalLine valign-wrapper">
                      <div class="col s12 center-align">
                        <a href="#">
                          <i class="material-icons md teal-text tooltipped" data-position="top" data-delay="50" data-tooltip="Sold">attach_money</i>
                        </a>
                      </div>
                    </div>
                  @else
                    <div class="status col s2 verticalLine valign-wrapper">
                      <div class="">
                        <span class="col s6 right-align">
                          <a href="#">
                            <i class="request material-icons grey-text text-darken-4 tooltipped" data-position="top" data-delay="50" data-tooltip="Not Requested">queue</i>
                          </a>
                        </span>
                        <span class="col s6 left-align">
                          <a href="#">
                            <i class="reserved material-icons grey-text text-darken-4 tooltipped" data-position="top" data-delay="50" data-tooltip=" Not Reserved">save</i>
                          </a>
                        </span>
                        <span class="col s6 right-align">
                          <a href="#">
                            <i class="on-delivery material-icons grey-text text-darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip=" Not On Delivery">local_shipping</i>
                          </a>
                        </span>
                        <span class="col s6 left-align">
                          <a href="#">
                            <i class="paid material-icons grey-text text-darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Not Paid">payment</i>
                          </a>
                        </span>
                      </div>
                    </div>
                  @endif
                  <div class="info col s2 center-align">
                    <a href="#"><img src="{{$product->img_path}}" width="75" height="75" class="circle"></a>
                  </div>
                  {{-- Product Info --}}
                  <div class="info col s4 verticalLine">
                    <a href="#" class="anchor-title teal-text" data-breeder="{{$product->breeder}}" data-age="{{$product->product_age}}" data-imgpath="{{$product->img_path}}" data-other-details="{{$product->other_details}}" data-type="{{ucfirst($product->product_type)}}" data-adg="{{$product->product_adg}}" data-fcr="{{$product->product_fcr}}" data-backfat-thickness="{{$product->product_backfat_thickness}}"><span class="col s12">{{$product->product_name}}</span></a>
                    <span class="col s12">
                      {{ucfirst($product->product_type)}} - {{ucfirst($product->product_breed)}}
                    </span>
                    <span class="col s12">
                      {{$product->breeder}}
                    </span>
                  </div>
                  {{-- Quantity Check --}}
                  @if($product->product_type === 'semen')
                    <div class="quantity col s2 verticalLine valign-wrapper">
                      <div class="col s12 center-align">
                        {{$product->product_quantity}}
                      </div>
                    </div>
                  @else
                    <div class="quantity col s2 verticalLine">
                    </div>
                  @endif
                  {{-- Actions --}}
                  <div class="action col s2">
                      @if($product->status === 'showcased')
                        <div class="col s12 center-align">
                          <form method="POST" action="{{route('cart.delete')}}" accept-charset="UTF-8" data-item-id="{{$product->item_id}}">
                            <input name="_method" type="hidden" value="DELETE">
                            <input name="_token" type="hidden" value="{{$product->token}}">
                            <a href="#" class="delete-from-swinecart btn">Remove</a>
                          </form>
                        </div>
                        <br>
                        <div class="col s12 center-align">
                          <form method="PUT" class="request-icon" action="{{route('cart.request')}}" accept-charset="UTF-8" data-item-id="{{$product->item_id}}" data-product-id="{{$product->product_id}}">
                            <input name="_token" type="hidden" value="{{$product->token}}">
                            <a href="#" class="request-product btn">Request</a>
                          </form>
                        </div>
                      @elseif($product->status === 'requested')
                        <div class="col s2 left-align">
                          <span class="col s12">
                            <a class="receive-button btn-flat" data-customer-id="{{$product->customer_id}}" data-breeder-id="{{$product->breeder_id}}" data-token="{{$product->token}}" class="modal-trigger">
                              (Approve)
                            </a>
                          </span>
                        </div>
                      @elseif($product->status === 'reserved')
                        <div class="col s2 left-align">
                          <span class="col s12">
                            <a class="receive-button btn-flat" data-customer-id="{{$product->customer_id}}" data-breeder-id="{{$product->breeder_id}}" data-token="{{$product->token}}" class="modal-trigger">
                              (Process)
                            </a>
                          </span>
                        </div>
                      @elseif($product->status === 'paid' or $product->status === 'on_delivery')
                        <div class="col s2">
                          <span class="col s12">
                            <a class="receive-button btn-flat" data-customer-id="{{$product->customer_id}}" data-breeder-id="{{$product->breeder_id}}" data-token="{{$product->token}}" class="modal-trigger">
                              (Receive)
                            </a>
                          </span>
                        </div>
                      @elseif($product->status === 'sold')
                        <span class="col s12 center-align">
                          <a class="rate-button btn-large" data-status="{{$product->status}}" data-product-id="{{$product->product_id}}" data-customer-id="{{$product->customer_id}}" data-breeder-id="{{$product->breeder_id}}" data-token="{{$product->token}}" class="modal-trigger">
                            Rate
                          </a>
                        </span>
                      @endif
                  </div>
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
    </div>


@endsection

@section('initScript')
    <script src="/js/customer/swinecart.js"> </script>
    <script src="/js/customer/customer_custom.js"> </script>
    <script src="/js/customer/swinecart_script.js"> </script>
@endsection
