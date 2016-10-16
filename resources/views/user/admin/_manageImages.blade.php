{{-- <form class="col s12">
  <div class="input-field col s12">
    <select>
      <option value="" disabled selected>Choose slide to edit</option>
      <option value="1">Slide 1</option>
      <option value="2">Slide 2</option>
      <option value="3">Slide 3</option>
    </select>
    <label>Carousel Slide</label>
  </div>

  <div class="row">
    <form class="col s12">
      <div class="row">
        <div class="input-field col s6">
          <input id="input_text" type="text">
          <label for="input_text">Title</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <textarea id="textarea1" class="materialize-textarea" length="120"></textarea>
          <label for="textarea1">Content</label>
        </div>
      </div>
    </form>
  </div>

  <div class="file-field input-field">
    <div class="btn">
      <span>File</span>
      <input type="file">
    </div>
    <div class="file-path-wrapper">
      <input class="file-path validate" type="image">
    </div>
  </div>
</form> --}}


{{--
    Displays Home page of Admin
--}}

@extends('layouts.adminLayout')

@section('title')
    | Admin
@endsection

@section('pageId')
    id="pages-home-images"
@endsection

@section('header')
    Manage Images
@endsection

@section('content')
    <div class="row">
      <div class="col s12">
          <h5>Replace Image</h5>
      </div>

      <div class="col s12">
          <form>
              <div class="col s12">
              <label>Preview</label>
              </div>
              <div class="col s12">
                  <img class="materialboxed" width="100%" height="300" src="http://placehold.it/1000x500">
              </div>
              <div class="input-field col s6">
                  <select id="app" class="browser-default">
                      <option value="" disabled selected>Choose image to change</option>
                      <option value="1">Image 1</option>
                      <option value="2">Image 2</option>
                      <option value="3">Image 3</option>
                  </select>

              </div>
              <div class="file-field input-field col s6">
                  <div class="btn">
                      <span>File</span>
                      <input type="file">
                  </div>
                  <div class="file-path-wrapper">
                      <input class="file-path validate" type="text">
                  </div>
              </div>

              <div class="col s12">
                  <div class="valign-wrapper">
                  <button class="btn waves-effect waves-light valign center-block" type="submit" name="action">Submit
                      <i class="material-icons right">send</i>
                  </button>
                  </div>
              </div>
          </form>
      </div>


      <div class="col s12">
          <h5>Add New Image</h5>
      </div>
      <div class="col s12">
          <form>
              <div class="file-field input-field col s12">
                  <div class="btn">
                      <span>File</span>
                      <input type="file">
                  </div>
                  <div class="file-path-wrapper">
                      <input class="file-path validate" type="text">
                  </div>
              </div>
              <div class="col s12">
                  <div class="valign-wrapper">
                      <button class="btn waves-effect waves-light valign center-block" type="submit" name="action">Submit
                      <i class="material-icons right">send</i>
                      </button>
                  </div>
              </div>

          </form>
      </div>


      <div class="col s12">
          <h5>Delete Image</h5>
      </div>


      <div class="col s12">
          <form>
              <div class="col s12">
              <label>Preview</label>
              </div>
              <div class="col s12">
                  <img class="materialboxed" width="100%" height="300" src="http://placehold.it/1000x500">
              </div>
              <div class="input-field col s6">
                  <select class="browser-default">
                      <option value="" disabled selected>Choose image to change</option>
                      <option value="1">Image 1</option>
                      <option value="2">Image 2</option>
                      <option value="3">Image 3</option>
                  </select>
              </div>

              <div class="col s6">
                  <div class="input-field">
                  <button class="btn waves-effect waves-light" type="submit" name="action">Delete
                  </button>
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

{{-- Modal for user inquiries --}}
<div id="modal1" class="modal modal-fixed-footer">
  <div id="message-modal-content" class="modal-content">
    <div class="center"><h5>"Username" Message</h5></div>
      <div class="center">Timestamp</div>
      <div class="divider"></div>
      <div class="row">
      <div class="col s12">
      <div id="message-panel" class="card-panel">
        <span class="black-text">
          Sample Text
        </span>
      </div>
    </div>
  </div>
</div>
  <div class="modal-footer">
    <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Resolve</a>
  </div>
</div>



@section('initScript')
    <script type="text/javascript" src="/js/admin/admin_custom.js"></script>
    <script type="text/javascript" src="/js/admin/users.js"></script>
    <script type="text/javascript" src="/js/admin/manageUsers_script.js"></script>
    <script type="text/javascript" src="/js/admin/pages.js"></script>
    <script type="text/javascript" src="/js/admin/managePages_script.js"></script>
@endsection
