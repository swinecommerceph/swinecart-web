{{--
    Displays products of all Breeder users
--}}

@extends('user.customer.home')

@section('title')
    | Products
@endsection

@section('page-id')
    id="page-customer-view-products"
@endsection

@section('breadcrumb-title')
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
        <!-- Sort Order -->
        <div class="input-field col right">
            <div class="left">
                <select>
                    <option value="" @if(!empty($filters['none']))
                                {{ $filters['none'] }}
                            @elseif(empty($filters['none']))
                                selected
                            @endif>Relevance</option>
                    <option value="age-asc" @if(!empty($filters['age-asc'])) {{ $filters['age-asc'] }} @endif > Age: Low to High</option>
                    <option value="age-desc" @if(!empty($filters['age-desc'])) {{ $filters['age-desc'] }} @endif >Age: High to Low</option>
                    <option value="backfat_thickness-asc" @if(!empty($filters['backfat_thickness-asc'])) {{ $filters['backfat_thickness-asc'] }} @endif >BT: Low to High</option>
                    <option value="backfat_thickness-desc" @if(!empty($filters['backfat_thickness-desc'])) {{ $filters['backfat_thickness-desc'] }} @endif >BT: High to Low</option>
                    <option value="fcr-asc" @if(!empty($filters['fcr-asc'])) {{ $filters['fcr-asc'] }} @endif >FCR: Low to High</option>
                    <option value="fcr-desc" @if(!empty($filters['fcr-desc'])) {{ $filters['fcr-desc'] }} @endif >FCR: High to Low</option>
                    <option value="adg-asc" @if(!empty($filters['adg-asc'])) {{ $filters['adg-asc'] }} @endif >ADG: Low to High</option>
                    <option value="adg-desc" @if(!empty($filters['adg-desc'])) {{ $filters['adg-desc'] }} @endif >ADG: High to Low</option>
                </select>
                <label> Sort By</label>
            </div>
            {{-- <div class="col right">
                <p>
                    <input class="with-gap" name="group-radio" type="radio" id="radio-asc"  />
                    <label for="radio-asc">Ascending</label>
                    <input class="with-gap" name="group-radio" type="radio" id="radio-desc"  />
                    <label for="radio-desc">Descending</label>
                </p>
            </div> --}}



        </div>
    </div>

    <div id="general-container" class="row">
        {{-- For the Filters (left column) --}}
        <div id="filter-container" class="col m3 l3">
            <div style="height: 1px;">
            </div>
            <div id="collapsible-container">
                <ul class="collapsible" data-collapsible="expandable">

                        <li id="filter-type">
                          <div class="collapsible-header"><i class="material-icons">more</i>Type</div>
                          <div class="collapsible-body">
                                {{-- Type --}}
                                <p>
                                    <input type="checkbox" class="filled-in filter-type" id="check-boar" data-type="boar" @if(!empty($filters['boar']))
                                        {{$filters['boar']}}
                                    @endif/>
                                    <label for="check-boar" @if(!empty($filters['boar']))
                                        style="font-weight:500;"
                                    @endif>Boar</label><br>

                                    <input type="checkbox" class="filled-in filter-type" id="check-sow" data-type="sow" @if(!empty($filters['sow']))
                                        {{$filters['sow']}}
                                    @endif/>
                                    <label for="check-sow" @if(!empty($filters['sow']))
                                        style="font-weight:500;"
                                    @endif>Sow</label><br>

                                    <input type="checkbox" class="filled-in filter-type" id="check-semen" data-type="semen" @if(!empty($filters['semen']))
                                        {{$filters['semen']}}
                                    @endif/>
                                    <label for="check-semen" @if(!empty($filters['semen']))
                                        style="font-weight:500;"
                                    @endif>Semen</label><br>
                                </p>
                          </div>
                        </li>
                        <li id="filter-details">
                          <div class="collapsible-header"><i class="material-icons">details</i>Product Details</div>
                          <div class="collapsible-body">
                            <p class="range-field">
                                {{-- Age --}}
                                <label for="slide-age">Age</label><br>
                                <input type="range" id="slide-age" min="0" max="15" />

                                {{-- Breed --}}
                                @foreach($breedFilters as $breedFilter)

                                    <input type="checkbox" class="filled-in filter-breed" id="check-{{$breedFilter->name}}" data-breed="{{$breedFilter->name}}" @if(!empty($filters[$breedFilter->name]))
                                        {{$filters[$breedFilter->name]}}
                                    @endif/>
                                    <label for="check-{{$breedFilter->name}}" @if(!empty($filters[$breedFilter->name]))
                                        style="font-weight:500;"
                                    @endif>{{ucfirst($breedFilter->name)}}</label><br>

                                @endforeach

                                <input type="checkbox" class="filled-in filter-breed" id="check-crossbreed" data-breed="crossbreed" @if(!empty($filters['crossbreed']))
                                    {{$filters['crossbreed']}}
                                @endif/>
                                <label for="check-crossbreed" @if(!empty($filters['crossbreed']))
                                    style="font-weight:500;"
                                @endif>Crossbreed</label>

                            </p>

                          </div>
                        </li>
                </ul>
            </div>
        </div>

        {{-- For the showcase of products (right column) --}}
        <div id="showcase-container" class="col m9 l9">

            <?php $counter = 1; $item = 0;?>
            @foreach($products as $product)
                <?php if($counter > 2) $counter = 1;?>
                @if($counter == 1)
                    <div class="row">
                @endif
                    {{-- Left --}}
                    <div class="col s12 m6 l6 ">
                      <div class="card hoverable">
                        <div class="card-image">
                          <img height="220" src="/{{ $images[$item] }}">
                        </div>
                        <div class="card-content">
                          <span class="card-title activator grey-text text-darken-4">{{$product->name}}<i class="material-icons right">more_vert</i></span>
                          <p class="row">
                              <span class="col s9">
                                  <?php
                                    if(str_contains($breeds[$item],'+')){
                                        $part = explode("+", $breeds[$item]);
                                        $breedValue = ucfirst($part[0])." x ".ucfirst($part[1]);
                                    }
                                    else $breedValue = ucfirst($breeds[$item]);
                                  ?>
                                  {{ucfirst($product->type).' - '.$breedValue}} <br>
                                  {{$product->age}} days old
                              </span>
                              <span class="right">
                                <a href="#" class="right tooltipped add-to-cart"  data-position="bottom" data-delay="50" data-tooltip="Add to Swine Cart" data-product-id="{{$product->id}}">
                                    <i class="material-icons red-text" style="font-size:35px">add_shopping_cart</i>
                                </a>
                              </span>
                          </p>
                        </div>
                        <div class="card-reveal">
                            <span class="card-title grey-text text-darken-4">{{$product->name}}<i class="material-icons right">close</i></span>
                            <p>
                                Average Daily Gain (grams): {{$product->adg}}<br>
                                FCR: {{$product->fcr}} <br>
                                Backfat Thickness (mm): {{$product->backfat_thickness}}<br>
                            </p>
                            <p>
                                Breeder info <br>
                                Name: {{ $breeders[$item] }} <br>
                                Farm Location: {{ $farms[$item] }}
                            </p>
                            <div>
                                <br>
                                <a href="{{ route('products.viewDetail', ['product' => $product->id]) }}" class="waves-effect waves-light btn red">View All Info</a>
                                <a href="#" class="right tooltipped add-to-cart"  data-position="bottom" data-delay="50" data-tooltip="Add to Swine Cart" data-product-id="{{$product->id}}">
                                    <i class="material-icons red-text" style="font-size:35px;">add_shopping_cart</i>
                                </a>
                            </div>

                        </div>
                      </div>
                    </div>

                @if($counter == 2)
                    </div>
                @endif
                <?php $counter++; $item++;?>
            @endforeach

            @if($counter == 1)
                <div class="col s12">
                    No results found.
                </div>
            @elseif($counter == 2)
                </div>
            @endif


            {{-- Pagination --}}
            <div class="row">
                <div class="center-align">
                    {{ $products->links() }}
                </div>
            </div>

        </div>
    </div>

@endsection

@section('customScript')
    <script src="/js/customer/filter.js"> </script>
    <script src="/js/customer/viewProducts_script.js"> </script>
@endsection
