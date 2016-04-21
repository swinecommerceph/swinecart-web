{{--
    Displays details of a chosen product
--}}

@extends('user.customer.home')

@section('title')
    | {{$product->name}}
@endsection

@section('pageId')
    id="page-customer-view-product-details"
@endsection

@section('breadcrumbTitle')
    {{$product->name}}
@endsection

@section('breadcrumb')
    <a href="{{ route('home_path') }}" class="breadcrumb">Home</a>
    <a href="{{ route('products.view') }}" class="breadcrumb">Products</a>
    <a href="#!" class="breadcrumb">{{$product->name}}</a>
@endsection

@section('content')
    <div class="row">
        <div class="col s12 m7">
            {{-- Primary Image --}}
            <div class="row">
                <div class="card">
                    <div class="card-image">
                        <img class="materialboxed" src="{{$product->img_path}}" >
                    </div>
                </div>
            </div>

            {{-- Image Carousel --}}
            <div class="row">
                <div class="carousel" style="height:220px;">
                    <a class="carousel-item" href="#one!"><img src="/{{$product->img_path}}"></a>
                    <a class="carousel-item" href="#two!"><img src="/images/swine.jpg"></a>
                    <a class="carousel-item" href="#three!"><img src="/images/duroc.jpg"></a>
                </div>
            </div>

        </div>
        {{-- Product Details --}}
        <div class="col s12 m5">
            <ul class="collection with-header">
                <li class="collection-header">
                    <h4 class="row">
                        <div class="col">
                            {{ $product->name }}
                        </div>
                        <div class="col right">
                            {!! Form::open(['route' => 'cart.add', 'data-product-id' => $product->id, 'data-type' => $product->type]) !!}
                                <a href="#" class="right tooltipped add-to-cart"  data-position="left" data-delay="50" data-tooltip="Add to Swine Cart">
                                    <i class="material-icons red-text" style="font-size:35px;">add_shopping_cart</i>
                                </a>
                            {!! Form::close() !!}
                        </div>
                    </h4>
                    <div class="row">
                        <div class="col">
                            {{ $product->breeder }} <br>
                            {{ $product->farm_province }}
                        </div>
                    </div>
                </li>
                <li class="collection-item">{{$product->type}} - {{$product->breed}}</li>
                <li class="collection-item">{{$product->age}} days old</li>
                <li class="collection-item">Average Daily Gain: {{$product->adg}} g</li>
                <li class="collection-item">FCR: {{$product->fcr}}</li>
                <li class="collection-item">Backfat Thickness: {{$product->backfat_thickness}} mm</li>
                <li class="collection-item">
                    <span class="row">
                        <i class="col s6">Delivery</i>
                        <span class="col s6">
                            <i class="material-icons yellow-text">star</i>
                            <i class="material-icons yellow-text">star</i>
                            <i class="material-icons yellow-text">star</i>
                            <i class="material-icons yellow-text">star_half</i>
                            <i class="material-icons yellow-text">star_border</i>
                        </span>
                    </span>
                    <span class="row">
                        <i class="col s6">Transaction</i>
                        <span class="col s6">
                            <i class="material-icons yellow-text">star</i>
                            <i class="material-icons yellow-text">star</i>
                            <i class="material-icons yellow-text">star</i>
                            <i class="material-icons yellow-text">star</i>
                            <i class="material-icons yellow-text">star_border</i>
                        </span>
                    </span>
                    <span class="row">
                        <i class="col s6">Product Quality</i>
                        <span class="col s6">
                            <i class="material-icons yellow-text">star</i>
                            <i class="material-icons yellow-text">star</i>
                            <i class="material-icons yellow-text">star</i>
                            <i class="material-icons yellow-text">star</i>
                            <i class="material-icons yellow-text">star_half</i>
                        </span>
                    </span>
                    <span class="row">
                        <i class="col s6">After Sales</i>
                        <span class="col s6">
                            <i class="material-icons yellow-text">star</i>
                            <i class="material-icons yellow-text">star</i>
                            <i class="material-icons yellow-text">star_half</i>
                            <i class="material-icons yellow-text">star_border</i>
                            <i class="material-icons yellow-text">star_border</i>
                        </span>
                    </span>

                </li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content black-text">
                    <span class="card-title">Other Details</span>
                    <p>I am a very simple card. I am good at containing small bits of information.
                    I am convenient because I require little markup to use effectively.</p>
                </div>
            </div>
        </div>
    </div>

@endsection
