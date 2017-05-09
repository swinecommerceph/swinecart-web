@extends('layouts.controlLayout')

@section('title')
    | Administrator Logs
@endsection

@section('pageId')
    id="admin-logs"
@endsection

@section('nav-title')
    Administrator Logs
@endsection

{{-- @section('header')
    <div class="row">
        <div class="col s5">
            <h4 id='admin-content-panel-header'>Administrator Logs</h4>
        </div>

        <div class="col s7">
            <div class="row">
                {!!Form::open(['route'=>'admin.search.logs', 'method'=>'GET', 'class'=>'search-user-form col s12'])!!}
                    <div class="input-field col s12">
                        <div class="col s6">
                            <input id="search-input" class="validate" type="text" name="search">
                            <label for="search-input">Search</label>
                        </div>
                        <div class="col s6">
                            <select multiple name="option[]">
                                <option disabled selected>Choose category</option>
                                <option value="Block" name="block">Block</option>
                                <option value="Unblock" name="unblock">Unblock</option>
                                <option value="Delete" name="delete">Delete</option>
                                <option value="Create" name="create">Create</option>
                                <option value="Accept" name="accept">Accept</option>
                                <option value="Reject" name="reject">Reject</option>
                            </select>
                        </div>
                    </div>

                    <div class="col hide">
                        <button id="search-button" class="btn waves-effect waves-light" type="submit">Submit</button>
                    </div>
                {!!Form::close()!!}
            </div>
        </div>
    </div>
@endsection --}}

@section('pageControl')
    <div class="row">
        <div class="col s12 m12 l12 xl12">
            {!!Form::open(['route'=>'admin.search.logs', 'method'=>'GET', 'class'=>'search-user-form row valign-wrapper'])!!}
                <div class="input-field col s12 m12 l12 xl12">
                    <div class="col s12 m12 l6 xl6">
                        <input id="search-input" type="text" name="search">
                        <label for="search-input">Search</label>
                    </div>
                    <div class="col s12 m7 l4 xl4">
                        <select multiple name="option[]">
                            <option disabled selected>Choose category</option>
                            <option value="Block" name="block">Block</option>
                            <option value="Unblock" name="unblock">Unblock</option>
                            <option value="Delete" name="delete">Delete</option>
                            <option value="Create" name="create">Create</option>
                            <option value="Accept" name="accept">Accept</option>
                            <option value="Reject" name="reject">Reject</option>
                        </select>
                    </div>
                    <div class="col s12 m3 l2 xl2">
                        <button id="search-button" class="btn waves-effect waves-light" type="submit">Search</button>
                    </div>
                </div>
            {!!Form::close()!!}
        </div>
    </div>
@endsection

@section('content')
    <table class="bordered striped responsive-table">
        <thead>
          <tr>
              <th data-field="time">Time</th>
              <th data-field="adminname">Admin Name</th>
              <th data-field="username">User Name</th>
              <th data-field="category">Category</th>
              <th data-field="reason">Reason</th>
              <th data-field="action">Action</th>
          </tr>
        </thead>

        <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{$log->created_at}}</td>
                    <td>{{$log->admin_name}}</td>
                    <td>{{$log->user}}</td>
                    @if ($log->category == 'Block')
                        <td class="orange-text">{{$log->category}}</td>
                    @elseif ($log->category == 'Unblock')
                        <td class="blue-text">{{$log->category}}</td>
                    @elseif ($log->category == 'Accept')
                        <td class="green-text">{{$log->category}}</td>
                    @elseif ($log->category == 'Reject')
                        <td class="red-text">{{$log->category}}</td>
                    @elseif ($log->category == 'Create')
                        <td class="green-text">{{$log->category}}</td>
                    @elseif ($log->category == 'Delete')
                        <td class="red-text">{{$log->category}}</td>
                    @endif
                    <td>{{$log->reason}}</td>
                    <td>{{$log->action}}</td>
                </tr>
            @empty
                <tr>
                    <td></td>
                    <td></td>
                    <td class="center">Administrator Log Empty</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endforelse
        </tbody>
      </table>
      <div class="pagination center"> {{ $logs->appends(Request::except('page'))->links() }} </div>

@endsection

@section('initScript')
    {{-- <script type="text/javascript" src="/js/admin/admin_custom.js"></script> --}}
    {{-- <script type="text/javascript" src="/js/admin/users.js"></script>
    <script type="text/javascript" src="/js/admin/manageUsers_script.js"></script>
    <script type="text/javascript" src="/js/admin/pages.js"></script>
    <script type="text/javascript" src="/js/admin/managePages_script.js"></script> --}}
@endsection
