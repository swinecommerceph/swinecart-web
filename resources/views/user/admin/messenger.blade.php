{{--
    Displays all users
--}}

@extends('layouts.adminLayout')

@section('title')
    | Users
@endsection

@section('pageId')
    id="page-admin-view-users"
@endsection

@section('breadcrumbTitle')
    Users
@endsection

@section('breadcrumb')
    <a href="{{ route('home_path') }}" class="breadcrumb">Home</a>
    <a href="#!" class="breadcrumb">Users</a>
@endsection

@section('content')



<div class="row">
    <div class="input-field col s10">
        <input autocomplete="off" id="email" name="email" type="text" class="validate">
        <label for="email" class="active">To</label>
    </div>
    <div class="input-field col s2 valign-wrapper">
        <a class="waves-effect waves-light btn modal-trigger valign" href="#modal1"><i class="material-icons">add</i></a>
    </div>
    <!--div class="input-field col s12">
        <input autocomplete="off" id="subject" name="subject" type="text" class="validate">
        <label for="subject" class="active">Subject</label>
    </div-->
    <div class="input-field col s12">
        <textarea id="message" name="message" class="materialize-textarea"></textarea>
        <label for="message" class="active">Message</label>
    </div>
    <div class="input-field col s12 valign-wrapper">
        <a class="waves-effect waves-light btn valign" id="send-message"><i class="material-icons left">send</i>Send</a>&nbsp;
        <div class="preloader-wrapper small active" id="sending" style="display:none;">
            <div class="spinner-layer spinner-teal-only">
              <div class="circle-clipper left">
                <div class="circle"></div>
              </div><div class="gap-patch">
                <div class="circle"></div>
              </div><div class="circle-clipper right">
                <div class="circle"></div>
              </div>
            </div>
        </div>
    </div>
</div>


  <div id="modal1" class="modal">
    <div class="modal-content">
      <h6>Select your recipients</h6>
      <div class="row">
        @foreach ($users as $user)
            <div class="col s4">
              <input type="checkbox" class="filled-in receipient" id="userid-{{$user->id}}" value="{{$user->email}}" />
              <label for="userid-{{$user->id}}">{{$user->name}}</label>
            </div>
        @endforeach
      </div>
    </div>
    <div class="modal-footer">
      <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat add-receipients">Add Recepients</a>
    </div>
  </div>

   

@endsection

@section('customScript')
    <script>
        $(document).ready(function(){

            console.log('test');

            $('.modal-trigger').modal();

            $('.add-receipients').click(function(){
                $('#email').val('');
                $( ".receipient" ).each(function( index ) {
                   if($(this).is(':checked') || $(this).prop('checked')){
                        if(!$('#email').val()){
                            $('#email').val($(this).attr('value'));
                        }else{
                            $('#email').val($('#email').val()+','+$(this).attr('value'));
                        }
                   }
                   else{
                   }
                });
            });


            $('#send-message').click(function(){
                $('#sending').show();


                $.ajax({
                    type: "POST",
                    url: "sendMessage",
                    data:{
                        _token: "{{{ csrf_token() }}}",
                        email: $('#email').val(),
                        //subject: $('#subject').val(),
                        message: $('#message').val(),
                    }, 
                    success: function(response){
                        Materialize.toast('Message has been sent.', 3000);
                        $('#email').val('');
                        //$('#subject').val('');
                        $('#message').val('');
                    },
                    error: function(e){
                        Materialize.toast('There was an error sending the message.', 3000);
                        console.error(e);
                    },
                    complete: function(){
                        $('#sending').hide();

                    }
                });
            });
        });
    </script>

@endsection

