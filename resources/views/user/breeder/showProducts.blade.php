{{--
    Displays products of the respective Breeder user
--}}

@extends('user.breeder.home')

@section('title')
    | Breeder - Products
@endsection

@section('page-id')
    id="page-breeder-manage-products"
@endsection

@section('breadcrumb-title')
    Products
@endsection

@section('breadcrumb')
    <a href="{{ route('home_path') }}" class="breadcrumb">Home</a>
    <a href="#!" class="breadcrumb">Products</a>
@endsection

@section('content')
    <div class="row">
        <div class="col s12 m10 offset-m1">
            <p class="caption">Manage your products. <br>
                {{-- <blockquote>* - required </blockquote> --}}
            </p>

            @include('common._errors')
            {!! Form::open(['route' => 'products.store', 'class' => 's12', 'id' => 'create-products']) !!}
                @include('user.breeder._productsForm')
            {!! Form::close() !!}
        </div>
    </div>
@endsection

@section('customScript')
    {{-- <script src="/js/breeder/createProfile_script.js"> </script> --}}
@endsection
