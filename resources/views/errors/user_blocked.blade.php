@extends('layouts.default')

@section('title')
    | Account Blocked
@endsection

@section('pageId')
    id="page-account-blocked"
@endsection

@section('navbarHead')
    @if(!Auth::user()->update_profile)
        {{-- Swine Cart --}}
    @endif
@endsection

@section('content')
    <div class="row">
        <div class="col s12 m12 l12 xl12">
            <h5>Account Temporarily Blocked</h5>
            <p>
                Check your email for more information about your account.
            </p>
        </div>
    </div>

@endsection

@section('initScript')

@endsection
