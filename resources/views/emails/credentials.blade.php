
@extends('layouts.adminNotificationsLayout')

@section('title')
    - SwineCart Breeder Credentials
@endsection

@section('header')
    <div class="row">
        <div class="col s12 m12 l12 xl12">
            <h4>Breeder Credentials<h4>
        </div>
    </div>
    <div class="divider"></div>
@endsection

@section('content')
  <div class="row">
      <div class="col s12 m12 l12 xl12">
          <p>The following are your credentials to access SwineCart.</p>
          <p>Email: <em><strong>{{$email}}</em></strong></p>
          <p>Password: <em><strong>{{$password}}</strong></em></p>
          <p><em>Note: Your password is randomly generated. You should consider changing it to something that you can easily remember</em><p>
      </div>
  </div>
@endsection
