@extends('layouts.adminLayout')

@section('title')
    | Admin
@endsection

@section('pageId')
    id="stats-page"
@endsection

@section('header')
    <div class="row">
        <div class="col s4">
            <h4 id='admin-content-panel-header'>Site Statistics</h4>
        </div>
    </div>

@endsection

@section('content')
    <script type="text/javascript">
        var jan = {!! $month[0] !!};
        var feb = {!! $month[1] !!};
        var mar = {!! $month[2] !!};
        var apr = {!! $month[3] !!};
        var may = {!! $month[4] !!};
        var jun = {!! $month[5] !!};
        var jul = {!! $month[6] !!};
        var aug = {!! $month[7] !!};
        var sep = {!! $month[8] !!};
        var oct = {!! $month[9] !!};
        var nov = {!! $month[10] !!};
        var dec = {!! $month[11] !!};
    </script>
    <div id="app-statistics">
    <ul id="tabs-swipe-demo" class="tabs">
        <li class="tab col s3"><a class="active" href="#deleted-chart">Deleted</a></li>
        <li class="tab col s3"><a class="tab-header" href="#blocked-chart" v-on:click.prevent="get_blocked_data()">Blocked</a></li>
        <li class="tab col s3"><a href="#transaction-chart">Transactions</a></li>
    </ul>
    <div id="deleted-chart" class="col s12"><canvas id="deleted_chart_area" width="400" height="250"></canvas></div>
    <div id="blocked-chart" class="col s12"><blocked-chart-area></blocked-chart-area></div>
    <div id="transaction-chart" class="col s12 green">Test 3</div>
    </div>


@endsection

@section('initScript')
    <script type="text/javascript" src="/js/admin/statistics.js"></script>
    <script type="text/javascript" src="/js/admin/statistics_script.js"></script>
@endsection
