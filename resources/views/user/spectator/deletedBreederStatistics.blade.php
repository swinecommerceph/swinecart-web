@extends('layouts.newSpectatorLayout')

@section('title')
    | Site Statistics
@endsection

@section('pageId')
    id="app-statistics"
@endsection

@section('nav-title')
    Site Statistics
@endsection

@section('pageControl')
    <div class="valign-wrapper row">
        <div class="valign center-block col s5 m5 l5 xl5">
            <h4 id='admin-content-panel-header'>Breeder Statistics</h4>
        </div>
        <div class="valign center-block col s7 m5 l5 xl5">
            <div class="input-field col s12 m12 l12 xl12">
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
    {{-- <div id="app-statistics" class="card-panel"> --}}
        <div class="row">
            <div class="col s12">
                <div class="valign-wrapper row">
                    <div class="input-field col s12 m12 l6 xl6">
                        <select onChange="window.location.href=this.value">
                            <option value="{{route('spectator.statisticsActiveBreeder')}}">Active Breeders</option>
                            <option value="{{route('spectator.statisticsBlockedBreeder')}}">Blocked Breeders</option>
                            <option selected value="{{route('spectator.statisticsDeletedBreeder')}}">Deleted Breeders</option>
                        </select>
                        <label>Chart</label>
                    </div>
                    <div class="col s12 m12 l6 xl6">
                        {!!Form::open(['route'=>'spectator.statisticsDeletedBreederYear', 'method'=>'GET', 'class'=>'row valign-wrapper'])!!}
                        <div class="col s12 m8 l9 xl9">
                            <label for="stats-year">Year</label>
                            <input id="stats-year" type="number" name="year" min="{{ $yearMinMax[0] }}" max="{{ $yearMinMax[1] }}" value="{{ $year }}">
                        </div>

                        <div class="col s12 m4 l3 xl3 center">
                            <button class="btn waves-effect waves-light" type="submit" name="year-submit">Show</button>
                        </div>
                        {!!Form::close()!!}
                    </div>
                </div>
                <canvas id="created_chart_area" width="400" height="250"></canvas>
            </div>
        </div>
    {{-- </div> --}}

@endsection

@section('initScript')
    <script type="text/javascript" src="/js/spectator/statistics.js"></script>
    <script type="text/javascript" src="/js/spectator/statistics_script.js"></script>
@endsection
