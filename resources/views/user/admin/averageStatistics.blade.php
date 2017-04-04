@extends('layouts.adminLayout')

@section('title')
    | Admin
@endsection

@section('pageId')
    id="average-breeder-stats-page"
@endsection

@section('header')
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
                    <select>
                        {{-- <option disabled selected>Choose option</option> --}}
                        <option {{$chartSelector[0]}} value="1">Average Monthly Breeders Created</option>
                        <option {{$chartSelector[1]}} value="2">Average Monthly Breeders Deleted</option>
                        <option {{$chartSelector[2]}} value="2">Average Monthly Breeders Blocked</option>
                    </select>
                    <label>Chart Type</label>
                </div>
                <div class="col s6 m6 l6 xl6">
                    <div class="row">
                        <div class="input-field col s6 m6 l6 xl6">
                            <input id="from" type="number" name="yearmin" min="{{ $yearMinMax[0] }}" max="{{ $yearMinMax[2] }}" value="{{$yearPast}}">
                            <label for="from">From</label>
                        </div>
                        <div class="input-field col s6 m6 l6 xl6">
                            <input id="to" type="number" name="yearmax" min="{{ $yearMinMax[2] }}" max="{{ $yearMinMax[1] }}" value="{{$yearNow}}">
                            <label for="to">To</label>
                        </div>
                    </div>
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
                        yearLabel.push({!! $yearPast !!}+index);
                        counts.push({!!$count !!});
                        index++;
                    </script>
                @endforeach
                <div class="input-field col s12 m12 l12 xl12">
                    <canvas id="average-chart-area" width="400" height="250"></canvas>
                </div>
            </div>

        </div>
    </div>


@endsection

@section('initScript')
    <script type="text/javascript" src="/js/admin/average_script.js"></script>
@endsection
