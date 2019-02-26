{{--
    Displays Customer profile form upon profile creation
--}}

@extends('user.customer.home')

@section('title')
    | Customer - Complete Profile
@endsection

@section('pageId')
    id="page-customer-create-profile"
@endsection

@section('breadcrumbTitle')
    Comlplete Profile
@endsection

@section('breadcrumb')
    <a href="{{ route('home_path') }}" class="breadcrumb">Home</a>
    <a href="#!" class="breadcrumb">Complete Profile</a>
@endsection

@section('homeContent')

@endsection

@section('content')
    <div class="row container">
        <div class="col s12 m10 offset-m1">
            <p class="caption">Please complete first your profile. <br>
                <blockquote>Fields with * are required.</blockquote>
            </p>

            @include('common._errors')
            {!! Form::open(['route' => 'customer.store', 'class' => 's12', 'id' => 'create-profile']) !!}
                @include('user.customer._createProfileForm')
            {!! Form::close() !!}
        </div>
    </div>

@endsection

@section('initScript')

@endsection

@section('customScript')
    <script type="text/javascript">
        // Initialization for select tags
        $('select').material_select();
    </script>
    <script type="text/javascript">
        var provinces = {!! $provinces !!};
    </script>
    <script src="{{ elixir('/js/customer/createProfile.js') }}"></script>
@endsection
