@extends('layouts.adminLayout')

@section('title')
    | Admin
@endsection

@section('pageId')
    id="pages-home-images"
@endsection

@section('header')
    Manage Text Content
@endsection

@section('content')
    <div class="row">
      <div class="col s12">
          <form>
              <div class="row">
                  <div class="input-field col s12">
                      <select class="browser-default">
                          <option value="" disabled selected>Choose slide to change</option>
                          <option value="1">Slide 1</option>
                          <option value="2">Slide 2</option>
                          <option value="3">Slide 3</option>
                      </select>
                  </div>
              </div>

              <div class="row">
                  <div class="input-field col s12">
                      <input id="input_text" type="text" length="10">
                      <label for="input_text">Header</label>
                  </div>

              </div>
              <div class="row">
                  <div class="input-field col s12">
                      <textarea id="textarea1" class="materialize-textarea" length="120"></textarea>
                      <label for="textarea1">Textarea</label>
                  </div>
              </div>

              <div class="row">
                  <div class="col s12">
                      <div class="valign-wrapper">
                          <button class="btn waves-effect waves-light valign center-block" type="submit" name="action">Submit
                          <i class="material-icons right">send</i>
                          </button>
                      </div>
                  </div>
              </div>

          </form>
      </div>
    </div>

@endsection

{{-- Add user modal --}}
  <div id="adduser" class="modal">
     {!!Form::open(['route'=>'admin.add.user', 'method'=>'POST', 'class'=>'add-user-form'])!!}
   <div class="modal-content">
     <h4>Add User</h4>
     <div class="divider"></div>
     <div class="row">
        <div class="col s12">

              <div class="row">
                 <div class = "addusercontainer" class="row">
                    <div class="input-field col s11">
                     <i class="material-icons prefix">account_circle</i>
                     <input id="icon_prefix" type="text" class="validate" name="name">
                     <label for="icon_prefix">Username</label>
                   </div>
                 </div>
             </div>

             <div class="row">
                <div class = "addusercontainer" class="row">
                   <div class="input-field col s11">
                    <i class="material-icons prefix">email</i>
                    <input id="icon_prefix" type="email" class="validate" name="email">
                    <label for="icon_prefix">Email Address</label>
                  </div>
                </div>
            </div>

        </div>
     </div>
   </div>
   <div class="modal-footer">
      <button id = "add-user-submit" class="btn waves-effect waves-light" type="submit" name="action">Add
       <i class="material-icons right">send</i>
     </button>
   </div>
    {!!Form::close()!!}
 </div>

@section('initScript')
    <script type="text/javascript" src="/js/admin/admin_custom.js"></script>
    <script type="text/javascript" src="/js/admin/users.js"></script>
    <script type="text/javascript" src="/js/admin/manageUsers_script.js"></script>
    <script type="text/javascript" src="/js/admin/pages.js"></script>
    <script type="text/javascript" src="/js/admin/managePages_script.js"></script>
@endsection
