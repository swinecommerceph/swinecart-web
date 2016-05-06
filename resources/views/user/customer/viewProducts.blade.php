{{--
    Displays products of all Breeder users
--}}

@extends('user.customer.home')

@section('title')
    | Products
@endsection

@section('pageId')
    id="page-customer-view-products"
@endsection

@section('breadcrumbTitle')
    Products
@endsection

@section('breadcrumb')
    <a href="{{ route('home_path') }}" class="breadcrumb">Home</a>
    <a href="#!" class="breadcrumb">Products</a>
@endsection

@section('content')
    {{-- Search bar --}}
    <nav id="search-container">
        <div id="search-field" class="nav-wrapper white">
            <div style="height:1px;">
            </div>
            <form>
                <div class="input-field">
                    <input id="search" type="search" placeholder="Search for a product" required>
                    <label for="search"><i class="material-icons teal-text">search</i></label>
                    <i class="material-icons">close</i>
                </div>
            </form>
        </div>
    </nav>

    <div class="row" style="padding-top:1rem;">
        {{-- Chips --}}
        <div id="chip-container" class="col s9 left"> </div>

        {{-- Sort Order --}}
        <div class="input-field col s3 right">
            <div class="">
                <select>
                    <option value="" @if(!empty($filters['none']))
                                {{ $filters['none'] }}
                            @elseif(empty($filters['none']))
                                selected
                            @endif>Relevance</option>
                    <option value="age-asc" @if(!empty($filters['age-asc'])) {{ $filters['age-asc'] }} @endif > Age: Low to High</option>
                    <option value="age-desc" @if(!empty($filters['age-desc'])) {{ $filters['age-desc'] }} @endif >Age: High to Low</option>
                    <option value="backfat_thickness-asc" @if(!empty($filters['backfat_thickness-asc'])) {{ $filters['backfat_thickness-asc'] }} @endif >Backfat Thickness</option>
                    <option value="fcr-asc" @if(!empty($filters['fcr-asc'])) {{ $filters['fcr-asc'] }} @endif >Feed Conversion Ratio</option>
                    <option value="adg-desc" @if(!empty($filters['adg-desc'])) {{ $filters['adg-desc'] }} @endif >Average Daily Gain</option>
                </select>
                <label> Sort By</label>
            </div>
        </div>
    </div>

    <div id="general-container" class="row">
        {{-- For the Filters (left column) --}}
        <div id="filter-container" class="col m3 l3">
            <div style="height: 1px;">
            </div>
            <div id="collapsible-container">
                <ul class="collapsible" data-collapsible="accordion">

                        <li id="filter-type">
                          <div class="collapsible-header active"><i class="material-icons">more</i>Type</div>
                          <div class="collapsible-body">
                                {{-- Type --}}
                                <p>
                                    <input type="checkbox" class="filled-in filter-type" id="check-boar" data-type="boar" @if(!empty($filters['boar']))
                                        {{$filters['boar']}}
                                    @endif/>
                                    <label for="check-boar" @if(!empty($filters['boar']))
                                        style="font-weight:500; color:#000;"
                                    @endif>Boar</label><br>

                                    <input type="checkbox" class="filled-in filter-type" id="check-sow" data-type="sow" @if(!empty($filters['sow']))
                                        {{$filters['sow']}}
                                    @endif/>
                                    <label for="check-sow" @if(!empty($filters['sow']))
                                        style="font-weight:500; color:#000;"
                                    @endif>Sow</label><br>

                                    <input type="checkbox" class="filled-in filter-type" id="check-semen" data-type="semen" @if(!empty($filters['semen']))
                                        {{$filters['semen']}}
                                    @endif/>
                                    <label for="check-semen" @if(!empty($filters['semen']))
                                        style="font-weight:500; color:#000;"
                                    @endif>Semen</label><br>
                                </p>
                          </div>
                        </li>
                        <li id="filter-details">
                          <div class="collapsible-header"><i class="material-icons">details</i>Breed</div>
                          <div class="collapsible-body">
                            <p class="range-field">

                                {{-- Breed --}}
                                @foreach($breedFilters as $breedFilter)

                                    <input type="checkbox" class="filled-in filter-breed" id="check-{{$breedFilter->name}}" data-breed="{{$breedFilter->name}}" @if(!empty($filters[$breedFilter->name]))
                                        {{$filters[$breedFilter->name]}}
                                    @endif/>
                                    <label for="check-{{$breedFilter->name}}" @if(!empty($filters[$breedFilter->name]))
                                        style="font-weight:500; color:#000;"
                                    @endif>{{ucfirst($breedFilter->name)}}</label><br>

                                @endforeach

                                <input type="checkbox" class="filled-in filter-breed" id="check-crossbreed" data-breed="crossbreed" @if(!empty($filters['crossbreed']))
                                    {{$filters['crossbreed']}}
                                @endif/>
                                <label for="check-crossbreed" @if(!empty($filters['crossbreed']))
                                    style="font-weight:500; color:#000;"
                                @endif>Crossbreed</label>
                            </p>

                          </div>
                        </li>
                        <li id="filter-location">
                            <div class="collapsible-header"><i class="material-icons">place</i>Province Location</div>
                            <div class="collapsible-body">
                                <div class="input-field col">
                                    <select>
                                        <option value="reg1" selected>Region I</option>
                                        <option value="reg2">Region II</option>
                                    </select>
                                </div>
                            </div>
                        </li>
                </ul>
            </div>
        </div>

        {{-- For the showcase of products (right column) --}}
        <div id="showcase-container" class="col m9 l9">

            <div class="row">

            <?php $counter = 1; ?>
            @foreach($products as $product)
                {{-- Card --}}
                <div class="col s12 m6 l6 ">
                  <div class="card hoverable">
                    <div class="card-image">
                        <a href="{{ route('products.viewDetail', ['product' => $product->id]) }}" >
                            <img height="220" src="{{ $product->img_path }}">
                        </a>
                    </div>
                    <div class="card-content">
                      <span class="card-title activator grey-text text-darken-4">{{$product->name}}<i class="material-icons right">more_vert</i></span>
                      <div class="row">
                          <div class="col s9">
                              {{$product->type}} - {{$product->breed}} <br>
                              {{$product->age}} days old
                          </div>
                          <div class="col right">
                            {!! Form::open(['route' => 'cart.add', 'data-product-id' => $product->id, 'data-type' => $product->type]) !!}
                                <a href="#" class="tooltipped add-to-cart"  data-position="bottom" data-delay="50" data-tooltip="Add to Swine Cart">
                                    <i class="material-icons red-text" style="font-size:35px">add_shopping_cart</i>

                                </a>
                            {!! Form::close()!!}
                          </div>
                      </div>
                    </div>
                    <div class="card-reveal">
                        <span class="card-title grey-text text-darken-4">{{$product['name']}}<i class="material-icons right">close</i></span>
                        <p>
                            Average Daily Gain: {{ $product->adg }} g<br>
                            Feed Conversion Ratio: {{ $product->fcr }} <br>
                            Backfat Thickness: {{ $product->backfat_thickness }} mm<br>
                        </p>
                        <p>
                            Breeder info <br>
                            Name: {{ $product->breeder }} <br>
                            Farm Location: {{ $product->farm_province }}
                        </p>
                        <div class="row">
                            <br>
                            <div class="col">
                                <a href="{{ route('products.viewDetail', ['product' => $product->id]) }}" class="waves-effect waves-light btn red">View All Info</a>
                            </div>
                            <div class="col right">
                                {!! Form::open(['route' => 'cart.add', 'data-product-id' => $product->id, 'data-type' => $product->type]) !!}
                                    <a href="#" class="tooltipped add-to-cart"  data-position="bottom" data-delay="50" data-tooltip="Add to Swine Cart">
                                        <i class="material-icons red-text" style="font-size:35px;">add_shopping_cart</i>
                                    </a>
                                {!! Form::close() !!}
                            </div>
                        </div>

                    </div>
                  </div>
                </div>
            @endforeach

            </div>

            {{-- Pagination --}}
            <div class="row">
                <div class="center-align">
                    {!! $products->appends($urlFilters)->links() !!}
                </div>
            </div>

        </div>
    </div>

@endsection

@section('customScript')
    <script src="/js/customer/filter.js"> </script>
    <script src="/js/customer/viewProducts_script.js"> </script>
@endsection
