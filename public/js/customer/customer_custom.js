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

var messages = new Vue({
    el: '#message-main-container',
    data:{
        token: '',
        unreadCount: 0
    },
    methods:{
        getNotificationCount: function(){
            // Get count of customer's notifications

            // Do AJAX
            this.$http.get(
                '/customer/messages/countUnread',
                {}
            ).then(
                function(response){
                    this.unreadCount = response.body;
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

var notifications = new Vue({
    el: '#notification-main-container',
    data:{
        topic: window.pubsubTopic,
        token: '',
        notifications: [],
        notificationCount: 0
    },
    methods:{
        getNotificationCount: function(){
            // Get count of customer's notifications

            // Do AJAX
            this.$http.get(
                config.customerNotifications_url + '/count',
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
                config.customerNotifications_url + '/get',
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
                    }, 500);
                },
                function(response){
                    console.log(response.statusText);
                }
            );

        }
    },
    filters: {
        transformToReadableDate: function(value){
            return moment(value).fromNow();
        }
    },
    created: function(){
        // Get notifications count
        this.getNotificationCount();
    },
    mounted: function(){
        var self = this;

        // Set-up configuration and subscribe to a topic in the pubsub server
        var onConnectCallback = function(session){

            session.subscribe(self.topic, function(topic, data) {
                // Update notificationCount and prompt a toast
                data = JSON.parse(data);
                if(data.type === 'notification'){
                    self.notificationCount++;
                    Materialize.toast('You have a notification.', 4000);
                }
            });
        };

        var onHangupCallback = function(code, reason, detail){
            console.warn('WebSocket connection closed');
            console.warn(code+': '+reason);
        };

        var conn = new ab.connect(
            config.pubsubWSServer,
            onConnectCallback,
            onHangupCallback,
            {
                'maxRetries': 30,
                'retryDelay': 2000,
                'skipSubprotocolCheck': true
            }
        );
    }
});
