{{--
    Displays Breeder profile form upon profile creation
--}}

@extends('user.breeder.home')

@section('title')
    | Breeder - Complete Profile
@endsection

@section('content')
    <div class="row">
        <div class="col s12 m8 offset-m2">
            <h4>Complete Profile </h4>
            <h6>Please complete first your profile.</h6>
            <span>* - required </span>
            @include('common._errors')
            {!! Form::open(['route' => 'breeder.store', 'class' => 's12']) !!}
                @include('user.breeder._profileForm')
            {!! Form::close() !!}
        </div>
    </div>
@endsection

@section('customScript')
    <script src="/js/breeder/profile.js"> </script>
@endsection
