@extends('layouts.adminNotificationsLayout')

@section('title')
    - SwineCart Product Notification
@endsection

@section('header')
    <div class="row">
        <div class="col s12 m12 l12 xl12">
            @if ($type == 0)
                <h4>Product Needed<h4>
            @elseif ($type == 1)
                <h4>Product Reservation Expiring<h4>
            @endif
        </div>
    </div>
    <div class="divider"></div>
@endsection

@section('content')
    <div class="row">
        <div class="col s12 m12 l12 xl12">
            @if ($type == 0)
                <p>The product <strong>{{ $product }}</strong> is needed by <strong>{{ $user->name }}</strong> on {{ $information->date_needed }}</p>
                <p>The customer's special request: <em>{{ $information->special_request }}</em></p>
                <p>Please attend to the customer's request as soon as possible</p>
            @elseif ($type == 1)
                <p>The product <strong>{{ $product }}</strong> reservation to <strong>{{ $user->name }}</strong> will expire on {{ $information->expiration_date }}</p>
                <p>Please attend to the transaction request as soon as possible</p>
            @endif
        </div>
    </div>
@endsection
