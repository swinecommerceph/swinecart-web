{{--
    Displays products of the respective Breeder user
--}}

@extends('user.breeder.home')

@section('title')
    | Breeder - Dashboard
@endsection

@section('pageId')
    id="page-breeder-dashboard"
@endsection

@section('breadcrumbTitle')
    Your Dashboard.
@endsection

@section('content')
    <br>

    <div id="card-status" class="row" v-cloak>

        {{-- Product Status --}}
        <div id="card-product-status" class="col grey lighten-4">
            {{-- Product Status label --}}
            <div class="col s12">
                <h4 class="left-align">
                    <a href="{{ route('dashboard.productStatus') }}"
                        style="font-weight: 500; color:hsl(0, 0%, 13%);"
                    >
                        Product Inventory and Status
                    </a>
                </h4>
            </div>

            {{-- Requested Products --}}
            <div class="col s12 m4">
                <a href="{{route('dashboard.productStatus',['status' => 'requested'])}}">
                    <div class="card hoverable">
                        <div class="card-content  grey lighten-5">
                            <span class="card-title">
                                <b>Requested</b>
                            </span>
                            <h3 class="black-text">@{{ overallRequested }}</h3>
                        </div>
                        <div class="card-action teal darken-3 ">
                            <a class="grey-text text-lighten-4">Boar: @{{ dashboardStats.requested.boar }} </a>
                            <a class="grey-text text-lighten-4">Sow: @{{ dashboardStats.requested.sow }} </a>
                            <a class="grey-text text-lighten-4">Gilt: @{{ dashboardStats.requested.gilt }} </a>
                            <a class="grey-text text-lighten-4">Semen: @{{ dashboardStats.requested.semen }} </a>
                        </div>
                    </div>      
                </a>
            </div>

            {{-- Reserved Products --}}
            <div class="col s12 m4">
                <a href="{{route('dashboard.productStatus',['status' => 'reserved'])}}">
                    <div class="card hoverable">
                        <div class="card-content  grey lighten-5">
                            <span class="card-title">
                                <b>Reserved</b>
                            </span>
                            <h3 class="black-text">@{{ overallReserved }}</h3>
                        </div>
                        <div class="card-action teal darken-3 ">
                            <a class="grey-text text-lighten-4">Boar: @{{ dashboardStats.reserved.boar }} </a>
                            <a class="grey-text text-lighten-4">Sow: @{{ dashboardStats.reserved.sow }} </a>
                            <a class="grey-text text-lighten-4">Gilt: @{{ dashboardStats.reserved.gilt }} </a>
                            <a class="grey-text text-lighten-4">Semen: @{{ dashboardStats.reserved.semen }} </a>
                        </div>
                    </div>
                </a>
            </div>

            {{-- On Delivery Products --}}
            <div class="col s12 m4">
                <a href="{{route('dashboard.productStatus',['status' => 'on_delivery'])}}">
                    <div class="card hoverable">
                        <div class="card-content  grey lighten-5">
                            <span class="card-title">
                                <b>On Delivery</b>
                            </span>
                            <h3 class="black-text">@{{ overallOnDelivery }}</h3>
                        </div>
                        <div class="card-action teal darken-3 ">
                            <a class="grey-text text-lighten-4">Boar: @{{ dashboardStats.on_delivery.boar }} </a>
                            <a class="grey-text text-lighten-4">Sow: @{{ dashboardStats.on_delivery.sow }} </a>
                            <a class="grey-text text-lighten-4">Gilt: @{{ dashboardStats.on_delivery.gilt }} </a>
                            <a class="grey-text text-lighten-4">Semen: @{{ dashboardStats.on_delivery.semen }} </a>
                        </div>
                    </div>
                    
                </a>
            </div>

            {{-- Product Management --}}
            <div class="row"></div>
            <div class="row">
                <h4 class="left-align" style="font-weight: 500; margin-left: 2vw; margin-top: 5vh;">
                    <a href="{{route('products',['type' => 'all-type', 'status' => 'all-status', 'sort' => 'none'])}}"
                        style="color:hsl(0, 0%, 13%);"
                    >
                        Product Management
                    </a>
                </h4>
            </div>
            
            {{-- Hidden Products --}}
            <div class="col s12 m4">
                <a href="{{route('products',['type' => 'all-type', 'status' => 'hidden', 'sort' => 'none'])}}">
                    <div class="card hoverable">
                        <div class="card-content   grey lighten-5">
                            <span class="card-title">
                                <b>Hidden</b>
                            </span>
                            <h3 class="black-text">@{{ overallHidden }}</h3>
                        </div>
                        <div class="card-action teal darken-3 ">
                            <a class="grey-text text-lighten-4">Boar: @{{ dashboardStats.hidden.boar }} </a>
                            <a class="grey-text text-lighten-4">Sow: @{{ dashboardStats.hidden.sow }} </a>
                            <a class="grey-text text-lighten-4">Gilt: @{{ dashboardStats.hidden.gilt }} </a>
                            <a class="grey-text text-lighten-4">Semen: @{{ dashboardStats.hidden.semen }} </a>
                        </div>
                    </div>
                    
                </a>
            </div>

            {{-- Displayed Products --}}
            <div class="col s12 m4">
                <a href="{{route('products',['type' => 'all-type', 'status' => 'displayed', 'sort' => 'none'])}}">
                    <div class="card hoverable">
                        <div class="card-content  grey lighten-5">
                            <span class="card-title">
                                <b>Displayed</b>
                            </span>
                            <h3 class="black-text">@{{ overallDisplayed }}</h3>
                        </div>
                        <div class="card-action teal darken-3 ">
                            <a class="grey-text text-lighten-4">Boar: @{{ dashboardStats.displayed.boar + dashboardStats.requested.boar }} </a>
                            <a class="grey-text text-lighten-4">Sow: @{{ dashboardStats.displayed.sow + dashboardStats.requested.sow }} </a>
                            <a class="grey-text text-lighten-4">Gilt: @{{ dashboardStats.displayed.gilt + dashboardStats.requested.gilt }} </a>
                            <a class="grey-text text-lighten-4">Semen: @{{ dashboardStats.displayed.semen + dashboardStats.requested.semen }} </a>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Total Products Available --}}
            <div class="col s12 m4">
                <a href="{{route('products',['type' => 'all-type', 'status' => 'all-status', 'sort' => 'none'])}}">
                    <div class="card hoverable">
                        <div class="card-content  grey lighten-5">
                            <span class="card-title">
                                <b>Available Products</b>
                            </span>
                            <h3 class="black-text">@{{ overallProductsAvailable }}</h3>
                        </div>
                        <div class="card-action teal darken-3 ">
                            <a class="grey-text text-lighten-4">Boar: @{{ dashboardStats.requested.boar + dashboardStats.displayed.boar + dashboardStats.hidden.boar }} </a>
                            <a class="grey-text text-lighten-4">Sow: @{{ dashboardStats.requested.sow + dashboardStats.displayed.sow + dashboardStats.hidden.sow }} </a>
                            <a class="grey-text text-lighten-4">Gilt: @{{ dashboardStats.requested.gilt + dashboardStats.displayed.gilt + dashboardStats.hidden.gilt }} </a>
                            <a class="grey-text text-lighten-4">Semen: @{{ dashboardStats.requested.semen + dashboardStats.displayed.semen + dashboardStats.hidden.semen }} </a>
                        </div>
                    </div>
                </a>
            </div>

        </div>

        <div class="row"></div>
        <div class="row"></div>
        {{-- Overall Performance --}}
        <div class="row">
            <h4 class="left-align" style="font-weight: 500; margin-left: 1vw;">
                Overall Performance
            </h4>

            {{-- Guide text --}}
            <p style="color:hsl(0, 0%, 30%); margin-left: 1vw;">Select a frequency to graph to see your performance:</p>
            
            {{-- Charts --}}
            <div id="charts-container" class="">  
                {{-- Frequencies --}}
                <div class="col s2">
                    <div class="">
                        <input class="with-gap" name="frequency" type="radio" id="frequency-monthly" value="monthly" v-model="chosenFrequency" @change="valueChange" />
                        <label for="frequency-monthly">Monthly</label>
                    </div>
                    <div class="">
                        <input class="with-gap" name="frequency" type="radio" id="frequency-weekly" value="weekly" v-model="chosenFrequency" @change="valueChange" />
                        <label for="frequency-weekly">Weekly</label>
                    </div>
                    <div class="">
                        <input class="with-gap" name="frequency" type="radio" id="frequency-daily" value="daily" v-model="chosenFrequency" @change="valueChange" />
                        <label for="frequency-daily">Daily</label>
                    </div>
                </div>

                {{-- Dates --}}
                <div class="col s8">
                    <div class="input-field col s4">
                        <custom-date-from-select v-model="dateFromInput"
                            :date-accreditation="latestAccreditation"
                            @date-from-select="dateFromChange"
                        >
                        </custom-date-from-select>
                    </div>
                    <div class="input-field col s4">
                        <custom-date-to-select v-model="dateToInput"
                            @date-to-select="dateToChange"
                            v-show="chosenFrequency !== 'weekly'"> </custom-date-to-select>
                    </div>
                    <div class="" style="margin-top:1rem;">
                        <a class="btn teal darken-3" @click.prevent="retrieveSoldProducts">
                            <b>Graph</b>
                        </a>
                    </div>
                </div>
            
                {{-- Bar Chart--}}
                <div class="col s12">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>
        
        {{-- Overall Average Rating Container --}}    
        <div class="col s12 m6">
            <br>
            <div class="card hoverable">
                <div class="card-content white">
                    <span class="card-title">
                        <a href="{{route('dashboard.reviews')}}"
                            style="font-weight: 700; color:hsl(0, 0%, 13%);"
                        >
                            Overall Average Rating
                        </a>
                    </span>
                    <h2 class="blue-text">@{{ overallRatings }}/5</h2>
                    <br><br>
                    <span style="color:hsl(0, 0%, 45%);">No. of reviews: @{{ dashboardStats.ratings.reviewsSize }}</span>
                </div>
                <div class="left-align" style="background: hsl(0, 0%, 97%); padding-top: 3vh; padding-bottom: 2vh;">
                    <a style="color:hsl(0, 0%, 29%); padding-left: 2vw;">Delivery: @{{ dashboardStats.ratings.delivery }} </a><br>
                    <a style="color:hsl(0, 0%, 29%); padding-left: 2vw;">Transaction: @{{ dashboardStats.ratings.transaction }} </a><br>
                    <a style="color:hsl(0, 0%, 29%); padding-left: 2vw;">Product Quality: @{{ dashboardStats.ratings.productQuality }} </a>
                </div>
            </div>
        </div>

        {{-- Reviews --}}
        <div class="col s12 m6">
            <br>
            <div class="card hoverable">
                <div class="card-content">
                    <span class="card-title">
                        <a href="{{route('dashboard.reviews')}}"
                            style="font-weight: 700;  color:hsl(0, 0%, 13%);"
                        >
                            Reviews
                        </a>
                    </span>
                    <div id="review-slider" class="slider">
                        <ul class="slides" style="background:hsl(0, 0%, 97%);">
                            <li v-for="review in (dashboardStats.ratings.reviews).slice(0,3)">
                                <div class="caption right-align">
                                    <h5 class="center-align" style="margin:0; color:hsl(0, 0%, 29%);">"@{{ review.comment }}"</h5>
                                    <h6 class="center-align" style="color:hsl(0, 0%, 45%);">- @{{ review.customerName }}</h6>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Customer Mapping Container--}}
    <div class="row">
        {{-- Location --}}
        <div class="col s12">
            <a href="{{route('map.customers')}}" >
                <div class="card hoverable teal darken-3">
                    <div class="card-content center-align">
                        <span class="card-title" style="font-weight: 600; color: #fafafa;">
                            Customer Mapping
                        </span>
                    </div>
                </div>
            </a>
        </div>
    </div>
@endsection

@section('customScript')
    <script type="text/javascript">
        var rawLatestAccreditation = "{{ $latestAccreditation }}";
        var rawServerDateNow = "{{ $serverDateNow }}";
        var rawChartTitle = "{!! $soldData['title'] !!}";

        var rawLabels = {!! json_encode($soldData['labels'])!!};
        var rawDataBoar = {{ json_encode($soldData['dataSets'][0]) }};
        var rawDataSow = {{ json_encode($soldData['dataSets'][1]) }};
        var rawDataGilt = {{ json_encode($soldData['dataSets'][2]) }};
        var rawDataSemen = {{ json_encode($soldData['dataSets'][3]) }};

        
        var rawDashboardStats = {!! json_encode($dashboardStats) !!};
    </script>
    <script src="{{ elixir('/js/breeder/dashboard.js') }}"></script>
@endsection
