
{{--
    Displays Customer profile form upon profile edit
--}}

@extends('user.breeder.home')

@section('title')
    | Customer - Messages
@endsection

@section('pageId')
    id="page-customer-messages"
@endsection

@section('breadcrumbTitle')
    Messages
@endsection

@section('breadcrumb')
    <a href="{{ route('home_path') }}" class="breadcrumb">Home</a>
    <a href="#!" class="breadcrumb">Messages</a>
@endsection

@section('content')
<style>
 	#chatMessages{ width: 100%; border: 1px solid #ddd; min-height: 100px; list-style: none; padding-left: 0px; height: 400px; overflow-y: auto;}
 	#chatMessages li { width: 100%; padding: 10px;}
 	#thread-collection{ height: 500px; overflow-y: auto; }

 	.chat-bubble { border-radius: 10px; min-width: 200px; padding:10px; }
 	.chat-bubble.in { float:left; background-color: #4fc3f7; }
 	.chat-bubble.out { float:right; background-color: #e0e0e0; }
</style>

<div class="row">

	<div class="col m3 row">
	  <ul class="collection" id="thread-collection">
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

	<div class="col m9 row">

		<div>

			<div class="panel panel-default">

				<div id="threadname" class="panel-heading center-align">
					@if($threadId != '' && sizeof($threads) == 0)
						{{ $otherName }}
					@elseif(sizeof($threads) == 0)
						You have no messages.
					@else
						{{ $threads[0]->otherparty() }}
					@endif
				</div>

				<div class="panel-body" id="chat">

					<ul id="chatMessages">

						@foreach($messages as $message)
							@if (($message->direction == 0 && $userType == 'Customer') || ($message->direction == 1 && $userType == 'Breeder'))
								<li class="message" :class="mine" style="clear:both">
									<div class="chat-bubble out">
										<span class="who">
											Me:
										</span>
										{{ $message->message }}
									</div>
								</li>
							@else
								<li class="message" :class="user" style="clear:both">
									<div class="chat-bubble in">
										<span class="who">
							    			{{ $message->sender() }}:
										</span>
										{{ $message->message }}
									</div>
								</li>
							@endif
						@endforeach

						<li v-for="message in messages" class="message" :class="message.class" style="display:none;clear:both;">
							<div class="chat-bubble" v-bind:class="message.dir">
								<span class="who">
					    			@{{ message.who }}:
								</span>
								@{{ message.msg }}
							</div>
						</li>

					</ul>

					<div style="display:table; width: 100%;">

						<input placeholder="Enter your message here."
						 		style="display:table-cell; width: 100%;"
							   type="text"
							   v-model="newMessage"
							   @keyup.enter="sendMessage"/>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>


@endsection

@section('customScript')
    <!-- Latest Vue JS CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.16/vue.min.js"></script>
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
<script type="text/javascript" src="/js/chat.js"></script>
@endsection
