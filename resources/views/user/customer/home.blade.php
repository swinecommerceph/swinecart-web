{{--
    Displays Home page of Customer User
--}}

@extends('layouts.default')

@section('title')
    | Customer
@endsection

@section('page-id')
    id="page-customer-home"
@endsection

@section('breadcrumb-title')
    Home
@endsection

@section('navbar_head')
    <li><a href="{{ route('products.view') }}"> Products </a></li>
    <li><a href="{{ route('home_path') }}"> <i class="material-icons left">message</i></a></li>
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
                            </div><div class="gap-patch">
                                <div class="circle"></div>
                            </div><div class="circle-clipper right">
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
                <a href="{{ route('home_path') }}" class="left">Go to Cart</a>
                <a href="{{ route('home_path') }}" class="right">Request items</a>
            </li>
        </ul>
    </li>
@endsection

@section('navbar_dropdown')
    <li><a href="{{ route('customer.edit') }}"> <i class="material-icons left">people</i> Update Profile</a></li>
    <li class="divider"></li>
    <li><a href="{{ route('home_path') }}"> <i class="material-icons left">shopping_cart</i> Swine Cart </a> </li>

@endsection

@section('static')
    <div class="fixed-action-btn" style="bottom: 30px; right: 24px;">
      <a id="back-to-top" class="btn-floating btn-large red tooltipped" data-position="left" data-delay="50" data-tooltip="Back To Top">
        <i class="large material-icons">keyboard_arrow_up</i>
      </a>
    </div>
@endsection

@section('content')
    <div class="row">
    </div>

    {{-- Search bar --}}
    <nav id="search-container">
        <div id="search-field" class="nav-wrapper white">
            <form>
                <div class="input-field">
                    <input id="search" type="search" placeholder="Search for a product" required>
                    <label for="search"><i class="material-icons teal-text">search</i></label>
                    <i class="material-icons">close</i>
                </div>
            </form>
        </div>
    </nav>

    <div class="row">
    </div>

    <div class="slider">
        <ul class="slides">
          <li>
            <img src="/images/demo/HP1.jpg"> <!-- random image -->
            <div class="caption center-align">
              <h3>Efficiency</h3>
              <h5 class="light grey-text text-lighten-3">Through the internet, the
system aims for faster and
hassle-free transaction between
consumers and retailers.</h5>
            </div>
          </li>
          <li>
            <img src="/images/demo/HP2.jpg"> <!-- random image -->
            <div class="caption left-align">
              <h3>Security</h3>
              <h5 class="light grey-text text-lighten-3">security and legitimacy of
both customers and
breeders is ensured
through establishing a set
of criteria/qualifications.</h5>
            </div>
          </li>
          <li>
            <img src="/images/demo/HP3.jpg"> <!-- random image -->
            <div class="caption right-align">
              <h3>Variety</h3>
              <h5 class="light grey-text text-lighten-3">security and legitimacy of both customers and
breeders is ensured through establishing a set
of criteria/qualifications.</h5>
            </div>
          </li>
          <li>
            <img src="/images/demo/HP4.jpg"> <!-- random image -->
            <div class="caption center-align">
              <h3>Swine Security</h3>
              <h5 class="light grey-text text-lighten-3">security and legitimacy of both customers and
breeders is ensured through establishing a set
of criteria/qualifications.</h5>
            </div>
          </li>
        </ul>
    </div>

@endsection

@section('initScript')
    <script src="/js/customer/swinecart.js"> </script>
    <script src="/js/customer/customer_custom.js"> </script>
@endsection
