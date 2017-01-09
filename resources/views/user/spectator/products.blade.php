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

        <div class="row">
            @forelse ($products as $product)
                <div class="col s4">
                    <div class="card small">
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

    <div id="product-modal" class="modal modal-fixed-footer">
        <div class="modal-content">
            <h4>{{$product->name}}</h4>
            <div class="divider"></div>
            <div class="row">
                <div class="col s12 center">
                    <img class="product_image" src="{{$product->image_name}}" alt="Image broken" onerror="this.src='/images/logo.png'" />
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
                                <td>{{$product->type}}</td>
                                <td>{{$product->adg}}</td>
                                <td>{{$product->fcr}}</td>
                                <td>{{$product->backfat_thickness}}</td>
                                <td>{{$product->quantity}}</td>
                                <td>{{$product->price}}</td>
                            </tr>
                        </tbody>
                    </table>

                </div>

            </div>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Close</a>
        </div>
    </div>

@endsection

@section('initScript')
    <script type="text/javascript" src="/js/admin/admin_custom.js"></script>
    <script type="text/javascript" src="/js/admin/users.js"></script>
    <script type="text/javascript" src="/js/admin/manageUsers_script.js"></script>
    <script type="text/javascript" src="/js/admin/pages.js"></script>
    <script type="text/javascript" src="/js/admin/managePages_script.js"></script>
@endsection
