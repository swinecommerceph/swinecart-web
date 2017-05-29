{{--
    Displays Breeder profile form upon profile edit
--}}

@extends('user.breeder.home')

@section('title')
    | Breeder - Update Profile
@endsection

@section('pageId')
    id="page-breeder-edit-profile"
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
            <div class="row">
                <div class="col s2 center-align">
                    <div id="logo-card" class="card">
                        <div class="card-image">
                            <img src="{{ $breeder->logoImage }}" alt="" />
                        </div>
                    </div>
                    <a id="change-logo" href="#">Change Logo</a>
                </div>
            </div>

            @include('common._errors')
            @include('user.breeder._editProfileForm')
        </div>
    </div>

    {{-- Remove Farm confirmation modal --}}
    <div id="confirmation-modal" class="modal">
        <div class="modal-content">
          <p>Are you sure you want to remove this farm?</p>
        </div>
        <div class="modal-footer">
          <a href="#!" id="confirm-remove" class=" modal-action modal-close waves-effect waves-green btn-flat"><i class="material-icons">done</i></a>
          <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat"><i class="material-icons">clear</i></a>
        </div>
    </div>

    {{-- Change Logo Modal --}}
    <div id="change-logo-modal" class="modal">
        <div class="modal-content">
            <h5>Change Logo</h5>
            <div class="row">
                <div class="col s12">
                    {!! Form::open(['route' => 'breeder.logoUpload', 'class' => 's12 dropzone', 'id' => 'logo-dropzone', 'enctype' => 'multipart/form-data']) !!}
        				<div class="fallback">
        					<input type="file" name="logo" accept="image/png, image/jpeg, image/jpg">
        				</div>
        			{!! Form::close() !!}
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Cancel</a>
            <a href="#!" id="confirm-change-logo" class="waves-effect waves-green btn-flat">Set Logo</a>
        </div>
    </div>

    {{--  Custom preview for dropzone --}}
    <div id="custom-preview" style="display:none;">
    	<div class="dz-preview dz-file-preview">
    		<div class="dz-image">
    			<img data-dz-thumbnail alt="" src=""/>
    		</div>
    		<div class="dz-details">
    			<div class="dz-filename"><span data-dz-name></span></div>
    			<div class="dz-size" data-dz-size></div>
    		</div>
    		<div class="dz-progress progress red lighten-4"><div class="determinate green" style="width:0%" data-dz-uploadprogress></div></div>
    		<div class="dz-success-mark"><span><i class='medium material-icons green-text'>check_circle</i></span></div>
    		<div class="dz-error-mark"><span><i class='medium material-icons orange-text text-lighten-1'>error</i></span></div>
    		<div class="dz-error-message"><span data-dz-errormessage></span></div>
    		<a>
                <i class="dz-remove material-icons red-text text-lighten-1 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Remove this image" data-dz-remove>cancel</i>
            </a>
    	</div>
    </div>
@endsection

@section('customScript')
    <script src="/js/vendor/dropzone.js"></script>
    <script src="/js/breeder/profile.js"> </script>
    <script src="/js/breeder/editProfile_script.js"> </script>
    @if(Session::has('message'))

        <script type="text/javascript">
            $(document).ready(function(){
                Materialize.toast('{{ Session::get('message') }}', 4000, 'green lighten-1');
            });
        </script>

    @endif
@endsection
