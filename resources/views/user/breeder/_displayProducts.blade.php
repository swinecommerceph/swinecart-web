{{--
    This is the view to display
    the Breeder's products
    in card layouts
--}}

{{-- General Actions container --}}
<div class="row">
    <div class="col s4 left">
        {{-- Add Button --}}
        <a href="#add-product-modal" class="btn-floating btn-large modal-trigger waves-effect waves-light teal darken-2 tooltipped add-product-button" data-position="top" data-delay="50" data-tooltip="Add Product">
            <i class="material-icons">add</i>
        </a>
        {{-- Publish selected Button --}}
        <a href="#" class="btn-floating btn-large waves-effect waves-light teal tooltipped publish-selected-button" data-position="top" data-delay="50" data-tooltip="Showcase all chosen">
            <i class="material-icons">publish</i>
        </a>
        {{-- Delete selected Button --}}
        <a href="#" class="btn-floating btn-large waves-effect waves-light grey tooltipped delete-selected-button" data-position="top" data-delay="50" data-tooltip="Delete all chosen">
            <i class="material-icons">delete</i>
        </a>
    </div>

    {{-- Dropdown container --}}
    <div class="col s8 right">
        <div class="row">
            <div class="input-field col right">
                <select>
                  <option value="" disabled selected>Choose Category</option>
                  <option value="age-asc">Age</option>
                  <option value="adg-desc">Average Daily Gain</option>
                  <option value="fcr-desc">Feed Conversion Ratio</option>
                  <option value="backfat_thickness-asc">Backfat Thickness</option>
                </select>
                <label>Sort By</label>
            </div>
            <div class="input-field col s3 right">
                <select>
                  <option value="" disabled selected>Choose Category</option>
                  <option value="showcased">Showcased</option>
                  <option value="unshowcased">Unshowcased</option>
                </select>
                <label>Status</label>
            </div>
            <div class="input-field col s3 right">
                <select>
                  <option value="" disabled selected>Choose Category</option>
                  <option value="all">All</option>
                  <option value="boar">Boar</option>
                  <option value="sow">Sow</option>
                  <option value="semen">Semen</option>
                </select>
                <label>Show</label>
            </div>

        </div>

    </div>
</div>

{{-- Products in card elements container --}}
<div id="view-products-container" class="row">
    @foreach($products as $product)
        <div class="col s12 m6 l4" id="product-{{$product->id}}">
            <div class="card hoverable">
                <div class="card-image waves-effect waves-block waves-light">
                    <img height="195"class="activator" src="{{$product->img_path}}">
                </div>
                <div class="card-content">
                    <span class="card-title activator grey-text text-darken-4 truncate">{{$product->name}}<i class="material-icons right">more_vert</i></span>
                    <p>
                        {{$product->type}} - {{$product->breed}} <br>
                        {{$product->age}} days old <br>
                    </p>
                </div>
                <div class="card-action">
                    <div class="row">
                        <div class="col left">
                            <input type="checkbox" name="product-{{$product->id}}" id="product-{{$product->id}}" data-product-id="{{$product->id}}" class="filled-in"/>
                            <label for="product-{{$product->id}}"></label>
                        </div>
                        <div class="col right">
                            {{-- Edit Button --}}
                            <a href="#" class="tooltipped edit-product-button" data-position="top" data-delay="50" data-tooltip="Edit {{$product->name}}">
                                <i class="material-icons teal-text" style="font-size:30px">edit</i>
                            </a>
                            {{-- Delete Button --}}
                            <a href="#" class="tooltipped delete-product-button" data-position="top" data-delay="50" data-tooltip="Delete {{$product->name}}">
                                <i class="material-icons grey-text text-darken-1" style="font-size:30px">delete</i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-reveal">
                    <span class="card-title grey-text text-darken-4">{{$product['name']}}<i class="material-icons right">close</i></span>
                    <p>
                        Quantity: {{$product->quantity}} <br>
                        ADG: {{$product->adg}} g<br>
                        FCR: {{$product->fcr}} <br>
                        Backfat Thickness: {{$product['backfat_thickness']}} mm <br>
                        <br>
                        {!! $product->other_details !!}
                    </p>
                </div>
            </div>

        </div>
    @endforeach
</div>
