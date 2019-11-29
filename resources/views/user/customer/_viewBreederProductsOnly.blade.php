<div class="col s12">

  <div class="row">

    @forelse($products as $product)

      <div class="col s12 m6">

        <div class="card hoverable">

          <div class="card-image" style="padding: 20px 20px;">
            <img style="width: 25vw; height: 15vh;" class="activator" src="{{ $product->img_path }}">
          </div>

          <div class="card-content" style="background: hsl(0, 0%, 97%);">
            <div class="row">

              <div class="col s10">
                <span class="card-title truncate" style="color: hsl(0, 0%, 13%); font-weight: 700;">
                  {{ $product->name }}
                </span>
              </div>

              <div class="col s1">
                <span>
                  <i class="activator material-icons right" style="cursor: pointer;">more_vert</i>
                </span>
              </div>

              <div class="row">
                <div class="col s9">

                  <span style="color: hsl(0, 0%, 13%); font-weight: 550;">
                    {{$product->type}} - {{$product->breed}}
                  </span> <br>
                  @if($product->age < 0)
                    <span style="color: hsl(0, 0%, 45%);">
                      Age: <i>Birthdate not included</i>
                    </span>
                  @else
                    <span style="color: hsl(0, 0%, 45%);">Age: {{$product->age}} days old</span> 
                  @endif
                </div>

                <div class="col right">
                  <a 
                    href="{{ route('login') }}"
                    class="btn primary primary-hover tooltipped add-to-cart"
                    data-position="bottom"
                    data-delay="50"
                    data-tooltip="Add to Swine Cart">
                    Add to Cart
                  </a>
                </div>

              </div>

            </div>
          </div>

          <!-- card content -->
          <div class="card-reveal">
            <div class="row">
              <div class="col s10">
                  <span class="card-title truncate" style="color: hsl(0, 0%, 13%); font-weight: 700;">{{$product['name']}}</span>        
              </div>
              <div class="col s1">
                <span><i class="card-title material-icons right" style="cursor: pointer;">close</i></span>  
              </div>
            </div>
            <table class="col s10">
              <thead> </thead>
              <tbody>
                  @if($product->type !== 'Semen')
                    <tr>
                      <td style="color: hsl(0, 0%, 13%); font-weight: 550; padding: 5px;"> Quantity: </td>
                      <td style="color: hsl(0, 0%, 13%); font-weight: 550; padding: 5px;"> {{ $product->quantity }} </td>
                    </tr>
                  @endif
                  <tr>
                      <td style="color: hsl(0, 0%, 13%); font-weight: 550; padding: 5px;"> Average Daily Gain (g): </td>
                      @if($product->adg === 0)
                        <td style="color: hsl(0, 0%, 29%);">
                          <i class="text-grey">Not Indicated</i>
                        </td>
                      @else
                        <td style="color: hsl(0, 0%, 13%); font-weight: 550; padding: 5px;"> {{ $product->adg }} </td>
                      @endif
                  </tr>
                  <tr>
                      <td style="color: hsl(0, 0%, 13%); font-weight: 550; padding: 5px;"> Feed Conversion Ratio: </td>
                      @if($product->fcr === 0.0)
                        <td style="color: hsl(0, 0%, 29%);">
                          <i class="text-grey">Not Indicated</i>
                        </td>
                      @else
                        <td style="color: hsl(0, 0%, 13%); font-weight: 550; padding: 5px;"> {{ $product->fcr }} </td>
                      @endif
                  </tr>
                  <tr>
                      <td style="color: hsl(0, 0%, 13%); font-weight: 550; padding: 5px;"> Backfat Thickness (mm): </td>
                      @if($product->backfat_thickness === 0.0)
                        <td style="color: hsl(0, 0%, 29%);">
                          <i class="text-grey">Not Indicated</i>
                        </td>
                      @else
                        <td style="color: hsl(0, 0%, 13%); font-weight: 550; padding: 5px;"> {{ $product->backfat_thickness }} </td>
                      @endif
                  </tr>
              </tbody>
            </table>

            <div class="col s10"> <br> </div>

            <table class="col s10">
              <thead> </thead>
              <tbody>
                <tr>
                  <td style="color: hsl(0, 0%, 45%); padding: 5px;"> Breeder Name: </td>
                  <td style="color: hsl(0, 0%, 45%); padding: 5px;"> {{ $breeder->name }} </td>
                </tr>
                <tr>
                  <td style="color: hsl(0, 0%, 45%); padding: 5px;"> Farm Location: </td>
                  <td style="color: hsl(0, 0%, 45%); padding: 5px;"> {{ $product->farm_province }} </td>
                </tr>
              </tbody>
            </table>
            

            <div class="row">
              <br>
              <div class="col right">
                <a 
                  href="{{ route('login') }}"
                  class="btn primary primary-hover tooltipped add-to-cart"
                  data-position="bottom"
                  data-delay="50"
                  data-tooltip="Add to Swine Cart">
                  Add to Cart
                </a>
              </div>
            </div>
          </div>

        </div>

      </div>

    @empty
      <div class="col s12 m6">
        No products found.
      </div>
    @endforelse

  </div>

</div>
