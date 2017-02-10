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
            <li class="tab col s3"><a class="active" href="#created-chart">Users Created</a></li>
            <li class="tab col s3"><a class="tab-header" href="#deleted-chart" v-on:click.prevent="get_deleted_data()">Deleted</a></li>
            <li class="tab col s3"><a class="tab-header" href="#blocked-chart" v-on:click.prevent="get_blocked_data()">Blocked</a></li>
            <li class="tab col s3"><a class="tab-header" href="#accepted-chart" v-on:click.prevent="get_accepted_data()">Accepted</a></li>
            <li class="tab col s3"><a href="#transaction-chart">Transactions</a></li>
        </ul>
        <div id="created-chart" class="col s12">

            {{-- @TODO
                        1) Select for filter on what chart to display, Deleted, Blocked, Accepted
                        2) Select filter for the selected chart to display data charts per day, per month or per year
                            * (optional) for year, can select range of year
            --}}
            {{-- <div class="row">
                <div id="user-filter-wrapper" class="col s4 m4 l4 right">
                    <a class="waves-effect waves-light btn disabled">Monthly</a>
                    <a class="waves-effect waves-light btn" v-on:click="get_yearly_created">Yearly</a>
                </div>
            </div> --}}
            <div class="row">
                {!!Form::open(['route'=>'admin.statistics.created.year', 'method'=>'GET'])!!}
                <div class="col s6 right">
                    <label for="stats-created-year">Year</label>
                    <input id="stats-created-year" type="number" name="year" min="{{ $yearMinMax[0] }}" max="{{ $yearMinMax[1] }}" value="{{ $year }}">
                    <div class="center">
                        <button class="btn waves-effect waves-light" type="submit" name="year-submit">Generate</button>
                    </div>
                </div>
                {!!Form::close()!!}
            </div>
            <canvas id="created_chart_area" width="400" height="250"></canvas>
        </div>
        <div id="deleted-chart" class="col s12"><deleted-chart-area></deleted-chart-area></canvas></div>
        <div id="blocked-chart" class="col s12"><blocked-chart-area></blocked-chart-area></div>
        <div id="accepted-chart" class="col s12"><accepted-chart-area></accepted-chart-area></div>
        <div id="transaction-chart" class="col s12"></div>
    </div>


@endsection

@section('initScript')
    <script type="text/javascript" src="/js/admin/statistics.js"></script>
    <script type="text/javascript" src="/js/admin/statistics_script.js"></script>
@endsection
