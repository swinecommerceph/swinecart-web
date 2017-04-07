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
        <div class="row valign-wrapper">
            <div class="col s6 m6 l6 xl6">
                <h4>Product Breakdown</h4>
            </div>
            <div class="valign center-block col s6 m6 l6 xl6">
                <div class="input-field col s12">
                    <select onChange="window.location.href=this.value">
                        <option disabled selected>Choose option</option>
                        <option value="{{route('spectator.statisticsActiveBreeder')}}">Breeder</option>
                        <option value="{{route('spectator.statisticsActiveCustomer')}}">Customer</option>
                        <option value="{{route('spectator.productbreakdown')}}">Product Breakdown</option>
                    </select>
                    <label>Display Statistics</label>
                </div>
            </div>
        </div>
        <div class="divider"></div>
        <div class="row">
            <div class="product-breakdown-title col s12 m12 l12 center-align">
                Total Products
            </div>
            <div class="product-breakdown-data col s12 m12 l12 center-align">
                {{$total}}
            </div>
        </div>
        <div class="row">
            <div class="product-breakdown-title col s12 m12 l12 center-align">
                Breakdown
            </div>
            <div class="col s12 m12 l12">
                <canvas id="productBreakdownChartArea" width="300" height="200"></canvas>
            </div>
        </div>
    </div>
@endsection

@section('initScript')
    <script type="text/javascript">
        var products = [{!! $boar !!}, {!! $gilt !!}, {!! $sow !!}, {!! $semen !!}];
        var labels = ["Boar", "Gilt", "Sow", "Semen"];
    </script>
    <script type="text/javascript" src="/js/spectator/productBreakdown_script.js"></script>
@endsection
