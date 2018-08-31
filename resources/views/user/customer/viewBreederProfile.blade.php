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
    <div class="row">
        <div class="collection with-header">
            <div class="collection-header">
                {{-- First Row --}}
                <h4 style="font-weight: 700;">
                    {{ $breeder->name }}
                    <img class="secondary-content" src="{{ $breeder->logoImage }}" style="width: 8vw; height:13vh;" alt="" />
                </h4>

                <span class="grey-text">
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
                        <table>
                            <tr>
                                <td style="color: hsl(0, 0%, 13%); padding: 5px; width: 100%;">
                                     Website: {{ $breeder->website }}
                                </td>
                            </tr>
                            <tr>
                                <td style="color: hsl(0, 0%, 13%); padding: 5px;">
                                    Produce: {{ $breeder->produce }}
                                </td>
                            </tr>
                            <tr>
                                <td style="color: hsl(0, 0%, 13%); padding: 5px;">
                                    Office Landline: {{ $breeder->office_landline }}
                                </td>
                            </tr>
                            <tr>
                                <td style="color: hsl(0, 0%, 13%); padding: 5px;">
                                    Mobile Landline: {{ $breeder->office_mobile }}
                                </td>
                            </tr>
                            <tr>
                                <td style="color: hsl(0, 0%, 13%); padding: 5px;">
                                    Contact Person: {{ $breeder->contactPerson_name }}
                                </td>
                            </tr>
                            <tr>
                                <td style="color: hsl(0, 0%, 13%); padding: 5px;">
                                    Contact Person's Mobile: {{ $breeder->contactPerson_mobile }}
                                </td>
                            </tr>
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

            <div class="collection-item row">
                @foreach ($breeder->farms as $farm)
                    <span class="col s6">
                        <span class="col s12">
                            <h5>Farm {{ $loop->index + 1 }}</h5>
                            <span class="grey-text">
                                {{ $farm->name }} <br>
                                {{ $farm->addressLine1 }},
                                {{ $farm->addressLine2 }},
                                {{ $farm->province }},
                                {{ $farm->zipCode }} <br>
                                Accredited {{ date_format(date_create($farm->accreditation_date), 'F Y') }} <br> <br>
                            </span>
                        </span>
                        <span class="col s3">Farm Type</span>
                        <span class="col s9">{{ $farm->farmType }}</span>

                        <span class="col s3">Farm Landline</span>
                        <span class="col s9">{{ $farm->landline }}</span>

                        <span class="col s3">Farm Mobile</span>
                        <span class="col s9">{{ $farm->mobile }}</span>
                    </span>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('customScript')

@endsection
