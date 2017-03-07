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
                <h4>Statistics</h4>
            </div>
        </div>
        <div class="divider"></div>
        <div class="row">
            <div class="col s12">
                <div class="row">
                    <div class="col s12">
                        <h5>Label 1</h5>
                        <canvas id="statsChart" width="400" height="250"></canvas>
                    </div>
                    <div class="col s12">

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
