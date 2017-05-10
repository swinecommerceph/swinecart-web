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
            <h4 id='admin-content-panel-header'>Customer Statistics</h4>
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
    <div class="row">
        <div class="col s12">
            <div class="valign-wrapper row">
                <div class="input-field col s12 m12 l6 xl6">
                    <select onChange="window.location.href=this.value">
                        <option value="{{route('spectator.statisticsActiveCustomer')}}">Active Customers</option>
                        <option selected value="{{route('spectator.statisticsBlockedCustomer')}}">Blocked Customers</option>
                        <option value="{{route('spectator.statisticsDeletedCustomer')}}">Deleted Customers</option>
                        <option {{$select[0]}} value="{{route('spectator.averageCustomerStatisticsCreated')}}">Average Monthly Customers Created</option>
                        <option {{$select[1]}} value="{{route('spectator.averageCustomerStatisticsBlocked')}}">Average Monthly Customers Blocked</option>
                        <option {{$select[2]}} value="{{route('spectator.averageCustomerStatisticsDeleted')}}">Average Monthly Customers Deleted</option>
                    </select>
                    <label>Chart</label>
                </div>
                <div class="col s12 m12 l6 xl6">

                    {!!Form::open(['route'=>$formroute, 'method'=>'GET', 'class'=>'row valign-wrapper'])!!}
                        <div class="input-field col s4 m4 l4 xl4">
                            <input id="from" type="number" name="yearmin" min="{{ $year[0] }}" max="{{ $year[1] }}" value="{{ $year[0] }}">
                            <label for="from">From</label>
                        </div>
                        <div class="input-field col s4 m4 l4 xl4">
                            <input id="to" type="number" name="yearmax" min="{{ $year[1] }}" max="{{ $year[2] }}" value="{{ $year[2] }}">
                            <label for="to">To</label>
                        </div>
                        <div class="col s4 m4 l4 xl4 valign">
                            <button class="btn waves-effect waves-light" type="submit" name="select">Select</button>
                        </div>
                    {!!Form::close()!!}

                </div>
            </div>
            <script type="text/javascript">
                var yearLabel = new Array();
                var counts = new Array();
                var index=0;
            </script>
            @foreach ($averageCount as $count)
                <script type="text/javascript">
                    // since index for the year is not known get the last year input then add an index value since we know that the count array will have the exact number of years to the max input
                    yearLabel.push({!! $year[0] !!}+index);
                    counts.push({!! $count !!});
                    index++;
                </script>
            @endforeach
            <canvas id="average_chart_area" width="400" height="250"></canvas>
        </div>
    </div>
@endsection

@section('initScript')
    <script type="text/javascript" src="/js/spectator/average_script.js"></script>
@endsection
