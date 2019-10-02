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
    <div class="row">
        <div class="col s12 m12 l12 xl12">
            <a href="{{route('notify_pending')}}" class="waves-effect waves-light btn right"><i class="material-icons left">markunread_mailbox</i>Notify Users</a>
        </div>
    </div>
@endsection

@section('content')
  <!-- Pending Breeders Registered by Admin -->

  <h5>Pending Breeders Registered by Admin</h5>

  <table class="bordered responsive-table">
    <thead>
      <tr>
          <th data-field="name">Name</th>
            <th data-field="name">Email</th>
          <th data-field="type">Account Type</th>
          <th data-field="action">Date Created</th>
      </tr>
    </thead>

    <tbody>
        @forelse($users as $user)
      <tr>
        <td>{{$user->name}}</td>
        <td>{{$user->email}}</td>
        <td>{{ucfirst($user->title)}}</td>
        <td>
            {{$user->created_at}}
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

  <br>
  <h5>Pending Self-Registered Breeders</h5>
  <br>
  <!-- Pending Self-Registered Breeders -->
  <table class="bordered responsive-table">
    <thead>
      <tr>
          <th data-field="name">Name</th>
            <th data-field="name">Email</th>
          <th data-field="type">Account Type</th>
          <th data-field="action">Date Created</th>
          <th data-field="action">Action</th>
      </tr>
    </thead>

    <tbody>
      @forelse($selfRegisteredBreeders as $selfRegisteredBreeder)
        <tr>
          <td>
            <a href="#self-registered-breeder">{{$selfRegisteredBreeder->name}}</a>
          </td>
          <td>{{$selfRegisteredBreeder->email}}</td>
          <td>{{ucfirst($selfRegisteredBreeder->userable_type)}}</td>
          <td>
              {{$selfRegisteredBreeder->created_at}}
          </td>
          <td>
            {!! Form::open([
              'action' => ['AdminController@updateSelfRegisteredBreeder', $selfRegisteredBreeder->id],
              'method' => 'PATCH'
            ]) !!}
              <button type="submit" class="waves-effect waves-light btn">Approve Breeder</button>
            {!! Form::close() !!}
          </td>
        </tr>

        <div id="self-registered-breeder" class="modal">
          <div class="modal-content">
            <h5>Pending Self-Registered Breeder:</h5>
            <p>Name: {{ $selfRegisteredBreeder->name }}</p>
            <p>Other Details to follow...</p>
          </div>

          <div class="modal-footer">
            <a href="#" class="modal-close waves-effect waves-green btn-flat">Close</a>
          </div>
        </div>

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
