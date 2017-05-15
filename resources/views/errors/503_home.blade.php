@extends('layouts.default')

@section('title')
    | | Site Not Available at the Moment
@endsection

@section('pageId')
    id="maintenenace"
@endsection

@section('content')
    <div class="row">
        <div class="col s12 m12 l12 xl12">
            <h3>Error: 503 (Service Unavailable)</h3>
        </div>
        <div id="error-message" class="col s12 m12 l12 xl12">
            Site not available for the time being. Please check again later.
        </div>
    </div>
@endsection

@section('initScript')

@endsection
