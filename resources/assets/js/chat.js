
$(document).ready(function(){
  
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
      mediaUrl: '',
      mediaUrlFromDropzone: null,
      mediaType: '',
      mediaDropzone: '',
      items: allMessages,
    },
    created : function () {
      const vueVm = this;

      Dropzone.options.mediaDropzone = false; // disabling the auto detect of dropzone js
      setTimeout(() => {
        /* Initialize Dropzone */
        vueVm.mediaDropzone = new Dropzone('#media-dropzone', {
          paramName: "medium",
          parallelUploads: 1,
          maxFiles: 1,
          maxFilesize: 50,
          acceptedFiles:
            "image/png, image/jpeg, image/jpg, video/avi, video/mp4, video/flv, video/mov",
          dictDefaultMessage:
            `
          <h5 style="font-weight: 300;">Drop images/videos here to upload</h5>
            <i class="material-icons">insert_photo</i>
            <i class="material-icons">movie</i>
          <br>
          <h5 style="font-weight: 300;">Or just click anywhere in this container to choose file</h5>
        `,
          previewTemplate: document.getElementById("custom-preview").innerHTML,
          init: function () {
            const dropzoneVm = this;

            dropzoneVm.on('success', (file, response) => {

              // get the returned json from the back end
              const mediaObject = response;
              vueVm.mediaType = mediaObject.media_type;
              vueVm.mediaUrlFromDropzone = mediaObject.media_url;

            })
          }
        });
      }, 0)
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
      
      // scroll down at the bottom of chat upon mounted
      $('#chatMessages').scrollTop($('#chatMessages')[0].scrollHeight);
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

        /* 
          Add the send message to sender (in UI form)
        */
        if (this.mediaUrl) {
          this.addMessage({
            "msg": '',
            "mediaUrl": this.mediaUrl,
            "mediaType": this.mediaType,
            "class"	: "mine",
            "who"	: "",
            "dir"	: "out",
          });
        }
        else {
          this.addMessage({
            "msg": message.message,
            "class": "mine",
            "who": "",
            "dir": "out",
          });
        }
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
				chatMessages.scrollTop = chatMessages.scrollHeight;
      },
			sendMessage : function() {
        
        if (threadid == '') return;

        // send only when there is a message or there is a media to be sent
        if (this.newMessage.length || this.mediaUrlFromDropzone) {
          // can send

          // create the message object to be send in Chat.php
          var message = {};
          message.from = userid;
          message.to = threadid;

          // assign what time of message to send
          if (this.newMessage) {
            // if message is text not media
            message.message = this.newMessage;
            message.media_url = '';
            message.media_type = '';
          }
          else if (this.mediaUrlFromDropzone) {
            /*
              Upon uploading, the image url will be bind to a temporary
              variable, and then later bind in the actual variable that is
              seen in the html.

              This is to avoid early binding/showing of image in the browser
            */

            if (this.mediaUrl) {
              this.mediaUrl = '';
              this.mediaType = '';
            }
            this.mediaUrl = this.mediaUrlFromDropzone;

            // if message is media and not text
            message.message = '';
            message.media_url = this.mediaUrl;
            message.media_type = this.mediaType;
          }

          // identify who is the sender
          if (usertype == 'Customer') message.direction = 0;
          else if (usertype == 'Breeder') message.direction = 1;
          else message.direction = 2;
        
          var msgToSend = JSON.stringify(message);

          this.conn.send(msgToSend); // send to user 

          this.addMeAmessage(msgToSend); // send dto self
        
          this.newMessage = ""; // clear the message area after sending

          if ((this.mediaDropzone.files).length) {
            
            /* 
              clear dropzone preview file(s)
              so that when the user clicks send, the files will be emptied
            */
            this.mediaUrlFromDropzone = null;
            this.mediaType = '';
            this.mediaUrl = '';
            this.mediaDropzone.removeAllFiles(true);

            // close modal after clicking the send button
            $('#upload-media-modal').modal('close'); 
          }
        }
        else return;
			},
			focusMe : function(event) {
				event.target.select();
      },
      uploadMedia: function() {
        $('#upload-media-modal').modal('open'); // opens modal for uploading picture
      }
		}
	});


});
