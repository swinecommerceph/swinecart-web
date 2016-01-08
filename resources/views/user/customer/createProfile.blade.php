@extends('user.customer.home')

@section('title')
    | Customer - Complete Profile
@endsection

@section('content')
    <div class="row">
        <div class="col s12 m6 offset-m3">
            <h4>Complete Profile </h4>
            <h6>Please complete first your profile</h6>
            {!! Form::open(['route' => 'customer.store', 'class' => 'col s12']) !!}
                @include('user.customer._profileForm')
            {!! Form::close() !!}
        </div>
    </div>

@endsection
