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
    <div>
        {{-- View for the administrator dashboard --}}
        Sample Text
    </div>
@endsection

@section('initScript')
    <script type="text/javascript" src="/js/admin/statistics.js"></script>
    <script type="text/javascript" src="/js/admin/statistics_script.js"></script>
@endsection
