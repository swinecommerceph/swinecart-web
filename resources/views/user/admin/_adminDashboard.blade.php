{{-- delete later if not used --}}
{{-- @TODO refactor code for dashboard and add dashboard code here --}}
@extends('layouts.adminLayout')

@section('title')
    | Admin
@endsection

@section('pageId')
    id="page-admin-home"
@endsection

{{-- @section('breadcrumbTitle')
    Home
@endsection --}}

@section('content')

@endsection

@section('initScript')
    <script type="text/javascript" src="/js/admin/admin_custom.js"></script>
    <script type="text/javascript" src="/js/admin/users.js"></script>
    <script type="text/javascript" src="/js/admin/manageUsers_script.js"></script>
    <script type="text/javascript" src="/js/admin/pages.js"></script>
    <script type="text/javascript" src="/js/admin/managePages_script.js"></script>
@endsection

{{-- Manage User Modal --}}
<div id="manage-user-modal" class="modal">
  <div class="modal-content">
     <h4>Username</h4>
     <div class="row">
        {!!Form::open(['route'=>'admin.block', 'method'=>'PUT', 'class'=>'block-form'])!!}
        <a id="block-data" href="#">
           <div class="col s6 center">
              <i id="block-icon" class="material-icons manage-icon">block</i>
              <input id="block-token" name="_token" type="hidden" value="">
              <input id="block-id" name="user_id" type="hidden" value="">
              <div id="block-label" class="col s12">Block</div>
           </div>
        </a>
        {!!Form::close()!!}
        {!!Form::open(['route'=>'admin.delete', 'method'=>'DELETE', 'class'=>'delete-form'])!!}
           <a id="delete-data" href="#">
              <div class="col s6 center">
                 <i id="delete-icon" class="material-icons manage-icon">close</i>
                 <input id="delete-token" name="_token" type="hidden" value="">
                 <input id="delete-id" name="user_id" type="hidden" value="">
                 <div id="delete-label" class="col s12">Delete</div>
              </div>
           </a>
        {!!Form::close()!!}
     </div>
     <div class="divider"></div>
     <div class="modal-footer">
       <a href="#!" id="cancel-manage" class=" modal-action modal-close waves-effect waves btn-flat">Cancel</a>
     </div>
  </div>
</div>

{{-- Manage User Modal for Accept and Reject --}}
<div id="accept-reject-modal" class="modal">
  <div class="modal-content">
     <h4>Username</h4>
     <div class="row">
        {!!Form::open(['route'=>'admin.add.user', 'method'=>'PUT', 'class'=>'accept-form'])!!}
        <a id="accept-data" href="#">
           <div class="col s6 center">
              <i id="accept-icon" class="material-icons manage-icon">check</i>
              <input id="accept-token" name="_token" type="hidden" value="">
              <input id="accept-id" name="user_id" type="hidden" value="">
              <div id="accept-label" class="col s12">Accept</div>
           </div>
        </a>
        {!!Form::close()!!}
        {!!Form::open(['route'=>'admin.reject', 'method'=>'DELETE', 'class'=>'delete-form'])!!}
           <a id="reject-data" href="#">
              <div class="col s6 center">
                 <i id="delete-icon" class="material-icons manage-icon">close</i>
                 <input id="reject-token" name="_token" type="hidden" value="">
                 <input id="reject-id" name="user_id" type="hidden" value="">
                 <div id="reject-label" class="col s12">Reject</div>
              </div>
           </a>
        {!!Form::close()!!}
     </div>
     <div class="divider"></div>
     <div class="modal-footer">
       <a href="#!" id="cancel-accept-reject" class=" modal-action modal-close waves-effect waves btn-flat">Cancel</a>
     </div>
  </div>
</div>

{{-- Delete modal --}}
<div id="delete-modal" class="modal action-dialog-box red lighten-5">
  <div class="modal-content">
    <h4>Delete User</h4>
    <div class="divider"></div>
    <p>Are you sure you want to delete this user?</p>
  </div>
  <div class="modal-footer red lighten-5">
    <a href="#!" id="cancel-delete" class=" modal-action modal-close waves-effect waves-red btn-flat">Cancel</a>
    <a href="#!" id="confirm-delete" class=" modal-action modal-close waves-effect waves-red btn-flat">Confirm</a>
  </div>
</div>

{{-- Block/Unblock modal --}}
<div id="block-modal" class="modal action-dialog-box orange lighten-5">
  <div class="modal-content">
    <h4>Block User</h4>
    <div class="divider"></div>
    <p>Are you sure you want to block this user?</p>
  </div>
  <div class="modal-footer orange lighten-5">
    <a href="#!" id="cancel-block" class=" modal-action modal-close waves-effect waves-orange btn-flat">Cancel</a>
    <a href="#!" id="confirm-block" class=" modal-action modal-close waves-effect waves-orange btn-flat">Confirm</a>
  </div>
</div>

