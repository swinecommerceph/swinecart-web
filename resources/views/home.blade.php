{{--
    Displays Home Page of the E-Commerce website
--}}

@extends('layouts.default')

@section('pageId')
    id="page-home"
@endsection

@section('homeContent')
    <div id="home-page" class="row teal lighten-5" style="height:100vh; margin-bottom:0px;">
        <div class="container">
            {{--  Logo --}}
            <div class="center" style="padding-top:2em; padding-bottom:2em;">
                <img src="/images/logodark.png">
            </div>

            {{-- Search bar --}}
            <nav id="search-container" class="row grey ligthen-3" style="opacity:0.8;">
                <div id="search-field" class="nav-wrapper grey lighten-4">
                    <form>
                        <div class="input-field">
                            <input id="search" type="search" placeholder="Search for a product" required>
                            <label for="search"><i class="material-icons teal-text text-darken-3">search</i></label>
                            <i class="material-icons">close</i>
                        </div>
                    </form>
                </div>
            </nav>

            <div class="row">
                <div class="col s4 offset-s4">
                    <a href="{{ route('login') }}" class="waves-effect waves-light btn-large col s12 teal lighten-1" style="font-size:24px;">Login</a>
                </div>
            </div>
            <div class="row">
                <div class="col s4 offset-s4">
                    <a href="{{ url('register') }}" class="waves-effect waves-light btn-large col s12 pink lighten-1" style="font-size:24px;">Register</a>
                </div>
            </div>

        </div>
    </div>
@endsection
