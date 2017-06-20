{{--
    Displays Breeder profile form upon profile creation
--}}

@extends('user.breeder.home')

@section('title')
    | Breeder - Complete Profile
@endsection

@section('pageId')
    id="page-breeder-create-profile"
@endsection

@section('breadcrumbTitle')
    Comlplete Profile
@endsection

@section('content')
    <div class="row">
        <div class="col s12 m10 offset-m1">
            <p class="caption">Please complete first your profile. <br>
                <blockquote>Fields with * are required.</blockquote>
            </p>

            @include('common._errors')
            {!! Form::open(['route' => 'breeder.store', 'class' => 's12', 'id' => 'create-profile']) !!}
                @include('user.breeder._createProfileForm')
            {!! Form::close() !!}
        </div>
    </div>
@endsection

@section('initScript')
    <script type="text/javascript">
        // Initialization for select tags
        $('select').material_select();
    </script>
@endsection

@section('customScript')
    <script type="text/javascript">
        var provinces = {!! $provinces !!};
    </script>
    <script src="/js/breeder/createProfile_script.js"> </script>
    <script src="/js/validation/formValidationMethods.js"> </script>
    <script src="/js/validation/breeder/createProfile_validation.js"> </script>
@endsection
