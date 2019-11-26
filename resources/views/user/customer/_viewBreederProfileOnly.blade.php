<div class="col s12">
  <div class="collection with-header">
    <div class="teal darken-2 white-text collection-header">
      <!-- First Row -->
      <h4 style="font-weight: 700;">
        {{ $breeder->name }}
        <img class="secondary-content" src="{{ $breeder->logoImage }}" style="width: 8vw; height:13vh;" alt="" />
      </h4>

      <span class="grey-text text-lighten-4">
        {{ $breeder->officeAddress_addressLine1 }},
        {{ $breeder->officeAddress_addressLine2 }},
        {{ $breeder->officeAddress_province }},
        {{ $breeder->officeAddress_zipCode }}
      </span>
    </div>

    <!-- Second Row -->

    <!-- Breeder Details -->
    <div class="white row">
      <div class="col s0.5"></div>
      <div class="col s6">
        <div class="row s12"></div>
        <div class="row s12">
          <table class="highlight" style="width: 100%;">
            <tbody>
              <tr>
                <td style="color: hsl(0, 0%, 13%); padding: 5px;">
                  Website:
                </td>
                <td style="color: hsl(0, 0%, 13%); padding: 5px;">
                  {{ $breeder->website }}
                </td>
              </tr>
              <tr>
                <td style="color: hsl(0, 0%, 13%); padding: 5px;">
                  Produce:
                </td>
                <td style="color: hsl(0, 0%, 13%); padding: 5px;">
                  {{ $breeder->produce }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="col s5"></div>
    </div>
      
    <div class="teal darken-2 white-text collection-header">
      <h4 style="font-weight: 700;">Farms</h4>
    </div>

    <div class="collection-item">

      @foreach ($breeder->farms as $farm)    
        <div class="s12">
          <p style="font-weight: 600;">Farm {{ $loop->index + 1 }}</p>
          <p>Farm Name: {{ $farm->name }}</p>
          <p>Farm Address: 
            {{ $farm->addressLine1 }},
            {{ $farm->addressLine2 }},
            {{ $farm->province }},
            {{ $farm->zipCode }}
          </p>
          <p>Accreditation Number: {{ $farm->accreditation_no }}</p>
          <hr>
        </div>
      @endforeach

    </div>

  </div>
</div>