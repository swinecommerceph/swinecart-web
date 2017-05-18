@extends('layouts.newSpectatorLayout')

@section('title')
    | Account Settings
@endsection

@section('pageId')
    id="spectator-account-settings"
@endsection

@section('nav-title')
    Account Settings
@endsection

@section('pageControl')
    <h5>Change Password</h5>
@endsection

@section('content')
    {!!Form::open(['route'=>'spectator.change_password', 'method' => 'PATCH'])!!}
        <div class="row">
            <div class="col s12 m12 l12 xl12 valign input-field">
                <input id="prevpass" type="password" class="validate" name="prevpass" required="">
                <label for="prevpass">Previous Password</label>
            </div>
        </div>
        <div class="row">
            <div class="col s12 m12 l12 xl12 valign input-field">
                <input id="newpass" type="password" class="validate" name="newpass" required="">
                <label for="newpass">New Password</label>
            </div>
        </div>
        <div class="row">
            <div class="col s12 m12 l12 xl12 valign input-field">
                <input id="confirmpass" type="password" class="validate" name="confirmpass" required="">
                <label for="confirmpass">Confirm Password</label>
            </div>
        </div>
        <div class="row center">
            <button id="search-button" class="btn waves-effect waves-light" type="submit">Submit</button>
        </div>
    {!!Form::close()!!}
@endsection

@section('initScript')

@endsection
