$(document).ready(function(){

    $('select').material_select();

    // For Breeder Dashboard slider
    $('#review-slider').slider({
        full_width: true,
        height: 240,
        interval: 4000
    });
});

var notifications = new Vue({
    el: '#notification-main-container',
    data:{
        token: '',
        notifications: [],
        notificationCount: 0
    },
    methods:{
        getNotificationCount: function(){
            // Get count of customer's notifications

            // Do AJAX
            this.$http.get(
                config.breederNotifications_url + '/count',
                {}
            ).then(
                function(response){
                    this.notificationCount = response.body;
                },
                function(response){
                    console.log(response.statusText);
                }
            );
        },

        getNotificationInstances: function(){
            // Get notification instances of Customer

            // Do AJAX
            this.$http.get(
                config.breederNotifications_url + '/get',
                {}
            ).then(
                function(response){
                    $('#notification-preloader-circular').hide();

                    // Store fetched data in local component data
                    this.notifications = JSON.parse(response.body[0]);
                    this.token = response.body[1];
                },
                function(response){
                    console.log(response.statusText);
                }
            );
        },

        goToNotification: function(index){
            // View notification. Includes redirecting to designated link and marking it as read

            var vm = this;

            // Do AJAX
            this.$http.patch(
                config.breederNotifications_url + '/seen',
                {
                    _token: this.token,
                    notificationId: this.notifications[index].id,
                }
            ).then(
                function(response){
                    window.setTimeout(function(){
                        window.location = vm.notifications[index].data.url;
                    }, 1000);
                },
                function(response){
                    console.log(response.statusText);
                }
            );

        }
    },
    created: function(){
        // Get notifications count
        this.getNotificationCount();
    }
});
