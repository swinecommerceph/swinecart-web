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
        <div class="row valign-wrapper">
            <div class="col s12 m12 l5 valign">
                <h4>Users</h4>
            </div>
            <div class="col s12 m12 l7 valign">
                <div class="row">
                    {!!Form::open(['route'=>'spectator.searchUser', 'method'=>'GET'])!!}
                        <div class="col s12 m12 l12 valign-wrapper">
                            <div class="input-field inline col s12 m12 l10 valign">
                                <input id="spectator-user-search" type="text" class="validate" name="search">
                                <label for="spectator-user-search">Search User</label>
                                <div class="row">
                                    <div class="col s12 m12 l6">
                                        <input type="checkbox" id="spectatorstats-breeder-checkbox" name="breeder"/>
                                        <label for="spectatorstats-breeder-checkbox">Breeder</label>
                                    </div>
                                    <div class="col s12 m12 l6">
                                        <input type="checkbox" id="spectatorstats-customer-checkbox" name="customer"/>
                                        <label for="spectatorstats-customer-checkbox">Customer</label>
                                    </div>
                                </div>
                            </div>
                            <button class="btn waves-effect waves-light" type="submit">Search</button>
                        </div>
                    {!!Form::close()!!}
                </div>
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
                          <th data-field="transactions">Details</th>
                      </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)

                            <tr>
                                <td>{{$user->name}}</td>
                                <td>{{ucfirst($user->title)}}</td>
                                <td><a href="#user-modal" class="waves-effect waves-light btn modal-trigger"><i class="material-icons left">view_headline</i>Details</a></td>
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
                  <div class="pagination center"> {{ $users->appends(Request::except('page'))->links() }} </div>
            </div>
        </div>
    </div>

    <div id="user-modal" class="modal modal-fixed-footer">
        <div class="modal-content">
            <h4>User Details</h4>
            <div class="divider"></div>
            <p>User Information</p>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Close</a>
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
