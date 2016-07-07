{{--
    Displays Account Deletion Notification
--}}
@extends('layouts.messageOneColumn')

@section('title')
    - Email Sent
@endsection

@section('content')
    <div class="row">
        <div class="col s12 m6 offset-m3">
            @if($type == 'deleted')
                <div class="card green darken-1">
                    <div class="card-content white-text">
                      <span class="card-title">Deleted</span>
                      <p>
                          Account Deleted
                      </p>
                    </div>
                </div>
            @elseif($type == 'blocked')
                <div class="card green darken-1">
                    <div class="card-content white-text">
                      <span class="card-title">Blocked</span>
                      <p>
                        Account Blocked
                      </p>
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection
