{{--
  Displays the reports regarding the breeder's performance
--}}

@extends('user.breeder.home')

@section('title')
  | Breeder - Reports
@endsection

@section('breadcrumbTitle')
    <div class="breadcrumb-container">
      Reports of Performance
    </div>    
@endsection

@section('breadcrumb')
    <div class="breadcrumb-container">
        <a href="{{ route('home_path') }}" class="breadcrumb">Home</a>
        <a href="#!" class="breadcrumb">Reports</a>
    </div>
@endsection

@section('breeder-content')
    <div class="row">
      <div class="col s12">
        <h3>Report of Performance</h3>

        <div id="card-status" class="row" v-cloak>

        </div>
      </div>
    </div>
@endsection
