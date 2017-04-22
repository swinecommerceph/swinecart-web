<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Swine E-Commerce PH @yield('title') </title>
        <style type="text/css">

         nav{
            background: teal;
            font-size: 50px;
            font-family: "Roboto", sans-serif;
            display: block;
            color: white;
            text-align: center;
            padding: 20px;
         }

         main{
            display: block;
            padding-left: 100px;
            padding-top: 20px;
            padding-bottom: 20px;
            padding-right: 100px;
            font-family: "Roboto", sans-serif;
         }

         footer{
            font-size: 15px;
            color: white;
            background: teal;
            font-family: "Roboto", sans-serif;
            display: block;
            padding: 15px;
         }
      </style>
    </head>
    <body>
        <nav>
            SwineCart
        </nav>
        <main>
            @yield('header')
            @yield('content')
        </main>
        <footer>
            © 2017 SwineCart
        </footer>
    </body>
</html>
