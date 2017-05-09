@extends('layouts.controlLayout')

@section('title')
    | Pending Accounts
@endsection

@section('pageId')
    id="admin-pending-accounts"
@endsection

@section('nav-title')
    Pending Accounts
@endsection

@section('pageControl')
    <div class="row">
        <div class="col s12 m12 l12 xl12">
            {!!Form::open(['route'=>'admin.searchPending', 'method'=>'GET', 'class'=>'row input-field valign-wrapper'])!!}
                <input id="search" type="search" name="search">
                <label class="label-icon" for="search"><i class="material-icons">search</i></label>
                <i class="material-icons">close</i>
            {!!Form::close()!!}
        </div>
    </div>
@endsection

{{-- @section('header')
    <div class="row">
        <div class="col s4">
            <h4 id='admin-content-panel-header'>Pending Users</h4>
        </div>

        <div class="col s8">
            <div class="row">
                {!!Form::open(['route'=>'admin.searchPending', 'method'=>'GET', 'class'=>'search-user-form'])!!}
                <div class="input-field col s9">
                    <input id="search-input" class="validate" type="text" name="search">
                    <label for="search-input">Search</label>
                </div>
                <div class="col hide">
                    <button id="search-button" class="btn waves-effect waves-light" type="submit" name="search">Submit</button>
                </div>

                {!!Form::close()!!}
            </div>
        </div>
    </div>
@endsection --}}

@section('content')

    {{-- <table class="bordered highlight responsive-table striped">
        <thead>
          <tr>
              <th data-field="name">Name</th>
              <th data-field="type">Account Type</th>
              <th data-field="action">Action</th>
          </tr>
        </thead>

        <tbody>
            @forelse($users as $user)
          <tr>
            <td>{{$user->name}}</td>
            <td>{{ucfirst($user->title)}}</td>
            <td>
                <div class="col s6">
                    <a class="waves-effect waves-light btn green lighten-1 accept-button"  data-id ="{{$user->user_id}}"><i class="material-icons left">check</i>Accept</a>
                </div>
                <div class="col s6">
                    <a class="waves-effect waves-light btn red lighten-1 reject-button" data-id ="{{$user->user_id}}"><i class="material-icons left">close</i>Reject</a>
                </div>
            </td>
          </tr>
          @empty
               <tr>
                   <td></td>
                   <td class="center">No User</td>
                   <td></td>
               </tr>
        @endforelse
        </tbody>
      </table>
      <div class="pagination center"> {{ $users->links() }} </div> --}}

      <table class="bordered responsive-table">
          <thead>
            <tr>
                <th data-field="name">Name</th>
                 <th data-field="name">Email</th>
                <th data-field="type">Account Type</th>
                <th data-field="action">Action</th>
            </tr>
          </thead>

          <tbody>
              @forelse($users as $user)
            <tr>
              <td>{{$user->name}}</td>
              <td>{{$user->email}}</td>
              <td>{{ucfirst($user->title)}}</td>
              <td>
                  <div class="col s12 m12 l6 xl6">
                      <a class="waves-effect waves-light btn green lighten-1 accept-button"  data-id ="{{$user->user_id}}">
                          <i class="material-icons left">check</i><span class="hide-on-med-and-down">Accept</span>
                      </a>
                  </div>
                  <div class="col s12 m12 l6 xl6">
                      <a class="waves-effect waves-light btn red lighten-1 reject-button" data-id ="{{$user->user_id}}">
                          <i class="material-icons left">close</i><span class="hide-on-med-and-down">Reject</span>
                      </a>
                  </div>
              </td>
            </tr>
            @empty
                 <tr>
                     <td></td>
                     <td class="right-align">No users found</td>
                     <td></td>
                     <td></td>
                 </tr>
          @endforelse
          </tbody>
        </table>
        <div class="pagination center"> {{ $users->links() }} </div>



      {{-- Accept modal --}}
      <div id="accept-modal" class="modal action-dialog-box green lighten-5">
        <div class="modal-content">
          <h4>Accept User</h4>
          <div class="divider"></div>
          <p>Are you sure you want to accept this user's application?</p>
        </div>
        <div class="modal-footer green lighten-5">
          <a href="#!" id="cancel-accept" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancel</a>
          {!!Form::open(['route'=>'admin.approve', 'method'=>'PUT', 'class'=>'delete-user-form'])!!}
              <input id="form-accept-id" type="hidden" name="id" value="">
              <button id="confirm-accept" class=" modal-action modal-close waves-effect waves-green lighten-5 btn-flat" type="submit">Confirm</button>
          {!!Form::close()!!}

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
          {!!Form::open(['route'=>'admin.reject', 'method'=>'DELETE', 'class'=>'delete-user-form'])!!}
              <input id="form-reject-id" type="hidden" name="id" value="">
              <button id="confirm-reject" class=" modal-action modal-close waves-effect waves-red lighten-5 btn-flat" type="submit">Confirm</button>
          {!!Form::close()!!}
        </div>
      </div>



@endsection

@section('initScript')
    {{-- <script type="text/javascript" src="/js/admin/admin_custom.js"></script> --}}
    <script type="text/javascript" src="/js/admin/users.js"></script>
    <script type="text/javascript" src="/js/admin/userPages_script.js"></script>
    <script type="text/javascript" src="/js/admin/pages.js"></script>
    <script type="text/javascript" src="/js/admin/managePages_script.js"></script>
    @if(Session::has('alert-accept'))
        <script type="text/javascript">
             Materialize.toast('User Successfully Added', 4000)
        </script>
    @elseif (Session::has('alert-reject'))
        <script type="text/javascript">
             Materialize.toast('User Application Rejected', 4000)
        </script>
    @endif
@endsection
