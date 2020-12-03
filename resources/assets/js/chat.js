
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
      media_url: '',
      temp_media_url: '',
      media_type: '',
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
              vueVm.media_type = mediaObject.media_type;
              vueVm.temp_media_url = mediaObject.media_url;

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
        me.addSystemMessage("Something went wrong. Please try again later!");
      };

			this.conn.onopen = function(event) {
        var message = {
          connect: true,
          userId: userid
        };
        this.conn.send(JSON.stringify(message));
			}.bind(this);

			this.conn.onmessage = function(event) {
        me.addServerMessage(event.data);
      };

      setTimeout(() => {
        var chatMessages = document.getElementById('chatMessages');
        chatMessages.scrollTop = chatMessages.scrollHeight;
      }, 100);
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

        if (message.media_type) {
          this.addMessage({
            "msg": '',
            "media_url": message.media_url,
            "media_type": message.media_type,
            "class": "mine",
            "who": "",
            "dir": "in",
          });
        }
        else {
          this.addMessage({
            "msg": message.message,
            "class": "system",
            "who": "System",
            "dir": "in",
          });
        }
			},
			addMeAmessage : function(message){
        message = JSON.parse(message);

        /* 
          Add the send message to sender (in UI form)
        */
        if (this.media_url) {
          this.addMessage({
            "msg": '',
            "media_url": this.media_url,
            "media_type": this.media_type,
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
        setTimeout(() => {
          Vue.nextTick(function () {
            this.scrollMessagesDown();
          }.bind(this));
        }, 0)
			},
			scrollMessagesDown : function(){
				var chatMessages = document.getElementById('chatMessages');
        chatMessages.scrollTop = chatMessages.scrollHeight;
      },
			sendMessage : function() {

        if (threadid == '') return;

        // send only when there is a message or there is a media to be sent
        if (this.newMessage.length || this.temp_media_url) {
          // can send

          // create the message object to be send in Chat.php
          var message = {};
          message.from_id = userid;
          message.to_id = threadid;

          // assign what time of message to send
          if (this.newMessage) {
            // if message is text not media
            message.message = this.newMessage;
            message.media_url = '';
            message.media_type = '';
          }
          else if (this.temp_media_url) {
            /*
              Upon uploading, the image url will be bind to a temporary
              variable, and then later bind in the actual variable that is
              seen in the html.

              This is to avoid early binding/showing of image in the browser
            */

            if (this.media_url) {
              this.media_url = '';
              this.media_type = '';
            }
            
            this.media_url = this.temp_media_url;

            // if message is media and not text
            message.message = '';
            message.media_url = this.media_url;
            message.media_type = this.media_type;
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
            this.media_url = null;
            this.media_type = '';
            this.media_url = '';
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
