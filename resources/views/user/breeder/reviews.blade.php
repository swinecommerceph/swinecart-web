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
    <div class="breadcrumb-container">    
      Reviews and Ratings
    </div>
@endsection

@section('breadcrumb')
    <div class="breadcrumb-container">
        <a href="{{ route('home_path') }}" class="breadcrumb">Home</a>
        <a href="#!" class="breadcrumb">Reviews and Ratings</a>
    </div>
@endsection

@section('breeder-content')
    <div class="row">
        <div class="col s12">
            <p class="caption">
                Your Reviews and Ratings. <br>
            </p>

            {{-- Reviews with their respective ratings --}}
            <ul v-cloak id="reviews-and-ratings-collection" class="collection with-header">
                <li class="collection-header">
                    <h5> Overall Average Rating </h5>
                    <average-star-rating :rating="{{ $overallRating }}"></average-star-rating> <br>
                    <span class="">
                        {{ $overallRating }} out of 5
                    </span>
                </li>
                <li class="collection-item row" v-for="(review, index) in reviewsAndRatings">
                    <span class="col s8">
                        {{-- Basic Information --}}
                        <span class="">
                            <average-star-rating :rating="averageRatingOfCustomer(index) | round"></average-star-rating> <br>
                            @{{ review.customerName }}
                            <span class="grey-text"> ( @{{ review.customerProvince }} ) </span>
                        </span><br><br>

                        <span class="grey-text">
                            "@{{ review.comment }}"
                        </span>

                    </span>

                    {{-- Date Information --}}
                    <span class="col s4 right-align grey-text">
                        @{{ review.date }}
                    </span>

                    {{-- View Ratings icon --}}
                    <span class="col s12 right-align"
                        style="cursor:pointer"
                        @click.prevent="toggleDetailedRatings(index)"
                        >
                        @{{ (review.showDetailedRatings) ? 'See Less' : 'See More' }}
                    </span>

                    {{-- Detailed ratings --}}
                    <span v-show="review.showDetailedRatings" class="col s12">
                        {{-- Delivery Rating --}}
                        <span class="col s3 right-align">
                            <i>Delivery</i>
                        </span>
                        <span class="col s9 left-align">
                            <i class="material-icons yellow-text">
                                @{{ (review.rating_delivery >= 1) ? 'star' : 'star_border' }}
                            </i>
                            <i class="material-icons yellow-text">
                                @{{ (review.rating_delivery >= 2) ? 'star' : 'star_border' }}
                            </i>
                            <i class="material-icons yellow-text">
                                @{{ (review.rating_delivery >= 3) ? 'star' : 'star_border' }}
                            </i>
                            <i class="material-icons yellow-text">
                                @{{ (review.rating_delivery >= 4) ? 'star' : 'star_border' }}
                            </i>
                            <i class="material-icons yellow-text">
                                @{{ (review.rating_delivery >= 5) ? 'star' : 'star_border' }}
                            </i>
                        </span> <br>

                        {{-- Transaction Rating --}}
                        <span class="col s3 right-align">
                            <i>Transaction</i>
                        </span>
                        <span class="col s9 left-align">
                            <i class="material-icons yellow-text">
                                @{{ (review.rating_transaction >= 1) ? 'star' : 'star_border' }}
                            </i>
                            <i class="material-icons yellow-text">
                                @{{ (review.rating_transaction >= 2) ? 'star' : 'star_border' }}
                            </i>
                            <i class="material-icons yellow-text">
                                @{{ (review.rating_transaction >= 3) ? 'star' : 'star_border' }}
                            </i>
                            <i class="material-icons yellow-text">
                                @{{ (review.rating_transaction >= 4) ? 'star' : 'star_border' }}
                            </i>
                            <i class="material-icons yellow-text">
                                @{{ (review.rating_transaction >= 5) ? 'star' : 'star_border' }}
                            </i>
                        </span>

                        {{-- Product Quality Rating --}}
                        <span class="col s3 right-align">
                            <i>Product Quality</i>
                        </span>
                        <span class="col s9 left-align">
                            <i class="material-icons yellow-text">
                                @{{ (review.rating_productQuality >= 1) ? 'star' : 'star_border' }}
                            </i>
                            <i class="material-icons yellow-text">
                                @{{ (review.rating_productQuality >= 2) ? 'star' : 'star_border' }}
                            </i>
                            <i class="material-icons yellow-text">
                                @{{ (review.rating_productQuality >= 3) ? 'star' : 'star_border' }}
                            </i>
                            <i class="material-icons yellow-text">
                                @{{ (review.rating_productQuality >= 4) ? 'star' : 'star_border' }}
                            </i>
                            <i class="material-icons yellow-text">
                                @{{ (review.rating_productQuality >= 5) ? 'star' : 'star_border' }}
                            </i>
                        </span>
                    </span>
                </li>
            </ul>
        </div>
    </div>

    <script type="text/x-template" id="average-star-rating">
        <div class="ratings-container" style="padding:0; position:relative; display:inline-block">
            <div class="star-ratings-top" style="position:absolute; z-index:1; overflow:hidden; display:block; white-space:nowrap;" :style="{ width: ratingToPercentage + '%' }">
                <i class="material-icons yellow-text"> star </i>
                <i class="material-icons yellow-text"> star </i>
                <i class="material-icons yellow-text"> star </i>
                <i class="material-icons yellow-text"> star </i>
                <i class="material-icons yellow-text"> star </i>
            </div>
            <div class="star-ratings-bottom" style="padding:0; z-index:0; display:block;">
                <i class="material-icons yellow-text"> star_border </i>
                <i class="material-icons yellow-text"> star_border </i>
                <i class="material-icons yellow-text"> star_border </i>
                <i class="material-icons yellow-text"> star_border </i>
                <i class="material-icons yellow-text"> star_border </i>
            </div>
        </div>
    </script>
@endsection

@section('customScript')
    <script type="text/javascript">
        var rawReviewsAndRatings = {!! $reviews !!};
    </script>
    <script src="{{ elixir('/js/breeder/reviews.js') }}"></script>
@endsection
