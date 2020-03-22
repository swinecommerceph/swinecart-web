{{--
    Displays products of all Breeder users
--}}

@extends( (Auth::guest()) ? 'layouts.default' : 'user.customer.home')

@section('title')
| Products
@endsection

@section('pageId')
id="page-customer-view-products"
@endsection

@if(!Auth::guest())
@section('breadcrumbTitle')
Browse Products
@endsection
@else
@section('publicProductsBreadcrumbTitle')
Browse Products
@endsection
@endif

@if(!Auth::guest())
@section('breadcrumb')
<a href="{{ route('home_path') }}" class="breadcrumb">Home</a>
<a href="#!" class="breadcrumb">Products</a>
@endsection
@else
@section('publicBreadcrumb')
<a href="#!" class="breadcrumb">
  <span class="primary-text" style="font-weight: 600;">Note:</span>
  You need to be logged in to actually buy a product.
</a>
@endsection
@endif

@section('content')
<div class="container">

  {{-- Search bar --}}
  <nav id="search-container">
    <div id="search-field" class="nav-wrapper white">
      <div style="height:1px;">
      </div>
      <form>
        <div class="input-field">
          <input id="search" type="search" name="q" placeholder="Search for a product" value="{{ request('q') }}"
            autocomplete="off">
          <label class="label-icon" for="search"><i class="material-icons teal-text">search</i></label>
          <i class="material-icons">close</i>
        </div>
      </form>
    </div>
  </nav>

  <div id="search-results" class="z-depth-2"
    style="display:none; position:absolute; background-color:white; z-index:9999;">
    <ul></ul>
  </div>

  <div class="row" style="padding-top:1rem;">
    {{-- Chips --}}
    <div id="chip-container" class="col s9 left"> </div>

    {{-- Sort Order --}}
    <div id="sort-by" class="input-field col s3 right">
      <select>
        <option value="" selected>Relevance</option>
        <option value="birthdate-asc" @if(!empty($filters['birthdate-asc'])) {{ $filters['birthdate-asc'] }} @endif>
          Age: High to Low</option>
        <option value="birthdate-desc" @if(!empty($filters['birthdate-desc'])) {{ $filters['birthdate-desc'] }} @endif>
          Age: Low to High</option>
        <option value="backfat_thickness-asc" @if(!empty($filters['backfat_thickness-asc']))
          {{ $filters['backfat_thickness-asc'] }} @endif>Backfat Thickness</option>
        <option value="fcr-asc" @if(!empty($filters['fcr-asc'])) {{ $filters['fcr-asc'] }} @endif>Feed Conversion Ratio
        </option>
        <option value="adg-desc" @if(!empty($filters['adg-desc'])) {{ $filters['adg-desc'] }} @endif>Average Daily Gain
        </option>
        <option value="breederrating-asc" @if(!empty($filters['breederrating-asc'])) {{ $filters['breederrating-asc'] }}
          @endif>Breeder Rating: High to Low</option>
      </select>
      <label> Sort By</label>
    </div>
  </div>

  <div id="general-container" class="row">
    {{-- For the Filters (left column) --}}
    <div id="filter-container" class="col m3 l3" style="font-size: 0.96em;">
      <div style="height: 1px;">
      </div>
      <div id="collapsible-container">
        <ul class="collapsible" data-collapsible="accordion">

          <li id="filter-type">
            <div class="collapsible-header active">
              <i class="material-icons teal-text">more</i>Type
            </div>
            <div class="collapsible-body">
              {{-- Type --}}
              <p>
                <input type="checkbox" class="filled-in filter-type" id="check-boar" data-type="boar"
                  @if(!empty($filters['boar'])) {{$filters['boar']}} @endif />
                <label for="check-boar" @if(!empty($filters['boar'])) style="font-weight:500; color:#000;"
                  @endif>Boar</label><br>

                <input type="checkbox" class="filled-in filter-type" id="check-sow" data-type="sow"
                  @if(!empty($filters['sow'])) {{$filters['sow']}} @endif />
                <label for="check-sow" @if(!empty($filters['sow'])) style="font-weight:500; color:#000;"
                  @endif>Sow</label><br>

                <input type="checkbox" class="filled-in filter-type" id="check-gilt" data-type="gilt"
                  @if(!empty($filters['gilt'])) {{$filters['gilt']}} @endif />
                <label for="check-gilt" @if(!empty($filters['gilt'])) style="font-weight:500; color:#000;"
                  @endif>Gilt</label><br>

                <input type="checkbox" class="filled-in filter-type" id="check-semen" data-type="semen"
                  @if(!empty($filters['semen'])) {{$filters['semen']}} @endif />
                <label for="check-semen" @if(!empty($filters['semen'])) style="font-weight:500; color:#000;"
                  @endif>Semen</label><br>
              </p>
            </div>
          </li>
          <li id="filter-details">
            <div class="collapsible-header"><i class="material-icons teal-text">details</i>Breed</div>
            <div class="collapsible-body">
              <p class="range-field">

                {{-- Breed --}}
                @foreach($breedFilters as $breedFilter)

                <input type="checkbox" class="filled-in filter-breed" id="check-{{$breedFilter->name}}"
                  data-breed="{{$breedFilter->name}}" @if(!empty($filters[$breedFilter->name]))
                {{$filters[$breedFilter->name]}}
                @endif/>
                <label for="check-{{$breedFilter->name}}" @if(!empty($filters[$breedFilter->name]))
                  style="font-weight:500; color:#000;"
                  @endif>
                  {{ucfirst($breedFilter->name)}}
                </label><br>

                @endforeach

                <input type="checkbox" class="filled-in filter-breed" id="check-crossbreed" data-breed="crossbreed"
                  @if(!empty($filters['crossbreed'])) {{$filters['crossbreed']}} @endif />
                <label for="check-crossbreed" @if(!empty($filters['crossbreed'])) style="font-weight:500; color:#000;"
                  @endif>Crossbreed</label>
              </p>

            </div>
          </li>
          <li id="filter-breeder">
            <div class="collapsible-header">
              <i class="material-icons teal-text">person</i>
              Breeder
            </div>
            <div class="collapsible-body">
              <p class="range-field">

                @foreach($breeders as $breeder)
                <input type="checkbox" class="filled-in filter-breeder"
                  id="check-{{ str_replace(" ", "-", $breeder['user']->name) }}"
                  data-breeder="{{ $breeder['user']->name }}" @if (!empty($filters[$breeder['user']->name]))
                {{ $filters[$breeder['user']->name] }}
                @endif/>

                <label for="check-{{ str_replace(" ", "-", $breeder['user']->name) }}" @if (!empty($filters[$breeder['user']->name]))
                  @endif>
                  {{ $breeder['user']->name }}
                </label>
                <br>
                @endforeach
              </p>
            </div>
          </li>
          <li id="filter-location">
            <div class="collapsible-header">
              <i class="material-icons teal-text">place</i>
              <a href="{{ route('map.breeders') }}" class="grey-text text-darken-4">
                Breeder Locations
              </a>
            </div>
          </li>
        </ul>
      </div>
    </div>

    <!-- For the showcase of products (right column) -->
    <div id="showcase-container" class="col m9 l9">

      <div class="row">

        @forelse($products as $product)
        <!-- Card -->
        <div class="col s12 m6 l6 ">
          <div class="card hoverable">
            <div class="card-image">
              <a href="{{ route('products.cViewDetail', ['product' => $product->id]) }}">
                <img style="width: 23vw;" src="{{ $product->img_path }}">
              </a>
            </div>
            <div class="card-content" style="background: hsl(0, 0%, 97%);">
              <div class="row">
                <div class="col s10">
                  <span class="card-title truncate"
                    style="color: hsl(0, 0%, 13%); font-weight: 700;">{{$product->name}}</span>
                </div>
                <div class="col s1">
                  <span>
                    <i class="activator material-icons right" style="cursor: pointer;">more_vert</i>
                  </span>
                </div>
              </div>

              <div class="row">
                <div class="col s9">
                  <span style="color: hsl(0, 0%, 13%); font-weight: 550; padding-left: 3px;">{{$product->type}} -
                    {{$product->breed}}</span> <br>
                  @if($product->age < 0) <span style="color: hsl(0, 0%, 45%); padding-left: 3px;">
                    Age: <i>Birthdate not included</i>
                    </span>
                    @else
                    <span style="color: hsl(0, 0%, 45%); padding-left: 3px;">Age: {{$product->age}} days old</span>
                    @endif
                    <br>
                    @if($product->type == 'Semen')
                    <span>
                      <mark>Stock:</mark><i>&nbsp;not applicable</i></span>
                    @elseif($product->type != 'Semen' && $product->is_unique == 0)
                    <span>
                      <mark>Stock:</mark>&nbsp;{{ $product->quantity }}</span>
                    @else
                    <br>
                    @endif
                </div>
                <div class="col right">
                  @if(Auth::guest())
                  <a href="{{ route('login') }}" class="btn primary primary-hover tooltipped add-to-cart"
                    data-position="bottom" data-delay="50" data-tooltip="Add to Swine Cart">
                    Add to Cart
                  </a>
                  @else
                  {!! Form::open([
                  'route' => 'cart.add',
                  'data-product-id' => $product->id,
                  'data-type' => $product->type]) !!}
                  <a href="#" class="btn primary primary-hover tooltipped add-to-cart" data-position="bottom"
                    data-delay="50" data-tooltip="Add to Swine Cart">
                    Add to Cart
                  </a>
                  {!! Form::close()!!}
                  @endif
                </div>
              </div>
            </div>

            <!-- View All Info-->
            <div class="card-reveal">
              <div class="row">
                <div class="col s10">
                  <span class="card-title truncate"
                    style="color: hsl(0, 0%, 13%); font-weight: 700;">{{$product['name']}}</span>
                </div>
                <div class="col s1">
                  <span><i class="card-title material-icons right" style="cursor: pointer;">close</i></span>
                </div>
              </div>
              <br>
              <table class="col s10">
                <thead> </thead>
                <tbody>
                  @if($product->type !== 'Semen')
                  <tr>
                    <td style="color: hsl(0, 0%, 13%); font-weight: 550;"> Quantity: </td>
                    <td style="color: hsl(0, 0%, 13%); font-weight: 550;"> {{ $product->quantity }} </td>
                  </tr>
                  @endif
                  <tr>
                    <td style="color: hsl(0, 0%, 13%); font-weight: 550;"> Average Daily Gain (g): </td>
                    @if($product->adg === 0)
                    <td style="color: hsl(0, 0%, 29%);">
                      <i class="text-grey">Not Indicated</i>
                    </td>
                    @else
                    <td style="color: hsl(0, 0%, 13%); font-weight: 550;"> {{ $product->adg }} </td>
                    @endif
                  </tr>
                  <tr>
                    <td style="color: hsl(0, 0%, 13%); font-weight: 550;"> Feed Conversion Ratio: </td>
                    @if($product->fcr === 0.0)
                    <td style="color: hsl(0, 0%, 29%);">
                      <i class="text-grey">Not Indicated</i>
                    </td>
                    @else
                    <td style="color: hsl(0, 0%, 13%); font-weight: 550;"> {{ $product->fcr }} </td>
                    @endif
                  </tr>
                  <tr>
                    <td style="color: hsl(0, 0%, 13%); font-weight: 550;"> Backfat Thickness (mm): </td>
                    @if($product->backfat_thickness === 0.0)
                    <td style="color: hsl(0, 0%, 29%);">
                      <i class="text-grey">Not Indicated</i>
                    </td>
                    @else
                    <td style="color: hsl(0, 0%, 13%); font-weight: 550;"> {{ $product->backfat_thickness }} </td>
                    @endif
                  </tr>
                </tbody>
              </table>

              <div class="col s10"> <br> </div>

              <table class="col s10">
                <thead> </thead>
                <tbody>
                  <tr>
                    <td style="color: hsl(0, 0%, 45%);"> Breeder Name: </td>
                    <td style="color: hsl(0, 0%, 45%);"> {{ $product->breeder }} </td>
                  </tr>
                  <tr>
                    <td style="color: hsl(0, 0%, 45%);"> Farm Location: </td>
                    <td style="color: hsl(0, 0%, 45%);"> {{ $product->farm_province }} </td>
                  </tr>
                </tbody>
              </table>
              <div class="col s10"> <br> </div>

              <div class="row">
                <br>
                <div class="col">
                  <a href="{{ route('products.cViewDetail', ['product' => $product->id]) }}"
                    class="waves-effect waves-light" style="
                                border: 2px solid #bbdefb;
                                background-color: white;
                                padding: 8px 18px;
                                font-size: 16px;
                                cursor: pointer;
                                color: #2196f3;
                                font-weight: 700;
                                border-radius: 5px;">
                    View All Info
                  </a>
                </div>
                <div class="col right">
                  @if(Auth::guest())
                  <a href="{{ route('login') }}" class="btn primary primary-hover tooltipped add-to-cart"
                    data-position="bottom" data-delay="50" data-tooltip="Add to Swine Cart">
                    Add to Cart
                  </a>
                  @else
                  {!! Form::open([
                  'route' => 'cart.add',
                  'data-product-id' => $product->id,
                  'data-type' => $product->type]) !!}
                  <a href="#" class="btn primary primary-hover tooltipped add-to-cart" data-position="bottom"
                    data-delay="50" data-tooltip="Add to Swine Cart">
                    Add to Cart
                  </a>
                  {!! Form::close()!!}
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>

        @empty
        <div class="col s12">
          No results found.
        </div>
        @endforelse
      </div>

      <!-- Pagination -->
      <div class="row">
        <div class="center-align">
          {!! $products->appends($urlFilters)->links() !!}
        </div>
      </div>

    </div>
  </div>
</div>

@endsection

@section('customScript')
<script src="{{ elixir('/js/customer/viewProducts.js') }}"></script>
@endsection