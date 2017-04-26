@extends('layouts.adminNotificationsLayout')

@section('title')
    - SwineCart Notification
@endsection

@section('header')
    <div class="row">
        <div class="col s12 m12 l12 xl12">
            @if ($type == 0)
                <h1>Transaction Cancelled</h1>
            @endif

        </div>
    </div>
    <hr>
@endsection

@section('content')
    <div class="row">
        <div class="col s12 m12 l12 xl12">
            @if ($type == 0)
                <p>
                    Transaction was cancelled due to problems in the availability of the product or the other party that you are trying to transact to.
                </p>
                <p>
                    We are sorry for the inconvenience. Thank you for your understanding and continued support to our services.
                </p>
            @endif
        </div>
    </div>
@endsection
