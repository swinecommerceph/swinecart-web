{{--
    Add Product Page of Breeder
--}}

@extends('user.breeder.home')

@section('title')
  | Breeder - Edit Product
@endsection

@section('breadcrumbTitle')
  <div class="breadcrumb-container">    
    Edit Product
  </div>
@endsection

@section('breadcrumb')
  <div class="breadcrumb-container">    
      <a href="{{route('products',['type' => 'all-type', 'status' => 'all-status', 'sort' => 'none'])}}" class="breadcrumb">Products</a>
      <a href="#!" class="breadcrumb">Edit Product</a>
  </div>
@endsection

@section('breeder-content')
  <h1>Edit Product</h1>
@endsection