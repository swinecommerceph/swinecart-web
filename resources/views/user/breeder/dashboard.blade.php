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
<div class="breadcrumb-container">
  Your Dashboard.
</div>
@endsection

@section('breeder-content')
<br>

<div id="card-status" class="row" v-cloak>

  {{-- Display current farm --}}
  <h5>Currently displayed farm: {{ $selectedFarm }} </h5>
  <br><br>

  <div class="col s12 m5 l4 xl3">
    <div id="farm-wrapper" style="margin-bottom: 4vh; margin-left: 0.5vw;" class="input-field">
      <select id="select-farm">
        <option value="all-farms" selected>All farms</option>
        <option v-for="farm in farms" v-bind:value="farm.id"> @{{ farm.name }} </option>
      </select>
      <label style="font-size: 1rem; color:hsl(0, 0%, 30%);">Selected farm:</label>
    </div>
  </div> <br><br><br><br>

  {{-- Product Status --}}
  <div id="card-product-status" class="col grey lighten-4">
    {{-- Product Status label --}}
    <div class="col s12">
      <h4 class="left-align">
        {{-- <a href="{{ route('dashboard.productStatus') }}" --}}
        <a href="#" style="font-weight: 500; color:hsl(0, 0%, 13%); cursor: default;">
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
            <a class="grey-text text-lighten-4">Boar: @{{ requestedProductsBoar }} </a>
            <a class="grey-text text-lighten-4">Sow: @{{ requestedProductsSow }} </a>
            <a class="grey-text text-lighten-4">Gilt: @{{ requestedProductsGilt }} </a>
            <a class="grey-text text-lighten-4">Semen: @{{ requestedProductsSemen }} </a>
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
            <a class="grey-text text-lighten-4">Boar: @{{ reservedProductsBoar }} </a>
            <a class="grey-text text-lighten-4">Sow: @{{ reservedProductsSow }} </a>
            <a class="grey-text text-lighten-4">Gilt: @{{ reservedProductsGilt }} </a>
            <a class="grey-text text-lighten-4">Semen: @{{ reservedProductsSemen }} </a>
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
            <a class="grey-text text-lighten-4">Boar: @{{ onDeliveryProductsBoar }} </a>
            <a class="grey-text text-lighten-4">Sow: @{{ onDeliveryProductsSow }} </a>
            <a class="grey-text text-lighten-4">Gilt: @{{ onDeliveryProductsGilt }} </a>
            <a class="grey-text text-lighten-4">Semen: @{{ onDeliveryProductsSemen }} </a>
          </div>
        </div>

      </a>
    </div>

    {{-- Product Management --}}
    <div class="row"></div>
    <div class="row">
      <h4 class="left-align" style="font-weight: 500; margin-left: 2vw; margin-top: 5vh;">
        {{-- <a href="{{route('products',['type' => 'all-type', 'status' => 'all-status', 'sort' => 'none'])}}"
        style="color:hsl(0, 0%, 13%);"
        > --}}
        <a href="#" style="font-weight: 500; color:hsl(0, 0%, 13%); cursor: default;">
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
            <a class="grey-text text-lighten-4">Boar: @{{ hiddenProductsBoar }} </a>
            <a class="grey-text text-lighten-4">Sow: @{{ hiddenProductsSow }} </a>
            <a class="grey-text text-lighten-4">Gilt: @{{ hiddenProductsGilt }} </a>
            <a class="grey-text text-lighten-4">Semen: @{{ hiddenProductsSemen }} </a>
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
            <a class="grey-text text-lighten-4">Boar: @{{ displayedProductsBoar }} </a>
            <a class="grey-text text-lighten-4">Sow: @{{ displayedProductsSow }}
            </a>
            <a class="grey-text text-lighten-4">Gilt: @{{ displayedProductsGilt }} </a>
            <a class="grey-text text-lighten-4">Semen: @{{ displayedProductsSemen }} </a>
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
            <a class="grey-text text-lighten-4">Boar:
              @{{ availableProductsBoar }} </a>
            <a class="grey-text text-lighten-4">Sow:
              @{{ availableProductsSow }} </a>
            <a class="grey-text text-lighten-4">Gilt:
              @{{ availableProductsGilt }} </a>
            <a class="grey-text text-lighten-4">Semen:
              @{{ availableProductsSemen }} </a>
          </div>
        </div>
      </a>
    </div>

  </div>

  <div class="row"></div>
  <div class="row"></div>
  <div class="row"></div>

  {{-- Sales Overview --}}
  <div class="row hide-on-small-only">
    <h4 class="left-align" style="font-weight: 500; margin-left: 1vw;">
      Sales Overview
    </h4> <br>

    {{-- Guide text --}}
    <p style="color:hsl(0, 0%, 30%); margin-left: 1vw;">Select range:</p>

    {{-- Charts --}}
    <div id="charts-container" class="row">
      <div class="row">
        {{-- Frequencies --}}
        <div class="col s2">
          {{-- Monthly --}}
          <input class="with-gap" name="frequency" type="radio" id="frequency-monthly" value="monthly"
            v-model="chosenFrequency" @change="valueChange" />
          <label for="frequency-monthly">Monthly</label>

          {{-- Weekly --}}
          <input class="with-gap" name="frequency" type="radio" id="frequency-weekly" value="weekly"
            v-model="chosenFrequency" @change="valueChange" />
          <label for="frequency-weekly">Weekly</label>

          {{-- Daily --}}
          <input class="with-gap" name="frequency" type="radio" id="frequency-daily" value="daily"
            v-model="chosenFrequency" @change="valueChange" />
          <label for="frequency-daily">Daily</label>
        </div>

        {{-- Dates --}}
        <div class="col s10">
          <div class="row">
            {{-- Date from --}}
            <div class="input-field col s4">
              <custom-date-from-select v-model="dateFromInput" :date-accreditation="latestAccreditation"
                @date-from-select="dateFromChange">
              </custom-date-from-select>
            </div>

            {{-- Date to --}}
            <div class="input-field col s4">
              <custom-date-to-select v-model="dateToInput" @date-to-select="dateToChange"
                v-show="chosenFrequency !== 'weekly'">
              </custom-date-to-select>
            </div>
          </div>
        </div>
      </div>

      {{-- View Sales Button --}}
      <div class="row">
        <div class="col s2">
          <a class="btn primary primary-hover" @click.prevent="retrieveSoldProducts">
            <b>View Sales</b>
          </a>
        </div>

      </div>

      {{-- Bar Chart--}}
      <div class="col s12">
        <canvas id="barChart"></canvas>
      </div>
    </div>

    <div class="row"></div>
    <div class="row"></div>

    {{-- Overall Average Rating Container --}}
    <div class="col s12 m6">
      <br>
      <div class="card hoverable">
        <div class="card-content white">
          <span class="card-title">
            <a href="{{route('dashboard.reviews')}}" style="font-weight: 700; color:hsl(0, 0%, 13%);">
              Overall Average Rating
            </a>
          </span>
          <h2 class="blue-text">@{{ overallRatings }}/5</h2>
          <br><br>
          <span style="color:hsl(0, 0%, 45%);">No. of reviews: @{{ dashboardStats.ratings.reviewsSize }}</span>
        </div>
        <div class="left-align" style="background: hsl(0, 0%, 97%); padding-top: 3vh; padding-bottom: 2vh;">
          <a style="color:hsl(0, 0%, 29%); padding-left: 2vw;">Delivery: @{{ dashboardStats.ratings.delivery }} </a><br>
          <a style="color:hsl(0, 0%, 29%); padding-left: 2vw;">Transaction: @{{ dashboardStats.ratings.transaction }}
          </a><br>
          <a style="color:hsl(0, 0%, 29%); padding-left: 2vw;">Product Quality:
            @{{ dashboardStats.ratings.productQuality }} </a>
        </div>
      </div>
    </div>

    {{-- Reviews --}}
    <div class="col s12 m6">
      <br>
      <div class="card hoverable">
        <div class="card-content">
          <span class="card-title">
            <a href="{{route('dashboard.reviews')}}" style="font-weight: 700;  color:hsl(0, 0%, 13%);">
              Reviews
            </a>
          </span>
          <div id="review-slider" class="slider">
            <ul class="slides" style="background:hsl(0, 0%, 97%);">
              <li v-for="review in (dashboardStats.ratings.reviews).slice(0,3)">
                <div class="caption truncate right-align">
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
    <div class="col s12 center-align">
      <a href="{{route('map.customers')}}">
        <div class="waves-effect waves-light card hoverable teal darken-3" style="border-radius: 7px !important;">
          <div class="card-content center-align">
            <span class="card-title" style="font-weight: 600; color: #fafafa;">
              See the locations of your customers
            </span>
          </div>
        </div>
      </a>
    </div>
  </div>
  @endsection

  @section('customScript')
  <script type="text/javascript">
    $(document).ready(function () {
      $('#farm-wrapper').on('click', function (event) {
        event.stopPropagation();
      });

      $('.datepicker').on('mousedown', function (event) {
        event.preventDefault();
      });
    });

    // Get the data from the Dashboard Controller and transfer it to Vue

    var rawLatestAccreditation = "{{ $latestAccreditation }}";
    var rawServerDateNow = "{{ $serverDateNow }}";
    var rawChartTitle = "{!! $soldData['title'] !!}";
    var rawLabels = {!! json_encode($soldData['labels'])!!};
    var rawDataBoar = {{ json_encode($soldData['dataSets'][0]) }};
    var rawDataSow = {{ json_encode($soldData['dataSets'][1]) }};
    var rawDataGilt = {{ json_encode($soldData['dataSets'][2]) }};
    var rawDataSemen = {{ json_encode($soldData['dataSets'][3]) }};

    var farmAddresses = {!! json_encode($farmAddresses) !!};

    var rawDashboardStats = {!! json_encode($dashboardStats) !!};
  </script>
  <script src="{{ elixir('/js/breeder/dashboard.js') }}"></script>
  @endsection