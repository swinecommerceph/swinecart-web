{{--
    Displays Customer profile form upon profile edit
--}}

@extends('user.customer.home')

@section('title')
    | Customer - Update Profile
@endsection

@section('content')
    <div class="row">
        <div class="col s12 m8 offset-m2">
            <h4>Update Profile </h4>
            <h6>Update your Profile</h6>
            {!! Form::model($customer,['route' => 'customer.update', 'method' =>'PUT', 'class' => 'col s12']) !!}
                @include('user.customer._profileForm')
            {!! Form::close() !!}
        </div>
    </div>
@endsection

@section('customScript')
    <script src="/js/customer/profile.js"> </script>
    @if(Session::has('message'))

        <script type="text/javascript">
            $(document).ready(function(){
                Materialize.toast('{{ Session::get('message') }}', 4000, 'green lighten-1');
            });
        </script>

    @endif
@endsection
