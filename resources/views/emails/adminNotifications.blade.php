@extends('layouts.adminNotificationsLayout')

@section('title')
    - SwineCart Account Notification
@endsection

@section('header')
    <div class="row">
        <div class="col s12 m12 l12 xl12">
            @if ($type == 0)
                <h4>Account Blocked<h4>
            @elseif ($type == 1)
                <h4>Account Unblocked<h4>
            @elseif ($type == 2)
                <h4>Account Deleted<h4>
            @elseif ($type == 3)
                <h4>Breeder Application Accepted<h4>
            @elseif ($type == 4)
                <h4>Breeder Application Rejected<h4>
            @endif
        </div>
    </div>
    <div class="divider"></div>
@endsection

@section('content')
    <div class="row">
        <div class="col s12 m12 l12 xl12">
            @if ($type == 0)
                <p>Dear {{$user->name}},</p>
                <p>Your account <em><strong>{{$user->email}}</em></strong> has been <strong>blocked</strong>.</p>
                <p>Please contact the site adminitstrator for more details and how to resolve this <em><strong>swinecommerceph@gmail.com</strong></em>.</p>
            @elseif ($type == 1)
                <p>Dear {{$user->name}},</p>
                <p>Your account <em><strong>{{$user->email}}</em></strong> has been <strong>unblocked</strong>.</p>
            @elseif ($type == 2)
                <p>Dear {{$user->name}},</p>
                <p>Your account <em><strong>{{$user->email}}</em></strong> has been <strong>deleted</strong>.</p>
                <p>Please contact the site adminitstrator for more details at <em><strong>swinecommerceph@gmail.com</strong></em>.</p>
            @elseif ($type == 3)
                <p>Dear {{$user->name}},</p>
                <p>Your breeder application has been <strong>accepted</strong>.</p>
            @elseif ($type == 4)
                <p>Dear {{$user->name}},</p>
                <p>Your breeder application has been <strong>rejected</strong>.</p>
                <p>Please contact the site adminitstrator for more details at <em><strong>swinecommerceph@gmail.com</strong></em>.</p>
            @endif
        </div>
    </div>
@endsection
