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
            <div class="col s12">
                <h4>Statistics Dashboard</h4>
            </div>
        </div>
        <div class="divider"></div>
        <div class="row">
            <div class="col s12">
                <div class="row">
                    <div class="col s12 m6 l6">
                        <a href="{{route('spectator.statisticsActiveBreeder')}}">
                            <div class="card-panel pink accent-1 hoverable black-text">
                                <div class="row">
                                    <div class="col s12 m12 l12 center-align">
                                        <i class="material-icons spectator-dashicon">local_shipping</i>
                                    </div>
                                    <div class="col s12 m12 l12 center-align spectator-dashlabel">
                                        Breeders
                                    </div>
                                    <div class="col s12 m12 l12 center-align">
                                        {{$data[0]}} New this Month
                                    </div>
                                    <div class=" col s12 m12 l12 center-align">
                                        {{$data[1]}} Deleted this Month
                                    </div>
                                    <div class=" col s12 m12 l12 center-align">
                                        {{$data[2]}} Blocked this Month
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col s12 m6 l6">
                        <a href="{{route('spectator.statisticsActiveCustomer')}}">
                            <div class="card-panel indigo accent-1 hoverable black-text">
                                <div class="row">
                                    <div class="col s12 m12 l12 center-align">
                                        <i class="material-icons spectator-dashicon">people</i>
                                    </div>
                                    <div class="col s12 m12 l12 center-align spectator-dashlabel">
                                        Customers
                                    </div>
                                    <div class="col s12 m12 l12 center-align">
                                        {{$data[3]}} New this Month
                                    </div>
                                    <div class=" col s12 m12 l12 center-align">
                                        {{$data[4]}} Deleted this Month
                                    </div>
                                    <div class=" col s12 m12 l12 center-align">
                                        {{$data[5]}} Blocked this Month
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col s12 m12 l12">
                        <div class="card-panel amber accent-1 hoverable black-text">
                            <div class="row">
                                <div class="col s12 m12 l12 center-align">
                                    <i class="material-icons spectator-dashicon">shopping_basket</i>
                                </div>
                                <div class="col s12 m12 l12 center-align spectator-dashlabel">
                                    Products
                                </div>
                                <div class="col s12 m12 l12 center-align">
                                    {{$data[6]}} in Inventory
                                </div>
                                <div class=" col s12 m12 l12 center-align">
                                    Inventory Summary:
                                    {{$data[7]}} Boar |
                                    {{$data[8]}} Gilt |
                                    {{$data[9]}} Sow |
                                    {{$data[10]}} Semen
                                </div>
                                <div class=" col s12 m12 l12 center-align">
                                    <a href="{{route('spectator.productbreakdown')}}">View Chart</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12 m12 l12 center-align spectator-dashlabel">
                                    Transaction
                                </div>
                                <div class="col s12 m12 l12 center-align">
                                    This Month's Product Status
                                </div>
                                <div class="col s12 m12 l12 center-align">
                                    {{$requested}} Requested
                                </div>
                                <div class="col s12 m12 l12 center-align">
                                    {{$reserved}} Reserved
                                </div>
                                <div class="col s12 m12 l12 center-align">
                                    {{$paid}} Paid
                                </div>
                                <div class="col s12 m12 l12 center-align">
                                    {{$on_delivery}} On Delivery
                                </div>
                                <div class="col s12 m12 l12 center-align">
                                    {{$sold}} Sold
                                </div>
                                {{-- <div class=" col s12 m12 l12 center-align">
                                    <a href="{{route('spectator.productbreakdown')}}">View More Information</a>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('initScript')
    <script type="text/javascript" src="/js/spectator/spectator_custom.js"></script>
    <script type="text/javascript" src="/js/spectator/statistics_script.js"></script>
    <script type="text/javascript" src="/js/spectator/statistics.js"></script>
@endsection
