@extends('layouts.default')

@section('title')
    | Products
@endsection

@if(!Auth::guest())
  @section('breadcrumbTitle')
    Browse Products
  @endsection
@else
  @section('publicProductsBreadcrumbTitle')
    Browse Products
  @endsection
@endif

@if(!Auth::guest())
  @section('breadcrumb')
    <a href="{{ route('home_path') }}" class="breadcrumb">Home</a>
    <a href="#!" class="breadcrumb">Products</a>
  @endsection
@endif

@section('content')
  <div class="container">
    <h5>Products</h5>
  </div>
@endsection

@section('customScript')
    <script src="{{ elixir('/js/customer/viewProducts.js') }}"></script>
@endsection
