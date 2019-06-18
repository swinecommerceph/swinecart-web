{{--
    This is the view to display
    the Breeder's products
    in card layouts
--}}

{{-- General Actions container --}}
<div class="row">
    <div class="col s4 left">
        {!! Form::open(['route' => 'products.updateSelected', 'id' => 'manage-selected-form']) !!}
            {{-- Add Button --}}
            {{-- <a href="#!" class="btn-floating btn-large waves-effect waves-light blue tooltipped add-product-button" data-position="top" data-delay="50" data-tooltip="Add Product">
                <i class="material-icons">add</i> --}}
            </a>
            {{-- Select All Button --}}
            <a href="#!" class="btn-floating btn-large waves-effect waves-light green tooltipped select-all-button" data-position="top" data-delay="50" data-tooltip="Select All Products">
                <i class="material-icons">event_available</i>
            </a>
            {{-- Display selected Button. Only show when products are hidden --}}
            @if(!empty($filters['hidden']))
                <a href="#!" class="btn-floating btn-large waves-effect waves-light teal lighten-2 tooltipped display-selected-button" data-position="top" data-delay="50" data-tooltip="Display all chosen">
                    <i class="material-icons">visibility</i>
                </a>
            {{-- Hide selected Button. Only show when products are displayed --}}
            @elseif(!empty($filters['displayed']))
                <a href="#!" class="btn-floating btn-large waves-effect waves-light teal lighten-2 tooltipped hide-selected-button" data-position="top" data-delay="50" data-tooltip="Hide all chosen">
                    <i class="material-icons">visibility_off</i>
                </a>
            @endif
            {{-- Delete selected Button --}}
            <a href="#!" class="btn-floating btn-large waves-effect waves-light red darken-4 tooltipped delete-selected-button" data-position="top" data-delay="50" data-tooltip="Delete all chosen">
                <i class="material-icons">delete</i>
            </a>
        {!! Form::close() !!}
    </div>

    {{-- Dropdown container --}}
    <div id="dropdown-container" class="col s8 right">
        <div class="row">
            <div id="sort-select" class="input-field col right">
                <select>
                    <option value="none">Relevance</option>
                    <option value="birthdate-asc" @if(!empty($filters['birthdate-asc'])) {{ $filters['birthdate-asc'] }} @endif>Age: High to Low</option>
                    <option value="birthdate-desc" @if(!empty($filters['birthdate-desc'])) {{ $filters['birthdate-desc'] }} @endif>Age: Low to High</option>
                    <option value="adg-desc" @if(!empty($filters['adg-desc'])) {{ $filters['adg-desc'] }} @endif>Average Daily Gain</option>
                    <option value="fcr-desc" @if(!empty($filters['fcr-desc'])) {{ $filters['fcr-desc'] }} @endif>Feed Conversion Ratio</option>
                    <option value="backfat_thickness-asc" @if(!empty($filters['backfat_thickness-asc'])) {{ $filters['backfat_thickness-asc'] }} @endif>Backfat Thickness</option>
                </select>
                <label>Sort By</label>
            </div>
            <div id="status-select" class="input-field col s3 right">
                <select>
                    <option value="all-status" selected>All</option>
                    <option value="displayed" @if(!empty($filters['displayed'])) {{ $filters['displayed'] }} @endif>Displayed</option>
                    <option value="hidden" @if(!empty($filters['hidden'])) {{ $filters['hidden'] }} @endif>Hidden</option>
                    <option value="requested" @if(!empty($filters['requested'])) {{ $filters['requested'] }} @endif>Requested</option>
                </select>
                <label>Status</label>
            </div>
            <div id="type-select" class="input-field col s3 right">
                <select>
                    <option value="all-type" selected>All</option>
                    <option value="boar" @if(!empty($filters['boar'])) {{ $filters['boar'] }} @endif>Boar</option>
                    <option value="sow" @if(!empty($filters['sow'])) {{ $filters['sow'] }} @endif>Sow</option>
                    <option value="gilt" @if(!empty($filters['gilt'])) {{ $filters['gilt'] }} @endif>Gilt</option>
                    <option value="semen" @if(!empty($filters['semen'])) {{ $filters['semen'] }} @endif>Semen</option>
                </select>
                <label>Show</label>
            </div>
        </div>
    </div>
</div>

