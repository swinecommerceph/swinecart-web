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
        <div class="container">

            <div class="col s8">
                <div id="home-carousel" class="carousel carousel-slider center" data-indicators="true" style="height:35rem;">
                    {{-- <div class="carousel-fixed-item center">
                        <a class="btn waves-effect white grey-text darken-text-2">button</a>
                    </div> --}}
                    <div class="carousel-item white-text" href="#one!" style="background-image:url('/images/demo/HP1.jpg'); background-size:cover;">
                        <h2>First Panel</h2>
                        <p class="black-text">The first panel</p>
                    </div>
                    <div class="carousel-item white-text" href="#two!" style="background-image:url('/images/demo/HP2.jpg'); background-size:cover;">
                        <h2>Second Panel</h2>
                        <p class="black-text">The second panel</p>
                    </div>
                    <div class="carousel-item white-text" href="#three!" style="background-image:url('/images/demo/HP3.jpg'); background-size:cover;">
                        <h2>Third Panel</h2>
                        <p class="black-text">The third panel</p>
                    </div>
                    <div class="carousel-item white-text" href="#four!" style="background-image:url('/images/demo/HP4.jpg'); background-size:cover;">
                        <h2>Fourth Panel</h2>
                        <p class="black-text">The fourth panel</p>
                    </div>
                </div>
            </div>
            <div class="col s4 valign-wrapper">
                <div class="center">
                    <img src="/images/logodark.png" alt="" />
                    <h2>SwineCart</h2>
                    <p>
                        Your next premium breed is just a click away
                    </p>
                    <p>
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
        <div class="container">
            <div class="col s6 center" style="margin: 3rem 0 2rem 0; border-right: thick solid #00695c;">
                <div class="col s12">
                    <div class="col s12">
                        <i class="ecommerce-icon grey-text text-darken-2">n</i>
                    </div>
                    <div class="col s12">
                        <h4 class="teal-text text-darken-2">BREEDER</h4>
                    </div>
                    <div class="col s12">
                        <p class="grey-text text-darken-1">
                            SBFAP Accredited Breeder Farm? Sell your products here.
                        </p>
                    </div>
                    <div class="col s12" style="margin:0 auto;">
                        {{-- <p class="left-align"> --}}
                            Get accredited. <br>
                            <a href="{{ url('login') }}" class="btn-flat"><i class="material-icons right">chevron_right</i> Log-in </a> <br>
                            <a href="{{ url('login') }}" class="btn-flat"><i class="material-icons right">chevron_right</i> Learn More </a>
                        {{-- </p> --}}
                    </div>
                </div>
            </div>
            <div class="col s6 center" style="margin: 3rem 0 2rem 0;">
                <div class="col s12">
                    <div class="col s12">
                        <i class="ecommerce-icon grey-text text-darken-2">v</i>
                    </div>
                    <div class="col s12">
                        <h4 class="teal-text text-darken-2">CUSTOMER</h4>
                    </div>
                    <div class="col s12">
                        <p class="grey-text text-darken-1">
                            Commercial hog raiser or backyard farmer? Find the good breeds here.
                        </p>
                    </div>
                    <div class="col s12" style="margin:0 auto;">
                        {{-- <p class="left-align"> --}}
                            Register. <br>
                            <a href="{{ url('login') }}" class="btn-flat"><i class="material-icons right">chevron_right</i> Log-in </a> <br>
                            <a href="{{ url('login') }}" class="btn-flat"><i class="material-icons right">chevron_right</i> Learn More </a>
                        {{-- </p> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Third row --}}
    <div class="row">
        <div class="col s12 center-align">
            <p class="teal-text text-darken-2">
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
        <div class="col s12 teal darken-4">
            <div class="col s4 center" style="margin: 2rem 0 2rem 0;">
                <div class="col s12">
                    <i class="ecommerce-icon black-text">b</i>
                    <h4 class="white-text">CONNECT WITH CUSTOMERS</h4>
                    <p class="white-text">
                        SwineCart supports various platforms to allow you to talk with your potential and exising customers.
                    </p>
                    <p class="white-text">
                        It supports chat, email, and SMS.
                    </p>
                </div>
            </div>
            <div class="col s4 center" style="margin: 2rem 0 2rem 0; border-left: thick solid white; border-right: thick solid white;">
                <div class="col s12">
                    <i class="ecommerce-icon black-text">n</i>
                    <h4 class="white-text">MANAGE INVENTORIES</h4>
                    <p class="white-text">
                        Get real-time sales updates on all your farms in your SwineCart dashboard and prevent inventory outage and overstock through SwineCart's inventory tracking.
                    </p>
                </div>
            </div>
            <div class="col s4 center" style="margin: 2rem 0 2rem 0;">
                <div class="col s12">
                    <i class="ecommerce-icon black-text">a</i>
                    <h4 class="white-text">ENGAGE YOUR CUSTOMERS</h4>
                    <p class="white-text">
                        Boost your product sales by letting your customers leave feedback and ratings in your breeder profile.
                    </p>
                </div>
            </div>
        </div>
    </div>
    {{-- <div id="home-page" class="row teal lighten-5" style="height:100vh; margin-bottom:0px;">
        <div class=""> --}}
            {{--  Logo --}}
            {{-- <div class="center" style="padding-top:2em; padding-bottom:2em;">
                <img src="/images/logodark.png">
            </div> --}}

            {{-- Search bar --}}
            {{-- <nav id="search-container" class="row grey ligthen-3" style="opacity:0.8;">
                <div id="search-field" class="nav-wrapper grey lighten-4">
                    <form>
                        <div class="input-field">
                            <input id="search" type="search" placeholder="Search for a product" required>
                            <label class="label-icon" for="search"><i class="material-icons teal-text text-darken-3">search</i></label>
                            <i class="material-icons">close</i>
                        </div>
                    </form>
                </div>
            </nav>

            <div class="row">
                <div class="col s4 offset-s4">
                    <a href="{{ route('login') }}" class="waves-effect waves-light btn-large col s12 teal lighten-1" style="font-size:24px;">Login</a>
                </div>
            </div>
            <div class="row">
                <div class="col s4 offset-s4">
                    <a href="{{ url('register') }}" class="waves-effect waves-light btn-large col s12 pink lighten-1" style="font-size:24px;">Register</a>
                </div>
            </div>

        </div>
    </div> --}}
@endsection
