{{--
    Displays Breeder profile form upon profile edit
--}}

@extends('user.breeder.home')

@section('title')
    | Breeder - Update Profile
@endsection

{{-- @section('breadcrumb')
    <a href="#!" class="breadcrumb"> Home </a>
    <a href="#!" class="breadcrumb"> Update Profile </a>
@endsection --}}

@section('content')
    <div class="row">
        <div class="col s12 m8 offset-m2">
            <h4>Update Profile </h4>
            <h6>Update your Profile</h6>
            {!! Form::model($breeder,['route' => 'breeder.update', 'method' =>'PUT', 'class' => 'col s12']) !!}
                @include('user.breeder._profileForm')
            {!! Form::close() !!}
        </div>
    </div>

@endsection

@section('customScript')
    <script src="/js/breeder/profile.js"> </script>
    @if(Session::has('message'))

        <script type="text/javascript">
            $(document).ready(function(){
                Materialize.toast('{{ Session::get('message') }}', 4000, 'green lighten-1');
            });
        </script>

    @endif
@endsection
