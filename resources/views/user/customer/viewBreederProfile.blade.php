{{--
  Display Breeder's profile
--}}

@extends( (Auth::guest()) ? 'layouts.default' : 'user.customer.home' )

@section('title')
  | {{ $breeder->name }}
@endsection

@section('pageId')
  id="page-view-breeder-profile"
@endsection

@section('breadcrumbTitle')
  {{ $breeder->name }}'s
@endsection

@if(!Auth::guest())
  @section('breadcrumb')
    <a href="{{ route('home_path') }}" class="breadcrumb">Home</a>
    <a href="{{ route('products.view') }}" class="breadcrumb">Products</a>
    <a href="#!" class="breadcrumb">{{ $breeder->name }}</a>
  @endsection
@else
  @section('publicViewBreederProfile')
    Breeder Profile: <b>{{ $breeder->name }}</b>
  @endsection
@endif 
  

@section('content')
  <div class="row container">

    <!-- Tabs -->
    <div class="col s12">
      <ul class="tabs z-depth-1">
        <li id="breeder-products-tab" class="tab col s6">
          <a href="#breeder-products">{{ $breeder->name }}'s Products</a>
        </li>
        <li id="breeder-profile-tab" class="tab col s6">
          <a href="#breeder-profile">{{ $breeder->name }}'s Profile</a>
        </li>
      </ul>
    </div>

    <!-- Breeder Products Tab-->
    <div class="col s12">
      <div id="breeder-products" class="card-panel">
        <div class="row">
          @include('user.customer._viewBreederProductsOnly')
        </div>
      </div>
    </div>

    <!-- Breeder Profile Tab -->
    <div class="col s12">
      <div id="breeder-profile" class="card-panel">
        <div class="row">
          @include('user.customer._viewBreederProfileOnly')
        </div>
      </div>
    </div>

  </div>

@endsection

@section('customScript')
  <script src="{{ elixir('/js/customer/viewBreederProfile.js') }}"></script>
@endsection
