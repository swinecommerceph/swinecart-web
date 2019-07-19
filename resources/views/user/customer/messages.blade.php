
{{--
    Displays Customer profile form upon profile edit
--}}

@extends('user.customer.home')

@section('title')
    | Customer - Messages
@endsection

@section('pageId')
    id="page-customer-messages"
@endsection

@section('breadcrumbTitle')
    Messages
@endsection

@section('content')
<style>
 	#chatMessages{ width: 100%; min-height: 100px; height: 50vh; overflow-y: auto;}
 	#chatMessages li { width: 100%; padding: 10px;}
 	#thread-collection{ height: 60vh; overflow-y: auto; }

 	.chat-bubble { border-radius: 10px; padding:10px; max-width: 30vw;}
 	.chat-bubble.in { float:left; background-color: #e0e0e0; color: #424242;}
 	.chat-bubble.out { float:right; background-color: #0071FF; color: white;}
</style>

<div class="row container" style="padding-left: 0.5vw;">
	<div id="threadname">
		@if($threadId != '' && sizeof($threads) == 0)
			{{ $otherName }}
		@elseif(sizeof($threads) == 0)
			You have no messages.
		@else
			{{ $threads[0]->otherparty() }}
		@endif
	</div>
</div>

<div class="row container">

	<div class="col m3 row">
	  <ul class="collection" id="thread-collection" style="border: 1px solid #ddd !important; margin: 0 !important;">
	  	@foreach($threads as $thread)
	  		@if($userType == 'Customer')
	  			<a id="thread-{{ $thread->breeder_id }}" href="/customer/messages/{{ $thread->breeder_id }}">
	  		@else
	  			<a id="thread-{{ $thread->customer_id }}" href="/breeder/messages/{{ $thread->customer_id }}">
	  		@endif

	  		@if(($threadId == $thread->breeder_id && $userType == 'Customer') || ($threadId == $thread->customer_id && $userType == 'Breeder'))
		    	<li class="collection-item avatar green lighten-4">
	  		@else
		    	<li class="collection-item avatar">
	  		@endif

		      <i class="material-icons circle small">chat</i>
		      <span class="title">
		         @if($thread->read_at == NULL)
		           *
		         @endif
		         {{ $thread->otherparty() }}
		      </span>

		    </li>
		    </a>
	    @endforeach
	  </ul>
	</div>

	<div class="col m9 row">

		<div>

			<div class="panel panel-default">

				

				<div class="panel-body" id="chat">

					<ul id="chatMessages" style="border: 1px solid #ddd;">

						@foreach($messages as $message)
							@if (($message->direction == 0 && $userType == 'Customer') || ($message->direction == 1 && $userType == 'Breeder'))
								<li class="message" :class="mine" style="clear:both">
									<div class="chat-bubble out">
										{{ $message->message }}
									</div>
								</li>
							@else
								<li class="message" :class="user" style="clear:both">
									<div class="chat-bubble in">
										{{ $message->message }}
									</div>
								</li>
							@endif
						@endforeach

						<li v-for="message in messages" class="message" :class="message.class" style="display:none;clear:both;">
							<div class="chat-bubble" v-bind:class="message.dir">
								@{{ message.msg }}
							</div>
						</li>

					</ul>

					<div class="row">
            <a
              href="#upload-media-modal"
              @click="sendMedia"
              id="modal-trigger"
              class="col s1 center-align"
              style="margin-top: 1vh; cursor: pointer;"
            >
              <i class="small material-icons primary-text">photo</i>
            </a>

						<div class="col s10 center-align">
							<input placeholder="Enter your message here."
						 		style="display:table-cell; width: 100%;"
							   type="text"
							   v-model="newMessage"
							   @keyup.enter="sendMessage">
            </div>
            
						<div 
					    @click="sendMessage" 
							class="col s1 center-align"
							style="margin-top: 1vh; cursor: pointer;">
							<i class="small material-icons primary-text">send</i>
						</div>
          </div>
          

          {{-- Upload Media Modal --}}
          <div id="upload-media-modal" class="modal modal-fixed-footer">
            <div class="modal-content">
              <h4>Upload Media</h4>
              <div class="row">
                {{-- {!! Form::open([
                    'route' => 'products.mediaUpload',
                    'class' => 's12 dropzone',
                    'id' => 'media-dropzone',
                    'enctype' => 'multipart/form-data'
                  ]) 
                !!}
                  <div class="fallback">
                    <input type="file"
                      name="media[]"
                      accept="image/png, image/jpeg, image/jpg, video/avi, video/mp4, video/flv, video/mov"
                      multiple
                    >
                  </div>
                {!! Form::close() !!} --}}
                
                {{-- <form action="/file-upload" class="dropzone">
                  <div class="fallback">
                    <input name="file" type="file" multiple />
                  </div>
                </form> --}}
              </div>

              
            </div>
            <div class="modal-footer">
              <button type="submit"
                      class="btn waves-effect
                            waves-light
                            modal-action
                            primary
                            primary-hover"
              >
                Send
              </button>
            </div>
          </div>

				</div>
			</div>
		</div>
	</div>

</div>


@endsection

@section('customScript')
<script>
$(document).ready(function(){
	$('.message').show(0);
});

	var username = "{{ $userName }}";
	var userid = "{{ $userId }}";
	var usertype = "{{ $userType }}";

	var chatport = "{{ $chatPort }}";
	var url = "{{ explode(':', str_replace('http://', '', str_replace('https://', '', App::make('url')->to('/'))))[0] }}";
    var threadid = "{{ $threadId }}";
	var otherparty;

</script>
<script src="{{ elixir('/js/chat.js') }}"></script>
@endsection
