@extends('layouts.adminLayout')

@section('title')
    | Admin
@endsection

@section('pageId')
    id="admin-logs"
@endsection

@section('header')
    <h4 id='admin-content-panel-header'>Administrator Logs</h4>
@endsection

@section('content')
    <table class="bordered striped responsive-table">
        <thead>
          <tr>
              <th data-field="time">Time</th>
              <th data-field="name">Name</th>
              <th data-field="action">Action</th>
          </tr>
        </thead>

        <tbody>
            @foreach($logs as $log)
                <tr>
                    <td>{{$log->created_at}}</td>
                    <td>{{$log->admin_name}}</td>
                    <td>{{$log->action}}</td>
                </tr>
            @endforeach
        </tbody>
      </table>
      <div class="pagination center"> {{ $logs->links() }} </div>

@endsection

@section('initScript')
    {{-- <script type="text/javascript" src="/js/admin/admin_custom.js"></script> --}}
    <script type="text/javascript" src="/js/admin/users.js"></script>
    <script type="text/javascript" src="/js/admin/manageUsers_script.js"></script>
    <script type="text/javascript" src="/js/admin/pages.js"></script>
    <script type="text/javascript" src="/js/admin/managePages_script.js"></script>
@endsection
