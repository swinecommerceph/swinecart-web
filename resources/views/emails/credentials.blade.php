
@extends('layouts.messageOneColumn')

@section('title')
    - User Credentials
@endsection

@section('page-id')
    id="page-credentials"
@endsection

@section('content')
   <div class="row credentials">
      <div class="col s12">
         Login Credentials for the Swine E-Commerce PH website
      </div>
      <div class="col s12">
         Email: {{$email}}
      </div>
      <div class="col s12">
         Password: {{$password}}
      </div>
   </div>

@endsection
