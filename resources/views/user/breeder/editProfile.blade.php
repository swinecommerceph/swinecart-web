@extends('user.breeder.home')

@section('title')
    | Breeder - Update Profile
@endsection

@section('content')
    <div class="row">
        <div class="col s12 m6 offset-m3">
            <h4>Update Profile </h4>
            <h6>Update your Profile</h6>
            {!! Form::model($breeder,['route' => 'breeder.update', 'method' =>'PUT', 'class' => 'col s12']) !!}
                @include('user.breeder._profileForm')
            {!! Form::close() !!}
        </div>
    </div>
@endsection
