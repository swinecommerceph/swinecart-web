@extends('layouts.adminNotificationsLayout')

@section('title')
    - SwineCart Account Notification
@endsection

@section('header')
    <div class="row">
        <div class="col s12 m12 l12 xl12">
            @if ($type == 0)
                <h1>Account Blocked</h1>
            @elseif ($type == 1)
                <h1>Account Unblocked</h1>
            @elseif ($type == 2)
                <h1>Account Deleted</h1>
            @elseif ($type == 3)
                <h1>Breeder Application Accepted</h1>
            @elseif ($type == 4)
                <h1>Breeder Application Rejected</h1>
            @endif
        </div>
    </div>
    <hr>
@endsection

@section('content')
    <div class="row">
        <div class="col s12 m12 l12 xl12">
            @if ($type == 0)
                <p>Dear {{$user->name}},</p>
                <p>Your account <em><strong>{{$user->email}}</em></strong> has been <strong>blocked</strong>.</p>
                <p>Reason: <em><strong>{{$user->block_reason}}</strong></em>.</p>
                <p>Please contact the site adminitstrator for more details and how to resolve this <em><strong>swinecommerceph@gmail.com</strong></em>.</p>
            @elseif ($type == 1)
                <p>Dear {{$user->name}},</p>
                <p>Your account <em><strong>{{$user->email}}</em></strong> has been <strong>unblocked</strong>.</p>
            @elseif ($type == 2)
                <p>Dear {{$user->name}},</p>
                <p>Your account <em><strong>{{$user->email}}</em></strong> has been <strong>deleted</strong>.</p>
                <p>Reason: <em><strong>{{$user->delete_reason}}</strong></em>.</p>
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
