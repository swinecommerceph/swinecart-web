@extends('layouts.adminLayout')

@section('title')
    | Admin
@endsection

@section('pageId')
    id="breeder-blocked-stats-page-spectator"
@endsection

@section('header')
    <div class="valign-wrapper row">
        <div class="valign center-block col s5">
            <h4 id='admin-content-panel-header'>Breeder Statistics</h4>
        </div>
        <div class="valign center-block col s7">
            <div class="input-field col s12">
                <select onChange="window.location.href=this.value">
                    <option disabled selected>Choose option</option>
                    <option value="{{route('spectator.statisticsActiveBreeder')}}">Breeder</option>
                    <option value="{{route('spectator.statisticsActiveCustomer')}}">Customer</option>
                    <option value="3">Products</option>
                </select>
                <label>Display Statistics</label>
            </div>
        </div>
    </div>

@endsection

@section('content')
    {{-- Accept the count per month and save it to a javascript variable for chart use --}}
    <script type="text/javascript">
        var jan = {!! $monthlyCount[0] !!};
        var feb = {!! $monthlyCount[1] !!};
        var mar = {!! $monthlyCount[2] !!};
        var apr = {!! $monthlyCount[3] !!};
        var may = {!! $monthlyCount[4] !!};
        var jun = {!! $monthlyCount[5] !!};
        var jul = {!! $monthlyCount[6] !!};
        var aug = {!! $monthlyCount[7] !!};
        var sep = {!! $monthlyCount[8] !!};
        var oct = {!! $monthlyCount[9] !!};
        var nov = {!! $monthlyCount[10] !!};
        var dec = {!! $monthlyCount[11] !!};
    </script>
    <div id="app-statistics" class="row">
        <div class="col s12">
            <div class="valign-wrapper row">
                <label for="stats-selection-buttons">Chart</label>
                <div class="col s6">
                    <div id="stats-selection-buttons" class="v-align col s4">
                        <a class="waves-effect waves-light btn" href="{{route('spectator.statisticsActiveBreeder')}}">Active</a>
                    </div>
                    <div class="v-align col s4">
                        <a class="waves-effect waves-light btn" href="{{route('spectator.statisticsDeletedBreeder')}}">Deleted</a>
                    </div>
                    <div class="v-align col s4">
                        <a class="waves-effect waves-light btn disabled">Blocked</a>
                    </div>
                </div>

                <div class="col s6">
                    {!!Form::open(['route'=>'spectator.statisticsBlockedBreederYear', 'method'=>'GET'])!!}
                    <label for="stats-year">Year</label>
                    <input id="stats-year" type="number" name="year" min="{{ $yearMinMax[0] }}" max="{{ $yearMinMax[1] }}" value="{{ $year }}">
                    <div class="center">
                        <button class="btn waves-effect waves-light" type="submit" name="year-submit">Show</button>
                    </div>
                    {!!Form::close()!!}
                </div>
            </div>
            <canvas id="created_chart_area" width="400" height="250"></canvas>
        </div>
    </div>


@endsection

@section('initScript')
    <script type="text/javascript" src="/js/spectator/statistics.js"></script>
    <script type="text/javascript" src="/js/spectator/statistics_script.js"></script>
@endsection
