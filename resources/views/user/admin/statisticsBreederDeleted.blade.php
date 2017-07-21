@extends('layouts.controlLayout')

@section('title')
    | Site Statistics: Breeder Statistics
@endsection

@section('pageId')
    id="admin-site-statistics-deleted-statistics-breeder"
@endsection

@section('nav-title')
    Site Statistics
@endsection

@section('pageControl')
    <div class="valign-wrapper row">
        <div class="valign center-block col s5 m5 l5 xl5">
            <h4 id='admin-content-panel-header'>Breeder Statistics</h4>
        </div>
        <div class="valign center-block col s7 m7 l7 xl7">
            <div class="input-field col s12 m12 l12 xl12">
                <select onChange="window.location.href=this.value">
                    <option disabled selected>Choose option</option>
                    <option value="{{route('admin.statistics.breeder.active')}}">Breeder</option>
                    <option value="{{route('admin.statistics.customer.active')}}">Customer</option>
                    <option value="{{route('admin.statistics.transactions')}}">Transactions</option>
                    <option value="{{route('admin.statistics.timeline')}}">Logs Timeline</option>
                    <option value="{{route('admin.statistics.averageNewBreeder')}}">Average Values</option>
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
        <div class="col s12 m12 l12 xl12">
            <div class="valign-wrapper row">
                <div class="input-field col s12 m12 l6 xl6 valign">
                        <select onChange="window.location.href=this.value">
                            <option disabled selected>Choose option</option>
                            <option value="{{route('admin.statistics.breeder.active')}}">Registered Breeders</option>
                            <option selected value="{{route('admin.statistics.breeder.deleted')}}">Deleted Breeders</option>
                            <option value="{{route('admin.statistics.breeder.blocked')}}">Blocked Breeders</option>
                            <option value="{{route('admin.statistics.breeder.logincount')}}">Active Breeders</option>
                        </select>
                    <label>Breeder Chart</label>
                </div>
                <div class="col s12 m12 l6 xl6 valign">
                    {!!Form::open(['route'=>'admin.statistics.breeder.deleted-year', 'method'=>'GET', 'class'=>'valign-wrapper'])!!}
                        <div class="col s12 m8 l8 xl8">
                            <label for="stats-year">Year</label>
                            <input id="stats-year" type="number" name="year" min="{{ $yearMinMax[0] }}" max="{{ $yearMinMax[1] }}" value="{{ $year }}">
                        </div>

                        <div class=" col s12 m4 l4 xl4 valign">
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
