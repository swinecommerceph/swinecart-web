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
                    <div class="col s12 m6 l6">
                        <div class="card-panel teal">
                            <div class="row">
                                <div class="col s12 m12 l12 center-align">
                                    Customers
                                </div>
                                <div class="col s12 m12 l12 center-align">
                                    {{$data[0]}} New
                                </div>
                                <div class=" col s12 m12 l12 center-align">
                                    {{$data[1]}} Deleted
                                </div>
                                <div class=" col s12 m12 l12 center-align">
                                    {{$data[2]}} Blocked
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col s12 m6 l6">
                        <div class="card-panel teal">
                            <span class="white-text">I am a very simple card. I am good at containing small bits of information.
                                I am convenient because I require little markup to use effectively. I am similar to what is called a panel in other frameworks.
                            </span>
                        </div>
                    </div>

                    <div class="col s12 m12 l12">
                        <div class="card-panel teal">
                            <span class="white-text">I am a very simple card. I am good at containing small bits of information.
                                I am convenient because I require little markup to use effectively. I am similar to what is called a panel in other frameworks.
                            </span>
                        </div>
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
