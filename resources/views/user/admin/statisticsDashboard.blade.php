@extends('layouts.adminLayout')

@section('title')
    | Admin
@endsection

@section('pageId')
    id="users-stats-dashboard"
@endsection

@section('header')
    <div class="row">
        <div class="col s4">
            <h4 id='admin-content-panel-header'>Site Statistics</h4>
        </div>
    </div>

@endsection

@section('content')
    <div class="row">
        <div class="col s12 m12 l12">
            <div class="card-panel teal lighten-5 hoverable">
                <div class="row">
                    <div id="statsdash-transact" class="col s7 m7 l7">
                        <div class="row">
                            <div class="col s12 m12 l12 statsdash-panel-title">
                                Users Statistics
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 m12 l12">
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
                <div class="row">
                    <div id="statsdash-productbreakdown" class="col s7 m7 l7">
                        <div class="row">
                            <div class="col s12 m12 l12 statsdash-panel-title">
                                Product Statistics
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 m12 l12">
                                Product Breakdown
                                <div class="row center">
                                    <div class="col s12 m12 l12">
                                        <div class="statsdash-data truncate">
                                            100
                                        </div>
                                        <div class="statsdash-description">
                                            Boars for sale
                                        </div>
                                    </div>
                                </div>
                                <div class="row center">
                                    <div class="col s12 m12 l12">
                                        <div class="statsdash-data truncate">
                                            50
                                        </div>
                                        <div class="statsdash-description">
                                            Sows for sale
                                        </div>
                                    </div>
                                </div>
                                <div class="row center">
                                    <div class="col s12 m12 l12">
                                        <div class="statsdash-data truncate">
                                            100000
                                        </div>
                                        <div class="statsdash-description">
                                            Semen for sale
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col s5 m5 l5">
                        <div class="row side-div">
                            <div id = "" class="col s12 m12 l12 center tooltipped" data-position="top" data-delay="60" data-tooltip="12 Products added this month">
                                <div class="statsdash-data truncate">
                                    12
                                </div>
                                <div class="statsdash-description">
                                    Products added this month
                                </div>
                            </div>
                        </div>
                        <div class="row side-div center">
                            <div id = "" class="col s12 m12 l12 tooltipped" data-position="top" data-delay="60" data-tooltip="100 Products requested">
                                <div class="statsdash-data truncate">
                                    100
                                </div>
                                <div class="statsdash-description">
                                    Products requested
                                </div>
                            </div>
                        </div>
                        <div class="row side-div center">
                            <div id = "" class="col s12 m12 l12 tooltipped" data-position="top" data-delay="60" data-tooltip="5000 Products sold">
                                <div class="statsdash-data truncate">
                                    5000
                                </div>
                                <div class="statsdash-description">
                                    Products sold
                                </div>
                            </div>
                        </div>
                        <div class="row side-div center">
                            <div class="statsdash-link" class="col s12 m12 l12">
                                <a href="#">More Details</a>
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
                                    <h1>No Happenings at the Moment</h1>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="row right-align">
                    <div class="col s12 m12 l12">
                        <a href="#">More Details</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('initScript')
    <script type="text/javascript" src="/js/admin/statsDashboard.js"></script>
    <script type="text/javascript" src="/js/admin/statsDashboard_script.js"></script>
@endsection
