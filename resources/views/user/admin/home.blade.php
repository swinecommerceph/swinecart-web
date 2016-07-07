{{--
    Displays Home page of Admin
--}}

@extends('layouts.adminLayout')

@section('title')
    | Admin
@endsection

@section('pageId')
    id="page-admin-home"
@endsection

@section('breadcrumbTitle')
    Home
@endsection

@section('content')

@endsection
@section('initScript')
  <script type="text/javascript" src="/js/admin/users.js"></script>
  <script type="text/javascript" src="/js/admin/manageUsers_script.js"></script>
@endsection
