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
    {{ $breeder->name }}
@endsection

@section('breadcrumb')
    <a href="{{ route('home_path') }}" class="breadcrumb">Home</a>
    <a href="{{ route('products') }}" class="breadcrumb">Products</a>
    <a href="#!" class="breadcrumb">{{ $breeder->name }}</a>
@endsection

@section('content')
    <div class="row">
        <ul class="collection with-header">
            <li class="collection-header">
                <h4>
                    {{ $breeder->name }}
                    <img class="secondary-content" src="{{ $breeder->logoImage }}" style="height: 100px; width: auto" alt="" />
                </h4>

                <span class="grey-text">
                    {{ $breeder->officeAddress_addressLine1 }},
                    {{ $breeder->officeAddress_addressLine2 }},
                    {{ $breeder->officeAddress_province }},
                    {{ $breeder->officeAddress_zipCode }}
                </span>
            </li>
            <li class="collection-item row">
                <span class="col s3"> Website </span>
                <span class="col s9"> <b>{{ $breeder->website }}</b> </span>
            </li>
            <li class="collection-item row">
                <span class="col s3"> Produce </span>
                <span class="col s9"> <b>{{ $breeder->produce }}</b> </span>
            </li>
            <li class="collection-item row">
                <span class="col s3"> Office Landline </span>
                <span class="col s9"> <b>{{ $breeder->office_landline }}</b> </span>
                <span class="col s3"> Office Mobile </span>
                <span class="col s9"> <b>{{ $breeder->office_mobile }}</b> </span>
            </li>
            <li class="collection-item row">
                <span class="col s3"> Contact Person </span>
                <span class="col s9"> <b>{{ $breeder->contactPerson_name }}</b> </span>
                <span class="col s3"> Contact Person Mobile </span>
                <span class="col s9"> <b>{{ $breeder->contactPerson_mobile }}</b> </span>
            </li>
            <li class="collection-item row">
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
            </li>
        </ul>
    </div>
@endsection

@section('customScript')

@endsection
