@extends('layouts.adminLayout')

@section('title')
    | Admin
@endsection

@section('pageId')
    id="home-all"
@endsection

@section('header')
    Users
@endsection

@section('content')

{{-- Remove this file if not used --}}
@forelse($users as $user)
<ul class="collection">
    <li class="collection-item avatar">
      <div class="row">
        <div class="col s8">
          <i class="material-icons circle">perm_identity</i>
          <span class="title">{{$user->name}}</span>
          <p>{{ucfirst($user->title)}}</p>
        </div>
        <div class="col s1 right">
            <a href="#" class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="Delete"><i class="material-icons">delete</i></a>
        </div>
        <div class="col s1 right">
            <a href="#" class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="Block"><i class="material-icons">block</i></a>
        </div>
      </div>
    </li>
  </ul>
  @empty
  <p>No users</p>
@endforelse

@section('initScript')
    {{-- <script type="text/javascript" src="/js/admin/admin_custom.js"></script> --}}
    <script type="text/javascript" src="/js/admin/users.js"></script>
    <script type="text/javascript" src="/js/admin/manageUsers_script.js"></script>
    <script type="text/javascript" src="/js/admin/pages.js"></script>
    <script type="text/javascript" src="/js/admin/managePages_script.js"></script>
@endsection
