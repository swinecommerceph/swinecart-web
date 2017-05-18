@extends('layouts.controlLayout')

@section('title')
    | Site Statistics: Statistics Dashboard
@endsection

@section('pageId')
    id="admin-site-statistics"
@endsection

@section('nav-title')
    Site Statistics
@endsection

@section('pageControl')

@endsection

{{-- @section('header')
    <div class="row">
        <div class="col s4">
            <h4 id='admin-content-panel-header'>Site Statistics</h4>
        </div>
    </div>

@endsection --}}

@section('content')
    <div class="row">
        <div class="col s12 m12 l12">
            <div class="card-panel teal lighten-5 hoverable">
                <div class="row">
                    <div class="col s12 m12 l12 statsdash-panel-title">
                        Users Statistics
                    </div>
                </div>
                <div class="row">
                    <div id="statsdash-transact" class="col s7 m7 l7">
                        <div class="row">
                            <div class="col s12 m12 l12">
                                Active Users in the last 5 months
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 m12 l12">
                                <script type="text/javascript">
                                    var monthlabel = new Array();
                                    var countdata = new Array();
                                </script>
                                @foreach ($monthlyCount as $count)
                                    <script type="text/javascript">
                                        countdata.push({!! $count !!});
                                    </script>
                                @endforeach
                                @foreach ($monthNames as $name)
                                    <script type="text/javascript">
                                         monthlabel.push("{!! $name !!}");
                                    </script>
                                @endforeach
                                <canvas id="dash-transaction-chart" class="col s12 m12 l12" width="400" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col s5 m5 l5">
                        <div class="row side-div">
                            <div id = "statsdash-del" class="col s12 m12 l12 center tooltipped" data-position="top" data-delay="60" data-tooltip="{{$stats[0]}} Users Deleted this month">
                                <div class="statsdash-data truncate">
                                    {{$stats[0]}}
                                </div>
                                <div class="statsdash-description">
                                    Users Deleted this month
                                </div>
                            </div>
                        </div>
                        <div class="row side-div center">
                            <div id = "statsdash-block" class="col s12 m12 l12 tooltipped" data-position="top" data-delay="60" data-tooltip="{{$stats[1]}} Users Blocked this month">
                                <div class="statsdash-data truncate">
                                    {{$stats[1]}}
                                </div>
                                <div class="statsdash-description">
                                    Users Blocked this month
                                </div>
                            </div>
                        </div>
                        <div class="row side-div center">
                            <div id = "statsdash-new" class="col s12 m12 l12 tooltipped" data-position="top" data-delay="60" data-tooltip="{{$stats[2]}} New users this month">
                                <div class="statsdash-data truncate">
                                    {{$stats[2]}}
                                </div>
                                <div class="statsdash-description">
                                    New users this month
                                </div>
                            </div>
                        </div>
                        <div class="row side-div center">
                            <div class="statsdash-link" class="col s12 m12 l12">
                                <a href="{{route('admin.statistics.breeder.active')}}">More Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col s12 m12 l12">
            <div class="card-panel indigo lighten-5 hoverable">
                <div class="row valign-wrapper">
                    <div class="col s12 m12 l12 valign">
                        <div class="row">
                            <div class="col s12 m12 l12 statsdash-panel-title">
                                Product Statistics
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 m12 l12">
                                <div class="row">
                                    <div class="col s12 m12 l12">
                                        Product Breakdown
                                    </div>
                                </div>
                                <div class="row valign-wrapper">
                                    <div class="col s12 m12 l4 center-align valign">
                                        <div class="row">
                                            <div class="col s12 m12 l12 statsdash-description">
                                                Total Products
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col s12 m12 l12 statsdash-data truncate tooltipped" data-position="bottom" data-delay="50" data-tooltip="{{$stats[4]}}">
                                                {{$stats[4]}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col s12 m12 l8 center">
                                        <canvas id="admin-product-breakdown-chart" class="col s12 m12 l12" width="400" height="300"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="row">
                            <div class="col s12 m12 l12">
                                <div class="row">
                                    <div class="col s6 m6 l6">
                                        <a href="#">Product Breakdown</a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s12 m12 l12 center-align">
                                        <div class="row">
                                            <div class="col s12 m12 l12 statsdash-description">
                                                Total Products
                                            </div>
                                                <div class="statsdash-data truncate tooltipped col s12 m12 l12" data-position="bottom" data-delay="50" data-tooltip="{{$stats[4]}}">
                                                {{$stats[4]}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s6 m6 l6 center-align">
                                        <div class="row">
                                            <div class="col s12 m12 l12 statsdash-description">
                                                Boar
                                            </div>
                                            <div class="statsdash-data truncate tooltipped col s12 m12 l12" data-position="bottom" data-delay="50" data-tooltip="{{$stats[5]}}">
                                                {{$stats[5]}}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col s12 m12 l12 statsdash-description">
                                                Gilt
                                            </div>
                                            <div class="statsdash-data truncate tooltipped col s12 m12 l12" data-position="bottom" data-delay="50" data-tooltip="{{$stats[6]}}">
                                                {{$stats[6]}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col s6 m6 l6 center-align">
                                        <div class="row">
                                            <div class="col s12 m12 l12 statsdash-description">
                                                Sow
                                            </div>
                                            <div class="statsdash-data truncate tooltipped col s12 m12 l12" data-position="bottom" data-delay="50" data-tooltip="{{$stats[7]}}">
                                                {{$stats[7]}}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col s12 m12 l12 statsdash-description">
                                                Semen
                                            </div>
                                            <div class="statsdash-data truncate tooltipped col s12 m12 l12" data-position="bottom" data-delay="50" data-tooltip="{{$stats[8]}}">
                                                {{$stats[8]}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <div class="row">
                            <div class="col s12 m12 l12">
                                <div class="row">
                                    <div class="col s12 m12 l12">
                                        Transaction Breakdown
                                    </div>
                                </div>
                                <div class="row center-align">
                                    <div class="col s12 m12 l12">
                                        <div class="row">
                                            <div class="col s12 m12 l12 statsdash-description">
                                                 <span class="tooltipped" data-position="right" data-delay="50" data-tooltip="This month">Total Transactions</span>
                                            </div>
                                            <div class="statsdash-data truncate tooltipped col s12 m12 l12" data-position="bottom" data-delay="50" data-tooltip="{{$stats[9]}}">
                                                {{$stats[9]}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row center-align">
                                    <div class="col s12 m12 l12">
                                        <div class="col s4 m4 l4">
                                            <div class="row">
                                                <div class="col s12 m12 l12 statsdash-description">
                                                     Requested
                                                </div>
                                                <div class="statsdash-data truncate tooltipped col s12 m12 l12" data-position="bottom" data-delay="50" data-tooltip="{{$stats[10]}}">
                                                    {{$stats[10]}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col s4 m4 l4">
                                            <div class="row">
                                                <div class="col s12 m12 l12 statsdash-description">
                                                     Reserved
                                                </div>
                                                <div class="statsdash-data truncate tooltipped col s12 m12 l12" data-position="bottom" data-delay="50" data-tooltip="{{$stats[11]}}">
                                                    {{$stats[11]}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col s4 m4 l4">
                                            <div class="row">
                                                <div class="col s12 m12 l12 statsdash-description">
                                                     Paid
                                                </div>
                                                <div class="statsdash-data truncate tooltipped col s12 m12 l12" data-position="bottom" data-delay="50" data-tooltip="{{$stats[12]}}">
                                                    {{$stats[12]}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col s12 m12 l12">
                                        <div class="col s6 m6 l6">
                                            <div class="row">
                                                <div class="col s12 m12 l12 statsdash-description">
                                                    On Delivery
                                                </div>
                                                <div class="statsdash-data truncate tooltipped col s12 m12 l12" data-position="bottom" data-delay="50" data-tooltip="{{$stats[13]}}">
                                                    {{$stats[13]}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col s6 m6 l6">
                                            <div class="row">
                                                <div class="col s12 m12 l12 statsdash-description">
                                                    Sold
                                                </div>
                                                <div class="statsdash-data truncate tooltipped col s12 m12 l12" data-position="bottom" data-delay="50" data-tooltip="{{$stats[14]}}">
                                                    {{$stats[14]}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 m12 l12 right-align">
                                <a href="{{route('admin.statistics.transactions')}}">More Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col s12 m12 l12">
            <div class="card-panel orange lighten-5 hoverable">
                <div class="row">
                    <div class="col s12 m12 l12 statsdash-panel-title">
                        Logs Timeline
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m12 l12">
                        Yesterday's Activity
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m12 l12">
                        <div class="bar"></div>
                        <div class="timeline">
                            @forelse ($stats[3] as $log)
                                <div class="entry">
                                    <h1>{{$log->created_at}}</h1>
                                    {{$log->admin_name." ".$log->action}}
                                </div>
                            @empty
                                <div class="entry">
                                    <h1>No Events Happened</h1>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="row right-align">
                    <div class="col s12 m12 l12">
                        <a href="{{route('admin.statistics.timeline')}}">More Details</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('initScript')
    <script type="text/javascript">
        var boar = {!! $stats[5] !!};
        var gilt = {!! $stats[6] !!};
        var sow = {!! $stats[7] !!};
        var semen = {!! $stats[8] !!};
    </script>
    <script type="text/javascript" src="/js/admin/statsDashboard.js"></script>
    <script type="text/javascript" src="/js/admin/statsDashboard_script.js"></script>
@endsection
