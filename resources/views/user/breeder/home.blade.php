@extends('layouts.default')

@section('title')
    | Breeder
@endsection

@section('navbar_head')
    <li><a href="{{ route('home_path') }}"><i class="fa fa-btn fa-archive"></i> Inventory </a></li>
@endsection


@section('content')
    <div class="container">
        <div class="page-header">
          <h1>Home - Breeder</h1>
        </div>
        <div class="">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Home
                </div>

                <div class="panel-body">
                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa.
                    Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
                    Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.
                    Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.
                    In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium.
                    Integer tincidunt. Cras dapibus.
                </div>
            </div>
        </div>
    </div>
@endsection
