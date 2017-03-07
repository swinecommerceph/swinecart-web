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
                          <th data-field="transactions">Transaction History</th>
                      </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)

                            <tr>
                                <td>{{$user->name}}</td>
                                <td>{{ucfirst($user->title)}}</td>
                                <td><a href="#user-modal" class="waves-effect waves-light btn modal-trigger"><i class="material-icons left">history</i>View</a></td>
                            </tr>

                          @empty
                            <tr>
                              <td></td>
                              <td class="flow-text">No User</td>
                              <td></td>
                            </tr>
                        @endforelse
                    </tbody>
                  </table>
                  <div class="pagination center"> {{ $users->links() }} </div>
            </div>
        </div>
    </div>

    <div id="user-modal" class="modal modal-fixed-footer">
        <div class="modal-content">
            <h4>Transaction History</h4>
            <div class="divider"></div>
            <p>User Transactions</p>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Close</a>
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
