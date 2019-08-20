@extends('layouts.default')

@section('title')
    | Account Blocked
@endsection

@section('pageId')
    id="page-account-blocked"
@endsection

@section('navbarHead')
    @if(!Auth::user()->update_profile)
        {{-- Swine Cart --}}
    @endif
@endsection

@section('content')
    <div id="home-page" class="row teal lighten-5" style="height:100vh; margin-bottom:0px;">
        <div class="container">
            {{--  Logo --}}
            <div class="center" style="padding-top:2em; padding-bottom:2em;">
                <img src="/images/logodark.png">
            </div>


            <div class="row">
                <div class="col s12 m12 l12 xl12 center-align">
                    <h4>Account Blocked</h4>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12 l12 xl12 center-align">
                    Check your email for more information about your account
                </div>
            </div>

        </div>
    </div>
@endsection

@section('initScript')

@endsection
