@extends('layouts.controlLayout')

@section('resources')

@endsection

@section('title')
    | Broadcast Message
@endsection

@section('pageId')
    id="admin-broadcast-message"
@endsection

@section('nav-title')
    Broadcast Message
@endsection

@section('pageControl')

@endsection

@section('content')

  {!!Form::open(['route'=>'admin.broadcast.send', 'method'=>'POST', 'class'=>'row', 'files' => true])!!}
    
  <div class="col s12 m12 l12 xl12">
    <br>
    <div class="row">
      <div class="col s12 m12 l12 xl12">
        <div id="broadcast-message-select" class="input-field col s12 m12 l12 xl12">
          <select id="select-users" name="sendto">
              <option select="selected" value=0>All Users</option>
              <option value=1>All Breeders</option>
              <option value=2>All Customers</option>
              <option value=3>Selected Users</option>
          </select>
          <label>Send to</label>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="input-field col s3">
          <input 
            style="border: none !important; border-radius: 0 !important; border-bottom: 1px solid #9e9e9e !important;"
            id="email_subject"
            placeholder="Example: SwineCart Announcement"
            type="text"
            length="20"
            name="email_subject">
          <label for="email_subject">Email Subject</label>
      </div>
      </div>

    <div id="selected-users" class="row"></div>

    <div class="row">
      <div class="col s12 m12 l12 xl12">
        <textarea id="announcement" name="announcement"></textarea>
        {{-- <input id="image" type="file" name="image" style="display: none;" onchange="" /> --}}
      </div>
    </div>
    <div class="row">
      {{-- <div class="col s12 m12 l12 xl12">
        <div class="file-field input-field">
          <div class="btn">
              <span><i class="material-icons center">attachment</i></span>
              <input type="file" multiple>
          </div>
          <div class="file-path-wrapper">
              <input class="file-path validate" type="text" name="attachment" placeholder="Add Attachments">
          </div>
        </div>
      </div> --}}
      {!! Form::file('attachment[]', array('multiple'=>true)) !!}
    </div>
    <div class="row right">
      <div class="col s12 m12 l12 xl12">
        <button class="btn waves-effect waves-light" type="submit" name="action">Send
        <i class="material-icons right">mail</i>
        </button>
      </div>
    </div>
  </div>

  {!!Form::close()!!}
@endsection

@section('initScript')
  <script src="/js/vendor/tinymce/js/tinymce/tinymce.min.js"></script>
  <script src="/js/admin/broadcastMessage_script.js"></script>
  <script>

    $(document).ready(function () {

      // prevent the bug for the select element
      $('#broadcast-message-select').on('click', (e) => {
        e.stopPropagation();
      });

      // only enable the add email button if there's a value in the input field
      $('body').on('keyup', '#email-to-be-added', function (e) {
        const emailToBeAddedElement = $(e.target);
        const addEmailToListElement = $('#add-email-to-list');

        addEmailToListElement.prop('disabled', emailToBeAddedElement.val() === '');
      })

      // remove the added email
      $('body').on('click', '.delete-email-item', function(e) {
        e.preventDefault();
        $(this).closest('li').remove();
      });

      // add the added email in the email list 
      addEmailToEmailList = function() {
        const emailToBeAdded = document.querySelector('#email-to-be-added');
        const addEmailToListElement = document.querySelector('#add-email-to-list');

        if (emailToBeAdded.value) {
          $('#email-list-container').append(`
            <li 
              class="collection-item">
              <div class="row">
                <div class="col s10" style="height: 1rem !important;">
                  <input value="${ emailToBeAdded.value }" name="emails[]" type="email">
                </div>
                <div class="col s2" style="padding-top: 1rem;">
                  <a href="#!" class="secondary-content">
                    <i class="material-icons delete-email-item grey-text">delete</i>
                  </a>
                </div>
              </div>
            </li>
          `);
        
          emailToBeAdded.value = '';
          addEmailToListElement.disabled = true;
        }
      }

      // insert an input field when Selected Users are picked in the select
      $('#broadcast-message-select select').on('change', function () {
        if (this.value === '3') {
          const selectUserOption = document.querySelector('#emails-container');
          if (selectUserOption === null) { // prevent multiple creation of the element
            $('#selected-users').append(`
              <div id="emails-container" class="row">
                <div class="input-field col s4">
                  <input 
                    placeholder="Example: johndoe@gmail.com"
                    id="email-to-be-added"
                    type="email">
                  <label for="email-to-be-added">Enter Email</label>
                </div>

                <div class="col s3" style="padding-top: 1.7rem !important;">
                  <button 
                    type="button"
                    id="add-email-to-list"
                    onclick="addEmailToEmailList()"
                    class="btn-floating btn waves-effect waves-light"
                    disabled>
                    <i class="material-icons">add</i>
                  </button>
                </div>

                <div class="col s5" style="padding-top: 1.7rem !important; max-height: 15rem; overflow-y: auto;">
                  <p>Email List</p>

                  <ul id="email-list-container" class="collection"></ul>
                </div>

              </div>
            `);
          }
        }
        else {
          // remove input field when other options are selected
          $('#emails-container').remove();
        }

      });
      

    });
  </script>
@endsection
