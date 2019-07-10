$(document).ready(function(){

    $('select').material_select();
    // For Breeder Dashboard slider
    $('#review-slider').slider({
        fullWidth: true,
        height: 240,
        interval: 4000
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
                '/breeder/messages/countUnread',
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
                    }, 500);
                },
                function(response){
                    console.log(response.statusText);
                }
            );

        }
    },
    filters:{
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
        
        // Determine if connection to websocket server must
        // be secure depending on the protocol
        var pubsubServer = (location.protocol === 'https:') ? config.pubsubWSSServer : config.pubsubWSServer;

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
            pubsubServer,
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
