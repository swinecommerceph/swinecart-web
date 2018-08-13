{{--
    Displays Home Page of the E-Commerce website
--}}

@extends('layouts.default')

@section('pageId')
    id="page-home"
@endsection

@section('homeContent')
    <div class="row"> </div>
    {{-- First row --}}
    <div class="row">

        <div class="col s7 offset-s1">
            <div id="homepage-slider" class="slider">
                <ul class="slides">
                    @forelse($homeContent as $content)
                        <li>
                            <img src= {{$content->path.$content->name}}>
                            <div class="caption center-align">
                                <h3>{{$content->title}}</h3>
                                <h5 class="light grey-text text-lighten-3 content-text">{{$content->text}}</h5>
                            </div>
                        </li>
                    @empty
                        <li>
                            <img src="/images/demo/home1.jpg"> <!-- random image -->
                            <div class="caption center-align">
                                {{-- <h3>Welcome to SwineCart!</h3> --}}
                                <h5 class="light grey-text text-lighten-3"></h5>
                            </div>
                        </li>
                        <li>
                            <img src="/images/demo/home2.jpg"> <!-- random image -->
                            <div class="caption left-align">
                                {{-- <h3>Left Aligned Caption</h3> --}}
                                <h5 class="light grey-text text-lighten-3"></h5>
                            </div>
                        </li>
                        <li>
                            <img src="/images/demo/home3.jpg"> <!-- random image -->
                            <div class="caption right-align">
                                {{-- <h3>Right Aligned Caption</h3> --}}
                                <h5 class="light grey-text text-lighten-3"></h5>
                            </div>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
        <div class="col s3 valign-wrapper">
            <div>
                <div class="col s12">
                    <p><br><br></p>
                    <img class="right" src="/images/logodark.png" alt="" width="180" height="180"/>
                </div>
                <div class="col s12">
                    <h2 class="right teal-text text-darken-4" style="margin:0; font-family:Electrolize;">SwineCart</h2>
                </div>
                <div class="col s12">
                    <h5 class="right-align grey-text" style="margin:0">
                        Your next premium breed is just a click away
                    </h5>
                    <p class="grey-text">
                        SwineCart is an e-commerce system that facilitates secure business transactions
                        between buyers and sellers of breeder swine and semen
                    </p>
                </div>
            </div>
        </div>

    </div>

    {{-- Second row --}}
    <div id="swinecart-users" class="row">
        <div class="col s12 center-align teal darken-3">
            <p>
                <h4 class="white-text">SWINECART USERS</h4>
            </p>
        </div>
        <div class="col s12 teal darken-3">
            <div class="container">
                <div class="col s6" style="margin: 3rem 0 2rem 0; border-right: thick solid #fff;">
                    <div class="col s12 white-text">
                        <div class="col s12 center">
                            <i class="ecommerce-icon white-text" style="font-size:10rem;">n</i>
                        </div>
                        <div class="col s12 center">
                            <h4 class="">BREEDER</h4>
                        </div>
                        <div class="col s12 center">
                            <p class="">
                                SBFAP Accredited Breeder Farm? Sell your products here.
                            </p>
                        </div>
                        <div class="col s12" style="">
                            {{-- <p class="left-align"> --}}
                                <a href="#!" class="btn-flat white-text"><i class="material-icons right">chevron_right</i> Get accredited </a> <br>
                                <a href="{{ url('login') }}" class="btn-flat white-text"><i class="material-icons right">chevron_right</i> Log-in </a> <br>
                                <a id="learn-more-breeder" href="#!" class="btn-flat white-text"><i class="material-icons right">chevron_right</i> Learn More </a>
                            {{-- </p> --}}
                        </div>
                    </div>
                </div>
                <div class="col s6" style="margin: 3rem 0 2rem 0;">
                    <div class="col s12 white-text">
                        <div class="col s12 center">
                            <i class="ecommerce-icon white-text" style="font-size:10rem;">v</i>
                        </div>
                        <div class="col s12 center">
                            <h4 class="">CUSTOMER</h4>
                        </div>
                        <div class="col s12 center">
                            <p class="">
                                Commercial hog raiser or backyard farmer? Find the good breeds here.
                            </p>
                        </div>
                        <div class="col s12" style="">
                            {{-- <p class="left-align"> --}}
                                <a href="{{ url('register') }}" class="btn-flat white-text"><i class="material-icons right">chevron_right</i> Register </a> <br>
                                <a href="{{ url('login') }}" class="btn-flat white-text"><i class="material-icons right">chevron_right</i> Log-in </a> <br>
                                <a id="learn-more-customer" href="#!" class="btn-flat white-text"><i class="material-icons right">chevron_right</i> Learn More </a>
                            {{-- </p> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Third row --}}
    <div id="breeder-features" class="row" style="margin-bottom:0;">
        <div class="col s12 center-align">
            <p class="teal-text text-darken-3">
                <h4>KEY FEATURES AND ADVANTAGES FOR BREEDERS</h4>
            </p>
        </div>

        {{-- First three features --}}
        <div class="col s12">
            <div class="col s4 center" style="margin: 2rem 0 2rem 0;">
                <div class="col s12">
                    <i class="ecommerce-icon black-text">a</i>
                    <h4 class="teal-text text-darken-2">REGISTRATION AND VERIFICATION</h4>
                    <p class="teal-text text-darken-1">
                        Already an SBFAP accredited breeder? Just create an account at swinecart.cf
                    </p>
                    <p class="teal-text text-darken-1">
                        Not yet an SBFAP accredited breeder? Visit swinecart.cf/sbfap for more information.
                    </p>
                </div>
            </div>
            <div class="col s4 center" style="margin: 2rem 0 2rem 0; border-left: thick solid #00695c; border-right: thick solid #00695c;">
                <div class="col s12">
                    <i class="ecommerce-icon black-text">p</i>
                    <h4 class="teal-text text-darken-2">SET-UP BREEDER PROFILE</h4>
                    <p class="teal-text text-darken-1">
                        After creating your account fill out your breeder profile and start managing your farms.
                    </p>
                </div>
            </div>
            <div class="col s4 center" style="margin: 2rem 0 2rem 0;">
                <div class="col s12">
                    <i class="ecommerce-icon black-text">f</i>
                    <h4 class="teal-text text-darken-2">SHOWCASE PRODUCTS</h4>
                    <p class="teal-text text-darken-1">
                        Display your products to let potential customers browse through product reviews, photos and videos.
                    </p>
                    <p class="teal-text text-darken-1">
                        Customers around the Philippines can search and purchase your products.
                    </p>
                </div>
            </div>
        </div>

        {{-- Last three features --}}
        <div class="col s12">
            <div class="col s4 center" style="margin: 2rem 0 2rem 0;">
                <div class="col s12">
                    <i class="ecommerce-icon black-text">b</i>
                    <h4 class="teal-text text-darken-2">CONNECT WITH CUSTOMERS</h4>
                    <p class="teal-text text-darken-1">
                        SwineCart supports various platforms to allow you to talk with your potential and exising customers.
                    </p>
                    <p class="teal-text text-darken-1">
                        It supports chat, email, and SMS.
                    </p>
                </div>
            </div>
            <div class="col s4 center" style="margin: 2rem 0 2rem 0; border-left: thick solid #00695c; border-right: thick solid #00695c;">
                <div class="col s12">
                    <i class="ecommerce-icon black-text">n</i>
                    <h4 class="teal-text text-darken-2">MANAGE INVENTORIES</h4>
                    <p class="teal-text text-darken-1">
                        Get real-time sales updates on all your farms in your SwineCart dashboard and prevent inventory outage and overstock through SwineCart's inventory tracking.
                    </p>
                </div>
            </div>
            <div class="col s4 center" style="margin: 2rem 0 2rem 0;">
                <div class="col s12">
                    <i class="ecommerce-icon black-text">a</i>
                    <h4 class="teal-text text-darken-2">ENGAGE YOUR CUSTOMERS</h4>
                    <p class="teal-text text-darken-1">
                        Boost your product sales by letting your customers leave feedback and ratings in your breeder profile.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Fourth row --}}
    <div id="customer-features" class="row">
        <div class="col s12 center-align teal darken-3">
            <p>
                <h4 class="white-text">KEY FEATURES AND ADVANTAGES FOR CUSTOMERS</h4>
            </p>
        </div>

        {{-- First three features --}}
        <div class="col s12 teal darken-3">
            <div class="col s4 center" style="margin: 2rem 0 2rem 0;">
                <div class="col s12 white-text">
                    <i class="ecommerce-icon white-text">n</i>
                    <h4 class="">TRUSTED SELLERS</h4>
                    <p class="">
                        Only farms accredited by Swine Breeders Farm Accreditation Program (SBFAP) can sell their products in SwineCart.
                    </p>
                    <p class="">
                        SBFAP is a program by  Bureau of Animal Industry (BAI) that ensures the availability of quality breeder swines in farms
                    </p>
                </div>
            </div>
            <div class="col s4 center" style="margin: 2rem 0 2rem 0; border-left: thick solid white; border-right: thick solid white;">
                <div class="col s12 white-text">
                    <i class="ecommerce-icon white-text">a</i>
                    <h4 class="">FIND VARIETY OF BREEDERS</h4>
                    <p class="">
                        Search for sellers near your area through an integrated map that approximates the location of accredited breeders.
                    </p>
                </div>
            </div>
            <div class="col s4 center" style="margin: 2rem 0 2rem 0;">
                <div class="col s12 white-text">
                    <i class="ecommerce-icon white-text">d</i>
                    <h4 class="">HIGH QUALITY PRODUCTS</h4>
                    <p class="">
                        Search for premium quality breeder swine and boar semen and browse through a wide variety of products from accredited breeders. Select products with the aid photos, videos, and complete product specifications
                    </p>
                </div>
            </div>
        </div>

        {{-- Last two features --}}
        <div class="col s12 teal darken-3">
            <div class="container">
                <div class="col s6" style="margin: 3rem 0 2rem 0; border-right: thick solid #fff;">
                    <div class="col s12 white-text">
                        <div class="col s12 center">
                            <i class="ecommerce-icon white-text">c</i>
                        </div>
                        <div class="col s12 center">
                            <h4 class="">FAST TRANSACTION</h4>
                        </div>
                        <div class="col s12 center">
                            <p class="">
                                Communicate with breeders through SwineCart's built-in chat feature. You can also monitor your orders in real-time from reservation to delivery and get updates via SMS and email.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col s6" style="margin: 3rem 0 2rem 0;">
                    <div class="col s12 white-text">
                        <div class="col s12 center">
                            <i class="ecommerce-icon white-text">v</i>
                        </div>
                        <div class="col s12 center">
                            <h4 class="">RATING AND FEEDBACK</h4>
                        </div>
                        <div class="col s12 center">
                            <p class="">
                                After buying from SwineCart, share your experience with the SwineCart community by posting product and breeder ratings. By sharing your feedback, you can help other customers understand the product and easily find what they need.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <div class="container">
        {{-- SwineCart simple description --}}
        <div class="row">
            <div class="col 16 s12">
                <p
                 style="
                    text-align: justify;
                    text-justify: inter-word;
                 ">
                    SwineCart is a project of the UPLB Institute of Computer Science. It is funded by  Department of Science and Technology - Philippine Council for Agriculture, Forestry and Natural Resources Research and Development (DOST-PCAARRD) and in cooperation with Accredited Swine Breeders Association of the Philippines (ASBAP).
                </p>
            </div>
        </div>

        {{-- Contact Information and Logos --}}
        <div class="row">
            <div class="col s4"></div>
            {{-- Contact Information for SwineCart--}}
            <div class="col s5 teal-text darken-3">
                <p>swinecommerceph@gmail.com</p>
                <p>(049) 536 2302 | (049) 536 2313</p>
            </div>
            {{-- Logos of ICS, PCAARRD, and UP --}}
            <div class="col s1">
                {{-- ICS logo --}}
                <img 
                    style="
                        margin-top: 20%;
                        height: auto;
                        width: 100%
                    "
                    src="/images/ics-logo.jpg">
            </div>
            <div class="col s1">
                {{-- UP logo --}}
                <img 
                    style="
                        margin-top: 20%;
                        height: auto;
                        width: 100%
                    "
                    src="/images/up-logo.png">
            </div>
            <div class="col s1">
                {{-- PCAARRD logo --}}
                <img 
                    style="
                        margin-top: 20%;
                        height: auto;
                        width: 100%
                    "
                    src="/images/pcaarrd-logo.png">
            </div>
        </div>
        
        {{-- Copyrights --}}
        <div class="row">
            <div class="col s12">
                <p>
                    Copyright All Rights Resevered © 2018
                </p>
            </div>
        </div>
    </div>
@endsection
