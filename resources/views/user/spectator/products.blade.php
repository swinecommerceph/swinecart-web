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
                                        <input id="spectatorproductminprice" type="number" value='{{$minmax->minprice}}' min ="{{$minmax->minprice}}" max="{{$minmax->maxprice}}" name="minPrice"/>
                                    </div>
                                    <div class="col s12 m12 l2 valign center-align">
                                        to
                                    </div>
                                    <div class="col s12 m12 l5 valign">
                                        <label for="spectatorproductmaxprice">Max Price</label>
                                        <input id="spectatorproductmaxprice" type="number" value="{{$minmax->maxprice}}" min ="{{$minmax->minprice}}" max="{{$minmax->maxprice}}" name="maxPrice"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12 m12 l6 valign-wrapper">
                                    <div class="col s12 m12 l5">
                                        <label for="spectatorproductminquantity">Min Quantity</label>
                                        <input id="spectatorproductminquantity" type="number" value="{{$minmax->minquantity}}" min ="{{$minmax->minquantity}}" max="{{$minmax->maxquantity}}" name="minQuantity"/>
                                    </div>
                                    <div class="col s12 m12 l2 valign center-align">
                                        to
                                    </div>
                                    <div class="col s12 m12 l5">
                                        <label for="spectatorproductmaxquantity">Max Quantity</label>
                                        <input id="spectatorproductmaxprice" type="number" value="{{$minmax->maxquantity}}" min ="{{$minmax->minquantity}}" max="{{$minmax->maxquantity}}" name="maxQuantity"/>
                                    </div>
                                </div>
                                <div class="col s12 m12 l6 valign-wrapper">
                                    <div class="col s12 m12 l5">
                                        <label for="spectatorproductminadg">Min ADG</label>
                                        <input id="spectatorproductminadg" type="number" value="{{$minmax->minadg}}" min ="{{$minmax->minadg}}" max="{{$minmax->maxadg}}" name="minADG"/>
                                    </div>
                                    <div class="col s12 m12 l2 valign center-align">
                                        to
                                    </div>
                                    <div class="col s12 m12 l5">
                                        <label for="spectatorproductmaxadg">Max ADG</label>
                                        <input id="spectatorproductmaxadg" type="number" value="{{$minmax->maxadg}}" min ="{{$minmax->minadg}}" max="{{$minmax->maxadg}}" name="maxADG"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12 m12 l6 valign-wrapper">
                                    <div class="col s12 m12 l5">
                                        <label for="spectatorproductminfcr">Min FCR</label>
                                        <input id="spectatorproductminfcr" type="number" value="{{$minmax->minfcr}}" min ="{{$minmax->minfcr}}" max="{{$minmax->maxfcr}}" name="minFCR"/>
                                    </div>
                                    <div class="col s12 m12 l2 valign center-align">
                                        to
                                    </div>
                                    <div class="col s12 m12 l5">
                                        <label for="spectatorproductmaxfcr">Max FCR</label>
                                        <input id="spectatorproductmaxfcr" type="number" value="{{$minmax->maxfcr}}" min ="{{$minmax->minfcr}}" max="{{$minmax->maxfcr}}" name="maxFCR"/>
                                    </div>
                                </div>
                                <div class="col s12 m12 l6 valign-wrapper">
                                    <div class="col s12 m12 l5">
                                        <label for="spectatorproductminbfat">Min Backfat</label>
                                        <input id="spectatorproductminbfat" type="number" value="{{$minmax->minbfat}}" min ="{{$minmax->minbfat}}" max="{{$minmax->maxbfat}}" name="minBackfatThickness"/>
                                    </div>
                                    <div class="col s12 m12 l2 valign center-align">
                                        to
                                    </div>
                                    <div class="col s12 m12 l5">
                                        <label for="spectatorproductmaxbfat">Max Backfat</label>
                                        <input id="spectatorproductmaxbfat" type="number" value="{{$minmax->maxbfat}}" min ="{{$minmax->minbfat}}" max="{{$minmax->maxbfat}}" name="maxBackfatThickness"/>
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
                    <div class="card small"
                        data-values="{{ $product->name }}|{{ ucfirst($product->type) }}|{{ $product->adg }}|{{ $product->fcr }}|{{ $product->backfat_thickness }}|{{ ucfirst($product->status) }}|{{ $product->quantity }}|{{ $product->price }}|{{ '/images/product/'.$product->image_name }}|{{ $product->other_details }}  ">
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
                                <script type="text/javascript">
                                    var info = [
                                        "{{ $product->name }}",
                                        "{{ ucfirst($product->type) }}",
                                        "{{ $product->adg }}",
                                        "{{ $product->fcr }}",
                                        "{{ $product->backfat_thickness }}",
                                        "{{ ucfirst($product->status) }}",
                                        "{{ $product->quantity }}",
                                        "{{ $product->price }}",
                                        "{{ '/images/product/'.$product->image_name }}",
                                        "{{ $product->other_details }}"
                                    ];
                                </script>
                                <p class="center"><a href="#product-modal" class="modal-trigger" v-bind:info=info v-on:click="displayProductModal()">See more information</a></p>
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

    {{-- TODO Fix the display of items appearing in the modal not matching the clicked item --}}
    {{--  Add javascript or use Vue.js to fix this --}}
    <div id="product-modal" class="modal modal-fixed-footer">
        <div class="modal-content">
            <h4 id="modal-header"></h4>

            <div class="row">
                <div class="col s12 m12 l4">
                    <img id="modal-image" class="product_image" src="" alt="Image broken" onerror="this.src='/images/logo.png'"/>
                </div>
                <div class="col s12 m12 l8">
                    <table>
                        <thead>
                            <tr>
                                <th data-field="type">Type</th>
                                <th data-field="adg">ADG</th>
                                <th data-field="fcr">FCR</th>
                                <th data-field="backfat_thickness">Backfat Thickness</th>
                                <th data-field="status">Status</th>
                                <th data-field="quantity">Quantity</th>
                                <th data-field="price">Price</th>

                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td id="data-type"></td>
                                <td id="data-adg"></td>
                                <td id="data-fcr"></td>
                                <td id="data-backfat"></td>
                                <td id="data-status"></td>
                                <td id="data-quantity"></td>
                                <td id="data-price"></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
            <div class="row">
                <div class="left col s12 m12 l12">
                    <h5>Other Product Information</h5>
                </div>
                <div id="data-information" class="col s12">

                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Close</a>
        </div>
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
