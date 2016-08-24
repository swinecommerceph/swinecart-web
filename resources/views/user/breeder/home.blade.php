{{--
    Displays Home page of Breeder User
--}}

@extends('layouts.default')

@section('title')
    | Breeder
@endsection

@section('pageId')
    id="page-breeder-home"
@endsection

@section('breadcrumbTitle')
    Home
@endsection

@section('navbarHead')
    <li><a href="{{ route('home_path') }}"> <i class="material-icons">message</i></a></li>
    <li><a href="{{ route('dashboard') }}"> <i class="material-icons">assessment</i></a></li>
@endsection

@section('navbarDropdown')
    <li><a href="{{ route('breeder.edit') }}"> <i class="material-icons left">mode_edit</i> Update Profile </a></li>
    <li><a href="{{ route('products') }}"> <i class="material-icons left">shop</i> Products </a></li>
@endsection

@section('static')
    <div class="fixed-action-btn click-to-toggle" style="bottom: 30px; right: 24px;">
      <a id="action-button" class="btn-floating btn-large waves-effect waves-light red" style="display:none;" data-position="left" data-delay="50" data-tooltip="More Actions">
        <i class="material-icons">more_vert</i>
        <ul>
            <li><a class="btn-floating waves-effect waves-light grey tooltipped delete-selected-button" data-position="left" data-delay="50" data-tooltip="Delete all chosen"><i class="material-icons">delete</i></a></li>
            @if(!empty($filters['unshowcased']))
                {{-- Only show when products are unshowcased --}}
                <li><a class="btn-floating waves-effect waves-light teal ligthen-2 tooltipped showcase-selected-button" data-position="left" data-delay="50" data-tooltip="Showcase all chosen"><i class="material-icons">unarchive</i></a></li>
            @elseif(!empty($filters['showcased']))
                {{-- Only show when products are showcased --}}
                <li><a class="btn-floating waves-effect waves-light teal ligthen-2 tooltipped unshowcase-selected-button" data-position="left" data-delay="50" data-tooltip="Unshowcase all chosen"><i class="material-icons">archive</i></a></li>
            @endif
            <li><a href="#" class="btn-floating modal-trigger waves-effect waves-light teal tooltipped select-all-button" data-position="left" data-delay="50" data-tooltip="Select All Products"><i class="material-icons">event_available</i></a></li>
            <li><a href="#" class="btn-floating modal-trigger waves-effect waves-light teal darken-2 tooltipped add-product-button" data-position="left" data-delay="50" data-tooltip="Add product"><i class="material-icons">add</i></a></li>
        </ul>
      </a>
    </div>
@endsection

@section('content')
    <div class="row">
    </div>
    <div class="row">
    </div>
    <div class="row">
    </div>
    {{-- Slider --}}
    <div class="slider home-slider">
        <ul class="slides">
          <li>
            <img src="/images/demo/HP1.jpg">
            <div class="caption center-align">
              <h3>Efficiency</h3>
              <h5 class="light grey-text text-lighten-3">Through the internet, the
system aims for faster and
hassle-free transaction between
consumers and retailers.</h5>
            </div>
          </li>
          <li>
            <img src="/images/demo/HP2.jpg">
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
            <img src="/images/demo/HP3.jpg">
            <div class="caption right-align">
              <h3>Variety</h3>
              <h5 class="light grey-text text-lighten-3">security and legitimacy of both customers and
breeders is ensured through establishing a set
of criteria/qualifications.</h5>
            </div>
          </li>
          <li>
            <img src="/images/demo/HP4.jpg">
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
    <script src="/js/breeder/breeder_custom.js"> </script>
@endsection
