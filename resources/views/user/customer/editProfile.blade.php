@extends('user.customer.home')

@section('title')
    | Customer - Update Profile
@endsection

@section('content')
    <div class="row">
        <div class="col s12 m6 offset-m3">
            <h4>Update Profile </h4>
            <h6>Update your Profile</h6>
            {!! Form::model($customer,['route' => 'customer.update', 'method' =>'PUT', 'class' => 'col s12']) !!}
                @include('user.customer._profileForm')
            {!! Form::close() !!}
        </div>
    </div>
@endsection