{{-- Products in card elements container --}}
<div id="view-products-container" class="row grey lighten-4">
    @if (count($products) > 0)
        <?php $productNumber = 1; ?>
        @foreach($products as $product)
            <div class="col s12 m6 l4" id="product-{{$product->id}}">
                <div
                    class="card hoverable"
                    style="border: solid 4px transparent;"
                >
                    <div class="card-image">
                        @if($product->status == 'hidden')
                            <a href="{{ route('products.bViewDetail', ['product' => $product->id]) }}">
                                <img src="{{$product->img_path}}" class="hidden">
                            </a>
                        @else
                            <a href="{{ route('products.bViewDetail', ['product' => $product->id]) }}">
                                <img src="{{$product->img_path}}">
                            </a>
                        @endif
                    </div>
                    <div class="card-content">
                        <div class="row">
                            <div class="col s10">
                                 <span class="card-title grey-text text-darken-4 truncate" style="color: hsl(0, 0%, 13%); font-weight: 700;">{{$product->name}}</span>
                            </div>
                            <div class="col s1">
                                <span><i class="card-title activator material-icons right">more_vert</i></span>
                            </div>
                        </div>
                       
                        <p>

                            <span style="color: hsl(0, 0%, 13%); font-weight: 550;">{{$product->type}} - {{$product->breed}}</span> <br>
                            @if($product->age < 0)
                              <span style="color: hsl(0, 0%, 45%);">
                                Age: <i>Birthdate not included</i>
                              </span>
                            @else
                              <span style="color: hsl(0, 0%, 45%);">Age: {{$product->age}} days old</span>
                            @endif
                        
                            @if($product->is_unique === 1)
                              <span class="badge blue white-text right-align"><b>UNIQUE</b></span>
                            @else
                              <span class="badge pink accent-1 white-text right-align"><b>MULTIPLIER</b></span>
                              @if($product->quantity === 0)
                              <span class="badge orange darken-4 white-text right-align"><b>OUT OF STOCK</b></span>
                              @endif
                            @endif
                        </p>
                    </div>
                    <div class="card-action" style="background: hsl(0, 0%, 95%); border-top: none !important;">
                        <div class="row">
                            <div class="col left">
                                <input type="checkbox" id="check-{{$productNumber}}" data-product-id="{{$product->id}}" class="filled-in single-checkbox"/>
                                <label for="check-{{$productNumber}}"></label>
                            </div>
                            <div class="col right">
                                {{-- Edit Button --}}
                                <a href="{{ route('products.editProduct', ['product' => $product->id]) }}" class="tooltipped edit-product-button" data-position="top" data-delay="50" data-tooltip="Edit '{{$product->name}}'" data-product-id="{{$product->id}}">
                                    <i class="material-icons teal-text text-darken-3" style="font-size:30px">edit</i>
                                </a>
                                @if(!empty($filters['hidden']) || $product->status == 'hidden')
                                    {{-- Display Button --}}
                                    <a href="#!" class="tooltipped display-product-button" data-position="top" data-delay="50" data-tooltip="Display '{{$product->name}}'" data-product-id="{{$product->id}}" data-product-name="{{$product->name}}">
                                        <i class="material-icons teal-text text-darken-3" style="font-size:30px">visibility</i>
                                    </a>
                                @elseif(!empty($filters['displayed']) || $product->status == 'displayed')
                                    {{-- Hide Button --}}
                                    <a href="#!" class="tooltipped hide-product-button" data-position="top" data-delay="50" data-tooltip="Hide '{{$product->name}}'" data-product-id="{{$product->id}}" data-product-name="{{$product->name}}">
                                        <i class="material-icons teal-text text-darken-3" style="font-size:30px">visibility_off</i>
                                    </a>
                                @endif
                                {{-- Delete Button --}}
                                <a href="#!" class="tooltipped delete-product-button" data-position="top" data-delay="50" data-tooltip="Delete '{{$product->name}}'" data-product-id="{{$product->id}}">
                                    <i class="material-icons grey-text text-darken-1" style="font-size:30px">delete</i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-reveal">
                        <div class="row">
                            <div class="col s10">
                                <span class="card-title truncate" style="color: hsl(0, 0%, 13%); font-weight: 700;">{{$product['name']}}</span>
                            </div>
                            <div class="col s1">
                                <span><i class="card-title material-icons right">close</i></span>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <table class="col s12">
                                <thead> </thead>
                                <tbody>
                                    @if($product->quantity !== -1)
                                      <tr>
                                          <td style="color: hsl(0, 0%, 13%); font-weight: 550;"> Quantity: </td>
                                          <td style="color: hsl(0, 0%, 13%); font-weight: 550;"> {{ $product->quantity }} </td>
                                      </tr>
                                    @endif
                                    <tr>
                                        <td style="color: hsl(0, 0%, 13%); font-weight: 550;"> Average Daily Gain (g): </td>
                                        <td style="color: hsl(0, 0%, 13%); font-weight: 550;"> {{ $product->adg }} </td>
                                    </tr>
                                    <tr>
                                        <td style="color: hsl(0, 0%, 13%); font-weight: 550;"> Feed Conversion Ratio: </td>
                                        <td style="color: hsl(0, 0%, 13%); font-weight: 550;"> {{ $product->fcr }} </td>
                                    </tr>
                                    <tr>
                                        <td style="color: hsl(0, 0%, 13%); font-weight: 550;"> Backfat Thickness (mm): </td>
                                        <td style="color: hsl(0, 0%, 13%); font-weight: 550;"> {{ $product->backfat_thickness }} </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col s10"> <br> </div>

                        <div class="row">
                            {{-- Quantity: {{$product->quantity}} <br>
                            ADG: {{$product->adg}} g<br>
                            FCR: {{$product->fcr}} <br>
                            Backfat Thickness: {{$product['backfat_thickness']}} mm <br>
                            <br> --}}
                            <div class="col">
                                <a href="{{ route('products.bViewDetail', ['product' => $product->id]) }}"
                                    class="waves-effect waves-light"
                                    style="
                                        border: 2px solid #bbdefb;
                                        background-color: white;
                                        padding: 8px 18px;
                                        font-size: 16px;
                                        cursor: pointer;
                                        color: #2196f3;
                                        font-weight: 700;
                                        border-radius: 5px;
                                    "
                                >
                                    View All Info
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <?php $productNumber++; ?>
        @endforeach
    @else
        <table>
            <tr>
                <td>
                    <h5 class="center">There are no products</h5>
                </td>
            </tr>
        </table>
    @endif
</div>

{{-- Pagination --}}
<div class="row">
    <div class="center-align">
        {!! $products->appends($urlFilters)->links() !!}
    </div>
</div>
