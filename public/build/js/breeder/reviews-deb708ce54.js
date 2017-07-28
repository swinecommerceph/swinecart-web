'use strict';

Vue.component('average-star-rating',{
    template: '#average-star-rating',
    props: ['rating'],
    computed: {
        ratingToPercentage: function(){
            return (100* this.rating / 5);
        }
    }
});

var reviewsAndRatingPage = new Vue({
    el: '#reviews-and-ratings-collection',
    data: {
        reviewsAndRatings: ''
    },
    methods: {
        // Toggle detailed ratings of a review
        toggleDetailedRatings: function(index){
            this.reviewsAndRatings[index].showDetailedRatings = !this.reviewsAndRatings[index].showDetailedRatings;
        },

        // Compute the overall average rating of the Customer
        averageRatingOfCustomer: function(index){
            var ratingDelivery = this.reviewsAndRatings[index].rating_delivery;
            var ratingTransaction = this.reviewsAndRatings[index].rating_transaction;
            var ratingProductQuality = this.reviewsAndRatings[index].rating_productQuality;
            return (ratingDelivery + ratingTransaction + ratingProductQuality)/3 ;
        }
    },
    filters: {
        round: function(value){
            // Round number according to precision
            var precision = 2;
            var factor = Math.pow(10, precision);
            var tempNumber = value * factor;
            var roundedTempNumber = Math.round(tempNumber);
            return roundedTempNumber / factor;
        }
    },
    created: function(){
        this.reviewsAndRatings = rawReviewsAndRatings;
    }
});
