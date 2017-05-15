{{--
    Displays Home Page of the E-Commerce website
--}}
@extends('layouts.errorlayout')

@section('title')
    | Site Not Available at the Moment
@endsection


@section('homeContent')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12 xl12">
                <h3>Error: 503 (Service Unavailable)</h3>
            </div>
            <div id="error-message" class="col s12 m12 l12 xl12">
                Site not available for the time being. Please check again later.
            </div>
        </div>
    </div>
@endsection
