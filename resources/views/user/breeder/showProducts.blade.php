{{--
    Displays products of the respective Breeder user
--}}

@extends('user.breeder.home')

@section('title')
    | Breeder - Products
@endsection

@section('pageId')
    id="page-breeder-manage-products"
@endsection

@section('breadcrumbTitle')
    Products
@endsection

@section('breadcrumb')
    <a href="{{ route('home_path') }}" class="breadcrumb">Home</a>
    <a href="#!" class="breadcrumb">Products</a>
@endsection

@section('content')
    <div class="row">
        <div class="col s12">
            <p class="caption">
                Manage your products. <br>
            </p>
            @include('user.breeder._displayProducts')
        </div>
        <div class="col s12 m12 l8 offset-l2">
            @include('common._errors')
            {!! Form::open(['route' => 'products.store', 'class' => 's12', 'id' => 'create-product']) !!}
                @include('user.breeder._productForm')
            {!! Form::close() !!}
        </div>
    </div>
@endsection

@section('customScript')
    <script src="/js/breeder/product.js"> </script>
    <script src="/js/breeder/manageProducts_script.js"> </script>
@endsection
