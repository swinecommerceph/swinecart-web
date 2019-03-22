
{{--
    Displays Customer profile form upon profile edit
--}}

@extends('user.breeder.home')

@section('title')
    | Breeder - Messages
@endsection

@section('pageId')
    id="page-customer-messages"
@endsection

@section('breadcrumbTitle')
    <div class="breadcrumb-container">    
      Messages
    </div>
@endsection

@section('breeder-content')
<style>
 	#chatMessages{ width: 100%; min-height: 100px; height: 50vh; overflow-y: auto;}
 	#chatMessages li { width: 100%; padding: 10px;}
 	#thread-collection{ height: 60vh; overflow-y: auto; }

 	.chat-bubble { border-radius: 10px; padding:10px; max-width: 30vw;}
 	.chat-bubble.in { float:left; background-color: #e0e0e0; color: #424242;}
 	.chat-bubble.out { float:right; background-color: #0071FF; color: white;}
</style>

<div class="row" style="padding-left: 0.5vw;">
	<div id="threadname">
		@if($threadId != '' && sizeof($threads) == 0)
			<p>{{ $otherName }}</p>
		@elseif(sizeof($threads) == 0)
			<p>You have no messages.</p>
		@else
			<p>{{ $threads[0]->otherparty() }}</p>
		@endif
	</div>
</div>

<div class="row">
	
	<!-- Left Column for list of 'chatted' names -->
	<div class="col m3">
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

	      <i class="material-icons circle small left">chat</i>
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
	
	<!-- Right column for actual chat box -->
	<div class="col m9" id="chat">
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
			<div class="col s11">
				<input placeholder="Enter your message here."
			 		style="display:table-cell; width: 100%;"
				   type="text"
				   v-model="newMessage"
				   @keyup.enter="sendMessage">
			</div>
			<div 
		    @click="sendMessage" 
				class="col s1"
				style="margin-top: 1vh; cursor: pointer;">
				<i class="material-icons teal-text">
					send
				</i>
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
