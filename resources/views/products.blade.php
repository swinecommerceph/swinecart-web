@extends('layouts.default')

@section('title')
    | Products
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
@endif

@section('content')
  <div class="container">

    <!-- Search bar -->
    <nav id="search-container">
      <div id="search-field" class="nav-wrapper white">
        <div style="height:1px;"></div>
        <form>
          <div class="input-field">
              <input 
                id="search"
                type="search"
                name="q"
                placeholder="Search for a product"
                value="{{ request('q') }}"
                autocomplete="off">
              <label class="label-icon" for="search">
                <i class="material-icons teal-text">search</i>
              </label>
              <i class="material-icons">close</i>
          </div>
        </form>
      </div>
    </nav>

    <div 
      id="search-results"
      class="z-depth-2" style="display:none; position:absolute; background-color:white; z-index:9999;">
        <ul></ul>
    </div>

    <div class="row" style="padding-top:1rem;">
      <!-- Chips -->
      <div id="chip-container" class="col s9 left"></div>

      <!-- Sort Order -->
      <div class="input-field col s3 right">
        <div class="">
          <select>
            <option value="" selected>Relevance</option>

            <option 
              value="birthdate-asc"
              @if(!empty($filters['birthdate-asc'])) {{ $filters['birthdate-asc'] }} @endif 
            >
              Age: High to Low
            </option>
            
            <option 
              value="birthdate-desc"
              @if(!empty($filters['birthdate-desc'])) {{ $filters['birthdate-desc'] }} @endif
            >
              Age: Low to High
            </option>
            
            <option 
              value="backfat_thickness-asc"
              @if(!empty($filters['backfat_thickness-asc'])) {{ $filters['backfat_thickness-asc'] }} @endif 
            >
              Backfat Thickness
            </option>

            <option 
              value="fcr-asc"
              @if(!empty($filters['fcr-asc'])) {{ $filters['fcr-asc'] }} @endif
            >
              Feed Conversion Ratio
            </option>
            
            <option 
              value="adg-desc"
              @if(!empty($filters['adg-desc'])) {{ $filters['adg-desc'] }} @endif
            >
              Average Daily Gain
            </option>

          </select>
          <label> Sort By</label>
        </div>
      </div>
    </div>

    <div id="general-container" class="row">
      
      <!-- LEFT COLUMN -->
      <div id="filter-container" class="col m3 l3" style="font-size: 0.96em;">
        <div id="collapsible-container">
          <ul class="collapsible" data-collapsible="accordion">
           
            <li id="filter-type">
              <div class="collapsible-header active">
                <i class="material-icons teal-text">more</i>Type
              </div>
              <div class="collapsible-body">
                <!-- Type -->
                <p>
                    <input 
                      type="checkbox"
                      class="filled-in filter-type"
                      id="check-boar"
                      data-type="boar"
                      @if(!empty($filters['boar'])) {{$filters['boar']}} @endif/>
                    <label 
                      for="check-boar"
                      @if(!empty($filters['boar'])) style="font-weight:500; color:#000;" @endif
                    >
                      Boar
                    </label><br>

                    <input 
                      type="checkbox"
                      class="filled-in filter-type"
                      id="check-sow"
                      data-type="sow"
                      @if(!empty($filters['sow'])) {{$filters['sow']}} @endif/>
                    <label 
                      for="check-sow"
                      @if(!empty($filters['sow'])) style="font-weight:500; color:#000;" @endif
                    >
                      Sow
                    </label><br>

                    <input 
                      type="checkbox"
                      class="filled-in filter-type"
                      id="check-gilt"
                      data-type="gilt"
                      @if(!empty($filters['gilt'])) {{$filters['gilt']}} @endif/>
                    <label 
                      for="check-gilt"
                      @if(!empty($filters['gilt'])) style="font-weight:500; color:#000;" @endif
                    >
                      Gilt
                    </label><br>

                    <input 
                      type="checkbox"
                      class="filled-in filter-type"
                      id="check-semen"
                      data-type="semen"
                      @if(!empty($filters['semen'])) {{$filters['semen']}} @endif/>
                    <label 
                      for="check-semen"
                      @if(!empty($filters['semen'])) style="font-weight:500; color:#000;" @endif
                    >
                      Semen
                    </label><br>
                </p>
              </div>
            </li>

            <li id="filter-details">
              <div class="collapsible-header">
                <i class="material-icons teal-text">details</i>Breed
              </div>
              <div class="collapsible-body">
                <p class="range-field">

                    <!-- Breed -->
                    @foreach($breedFilters as $breedFilter)

                        <input 
                          type="checkbox"
                          class="filled-in filter-breed"
                          id="check-{{$breedFilter->name}}"
                          data-breed="{{$breedFilter->name}}"
                          @if(!empty($filters[$breedFilter->name])) {{$filters[$breedFilter->name]}} @endif/>
                        <label 
                          for="check-{{$breedFilter->name}}"
                          @if(!empty($filters[$breedFilter->name])) style="font-weight:500; color:#000;" @endif
                        >
                          {{ucfirst($breedFilter->name)}}
                        </label><br>

                    @endforeach

                    <input 
                      type="checkbox"
                      class="filled-in filter-breed"
                      id="check-crossbreed"
                      data-breed="crossbreed"
                      @if(!empty($filters['crossbreed'])) {{$filters['crossbreed']}} @endif/>
                    <label 
                      for="check-crossbreed"
                      @if(!empty($filters['crossbreed'])) style="font-weight:500; color:#000;" @endif
                    >
                      Crossbreed
                    </label>
                </p>
              </div>
            </li>

            <!-- Filter Breeder -->
            {{-- <li id="filter-breeder">
              <div class="collapsible-header">
                <i class="material-icons teal-text">person</i>Breeder
              </div>
              <div class="collapsible-body">
                <p class="range-field">

                  @foreach($breeders as $breeder)
                    <input 
                      type="checkbox"
                      class="filled-in filter-breeder"
                      id="check-{{ $breeder->name }}"
                      data-breeder="{{ $breeder->name }}"
                    >
                    <label for="check-{{ $breeder->name }}">
                      {{ $breeder->name }}
                    </label><br>

                  @endforeach
                </p>
              </div>
            </li> --}}

            <li id="filter-location">
              <div 
                class="collapsible-header">
                <i class="material-icons teal-text">place</i>
                <a 
                  href="{{ route('map.breeders') }}"
                  class="grey-text text-darken-4"
                >
                  Breeder Locations
                </a>
              </div>
            </li>

          </ul>
        </div>
      </div>

      

    </div>

  </div>
@endsection

@section('customScript')
    <script src="{{ elixir('/js/customer/viewProducts.js') }}"></script>
@endsection
