@extends('layouts.adminLayout')

@section('title')
    | Admin
@endsection

@section('pageId')
    id="totaltransactions-stats-page"
@endsection

@section('header')
    <div class="valign-wrapper row">
        <div class="valign center-block col s12 m12 l6">
            <h4 id='admin-content-panel-header'>Transaction Statistics</h4>
        </div>
        <div class="valign center-block col s12 m12 l6">
            <div class="input-field col s12">
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
    <div class="row valign-wrapper">
        <div class="col s12 m6 l6 valign">
            <select onChange="window.location.href=this.value">
                <option value="{{route('admin.statistics.transactions')}}">Monthly Transactions</option>
                <option selected value="{{route('admin.statistics.totaltransactions')}}">Total Completed Transactions</option>
            </select>
        </div>
        <div class="col s12 m6 l6">
            {!!Form::open(['route'=>'admin.statistics.totaltransactions-year', 'method'=>'GET', 'class'=>'row valign-wrapper'])!!}
                <div class="col s4 m4 l4 valign">
                    <label for="stats-year">From</label>
                    <input type="number" name="minyear" min="{{ $minmaxyear[0] }}" max="{{ $minmaxyear[2] }}" value="{{ $selectedMin }}">
                </div>
                <div class="col s4 m4 l4 valign">
                    <label for="stats-year">To</label>
                    <input type="number" name="maxyear" min="{{ $minmaxyear[2] }}" max="{{ $minmaxyear[1] }}" value="{{ $selectedMax }}">
                </div>
                <div class="col s4 m4 l4 valign">
                    <button class="btn waves-effect waves-light" type="submit" name="action">Select</button>
                </div>
            {!!Form::close()!!}
        </div>
    </div>
    <div class="row">
        <script type="text/javascript">
            var yearLabel = new Array();
            var counts = new Array();
        </script>
        @foreach ($showTransactions as $transactions)
            <script type="text/javascript">
                yearLabel.push({!! $transactions->year !!});
                counts.push({!! $transactions->count !!});
            </script>
        @endforeach
        <div class="col s12 m12 l12">
            <canvas id="admin-totaltransaction-page-chart" width="500" height="350"></canvas>
        </div>
    </div>
@endsection

@section('initScript')

    <script type="text/javascript" src="/js/admin/totaltransaction_script.js"></script>
@endsection
