<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
      	<meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Swine E-Commerce PH @yield('title') </title>

        <link href="/css/materialize.min.css" rel="stylesheet" type="text/css">
        <link href="/css/dropzone.css" rel="stylesheet" type="text/css">
        <link href="/css/icon.css" rel="stylesheet" type="text/css">
        <link href="/css/style.css" rel="stylesheet" type="text/css">
        <link href="/js/vendor/VideoJS/video-js.min.css" rel="stylesheet">
    </head>
    <body @yield('pageId')>
        <div class="navbar-fixed">
            <nav class="teal darken-3">
                <div class="nav-wrapper container">
                    <a href="{{ route('home_path') }}"><img src="/images/logowhite.png" height=65/>&nbsp&nbsp<div class="brand-logo">Swine E-Commerce PH</div></a>

                    <ul id="nav-mobile" class="right hide-on-med-and-down">
                        <li><a>{{ Auth::user()->name }}</a> </li>
                        {{-- <li>
                            <a class="waves-effect waves-light modal-trigger tooltipped" href="{{ route('admin_logs') }}" data-position="bottom" data-delay="40" data-tooltip="Administrator Logs">
                                <i class="material-icons">class</i>
                            </a>
                        </li> --}}

                        <li>
                            <li><a href="{{ url('logout') }}">Logout</a></li>
                        </li>
                    </ul>
                </div>

                {{-- Preloader Progress --}}
                <div id="preloader-progress" class="progress red lighten-4" style="display:none;">
                  <div class="indeterminate red"></div>
                </div>
            </nav>
        </div>

        <div class="container">
            <div class="row">
                <div class="col s3">
                    <ul class="collapsible" data-collapsible="accordion">
                        <li>
                            <a href="{{route('spectator.users')}}" class="black-text"><div class="collapsible-header"><i class="material-icons">face</i>Users</div></a>
                        </li>
                        <li>
                            <a href="{{route('spectator.products')}}" class="black-text"><div class="collapsible-header"><i class="material-icons">shopping_basket</i>Products</div></a>
                        </li>
                        <li>
                            <a href="{{route('spectator.logs')}}" class="black-text"><div class="collapsible-header"><i class="material-icons">view_headline</i>Logs</div></a>
                        </li>
                        <li>
                            <a href="{{route('spectator.statistics')}}" class="black-text"><div class="collapsible-header"><i class="material-icons">trending_up</i>Site Statistics</div></a>
                        </li>
                    </ul>
                </div>
                <div class="col s9">
                    @yield('content')
                </div>
            </div>
        </div>

    </body>
</html>
