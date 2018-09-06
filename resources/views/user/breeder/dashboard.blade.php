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

@section('breadcrumb')
    <a href="{{ route('home_path') }}" class="breadcrumb">Home</a>
    <a href="#!" class="breadcrumb">Dashboard</a>
@endsection

@section('content')
    <br>
    <div class="row">
        <h4 class="center-align" style="font-weight: 700;">
            Overall Performance
        </h4>
    </div>

    <div id="card-status" class="row" v-cloak>

        <div class="row">
            {{-- Guide text --}}
            <p style="color:hsl(0, 0%, 30%); margin-left: 1vw;">Select a frequency to graph:</p>
            
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
                <div class="col s5">
                    <div class="input-field col s5">
                        <custom-date-from-select v-model="dateFromInput"
                            :date-accreditation="latestAccreditation"
                            @date-from-select="dateFromChange"
                        >
                        </custom-date-from-select>
                    </div>
                    <div class="input-field col s5">
                        <custom-date-to-select v-model="dateToInput"
                            @date-to-select="dateToChange"
                            v-show="chosenFrequency !== 'weekly'"> </custom-date-to-select>
                    </div>
                    <div class="" style="margin-top:1rem;">
                        <a class="btn-floating" @click.prevent="retrieveSoldProducts"><i class="material-icons">send</i></a>
                    </div>
                </div>
            
                {{-- Bar Chart--}}
                <div class="col s12">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Product Status --}}
        <div id="card-product-status" class="col grey lighten-4">
            <div class="col s12">
                <h4 class="center-align">
                    <a href="{{ route('dashboard.productStatus') }}"
                        style="font-weight: 700; color:hsl(0, 0%, 13%);"
                    >
                        Product Status
                    </a>
                </h4>
            </div>

            {{-- Requested Products --}}
            <div class="col s12 m4">
                <div class="card hoverable">
                    <div class="card-content blue lighten-5">
                        <span class="card-title">
                            <a href="{{route('dashboard.productStatus',['status' => 'requested'])}}"
                                class="blue-text"
                                style="font-weight: 700;"
                            >
                                Requested
                            </a>
                        </span>
                        <h3>@{{ overallRequested }}</h3>
                    </div>
                    <div class="card-action blue lighten-4">
                        <a style="color:hsl(0, 0%, 13%);">Boar: @{{ dashboardStats.requested.boar }} </a>
                        <a style="color:hsl(0, 0%, 13%);">Sow: @{{ dashboardStats.requested.sow }} </a>
                        <a style="color:hsl(0, 0%, 13%);">Gilt: @{{ dashboardStats.requested.gilt }} </a>
                        <a style="color:hsl(0, 0%, 13%);">Semen: @{{ dashboardStats.requested.semen }} </a>
                    </div>
                </div>
            </div>

            {{-- Reserved Products --}}
            <div class="col s12 m4">
                <div class="card hoverable">
                    <div class="card-content blue lighten-5">
                        <span class="card-title">
                            <a href="{{route('dashboard.productStatus',['status' => 'reserved'])}}"
                                class="blue-text"
                                style="font-weight: 700;"
                            >
                                Reserved
                            </a>
                        </span>
                        <h3>@{{ overallReserved }}</h3>
                    </div>
                    <div class="card-action blue lighten-4">
                        <a style="color:hsl(0, 0%, 13%);">Boar: @{{ dashboardStats.reserved.boar }} </a>
                        <a style="color:hsl(0, 0%, 13%);">Sow: @{{ dashboardStats.reserved.sow }} </a>
                        <a style="color:hsl(0, 0%, 13%);">Gilt: @{{ dashboardStats.reserved.gilt }} </a>
                        <a style="color:hsl(0, 0%, 13%);">Semen: @{{ dashboardStats.reserved.semen }} </a>
                    </div>
                </div>
            </div>

            {{-- On Delivery Products --}}
            <div class="col s12 m4">
                <div class="card hoverable">
                    <div class="card-content blue lighten-5">
                        <span class="card-title">
                            <a href="{{route('dashboard.productStatus',['status' => 'on_delivery'])}}"
                                class="blue-text"
                                style="font-weight: 700;"
                            >
                                On Delivery
                            </a>
                        </span>
                        <h3>@{{ overallOnDelivery }}</h3>
                    </div>
                    <div class="card-action blue lighten-4">
                        <a style="color:hsl(0, 0%, 13%);">Boar: @{{ dashboardStats.on_delivery.boar }} </a>
                        <a style="color:hsl(0, 0%, 13%);">Sow: @{{ dashboardStats.on_delivery.sow }} </a>
                        <a style="color:hsl(0, 0%, 13%);">Gilt: @{{ dashboardStats.on_delivery.gilt }} </a>
                        <a style="color:hsl(0, 0%, 13%);">Semen: @{{ dashboardStats.on_delivery.semen }} </a>
                    </div>
                </div>
            </div>

            <div class="row"></div>
            <div class="row">
                <h4 class="center-align" style="font-weight: 700;">Product Management</h4>
            </div>
            

            {{-- Hidden Products --}}
            <div class="col s12 m4">
                <div class="card hoverable">
                    <div class="card-content  blue lighten-5">
                        <span class="card-title">
                            <a href="{{route('products',['type' => 'all-type', 'status' => 'hidden', 'sort' => 'none'])}}"
                                class="blue-text"
                                style="font-weight: 700;"
                            >
                                Hidden
                            </a>
                        </span>
                        <h3>@{{ overallHidden }}</h3>
                    </div>
                    <div class="card-action blue lighten-4">
                        <a style="color:hsl(0, 0%, 13%);">Boar: @{{ dashboardStats.hidden.boar }} </a>
                        <a style="color:hsl(0, 0%, 13%);">Sow: @{{ dashboardStats.hidden.sow }} </a>
                        <a style="color:hsl(0, 0%, 13%);">Gilt: @{{ dashboardStats.hidden.gilt }} </a>
                        <a style="color:hsl(0, 0%, 13%);">Semen: @{{ dashboardStats.hidden.semen }} </a>
                    </div>
                </div>
            </div>

            {{-- Displayed Products --}}
            <div class="col s12 m4">
                <div class="card hoverable">
                    <div class="card-content blue lighten-5">
                        <span class="card-title">
                            <a href="{{route('products',['type' => 'all-type', 'status' => 'displayed', 'sort' => 'none'])}}"
                                class="blue-text"
                                style="font-weight: 700;"
                            >
                                Displayed
                            </a>
                        </span>
                        <h3>@{{ overallDisplayed }}</h3>
                    </div>
                    <div class="card-action blue lighten-4">
                        <a style="color:hsl(0, 0%, 13%);">Boar: @{{ dashboardStats.displayed.boar + dashboardStats.requested.boar }} </a>
                        <a style="color:hsl(0, 0%, 13%);">Sow: @{{ dashboardStats.displayed.sow + dashboardStats.requested.sow }} </a>
                        <a style="color:hsl(0, 0%, 13%);">Gilt: @{{ dashboardStats.displayed.gilt + dashboardStats.requested.gilt }} </a>
                        <a style="color:hsl(0, 0%, 13%);">Semen: @{{ dashboardStats.displayed.semen + dashboardStats.requested.semen }} </a>
                    </div>
                </div>
            </div>

            {{-- Total Products Available --}}
            <div class="col s12 m4">
                <div class="card hoverable">
                    <div class="card-content blue lighten-5">
                        <span class="card-title">
                            <a href="{{route('products',['type' => 'all-type', 'status' => 'all-status', 'sort' => 'none'])}}"
                                    class="blue-text"
                                    style="font-weight: 700;"
                                >
                                    Available Products
                                </a>
                        </span>
                        <h3>@{{ overallProductsAvailable }}</h3>
                    </div>
                    <div class="card-action blue lighten-4">
                        <a style="color:hsl(0, 0%, 13%);">Boar: @{{ dashboardStats.requested.boar + dashboardStats.displayed.boar + dashboardStats.hidden.boar }} </a>
                        <a style="color:hsl(0, 0%, 13%);">Sow: @{{ dashboardStats.requested.sow + dashboardStats.displayed.sow + dashboardStats.hidden.sow }} </a>
                        <a style="color:hsl(0, 0%, 13%);">Gilt: @{{ dashboardStats.requested.gilt + dashboardStats.displayed.gilt + dashboardStats.hidden.gilt }} </a>
                        <a style="color:hsl(0, 0%, 13%);">Semen: @{{ dashboardStats.requested.semen + dashboardStats.displayed.semen + dashboardStats.hidden.semen }} </a>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Overall Average Rating --}}
        <div class="col s12 m6">
            <br>
            <div class="card">
                <div class="card-content grey white-text">
                    <span class="card-title">
                        <a href="{{route('dashboard.reviews')}}" class="white-text">Overall Average Rating</a>
                    </span>
                    <h3>@{{ overallRatings }}/5</h3>
                    <span>No. of reviews: @{{ dashboardStats.ratings.reviewsSize }}</span>
                </div>
                <div class="card-action grey">
                    <a class="white-text">Delivery: @{{ dashboardStats.ratings.delivery }} </a>
                    <a class="white-text">Transaction: @{{ dashboardStats.ratings.transaction }} </a>
                    <a class="white-text">Product Quality: @{{ dashboardStats.ratings.productQuality }} </a>
                </div>
            </div>
        </div>

        {{-- Reviews --}}
        <div class="col s12 m6">
            <br>
            <div class="card">
                <div class="card-content teal white-text">
                    <span class="card-title">
                        <a href="{{route('dashboard.reviews')}}" class="white-text">Reviews</a>
                    </span>
                    <div id="review-slider" class="slider">
                        <ul class="slides teal">
                            <li v-for="review in dashboardStats.ratings.reviews">
                                <img src="">
                                <div class="caption center-align">
                                    <h5 style="margin:0;">"@{{ review.comment }}"</h5>
                                    <h6 class="light grey-text text-lighten-3">- @{{ review.customerName }}</h6>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        {{-- Location --}}
        <div class="col s12">
            <div class="card">
                <div class="card-content teal darken-4 white-text">
                    <span class="card-title">
                        <a href="{{route('map.customers')}}" class="white-text">Customer Mapping</a>
                    </span>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customScript')
    <script type="text/javascript">
        var rawLatestAccreditation = "{{ $latestAccreditation }}";
        var rawServerDateNow = "{{ $serverDateNow }}";
        var rawChartTitle = "{!! $soldData['title'] !!}";
        var rawBarChartData = {
            labels: {!! json_encode($soldData['labels']) !!},
            datasets: [{
                label: 'Boar',
                backgroundColor: 'rgb(255, 99, 132)',
                data: {{ json_encode($soldData['dataSets'][0]) }}
            }, {
                label: 'Sow',
                backgroundColor: 'rgb(54, 162, 235)',
                data: {{ json_encode($soldData['dataSets'][1]) }}
            }, {
                label: 'Gilt',
                backgroundColor: 'rgb(75, 192, 192)',
                data: {{ json_encode($soldData['dataSets'][2]) }}
            }, {
                label: 'Semen',
                backgroundColor: 'rgb(153, 102, 255)',
                data: {{ json_encode($soldData['dataSets'][3]) }}
            }]

        };
        var rawDashboardStats = {!! json_encode($dashboardStats) !!};
    </script>
    <script src="{{ elixir('/js/breeder/dashboard.js') }}"></script>
@endsection
