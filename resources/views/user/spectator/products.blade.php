@extends('layouts.spectatorLayout')

@section('title')
    | Spectator
@endsection

@section('pageId')
    id="page-spectator-products"
@endsection

{{-- @section('header')
    <h4>Admin Dashboard</h4>
@endsection --}}

@section('content')
    <div id="app-products" class="card-panel">
        <div class="row">
            <div class="col s12 m12 l3">
                <h4>Products</h4>
            </div>
            <div class="col s12 m12 l9">
                {!!Form::open(['route'=>'spectator.searchProduct', 'method'=>'GET', 'class'=>'spectator_product_search'])!!}
                <div class="row">
                    <div class="col s12 m12 l12 valign-wrapper">
                        <div class="input-field inline col s12 m12 l10 valign">
                            <input id="search" type="text" class="validate" name="search">
                            <label for="search">Search Product Name</label>
                        </div>
                        <button id="search-button" class="btn waves-effect waves-light" type="submit">Search</button>
                    </div>

                    <div class="col s12 right-align">
                            <a v-on:click="toggled = !toggled" href="#" class="teal-text">Advanced Search Options</a>
                    </div>
                    <transition name="fade">
                        <div v-if="toggled" class="col s12 m12 l12">
                            <div class="row valign-wrapper">
                                <div class="col s12 m12 l4">
                                    <div class="col s6 m6 l6">
                                        <input type="checkbox" class="filled-in" id="spectatorproductboar" name="boar" value="boar"/>
                                        <label for="spectatorproductboar">Boar</label>
                                    </div>
                                    <div class="col s6 m6 l6">
                                        <input type="checkbox" class="filled-in" id="spectatorproductgilt" name="gilt" value="gilt"/>
                                        <label for="spectatorproductgilt">Gilt</label>
                                    </div>
                                    <div class="col s6 m6 l6">
                                        <input type="checkbox" class="filled-in" id="spectatorproductsow" name="sow" value="sow"/>
                                        <label for="spectatorproductsow">Sow</label>
                                    </div>
                                    <div class="col s6 m6 l6">
                                        <input type="checkbox" class="filled-in" id="spectatorproductsemen" name="semen" value="semen"/>
                                        <label for="spectatorproductsemen">Semen</label>
                                    </div>
                                </div>
                                <div class="col s12 m12 l8 valign-wrapper">
                                    <div class="col s12 m12 l5 valign">
                                        <label for="spectatorproductminprice">Min Price</label>
                                        <input id="spectatorproductminprice" type="number" value='{{$minmax->minprice}}' min ="{{$minmax->minprice}}" max="{{($minmax->maxprice+$minmax->minprice)/2}}" name="minPrice"/>
                                    </div>
                                    <div class="col s12 m12 l2 valign center-align">
                                        to
                                    </div>
                                    <div class="col s12 m12 l5 valign">
                                        <label for="spectatorproductmaxprice">Max Price</label>
                                        <input id="spectatorproductmaxprice" type="number" value="{{$minmax->maxprice}}" min ="{{($minmax->maxprice+$minmax->minprice)/2}}" max="{{$minmax->maxprice}}" name="maxPrice"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12 m12 l6 valign-wrapper">
                                    <div class="col s12 m12 l5">
                                        <label for="spectatorproductminquantity">Min Quantity</label>
                                        <input id="spectatorproductminquantity" type="number" value="{{$minmax->minquantity}}" min ="{{$minmax->minquantity}}" max="{{($minmax->maxquantity+$minmax->minquantity)/2}}" name="minQuantity"/>
                                    </div>
                                    <div class="col s12 m12 l2 valign center-align">
                                        to
                                    </div>
                                    <div class="col s12 m12 l5">
                                        <label for="spectatorproductmaxquantity">Max Quantity</label>
                                        <input id="spectatorproductmaxprice" type="number" value="{{$minmax->maxquantity}}" min ="{{($minmax->maxquantity+$minmax->minquantity)/2}}" max="{{$minmax->maxquantity}}" name="maxQuantity"/>
                                    </div>
                                </div>
                                <div class="col s12 m12 l6 valign-wrapper">
                                    <div class="col s12 m12 l5">
                                        <label for="spectatorproductminadg">Min ADG</label>
                                        <input id="spectatorproductminadg" type="number" value="{{$minmax->minadg}}" min ="{{$minmax->minadg}}" max="{{($minmax->maxadg+$minmax->minadg)/2}}" name="minADG"/>
                                    </div>
                                    <div class="col s12 m12 l2 valign center-align">
                                        to
                                    </div>
                                    <div class="col s12 m12 l5">
                                        <label for="spectatorproductmaxadg">Max ADG</label>
                                        <input id="spectatorproductmaxadg" type="number" value="{{$minmax->maxadg}}" min ="{{($minmax->maxadg+$minmax->minadg)/2}}" max="{{$minmax->maxadg}}" name="maxADG"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12 m12 l6 valign-wrapper">
                                    <div class="col s12 m12 l5">
                                        <label for="spectatorproductminfcr">Min FCR</label>
                                        <input id="spectatorproductminfcr" type="number" value="{{$minmax->minfcr}}" min ="{{$minmax->minfcr}}" max="{{($minmax->maxfcr+$minmax->minfcr)/2}}" name="minFCR"/>
                                    </div>
                                    <div class="col s12 m12 l2 valign center-align">
                                        to
                                    </div>
                                    <div class="col s12 m12 l5">
                                        <label for="spectatorproductmaxfcr">Max FCR</label>
                                        <input id="spectatorproductmaxfcr" type="number" value="{{$minmax->maxfcr}}" min ="{{($minmax->maxfcr+$minmax->minfcr)/2}}" max="{{$minmax->maxfcr}}" name="maxFCR"/>
                                    </div>
                                </div>
                                <div class="col s12 m12 l6 valign-wrapper">
                                    <div class="col s12 m12 l5">
                                        <label for="spectatorproductminbfat">Min Backfat</label>
                                        <input id="spectatorproductminbfat" type="number" value="{{$minmax->minbfat}}" min ="{{$minmax->minbfat}}" max="{{($minmax->maxbfat+$minmax->minbfat)/2}}" name="minBackfatThickness"/>
                                    </div>
                                    <div class="col s12 m12 l2 valign center-align">
                                        to
                                    </div>
                                    <div class="col s12 m12 l5">
                                        <label for="spectatorproductmaxbfat">Max Backfat</label>
                                        <input id="spectatorproductmaxbfat" type="number" value="{{$minmax->maxbfat}}" min ="{{($minmax->maxbfat+$minmax->minbfat)/2}}" max="{{$minmax->maxbfat}}" name="maxBackfatThickness"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </transition>
                </div>
                {!!Form::close()!!}
            </div>
        </div>

        <div class="row">
            <div class="divider"></div>
        </div>

        {{-- MAKE COMPONENT FOR THIS --}}
        <div id="main-container" class="row">
            @forelse ($products as $product)
                <div class="col s4">
                    <div class="card small">
                        <div class="card-image waves-effect waves-block waves-light">
                            <img class="activator" src="{{'/images/product/'.$product->image_name}}" alt="Image broken" onerror="this.src='/images/logo.png'"/>
                        </div>
                        <div class="card-content">
                            <span class="card-title activator grey-text text-darken-4">{{$product->name}}<i class="material-icons right">more_vert</i></span>
                        </div>
                        <div class="card-reveal">
                            <span class="card-title grey-text text-darken-4">{{$product->name}}<i class="material-icons right">close</i></span>
                            <p>Information</p>
                            <p>
                                <p>{{$product->other_details}}</p>
                                <p class="center"><a href="#spectator-product-modal" class="modal-trigger" v-on:click.prevent="displayProductModal('{{$product->id}}')">See more information</a></p>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- <product-modal v-if:show></product-modal> --}}
            @empty
                <div class="center col s12">
                    No Products to Display
                </div>
            @endforelse
        </div>

        <div class="row">
            <div class="col s12">
                <ul class="pagination center">
                    {{ $products->appends(Request::except('page'))->links() }}
                </ul>
            </div>
        </div>

    </div>


    <div id="spectator-product-modal" class="modal">
        {{-- Insert DOM element in ajax call --}}
    </div>

@endsection

@section('initScript')
    <script type="text/javascript">
        // Variables
        var minPrice = {!! $minmax->minprice !!}
        var maxPrice = {!! $minmax->maxprice !!}
        var minQuantity = {!! $minmax->minquantity !!}
        var maxQuantity = {!! $minmax->maxquantity !!}
        var minADG = {!! $minmax->minadg !!}
        var maxADG = {!! $minmax->maxadg !!}
        var minFCR = {!! $minmax->minfcr !!}
        var maxFCR = {!! $minmax->maxfcr !!}
        var minBackfatThickness = {!! $minmax->minbfat !!}
        var maxBackfatThickness = {!! $minmax->maxbfat   !!}
    </script>
    <script type="text/javascript" src="/js/spectator/spectator_custom.js"></script>
    <script type="text/javascript" src="/js/spectator/products.js"></script>
    <script type="text/javascript" src="/js/spectator/productsPage.js"></script>
@endsection
