@extends('layouts.spectatorLayout')

@section('title')
    | Spectator
@endsection

@section('pageId')
    id="page-spectator-users"
@endsection

@section('header')
    <h4>Admin Dashboard</h4>
@endsection

@section('content')
    <div class="card-panel">
        <div class="row">
            <div class="col s6">
                <h4>Products</h4>
            </div>
            <div class="col s6">
                {!!Form::open(['route'=>'spectator.searchProduct', 'method'=>'GET', 'class'=>'spectator_product_search'])!!}
                <div class="input-field inline">
                    <input id="search" type="text" class="validate">
                    <label for="search" data-error="wrong" data-success="right">Search Product</label>
                </div>
                {!!Form::close()!!}
            </div>
        </div>
        <div class="row">
            <div class="divider"></div>
        </div>

        <div id="main-container" class="row">
            @forelse ($products as $product)
                <div class="col s4">
                    <div class="card small"
                        data-values="{{ $product->name }}|{{ $product->type }}|{{ $product->adg }}|{{ $product->fcr }}|{{ $product->backfat_thickness }}|{{ $product->status }}|{{ $product->quantity }}|{{ $product->price }}|{{ $product->image_name }}|{{ $product->other_details }}  ">
                        <div class="card-image waves-effect waves-block waves-light">
                            <img class="activator" src="{{$product->image_name}}" alt="Image broken" onerror="this.src='/images/logo.png'">
                        </div>
                        <div class="card-content">
                            <span class="card-title activator grey-text text-darken-4">{{$product->name}}<i class="material-icons right">more_vert</i></span>
                        </div>
                        <div class="card-reveal">
                            <span class="card-title grey-text text-darken-4">{{$product->name}}<i class="material-icons right">close</i></span>
                            <p>Information</p>
                            <p>
                                <p> {{$product->other_details}}</p>
                                <p class="center"><a href="#product-modal" class="modal-trigger">See more information</a></p>
                            </p>
                        </div>
                    </div>
                </div>


            @empty
                <div class="center col s12">
                    No Products to Display
                </div>
            @endforelse
        </div>
        <div class="row">
            <div class="col s12">
                <ul class="pagination center">
                    {{ $products->links() }}
                </ul>
            </div>
        </div>

    </div>

    {{-- TODO Fix the display of items appearing in the modal not matching the clicked item --}}
    {{--  Add javascript or use Vue.js to fix this --}}
    <div id="product-modal" class="modal modal-fixed-footer">
        <div class="modal-content">
            <h4 id="modal-header"></h4>
            <div class="divider"></div>
            <div class="row">
                <div class="col s12 center">
                    <img id="modal-image" class="product_image" src="" alt="Image broken" onerror="this.src='/images/logo.png'" />
                </div>
                <div class="col s12">
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
                <div class="left col s12">
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
    <script type="text/javascript" src="/js/spectator/spectator_custom.js"></script>
    <script type="text/javascript" src="/js/spectator/products.js"></script>
    <script type="text/javascript" src="/js/spectator/productsPage.js"></script>
@endsection
