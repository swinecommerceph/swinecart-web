@extends('layouts.adminLayout')

@section('title')
    | Admin
@endsection

@section('pageId')
    id="users-stats-timeline"
@endsection

@section('header')
    <div class="row valign-wrapper">
        <div class="col s12 m6 l6">
            <h4 id='admin-content-panel-header'>Logs Timeline</h4>
        </div>
        {!!Form::open(['route'=>'admin.statistics.timeline-date', 'method'=>'GET', 'class'=>'col s12 m6 l6 valign-wrapper'])!!}
        <div class="col s6 m8 l8 valign">
            <label for="timeline-datepicker">Choose date</label>
            <input id="timeline-datepicker" type="date" class="datepicker" name="date"></input>
        </div>
        <div class="col s6 m4 l4 valign">
            <button class="btn waves-effect waves-light" type="submit" name="action">Submit</button>
        </div>
        {!!Form::close()!!}
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col s12 l12 m12">
            <h5>{{$dateNow}}</h5>
        </div>
    </div>
    <div class="row" id="timeline-container">
        <div class="col s12 m12 l12">
            <ul class="cbp_tmtimeline">
                @forelse ($adminLogs as $logs)
                    <li>
                        <time class="cbp_tmtime">
                            {{-- <span>4/10/13</span>  --}}
                            <span>{{$logs->created_at}}</span>
                        </time>
                        <div class="cbp_tmicon"></div>
                        <div class="cbp_tmlabel">
                            <h5>{{$logs->category}}</h5>
                            <p>{{$logs->action}}</p>
                            <p class="right-align timeline-performed">Performed by: {{$logs->admin_name}}</p>
                        </div>
                    </li>
                @empty
                    <li>
                        <time class="cbp_tmtime">
                            {{-- <span>4/10/13</span>  --}}
                            <span>00:00:00</span>
                        </time>
                        <div class="cbp_tmicon"></div>
                        <div class="cbp_tmlabel">
                            <h5>No happenings in this day</h5>
                            <p></p>
                        </div>
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection

@section('initScript')

@endsection
