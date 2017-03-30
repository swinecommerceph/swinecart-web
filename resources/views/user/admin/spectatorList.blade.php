@extends('layouts.adminLayout')

@section('title')
    | Admin
@endsection

@section('pageId')
    id="spectatorlist"
@endsection

@section('header')
    <div class="row valign-wrapper">
        <div class="col s12 m12 l6">
            <h4>Spectator List</h4>
        </div>

        <div class="col s12 m12 l6 valign">
            {!!Form::open(['route'=>'admin.spectatorlist-search', 'method'=>'GET', 'class'=>'row input-field valign-wrapper'])!!}
                <div class="col s8 m8 l8 valign">
                    <input id="search-input" class="validate" type="text" name="search">
                    <label for="search-input">Search</label>
                </div>

                <div class="col s4 m4 l4 valign">
                    <button id="search-button" class="btn waves-effect waves-light" type="submit">Submit</button>
                </div>
            {!!Form::close()!!}
        </div>
    </div>
@endsection

@section('content')
    <table class="responsive-table bordered striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Join Date</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($spectators as $spectator)
                <tr>
                    <td>{{$spectator->userable_id}}</td>
                    <td>{{$spectator->name}}</td>
                    <td>{{$spectator->email}}</td>
                    <td>{{ucfirst($spectator->title)}}</td>
                    <td>{{$spectator->created_at}}</td>
                </tr>
            @empty
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endforelse

        </tbody>
    </table>
    <div class="pagination center"> {{ $spectators->appends(Request::except('page'))->links() }} </div>
@endsection

@section('initScript')
    <script type="text/javascript" src="/js/admin/transaction_script.js"></script>
@endsection
