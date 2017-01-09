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
                <div style="width:75%;">
                    <canvas id="lineChartTest">
                        {!! $charts[0]->render() !!}
                    </canvas>
                </div>
                <div style="width:75%;">
                    <canvas id="barChartTest">
                        {!! $charts[1]->render() !!}
                    </canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('initScript')
    <script type="text/javascript" src="/js/admin/admin_custom.js"></script>
    <script type="text/javascript" src="/js/admin/users.js"></script>
    <script type="text/javascript" src="/js/admin/manageUsers_script.js"></script>
    <script type="text/javascript" src="/js/admin/pages.js"></script>
    <script type="text/javascript" src="/js/admin/managePages_script.js"></script>
@endsection
