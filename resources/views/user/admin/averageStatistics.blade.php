@extends('layouts.controlLayout')

@section('title')
    | Site Statistics: Average Statistics
@endsection

@section('pageId')
    id="admin-site-statistics-average"
@endsection

@section('nav-title')
    Site Statistics
@endsection

@section('pageControl')
    <div class="valign-wrapper row">
        <div class="valign center-block col s5 m5 l5 xl5">
            <div class="row">
                <div class="col s12 m12 l12 xl12">
                    <h4 id='admin-content-panel-header'>Average Statistics</h4>
                </div>
            </div>
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
            <div class="input-field col s12 m12 l12 xl12">
                <select onChange="window.location.href=this.value">
                    <option {{$roleSelector[0]}} value="{{route('admin.statistics.averageNewBreeder')}}">Breeder</option>
                    <option {{$roleSelector[1]}} value="{{route('admin.statistics.averageNewCustomers')}}">Customer</option>
                </select>
                <label>User Type</label>
            </div>
        </div>
    </div>

@endsection

@section('content')
    {{-- Accept the count per month and save it to a javascript variable for chart use --}}

    <div id="app-statistics" class="row">
        <div class="col s12">
            <div class="row">
                <div class="input-field col s6 m6 l6 xl6">
                    <select onChange="window.location.href=this.value">
                        {{-- <option disabled selected>Choose option</option> --}}
                        <option {{$chartSelector[0]}} value="{{route($chartRoute[0])}}">Average Monthly Created</option>
                        <option {{$chartSelector[1]}} value="{{route($chartRoute[1])}}">Average Monthly Blocked</option>
                        <option {{$chartSelector[2]}} value="{{route($chartRoute[2])}}">Average Monthly Deleted</option>
                    </select>
                    <label>Chart Type</label>
                </div>
                <div class="col s6 m6 l6 xl6">
                    {{-- <div class="row valign-wrapper"> --}}
                        {!!Form::open(['route'=>$route, 'method'=>'GET', 'class'=>'row valign-wrapper'])!!}
                            <div class="input-field col s4 m4 l4 xl4">
                                <input id="from" type="number" name="yearmin" min="{{ $yearMinMax[0] }}" max="{{ $yearMinMax[2] }}" value="{{$yearminimum}}">
                                <label for="from">From</label>
                            </div>
                            <div class="input-field col s4 m4 l4 xl4">
                                <input id="to" type="number" name="yearmax" min="{{ $yearMinMax[2] }}" max="{{ $yearMinMax[1] }}" value="{{$yearmaximum}}">
                                <label for="to">To</label>
                            </div>
                            <div class="col s4 m4 l4 xl4 valign">
                                <button class="btn waves-effect waves-light" type="submit" name="select">Select</button>
                            </div>
                        {!!Form::close()!!}
                    {{-- </div> --}}
                </div>
            </div>
            <div class="row">
                <script type="text/javascript">
                    var yearLabel = new Array();
                    var counts = new Array();
                    var index=0;
                </script>
                @foreach ($averageCount as $count)
                    <script type="text/javascript">
                        // since index for the year is not known get the last year input then add an index value since we know that the count array will have the exact number of years to the max input
                        yearLabel.push({!! $yearminimum !!}+index);
                        counts.push({!!$count !!});
                        index++;
                    </script>
                @endforeach
                <div class="col s12 m12 l12 xl12">
                    <canvas id="average-chart-area" width="400" height="250"></canvas>
                </div>
            </div>

        </div>
    </div>


@endsection

@section('initScript')
    <script type="text/javascript" src="/js/admin/average_script.js"></script>
@endsection
