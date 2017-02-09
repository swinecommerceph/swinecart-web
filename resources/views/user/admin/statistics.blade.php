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
            <div class="row">
                {{-- <div class="col s6">
                    <h5>User Statistics</h5>
                </div> --}}
                <div class="input-field col s6 right">
                    <select>
                        <option v-on:click.prevent="get_weekly_created()">Weekly Statistics</option>
                        <option v-on:click.prevent="get_monthly_created()">Monthly Statistics</option>
                        <option v-on:click.prevent="get_yearly_created()">Yearly Statistics</option>
                    </select>
                    <label>Chart Options</label>
                </div>
            </div>
            <canvas id="created_chart_area" width="400" height="250"></canvas>
        </div>
        <div id="deleted-chart" class="col s12"><deleted-chart-area></deleted-chart-area></canvas></div>
        <div id="blocked-chart" class="col s12"><blocked-chart-area></blocked-chart-area></div>
        <div id="accepted-chart" class="col s12"><accepted-chart-area></accepted-chart-area></div>
        <div id="transaction-chart" class="col s12">Space for transaction count per category</div>
    </div>


@endsection

@section('initScript')
    <script type="text/javascript" src="/js/admin/statistics.js"></script>
    <script type="text/javascript" src="/js/admin/statistics_script.js"></script>
@endsection
