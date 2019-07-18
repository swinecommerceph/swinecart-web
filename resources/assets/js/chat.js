$(document).ready(function(){
  
  Vue.component('file-upload', VueUploadComponent)

	var vue = new Vue({
		el: '#chat',
		data : {
			messages: [],
			newMessage: "",
			userName: username,
			port: chatport,
			uri: url,
			conn: false,
			user: "",
			mine: "",
		},
		mounted : function(){
			// default port
			this.port = this.port.length == 0 ? '9090' : this.port;

			// init connection
			this.conn = (location.protocol === 'https:') ? new WebSocket('wss://'+this.uri+'/chat') : new WebSocket('ws://'+this.uri+'/chat');
			var me = this;

			this.conn.onclose = function (event) {

		        var reason;

		        if (event.code == 1000)
		            reason = "Normal closure, meaning that the purpose for which the connection was established has been fulfilled.";

		        else if(event.code == 1001)
		            reason = "An endpoint is \"going away\", such as a server going down or a browser having navigated away from a page.";

		        else if(event.code == 1002)
		            reason = "An endpoint is terminating the connection due to a protocol error";

		        else if(event.code == 1003)
		            reason = "An endpoint is terminating the connection because it has received a type of data it cannot accept (e.g., an endpoint that understands only text data MAY send this if it receives a binary message).";

		        else if(event.code == 1004)
		            reason = "Reserved. The specific meaning might be defined in the future.";

		        else if(event.code == 1005)
		            reason = "No status code was actually present.";

		        else if(event.code == 1006)
		           reason = "Abnormal error, e.g., without sending or receiving a Close control frame";

		        else if(event.code == 1007)
		            reason = "An endpoint is terminating the connection because it has received data within a message that was not consistent with the type of the message (e.g., non-UTF-8 [http://tools.ietf.org/html/rfc3629] data within a text message).";

		        else if(event.code == 1008)
		            reason = "An endpoint is terminating the connection because it has received a message that \"violates its policy\". This reason is given either if there is no other sutible reason, or if there is a need to hide specific details about the policy.";

		        else if(event.code == 1009)
		           reason = "An endpoint is terminating the connection because it has received a message that is too big for it to process.";

		        else if(event.code == 1010) // Note that this status code is not used by the server, because it can fail the WebSocket handshake instead.
		            reason = "An endpoint (client) is terminating the connection because it has expected the server to negotiate one or more extension, but the server didn't return them in the response message of the WebSocket handshake. <br /> Specifically, the extensions that are needed are: " + event.reason;

		        else if(event.code == 1011)
		            reason = "A server is terminating the connection because it encountered an unexpected condition that prevented it from fulfilling the request.";

		        else if(event.code == 1015)
		            reason = "The connection was closed due to a failure to perform a TLS handshake (e.g., the server certificate can't be verified).";
		        else
		            reason = "Unknown reason";

		        me.addSystemMessage("Connection closed: " + reason);
	   	 	};

			this.conn.onopen = function(event) {
			    // me.addSystemMessage("Connection established.");

			    var message = {};
				message.from = userid;
				message.to = null;
				message.message = "Connection established.";
				message.direction = null;

				var msgToSend = JSON.stringify(message);

			    this.conn.send(msgToSend);
			}.bind(this);

			this.conn.onmessage = function(event) {
			  	me.addServerMessage(event.data);
			};
		},
		methods : {
			addSystemMessage : function(message){
				this.addMessage({
					"msg" 	: message,
					"class"	: "system",
					"who"	: "System",
					"dir"	: "in",
				});
			},
			addServerMessage : function(message){
				message = JSON.parse(message);
				
				this.addMessage({
					"msg" 	: message.message,
					"class"	: "user",
					"who"	: message.from,
					"dir"	: "in",
				});
			},
			addMeAmessage : function(message){
				message = JSON.parse(message);

				this.addMessage({
					"msg" 	: message.message,
					"class"	: "mine",
					"who"	: "",
					"dir"	: "out",
				});
			},
			addMessage : function(message) {
				this.messages.push(message);

				if($('#thread-'+threadid).length)
					$('#thread-collection').prepend($('#thread-'+threadid));

				// allow the DOM to get updated
				Vue.nextTick(function () {
					this.scrollMessagesDown();
				}.bind(this));
			},
			scrollMessagesDown : function(){
				var chatMessages = document.getElementById('chatMessages');
				chatMessages.scrollTop = 1000000;
			},
			sendMessage : function() {
				if (!this.newMessage.length || threadid == '')
					return;

				var message = {};
				message.from = userid;
				message.to = threadid;
				message.message = this.newMessage;
				if(usertype == 'Customer'){
					message.direction = 0;
				}else if(usertype == 'Breeder'){
					message.direction = 1;
				}else{
					message.direction = 2;
				}
				// message.direction = (usertype == 'Customer')?0:1;

				var msgToSend = JSON.stringify(message);

				this.conn.send(msgToSend);

				this.addMeAmessage(msgToSend);

				this.newMessage = "";
			},
			focusMe : function(event) {
				event.target.select();
      },
      sendMedia: function() {
        console.log('haha');
      }
		}
	});


});
