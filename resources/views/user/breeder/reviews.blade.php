{{--
    Displays the reviews of the respective Breeder user from the Customer users
--}}

@extends('user.breeder.home')

@section('title')
    | Breeder - Reviews
@endsection

@section('pageId')
    id="page-reviews-and-ratings"
@endsection

@section('breadcrumbTitle')
    Reviews and Ratings
@endsection

@section('breadcrumb')
    <a href="{{ route('home_path') }}" class="breadcrumb">Home</a>
    <a href="#!" class="breadcrumb">Reviews and Ratings</a>
@endsection

@section('content')
    <div class="row">
        <div class="col s12">
            <p class="caption">
                Your Reviews and Ratings. <br>
            </p>
        </div>
    </div>

    <div class="row">
        
    </div>
@endsection

@section('customScript')

@endsection
