{{--
    Displays products of the respective Breeder user
--}}

@extends('user.breeder.home')

@section('title')
    | Breeder - Product Status
@endsection

@section('pageId')
    id="page-breeder-product-status"
@endsection

@section('breadcrumbTitle')
    Product Status
@endsection

@section('breadcrumb')
    <a href="{{ route('home_path') }}" class="breadcrumb">Home</a>
    <a href="{{ route('dashboard') }}" class="breadcrumb">Dashboard</a>
    <a href="#!" class="breadcrumb">Product Status</a>
@endsection

@section('content')
    <div class="row">
        <div class="col s12">
            <p class="caption">
                See what's happening with your products. <br>
            </p>
        </div>
    </div>

    <div class="row">
        <table id="product-status-table" class="">
            <thead>
                <tr>
                    <th> Product Information </td>
                    <th> Status </td>
                    <th> Action </td>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td> {{ $product->name }} </td>
                        <td> {{ $product->status }} </td>
                        <td>
                            @if($product->status == 'requested')
                                <a class="product-request-icon" href="#" data-product-id="{{$product->id}}" data-product-name="{{$product->name}}"><i class="material-icons teal-text">face</i></a>
                            @elseif($product->status == 'reserved')
                                <div class="row">
                                    <a class="col s1 product-delivery-icon" href="#" data-product-id="{{$product->id}}" data-product-name="{{$product->name}}" data-token="{{$token}}"><i class="material-icons teal-text">local_shipping</i></a>
                                    <a class="col s1 product-paid-icon" href="#" data-product-id="{{$product->id}}" data-product-name="{{$product->name}}" data-token="{{$token}}"><i class="material-icons teal-text">credit_card</i></a>
                                    <a class="col s1" href="#"><i class="material-icons teal-text">message</i></a>
                                </div>
                            @elseif($product->status == 'on_delivery')
                                <a class="view-code-icon" href="#" data-code="{{$product->code}}"><i class="material-icons teal-text">code</i></a>
                            @elseif($product->status == 'paid')
                                <div class="row">
                                    <a class="col s1 view-code-icon" href="#" data-code="{{$product->code}}"><i class="material-icons teal-text">code</i></a>
                                    <a class="col s1 product-delivery-icon" href="#" data-product-id="{{$product->id}}" data-product-name="{{$product->name}}" data-token="{{$token}}"><i class="material-icons teal-text">local_shipping</i></a>
                                </div>
                            @elseif($product->status == 'sold')
                                <a href="#"><i class="material-icons teal-text">thumb_up</i></a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Product Requests Modal --}}
    <div id="product-requests-modal" class="modal modal-fixed-footer">
        <div class="modal-content">
            <h4>Product Requests</h4>
            <ul class="collection"></ul>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Close</a>
        </div>

    </div>

    {{-- Reserve Product Confirmation Modal --}}
    <div id="reserve-product-confirmation-modal" class="modal">
        <div class="modal-content">
            <h4>Reserve Product Confirmation</h4>
            <p>
                Are you sure you want to reserve Product?
            </p>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Close</a>
            <a href="#!" class="modal-action waves-effect waves-green btn-flat confirm-reserve-button">Yes</a>

        </div>
    </div>

    {{-- Product Delivery Confirmation Modal --}}
    <div id="product-delivery-confirmation-modal" class="modal">
        <div class="modal-content">
            <h4>Product Delivery Confirmation</h4>
            <p>
                Are you sure the product is on delivery?
            </p>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Close</a>
            <a href="#!" class="modal-action waves-effect waves-green btn-flat confirm-delivery-button">Yes</a>
        </div>
    </div>

    {{-- Paid Product Confirmation Modal --}}
    <div id="paid-product-confirmation-modal" class="modal">
        <div class="modal-content">
            <h4>Paid Product Confirmation</h4>
            <p>
                Are you sure the product is paid already?
            </p>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Close</a>
            <a href="#!" class="modal-action waves-effect waves-green btn-flat confirm-paid-button">Yes</a>
        </div>
    </div>

    {{-- View Code Modal --}}
    <div id="view-code-modal" class="modal">
        <div class="modal-content">
            <h4>View Code</h4>
            <p>
                Confirmation Code:
            </p>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Close</a>
        </div>
    </div>
@endsection

@section('customScript')
    <script src="/js/vendor/DataTables/datatables.min.js"></script>
    <script src="/js/breeder/dashboard.js"></script>
    <script src="/js/breeder/dashboardProductStatus_script.js"></script>
@endsection
