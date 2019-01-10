{{--
    Displays products of all Breeder users
--}}

@extends('layouts.default')

@section('title')
    | Products
@endsection

@section('breadcrumbTitle')
    Products
@endsection

@section('breadcrumb')
    <a href="{{ route('home_path') }}" class="breadcrumb">Home</a>
    <a href="#!" class="breadcrumb">Products</a>
@endsection

@section('content')
  <h1>All Products</h1>
@endsection

@section('customScript')
    <script src="{{ elixir('/js/customer/viewProducts.js') }}"></script>
@endsection
