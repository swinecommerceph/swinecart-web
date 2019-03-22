{{--
    Display Breeder's profile
--}}

@extends('user.customer.home')

@section('title')
    | {{ $breeder->name }}
@endsection

@section('pageId')
    id="page-view-breeder-profile"
@endsection

@section('breadcrumbTitle')
    Breeder's Profile
@endsection

@section('breadcrumb')
    <a href="{{ route('home_path') }}" class="breadcrumb">Home</a>
    <a href="{{ route('products.view') }}" class="breadcrumb">Products</a>
    <a href="#!" class="breadcrumb">{{ $breeder->name }}</a>
@endsection

@section('content')
    <div class="row container">
        <div class="collection with-header">
            <div class="teal darken-2 white-text collection-header">
                {{-- First Row --}}
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

            {{-- Second Row --}}

            {{-- Breeder Details --}}
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
                                <tr>
                                    <td style="color: hsl(0, 0%, 13%); padding: 5px;">
                                        Office Landline:
                                    </td>
                                    <td style="color: hsl(0, 0%, 13%); padding: 5px;">
                                        {{ $breeder->office_landline }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: hsl(0, 0%, 13%); padding: 5px;">
                                        Mobile Landline:
                                    </td>
                                    <td style="color: hsl(0, 0%, 13%); padding: 5px;">
                                        {{ $breeder->office_mobile }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: hsl(0, 0%, 13%); padding: 5px;">
                                        Contact Person:
                                    </td>
                                    <td style="color: hsl(0, 0%, 13%); padding: 5px;">
                                        {{ $breeder->contactPerson_name }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: hsl(0, 0%, 13%); padding: 5px;">
                                        Contact Person's Mobile:
                                    </td>
                                    <td style="color: hsl(0, 0%, 13%); padding: 5px;">
                                        {{ $breeder->contactPerson_mobile }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col s5"></div>
            </div>
            
            {{--
            <li class="collection-item row">
                <span class="col s3"> Website </span>
                <span class="col s9">{{ $breeder->website }}</span>
            </li>
            <li class="collection-item row">
                <span class="col s3"> Produce </span>
                <span class="col s9">{{ $breeder->produce }}</span>
            </li>
            <li class="collection-item row">
                <span class="col s3"> Office Landline </span>
                <span class="col s9">{{ $breeder->office_landline }}</span>
                <span class="col s3"> Office Mobile </span>
                <span class="col s9">{{ $breeder->office_mobile }}</span>
            </li>
            <li class="collection-item row">
                <span class="col s3"> Contact Person </span>
                <span class="col s9">{{ $breeder->contactPerson_name }}</span>
                <span class="col s3"> Contact Person Mobile </span>
                <span class="col s9">{{ $breeder->contactPerson_mobile }}</span>
            </li>
            --}} 
            
            <div class="teal darken-2 white-text collection-header">
                <h4 style="font-weight: 700;">Farms</h4>
            </div>

            <div class="collection-item row">
                @foreach ($breeder->farms as $farm)    
                        <span class="s8">
                            <h5 style="font-weight: 600;">Farm {{ $loop->index + 1 }}</h5>
                            <table class="highlight" style="width:100%;">
                                <tr>
                                    <td style="color: hsl(0, 0%, 13%); padding: 5px;">
                                        Farm Name: 
                                    </td>
                                    <td style="color: hsl(0, 0%, 13%); padding: 5px;">
                                        {{ $farm->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: hsl(0, 0%, 13%); padding: 5px;">
                                        Address: 
                                    </td>
                                    <td style="color: hsl(0, 0%, 13%); padding: 5px;">
                                        {{ $farm->addressLine1 }}, {{ $farm->addressLine2 }}, {{ $farm->province }}, {{ $farm->zipCode }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: hsl(0, 0%, 13%); padding: 5px;">
                                        Accreditation Date:
                                    </td>
                                    <td style="color: hsl(0, 0%, 13%); padding: 5px;">
                                        {{ date_format(date_create($farm->accreditation_date), 'F Y') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: hsl(0, 0%, 13%); padding: 5px;">
                                        Farm Type:
                                    </td>
                                    <td style="color: hsl(0, 0%, 13%); padding: 5px;">
                                        {{ $farm->farmType }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: hsl(0, 0%, 13%); padding: 5px;">
                                        Farm Landline:
                                    </td>
                                    <td style="color: hsl(0, 0%, 13%); padding: 5px;">
                                        {{ $farm->landline }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: hsl(0, 0%, 13%); padding: 5px;">
                                        Farm Mobile:
                                    </td>
                                    <td style="color: hsl(0, 0%, 13%); padding: 5px;">
                                        {{ $farm->mobile }}
                                    </td>
                                </tr>
                            </table>
                            
                            {{--
                            <span>Farm Name: {{ $farm->name }}</span> <br>
                            <span>Address: {{ $farm->addressLine1 }}, {{ $farm->addressLine2 }}, {{ $farm->province }}, {{ $farm->zipCode }}</span>
                            <span>Accreditation Date: {{ date_format(date_create($farm->accreditation_date), 'F Y') }}</span> <br>
                            <span>Farm Type: {{ $farm->farmType }}</span> <br>
                            <span>Farm Landline: {{ $farm->landline }}</span> <br>
                            <span>Farm Mobile: {{ $farm->mobile }}</span> <br>
                            --}}
                        </span>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('customScript')

@endsection
