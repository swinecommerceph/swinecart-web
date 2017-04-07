<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
      	<meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SwineCart @yield('title') </title>
        <link href="/css/materialize.min.css" rel="stylesheet" type="text/css">
        <link href="/css/dropzone.css" rel="stylesheet" type="text/css">
        <link href="/css/icon.css" rel="stylesheet" type="text/css">
        <link href="/css/style.css" rel="stylesheet" type="text/css">
        <link href="/css/nouislider.min.css" rel="stylesheet">
        <link href="/js/vendor/VideoJS/video-js.min.css" rel="stylesheet">
        <script type="text/javascript" src="/js/vendor/chart.min.js"></script>
    </head>
    <body @yield('pageId')>

        <div class="row">
            <div class="col l2 xl2">
                <ul id="slide-out" class="side-nav fixed">
                    <li>
                        <div class="userView">
                            <div class="background">
                                <img src="http://placehold.it/500/223344">
                            </div>
                            <a href="#!user"><img class="circle" src="http://placehold.it/200/ff2233"></a>
                            <a href="#!name"><span class="white-text name">John Doe</span></a>
                            <a href="#!email"><span class="white-text email">jdandturk@gmail.com</span></a>
                        </div>
                    </li>
                    <li><a href="#!"><i class="material-icons">cloud</i>First Link With Icon</a></li>
                    <li><a href="#!">Second Link</a></li>
                    <li><div class="divider"></div></li>
                    <li><a class="subheader">Subheader</a></li>
                    <li><a class="waves-effect" href="#!">Third Link With Waves</a></li>
                </ul>
                <a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">menu</i></a>
            </div>
            <div class="col l12 xl12 blue">
                sadsa
            </div>
        </div>


        @yield('initScript')
        {{-- Custom scripts for certain pages/functionalities --}}

        @yield('customScript')
    </body>
</html>
