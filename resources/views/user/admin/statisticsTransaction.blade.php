@extends('layouts.adminLayout')

@section('title')
    | Admin
@endsection

@section('pageId')
    id="transactions-stats-page"
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
                </select>
                <label>Display Statistics</label>
            </div>
        </div>
    </div>

@endsection

@section('content')
    <div class="row valign-wrapper">
        <div class="col s12 m6 l6 valign">
            Monthly Transactions
        </div>
        <div class="col s12 m6 l6">
            {!!Form::open(['route'=>'admin.statistics.transactions-date', 'method'=>'GET', 'class'=>'row valign-wrapper'])!!}
                <div class="col s8 m8 l8 valign">
                    <label for="stats-year">Year</label>
                    <input type="number" name="year" min="2000" max="{{ $year }}" value="{{ $year }}">
                </div>
                <div class="col s4 m4 l4 valign">
                    <button class="btn waves-effect waves-light" type="submit" name="action">Select</button>
                </div>
            {!!Form::close()!!}
        </div>
    </div>
    <div class="row">
        <div class="col s12 m12 l12">
            <canvas id="admin-transaction-page-chart" width="500" height="350"></canvas>
        </div>
    </div>
@endsection

@section('initScript')
    <script type="text/javascript">
        var jan = {!! $transactions[0] !!};
        var feb = {!! $transactions[1] !!};
        var march = {!! $transactions[2] !!};
        var april = {!! $transactions[3] !!};
        var may = {!! $transactions[4] !!};
        var june = {!! $transactions[5] !!};
        var july = {!! $transactions[6] !!};
        var aug = {!! $transactions[7] !!};
        var sept = {!! $transactions[9] !!};
        var oct = {!! $transactions[9] !!};
        var nov = {!! $transactions[10] !!};
        var dec = {!! $transactions[11] !!};
    </script>
    <script type="text/javascript" src="/js/admin/transaction_script.js"></script>
@endsection
