{{--
    Displays Home Page of the E-Commerce website
--}}
@extends('layouts.errorlayout')

@section('title')
    | Site Not Available at the Moment
@endsection


@section('homeContent')
    <div id="home-page" class="row teal lighten-5" style="height:100vh; margin-bottom:0px;">
        <div class="container">
            {{--  Logo --}}
            <div class="center" style="padding-top:2em; padding-bottom:2em;">
                <img src="/images/logodark.png">
            </div>


            <div class="row">
                <div class="col s12 m12 l12 xl12 center-align">
                    <h4>Site Under Maintenance</h4>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12 l12 xl12 center-align">
                    Please come back later
                </div>
            </div>

        </div>
    </div>
@endsection
