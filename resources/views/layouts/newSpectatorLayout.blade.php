<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
      	<meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SwineCart @yield('title')</title>
        <link href="/css/materialize.min.css" rel="stylesheet" type="text/css">
        <link href="/css/icon.css" rel="stylesheet" type="text/css">
        <link href="/css/style.css" rel="stylesheet" type="text/css">
        <link href="/css/admin_spectator.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="/js/vendor/chart.min.js"></script>
    </head>
    <body id="admin-body">
        <nav class="admin-layout-nav teal darken-3">
            <div class="nav-wrapper row valign-wrapper">
                <a href="#" data-activates="slide-out" class="button-collapse hide-on-large"><i class="material-icons">menu</i></a>
                <div class="col s12 m12 l12 xl12 valign">
                    <span class="admin-layout-nav-title">@yield('nav-title')</span>
                </div>
            </div>
        </nav>
        <ul id="slide-out" class="admin-side-nav side-nav fixed teal lighten-2">
            <li>
                <div class="swinecart-logo">
                    <a href="{{route('spectator_path')}}"><img id="admin-brand-logo" src="/images/logowhite.png" height=65/><span  class="brand-logo white-text">SwineCart</span></a>
                </div>
            </li>
            <li>
                <div class="side-nav-user-info">
                    <a id="this_user_information_trigger" href="#this_user_information" class="white-text"><img class="circle side-nav-user-image" src="/images/logoblack.png" height=40/>{{ Auth::user()->name }}</a>
                </div>
            </li>
            <li><a class="subheader white-text">Menu</a></li>
            <li><a class="waves-effect white-text" href="{{route('spectator.users')}}"><i class="material-icons white-text">account_circle</i>All Users</a></li>
            <li><a class="waves-effect white-text" href="{{route('spectator.products')}}"><i class="material-icons white-text">shopping_basket</i>Products</a></li>
            <li><a class="waves-effect white-text" href="{{route('spectator.statistics')}}"><i class="material-icons white-text">trending_up</i>Site Statistics</a></li>
            <li><a class="waves-effect white-text" href="{{ url('logout') }}"><i class="material-icons white-text">power_settings_new</i>Log Out</a></li>
        </ul>
        <main @yield('pageId')class="admin-layout-main">
            <div class="container">
                @yield('pageControl')
                <div class="row">
                    <div class="col s12 m12 l12 xl12">
                        @yield('content')
                    </div>
                </div>
            </div>

            <div class="fixed-action-btn horizontal">
                <a id="back-to-top" class="btn-floating btn-large red lighten-1" data-position="top" data-delay="50" data-tooltip="Back to Top">
                    <i class="material-icons">keyboard_arrow_up</i>
                </a>
            </div>
        </main>
        <footer class="admin-layout-footer page-footer teal darken-2">
            <div class="container">
                <div class="row">
                    <div class="col l6 s12">
                        <h5 class="white-text">SwineCart</h5>
                    </div>
                </div>
            </div>
            <div class="footer-copyright">
                <div class="container">
                    © 2017 SwineCart
                </div>
            </div>
        </footer>

        {{-- Spectator Modals --}}

        {{-- User Information Modal --}}
        <div id="this_user_information" class="modal">
            <div class="modal-content">
                <div class="row">
                    <div class="col s12 m12 l12 xl12">
                        <h5>User Information</h5>
                    </div>
                </div>
                <div class="divider"></div>
                <div id="this_user_information_content" class="row">
                    <div class="center">
                        <div class="preloader-wrapper small active">
                            <div class="spinner-layer spinner-green-only">
                                <div class="circle-clipper left">
                                    <div class="circle"></div>
                                </div><div class="gap-patch">
                                    <div class="circle"></div>
                                </div><div class="circle-clipper right">
                                    <div class="circle"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-action modal-close waves-effect waves-teal btn-flat">Close</a>
            </div>
        </div>

        <script src="/js/vendor/jquery.min.js"></script>
        <script src="/js/vendor/VueJS/vue.js"></script>
        <script src="/js/vendor/materialize.min.js"></script>
        <script src="/js/config.js"></script>
        <script type="text/javascript" src="/js/spectator/spectator_custom.js"></script>
        <script type="text/javascript" src="/js/spectator/spectatorInformation.js"></script>
        <script src="/js/custom.js"></script>
        @yield('initScript')
            {{-- Custom scripts for certain pages/functionalities --}}
        @yield('customScript')
    </body>
</html>
