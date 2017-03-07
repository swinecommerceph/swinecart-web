@extends('layouts.adminLayout')

@section('title')
    | Admin
@endsection

@section('pageId')
    id="breeder-active-stats-page"
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
                    <option value="{{route('admin.statistics.breeder.active')}}">Breeder</option>
                    <option value="{{route('admin.statistics.customer.active')}}">Customer</option>
                    <option value="3">Transactions</option>
                    <option value="3">Logs Timeline</option>
                </select>
                <label>Display Statistics</label>
            </div>
        </div>
    </div>

@endsection

@section('content')
    {{-- Accept the count per month and save it to a javascript variable for chart use --}}
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
    <div id="app-statistics" class="row">
        <div class="col s12">
            <div class="valign-wrapper row">
                <label for="stats-selection-buttons">Chart</label>
                <div class="col s6">
                    <div id="stats-selection-buttons" class="v-align col s4">
                        <a class="waves-effect waves-light btn disabled">Active</a>
                    </div>
                    <div class="v-align col s4">
                        <a class="waves-effect waves-light btn" href="{{route('admin.statistics.breeder.deleted')}}">Deleted</a>
                    </div>
                    <div class="v-align col s4">
                        <a class="waves-effect waves-light btn" href="{{route('admin.statistics.breeder.blocked')}}">Blocked</a>
                    </div>
                </div>

                <div class="col s6">
                    {!!Form::open(['route'=>'admin.statistics.breeder.active-year', 'method'=>'GET'])!!}
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
    <script type="text/javascript" src="/js/admin/statistics.js"></script>
    <script type="text/javascript" src="/js/admin/statistics_script.js"></script>
@endsection