{{-- Accept modal --}}
<div id="accept-modal" class="modal action-dialog-box green lighten-5">
  <div class="modal-content">
    <h4>Accept User</h4>
    <div class="divider"></div>
    <p>Are you sure you want to accept this user's application?</p>
  </div>
  <div class="modal-footer green lighten-5">
    <a href="#!" id="cancel-accept" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancel</a>
    <a href="#!" id="confirm-accept" class=" modal-action modal-close waves-effect waves-green btn-flat">Confirm</a>
  </div>
</div>

{{-- Reject modal --}}
<div id="reject-modal" class="modal action-dialog-box red lighten-5">
  <div class="modal-content">
    <h4>Reject User</h4>
    <div class="divider"></div>
    <p>Are you sure you want to reject this user's application?</p>
  </div>
  <div class="modal-footer red lighten-5">
    <a href="#!" id="cancel-reject" class=" modal-action modal-close waves-effect waves-red btn-flat">Cancel</a>
    <a href="#!" id="confirm-reject" class=" modal-action modal-close waves-effect waves-red btn-flat">Confirm</a>
  </div>
</div>

{{-- Add user modal --}}
  <div id="adduser" class="modal">
     {!!Form::open(['route'=>'admin.add.user', 'method'=>'POST', 'class'=>'add-user-form'])!!}
   <div class="modal-content">
     <h4>Add User</h4>
     <div class="divider"></div>
     <div class="row">
        <div class="col s12">

              <div class="row">
                 <div class = "addusercontainer" class="row">
                    <div class="input-field col s11">
                     <i class="material-icons prefix">account_circle</i>
                     <input id="icon_prefix" type="text" class="validate" name="name">
                     <label for="icon_prefix">Username</label>
                   </div>
                 </div>
             </div>

             <div class="row">
                <div class = "addusercontainer" class="row">
                   <div class="input-field col s11">
                    <i class="material-icons prefix">email</i>
                    <input id="icon_prefix" type="email" class="validate" name="email">
                    <label for="icon_prefix">Email Address</label>
                  </div>
                </div>
            </div>

        </div>
     </div>
   </div>
   <div class="modal-footer">
      <button id = "add-user-submit" class="btn waves-effect waves-light" type="submit" name="action">Add
       <i class="material-icons right">send</i>
     </button>
   </div>
    {!!Form::close()!!}
 </div>

{{-- Modal for user inquiries --}}
<div id="modal1" class="modal modal-fixed-footer">
  <div id="message-modal-content" class="modal-content">
    <div class="center"><h5>"Username" Message</h5></div>
      <div class="center">Timestamp</div>
      <div class="divider"></div>
      <div class="row">
      <div class="col s12">
      <div id="message-panel" class="card-panel">
        <span class="black-text">
          Sample Text
        </span>
      </div>
    </div>
  </div>
</div>
  <div class="modal-footer">
    <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Resolve</a>
  </div>
</div>


{{-- <div class="row">
<a href="#!" id="total-user-summary">
  <div class="col s6" >
    <div id="total-card" class="card-panel card-summary hoverable">
       <div class="center white-text row">
          <div class="col s4 label-wrapper">
             <div class="left">
                <i class="ecommerce-icon">p</i>
             </div>
             <div class="">
                <div class="summary-title">TOTAL USERS</div>
             </div>
          </div>

           <div class="center white-text summary-data col s8">
             {{$summary[0]}}
           </div>
       </div>

    </div>
  </div>
</a>
<a href="#!" id="total-blocked-summary">
   <div class="col s6" >
     <div id="blocked-card" class="card-panel card-summary hoverable">
        <div class="center white-text row">
           <div class="col s4 label-wrapper">
              <div class="">
                 <i class="ecommerce-icon">b</i>
              </div>
              <div class="">
                 <div class="summary-title">BLOCKED USERS</div>
              </div>
           </div>

            <div class="center white-text summary-data col s8">
              {{$summary[4]}}
            </div>
        </div>
     </div>
   </div>
</a>
<a href="#!" id="total-pending-summary">
   <div class="col s6" >
     <div id="pending-card" class="card-panel card-summary hoverable">
        <div class="center white-text row">
           <div class="col s4 label-wrapper">
              <div class="">
                 <i class="ecommerce-icon">w</i>
              </div>
              <div class="">
                 <div class="summary-title">PENDING BREEDERS</div>
              </div>
           </div>

           <div class="center white-text summary-data col s8">
              {{$summary[3]}}
           </div>
        </div>
     </div>
   </div>
</a>
<a href="#!">
   <div class="col s6" >
     <div id="inquiries-card" class="card-panel card-summary hoverable">
        <div class="center white-text row">
           <div class="col s4 label-wrapper">
              <div class="left">
                 <i class="ecommerce-icon">d</i>
              </div>
              <div class="">
                 <div class="summary-title">USER INQUIRIES</div>
              </div>
           </div>

           <div class="center white-text summary-data col s8">
              3
           </div>
        </div>
     </div>
   </div>
</a>
</div>

</div>
</div> --}}
