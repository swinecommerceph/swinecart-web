@extends('layouts.spectatorLayout')

@section('title')
    | Spectator
@endsection

@section('pageId')
    id="page-spectator-users"
@endsection

@section('header')
    <h4>Admin Dashboard</h4>
@endsection

@section('content')
    <div class="card-panel">
        <div class="row">
            <div class="col s12">
                <h4>Users</h4>
            </div>
        </div>
        <div class="divider"></div>
        <div class="row">
            <div class="col s12">
                <table class="bordered highlight responsive-table striped">
                    <thead>
                      <tr>
                          <th data-field="name">Name</th>
                          <th data-field="type">Account Type</th>
                      </tr>
                    </thead>

                    <tbody>
                        @forelse($users as $user)
                            <tr>
                            <td>{{$user->name}}</td>
                            <td>{{ucfirst($user->title)}}</td>
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
                  <div class="pagination center"> {{ $users->links() }} </div>
            </div>
        </div>
    </div>
@endsection

@section('initScript')
    <script type="text/javascript" src="/js/admin/admin_custom.js"></script>
    <script type="text/javascript" src="/js/admin/users.js"></script>
    <script type="text/javascript" src="/js/admin/manageUsers_script.js"></script>
    <script type="text/javascript" src="/js/admin/pages.js"></script>
    <script type="text/javascript" src="/js/admin/managePages_script.js"></script>
@endsection
