{{--
    Displays Verification code of the user
    which is sent to the User's email
--}}

@extends('layouts.messageOneColumn')

@section('title')
    - Verification Code
@endsection

@section('page-id')
    id="page-verification"
@endsection

{{-- This section will be used in styling the email verification template --}}
{{-- @section('email-css')
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,700" rel="stylesheet" type="text/css">
    <style media="screen">
        html{
            line-height: 1.5;
            font-family: "Roboto", sans-serif;
            font-weight: normal;
            color: rgba(0,0,0,0.87);
            box-sizing: border-box;
            display: block;
        }

        div{
            display: block;
        }

        nav{
            color: #fff;
            background-color: #ee6e73;
            width: 100%;
        }

        .navbar-fixed{
            position: relative;
            height: 56px;
            z-index: 998;
        }

        .navbar-fixed nav {
            position: fixed;
        }

        .teal.darken-3{
            background-color: #00695c !important;
        }

    </style>
@endsection --}}

@section('content')
    <div class="row">
        <div class="col s12 m6 offset-m3">
            <div class="card">
                <div class="card-content">
                  <span class="card-title">Verification Code</span>
                  <p>
                      Registration is almost complete. <br>
                      Below is your verification code to verify your email.
                      Just click on the link below.
                  </p>
                </div>
                <div id="verification-code" class="card-action">
                  {{ link_to_route('verCode.send', $verCode, ['email' => $email, 'verCode' => $verCode]) }}
                </div>
            </div>
        </div>
    </div>

@endsection
