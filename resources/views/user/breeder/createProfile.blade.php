{{--
    Displays Breeder profile form upon profile creation
--}}

@extends('user.breeder.home')

@section('title')
    | Breeder - Complete Profile
@endsection

@section('breadcrumb-title')
    Comlplete Profile
@endsection

@section('content')
    <div class="row">
        <div class="col s12 m10 offset-m1">
            <p class="caption">Please complete first your profile. <br>
                <blockquote>* - required </blockquote>
            </p>

            @include('common._errors')
            {!! Form::open(['route' => 'breeder.store', 'class' => 's12', 'id' => 'create-profile']) !!}
                @include('user.breeder._createProfileForm')
            {!! Form::close() !!}
        </div>
    </div>
@endsection

@section('customScript')
    <script src="/js/breeder/createProfile_script.js"> </script>
@endsection
