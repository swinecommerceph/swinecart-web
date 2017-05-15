@extends('layouts.adminNotificationsLayout')

@section('title')
    - SwineCart Breeder Account Expiration
@endsection

@section('header')
    <div class="row">
        <div class="col s12 m12 l12 xl12">
            @if ($type == 0)
                <h1>Breeder Accreditation Expiration</h1>
            @elseif ($type == 1)
                <h1>Breeder Accreditation Expiration</h1>
            @endif
        </div>
    </div>
    <hr>
@endsection

@section('content')
    <div class="row">
        <div class="col s12 m12 l12 xl12">
            @if ($type == 0)
                <p>Dear {{$username}},</p>
                <p>Your account <em><strong>{{$email}}</em></strong> accreditation will expire on <strong>{{$expiration}}</strong>.</p>
                <p>Please consider renewing your accreditation as soon as possible</p>
            @elseif ($type == 1)
                <p>Dear {{$username}},</p>
                <p>Your account <em><strong>{{$email}}</em></strong> accreditation will expire on <strong>{{$expiration}}</strong>.</p>
                <p>Please consider renewing your accreditation as soon as possible</p>
            @elseif ($type == 2)
                <p>Dear {{$username}},</p>
                <p>Your account <em><strong>{{$email}}</em></strong> has been temporarily blocked due to expired breeder accreditation.</p>
                <p>Please consider renewing your accreditation to continue using our services. Thank you</p>
            @endif

        </div>
    </div>
@endsection
