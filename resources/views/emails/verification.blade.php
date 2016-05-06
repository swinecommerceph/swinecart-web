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
