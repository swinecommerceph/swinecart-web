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
      <div class="card green darken-1">
        <div class="card-content white-text">
          <span class="card-title">Thank you for Registering!</span>
          <p>Note that only accredited breeders are allowed to register.</p>
          <p>Please now wait for email the Administrator of SwineCart to approve your account.</p>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col s12 m6 offset-m3">
      <div class="card-content">
        <span class="card-title"></span>
        
      </div>
      <div class="card-action">
        {{ link_to('/', 'Home') }}
      </div>
    </div>
  </div>
@endsection