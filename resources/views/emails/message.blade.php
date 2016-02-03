{{--
    Displays Success message on the email sent
--}}

@extends('layouts.messageOneColumn')

@section('title')
    - Email Sent
@endsection

@section('content')
    <div class="row">
        <div class="col s12 m6 offset-m3">
            @if($type == 'sent')
                <div class="card green darken-1">
                    <div class="card-content white-text">
                      <span class="card-title">Success</span>
                      <p>
                          Verification code sent to your email: {{ " ".$email }}<br>
                          Please check your email.
                      </p>
                    </div>
                </div>
            @elseif($type == 'resent')
                <div class="card green darken-1">
                    <div class="card-content white-text">
                      <span class="card-title">Verification Code Resend</span>
                      <p>
                          Verification code resent to your email: {{ " ".$email }}<br>
                          Please check your email.
                      </p>
                    </div>
                </div>
            @elseif($type == 'verify')
                <div class="card deep-orange">
                    <div class="card-content white-text">
                      <span class="card-title">Verify Email</span>
                      <p>
                          Please verify your email first. <br>
                          Check your email for the verification code.
                      </p>
                    </div>
                </div>

            @endif

        </div>
    </div>

    <div class="row">
        <div class="col s12 m6 offset-m3">
            <div class="card">
                <div class="card-content">
                  <span class="card-title"></span>
                  <p>
                      Email verification not received yet? <br>
                      Click the "Resend" link below to resend verification code.
                  </p>
                </div>
                <div class="card-action">
                  {{ link_to_route('verCode.resend', 'Resend', ['email' => $email, 'verCode' => $verCode]) }}
                  {{ link_to('/', 'Home') }}
                </div>
            </div>
        </div>
    </div>
@endsection
