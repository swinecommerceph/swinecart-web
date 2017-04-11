<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Swine E-Commerce PH @yield('title') </title>
        <link href="{{ URL::asset('https://fonts.googleapis.com/css?family=Raleway:300,400,500,700') }}" rel="stylesheet" type="text/css">
    	<link href="{{ URL::asset('https://fonts.googleapis.com/icon?family=Material+Icons')}}" rel="stylesheet">

    	<link rel="stylesheet" href="{{URL::asset('https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css')}}">

    </head>
    <body>
        <nav class="teal darken-3">
            <div class="nav-wrapper">
                <a href="{{route('home_path')}}">
                    {{-- <img class="" src="logo.png" height=65 style="padding:.4rem 0 .4rem 0;"/> --}}
                    {{-- <span class="brand-logo hide-on-med-and-down">SwineCart</span> --}}
                    <span class="brand-logo">SwineCart</span>
                </a>
            </div>
        </nav>
        <main>
            <div class="container">
                @yield('header')
                @yield('content')
            </div>
        </main>
        <footer class="page-footer teal darken-3">
          <div class="container">
            <div class="row">
              <div class="col l6 s12">
                    <a href="{{route('home_path')}}"><h5 class="white-text">SwineCart</h5></a>
                <p class="grey-text text-lighten-4">{{-- Footer Content --}} </p>
              </div>
              <div class="col l4 offset-l2 s12">
                <h5 class="white-text"> {{-- Link Title --}} </h5>
                <ul>
                    {{-- More links --}}
                </ul>
              </div>
            </div>
          </div>
          <div class="footer-copyright">
            <div class="container">
            © {{Carbon\Carbon::now()->year}} SwineCart
            <a class="grey-text text-lighten-4 right" href="#!"></a>
            </div>
          </div>
        </footer>
        <script type="text/javascript" src="{{ URL::asset('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-alpha1/jquery.min.js') }}"></script>
    	<script type="text/javascript" src="{{ URL::asset('https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js') }}"></script>
    </body>
</html>
