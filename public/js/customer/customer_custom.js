$(document).ready(function(){

    // Initialization for select tags
    $('select').material_select();

    // Initialization for number of items in Swine Cart
    swinecart.get_quantity();

    // Add product to Swine Cart
    $(".add-to-cart").click(function(e){
        e.preventDefault();
        swinecart.add($(this).parents('form'));
    })

    // Get items from Swine Cart
    $('#cart-icon').hover(function(e){
        e.preventDefault();
        if($(this).hasClass('active')) swinecart.get_items();
    });

    // Delete item from Swine Cart
    $('body').on('click', '#cart-dropdown .delete-from-swinecart' ,function(e){
        e.preventDefault();
        swinecart.delete($(this).parents('form'), $(this).parents('li').first());
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
                config.customerNotifications_url+'/count',
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
                config.customerNotifications_url,
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
                config.customerNotifications_url + '/seen',
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
