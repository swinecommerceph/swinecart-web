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
                <h4>Product Breakdown</h4>
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
