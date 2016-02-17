{{--
    Displays Customer profile form upon profile creation
--}}

@extends('user.customer.home')

@section('title')
    | Customer - Complete Profile
@endsection

@section('breadcrumb-title')
    Comlplete Profile
@endsection

@section('breadcrumb')
    <a href="{{ route('home_path') }}" class="breadcrumb">Home</a>
    <a href="#!" class="breadcrumb">Complete Profile</a>
@endsection

@section('content')
    <div class="row">
        <div class="col s12 m10 offset-m1">
            <p class="caption">Please complete first your profile. <br>
                <blockquote>* - required </blockquote>
            </p>

            @include('common._errors')
            {!! Form::open(['route' => 'customer.store', 'class' => 's12', 'id' => 'create-profile']) !!}
                @include('user.customer._createProfileForm')
            {!! Form::close() !!}
        </div>
    </div>

@endsection

@section('customScript')
    <script src="/js/customer/createProfile_script.js"> </script>
@endsection
