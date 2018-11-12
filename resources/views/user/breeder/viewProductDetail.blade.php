{{--
    Displays details of a chosen product
--}}

@extends('user.breeder.home')

@section('title')
    | {{$product->name}}
@endsection

@section('pageId')
    id="page-breeder-view-product-details"
@endsection

@section('breadcrumbTitle')
    {{$product->name}}
@endsection

@section('breadcrumb')
    <a href="{{ route('home_path') }}" class="breadcrumb">Home</a>
    <a href="{{ route('products') }}" class="breadcrumb">Products</a>
    <a href="#!" class="breadcrumb">{{$product->name}}</a>
@endsection

@section('content')
    <div class="row">
        <div class="col s12 m7">
            {{-- Primary Image --}}
            <div class="row">
                <div class="card">
                    <div class="card-image">
                        <img style="width: 40vw; height: 50vh;" src="{{$product->img_path}}" data-imagezoom="{{ $product->def_img_path }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col s12">
                <ul class="tabs tabs-fixed-width">
                    <li id="image-carousel-tab" class="tab col s3"><a class="active" href="#images-carousel">Images</a></li>
                    <li id="video-carousel-tab" class="tab col s3"><a href="#videos-carousel">Videos</a></li>
                </ul>
                </div>
                {{-- Image Carousel --}}
                <div id="images-carousel" class="col s12">
                    <div class="carousel" style="height:14rem;">
                        <a class="carousel-item" href="#!"><img src="{{$product->img_path}}"></a>
                        @foreach($product->imageCollection as $image)
                            <a class="carousel-item" href="#!"><img src="/images/product/{{$image->name}}"></a>
                        @endforeach
                    </div>
                </div>
                {{--  Video Carousel --}}
                <div id="videos-carousel" class="col s12">
                    @if(count($product->videoCollection) > 0)
                        <div class="carousel" style="height:14rem;">
                            @foreach($product->videoCollection as $video)
                                <a class="carousel-item" href="#!">
                                    <video class="responsive-video" controls>
                                        <source src="/videos/product/{{$video->name}}" type="{{$video->type}}">
                                    </video>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
        {{-- Product Details --}}
        <div class="col s12 m5">
            <ul class="collection with-header">
                <li class="collection-header">
                    <h4 class="row">
                        <div class="col" style="color: hsl(0, 0%, 13%); font-weight: 700">
                            {{ $product->name }}
                        </div>
                    </h4>
                </li>
                <li class="collection-item" style="font-weight: 700;">{{$product->type}} - {{$product->breed}}</li>
                <li class="collection-item">Born on {{$product->birthdate}} ({{$product->age}} days old)</li>
                <li class="collection-item">Average Daily Gain: {{$product->adg}} g</li>
                <li class="collection-item">Feed Conversion Ratio: {{$product->fcr}}</li>
                <li class="collection-item">Backfat Thickness: {{$product->backfat_thickness}} mm</li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content black-text">
                    <span class="card-title">Other Details</span>
                    <p>{!! $product->other_details !!}</p>
                </div>
            </div>
        </div>
    </div>

    <script type="text/x-template" id="average-star-rating">
        <div class="ratings-container" style="padding:0; position:relative; display:inline-block">
            <div class="star-ratings-top" style="position:absolute; z-index:1; overflow:hidden; display:block; white-space:nowrap;" :style="{ width: ratingToPercentage + '%' }">
                <i class="material-icons yellow-text"> star </i>
                <i class="material-icons yellow-text"> star </i>
                <i class="material-icons yellow-text"> star </i>
                <i class="material-icons yellow-text"> star </i>
                <i class="material-icons yellow-text"> star </i>
            </div>
            <div class="star-ratings-bottom" style="padding:0; z-index:0; display:block;">
                <i class="material-icons yellow-text"> star_border </i>
                <i class="material-icons yellow-text"> star_border </i>
                <i class="material-icons yellow-text"> star_border </i>
                <i class="material-icons yellow-text"> star_border </i>
                <i class="material-icons yellow-text"> star_border </i>
            </div>
        </div>
    </script>
@endsection

@section('customScript')
    <script src="{{ elixir('/js/breeder/viewProductDetail.js') }}"></script>
@endsection
