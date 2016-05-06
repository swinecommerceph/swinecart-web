{{--
    Displays Customer profile form upon profile edit
--}}

@extends('user.customer.home')

@section('title')
    | Customer - Update Profile
@endsection

@section('pageId')
    id="page-customer-edit-profile"
@endsection

@section('breadcrumbTitle')
    Update Profile
@endsection

@section('breadcrumb')
    <a href="{{ route('home_path') }}" class="breadcrumb">Home</a>
    <a href="#!" class="breadcrumb">Update Profile</a>
@endsection

@section('content')
    <div class="row">
        <div class="col s12 m10 offset-m1">
            <p class="caption">Update your profile.</p>
            @include('common._errors')
            @include('user.customer._editProfileForm')
        </div>
    </div>
    {{-- Modal Structure --}}
    <div id="confirmation-modal" class="modal">
        <div class="modal-content">
          <p>Are you sure you want to remove this farm?</p>
        </div>
        <div class="modal-footer">
          <a href="#!" id="confirm-remove" class=" modal-action modal-close waves-effect waves-green btn-flat"><i class="material-icons">done</i></a>
          <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat"><i class="material-icons">clear</i></a>
        </div>
    </div>
@endsection

@section('customScript')
    <script src="/js/customer/profile.js"> </script>
    <script src="/js/customer/editProfile_script.js"> </script>
    @if(Session::has('message'))

        <script type="text/javascript">
            $(document).ready(function(){
                Materialize.toast('{{ Session::get('message') }}', 2000, 'green lighten-1');
            });
        </script>

    @endif
@endsection
