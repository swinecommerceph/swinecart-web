$(document).ready(function(){

    var conn = new ab.Session('ws://localhost:8080',
        function() {
           conn.subscribe('hardTasks', function(topic, data) {
               // This is where you would add the new article to the DOM (beyond the scope of this tutorial)
               console.log('New task name added "' + topic + '" : ' + data.name);
           });
        },
        function() {
           console.warn('WebSocket connection closed');
        },
        {'skipSubprotocolCheck': true}
    );

});
