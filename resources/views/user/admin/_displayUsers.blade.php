@extends('layouts.adminLayout')

@section('title')
    | Admin
@endsection

@section('pageId')
    id="home-all"
@endsection

@section('header')
    <div class="row">
        <div class="col s4">
            <h4 id='admin-content-panel-header'>Users</h4>
        </div>

        <div class="col s8">
            <div class="row">
                {!!Form::open(['route'=>'admin.search', 'method'=>'GET', 'class'=>'search-user-form col s12'])!!}
                    <div class="input-field col s12">
                        <div class="col s7">
                            <input id="search-input" class="validate" type="text" name="search">
                            <label for="search-input">Search</label>
                        </div>
                        <div class="col s5">
                            {{-- <div class="col s6">
                                <input type="checkbox" id="check-admin" name ="admin" value="1"/>
                                <label for="check-admin">Admin</label>
                            </div> --}}

                            <div class="col s6">
                                <input type="checkbox" id="check-breeder" name ="breeder" value="2"/>
                                <label for="check-breeder">Breeder</label>
                            </div>

                            <div class="col s6">
                                <input type="checkbox" id="check-customer" name="customer" value="3"/>
                                <label for="check-customer">Customer</label>
                            </div>
                        </div>
                    </div>

                    <div class="col hide">
                        <button id="search-button" class="btn waves-effect waves-light" type="submit">Submit</button>
                    </div>
                {!!Form::close()!!}
            </div>
        </div>
    </div>

@endsection

@section('content')

    <table class="bordered highlight responsive-table striped">
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
                @if ($user->title == 'admin')
                    <td></td>
                @else
                    <td>
                        @if ($user->blocked_at == NULL)
                            <div class="col s6">
                                <a class="waves-effect waves-light btn orange lighten-1 block-button" data-id ="{{$user->user_id}}" ><i class="material-icons left">block</i>Block</a>
                            </div>
                        @else
                            <div class="col s6">
                                <a class="waves-effect waves-light btn green lighten-1 unblock-button"  data-id ="{{$user->user_id}}"><i class="material-icons left">remove_circle_outline</i>Unblock</a>
                            </div>
                        @endif

                        <div class="col s6">
                            <a class="waves-effect waves-light btn red lighten-1 delete-button" data-id ="{{$user->user_id}}"><i class="material-icons left">delete</i>Delete</a>
                        </div>
                    </td>
                @endif
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
      <div class="pagination center"> {{ $users->appends(Request::except('page'))->links() }} </div>


      {{-- Delete modal --}}
      <div id="delete-modal" class="modal action-dialog-box red lighten-5">
        <div class="modal-content">
          <h4>Delete User</h4>
          <div class="divider"></div>
          <p>Are you sure you want to delete this user?</p>
        </div>
        <div class="modal-footer red lighten-5">
          <a href="#!" id="cancel-delete" class=" modal-action modal-close waves-effect waves-red btn-flat">Cancel</a>
            {!!Form::open(['route'=>'admin.delete', 'method'=>'DELETE', 'class'=>'delete-user-form'])!!}
                <input id="form-delete-id" type="hidden" name="id" value="">
                {{-- <a href="#!" id="confirm-delete" class=" modal-action modal-close waves-effect waves-red btn-flat"  type="submit" >Confirm</a> --}}
                <button id="confirm-delete" class=" modal-action modal-close waves-effect waves-red btn-flat" type="submit">Confirm</button>
            {!!Form::close()!!}

        </div>
      </div>

      {{-- Block modal --}}
      <div id="block-modal" class="modal action-dialog-box orange lighten-5">
        <div class="modal-content">
          <h4>Block User</h4>
          <div class="divider"></div>
          <p>Are you sure you want to block this user?</p>
        </div>
        <div class="modal-footer orange lighten-5">
          <a href="#!" id="cancel-block" class=" modal-action modal-close waves-effect waves-orange btn-flat">Cancel</a>
          {!!Form::open(['route'=>'admin.block', 'method'=>'PUT', 'class'=>'block-user-form'])!!}
              <input id="form-block-id" type="hidden" name="id" value="">
              <button id="confirm-block" class=" modal-action modal-close waves-effect waves-orange btn-flat" type="submit">Confirm</button>
          {!!Form::close()!!}
        </div>
      </div>

      {{-- Unblock modal --}}
      <div id="unblock-modal" class="modal action-dialog-box green lighten-5">
        <div class="modal-content">
          <h4>Unblock User</h4>
          <div class="divider"></div>
          <p>Are you sure you want to unblock this user?</p>
        </div>
        <div class="modal-footer green lighten-5">
          <a href="#!" id="cancel-block" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancel</a>
          {!!Form::open(['route'=>'admin.block', 'method'=>'PUT', 'class'=>'unblock-user-form'])!!}
              <input id="form-unblock-id" type="hidden" name="id" value="">
              <button id="confirm-unblock" class=" modal-action modal-close waves-effect waves-green btn-flat" type="submit">Confirm</button>
          {!!Form::close()!!}
        </div>
      </div>


@endsection

@section('initScript')
    {{-- <script type="text/javascript" src="/js/admin/admin_custom.js"></script> --}}
    <script type="text/javascript" src="/js/admin/users.js"></script>
    <script type="text/javascript" src="/js/admin/manageUsers_script.js"></script>
    <script type="text/javascript" src="/js/admin/pages.js"></script>
    <script type="text/javascript" src="/js/admin/managePages_script.js"></script>
@endsection
