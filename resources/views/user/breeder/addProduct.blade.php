{{--
    Add Product Page of Breeder
--}}

@extends('user.breeder.home')

@section('title')
  | Breeder - Add Products
@endsection

@section('breadcrumbTitle')
  <div class="breadcrumb-container">    
    Add Product
  </div>
@endsection

@section('breadcrumb')
  <div class="breadcrumb-container">    
      <a href="{{ route('home_path') }}" class="breadcrumb">Home</a>
      <a href="#!" class="breadcrumb">Products</a>
  </div>
@endsection

@section('breeder-content')
  <div class="row">
    <div class="col s12">
      <h4>Add Product Form</h4>
    </div>
  </div>
@endsection

@section('customScript')
    <script src="{{ elixir('/js/breeder/showProducts.js') }}"></script>
@endsection