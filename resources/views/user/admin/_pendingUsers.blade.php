@extends('layouts.controlLayout')

@section('title')
    | Pending Accounts
@endsection

@section('pageId')
    id="admin-pending-accounts"
@endsection

@section('nav-title')
    Pending Accounts
@endsection

@section('pageControl')
    <div class="row">
        <div class="col s12 m12 l12 xl12">
            {!!Form::open(['route'=>'admin.searchPending', 'method'=>'GET', 'class'=>'row input-field valign-wrapper'])!!}
                <input id="search" type="search" name="search">
                <label class="label-icon" for="search"><i class="material-icons">search</i></label>
                <i class="material-icons">close</i>
            {!!Form::close()!!}
        </div>
    </div>
    <div class="row">
        <div class="col s12 m12 l12 xl12">
            <a href="{{route('notify_pending')}}" class="waves-effect waves-light btn right"><i class="material-icons left">markunread_mailbox</i>Notify Users</a>
        </div>
    </div>
@endsection

@section('content')
  <!-- Pending Breeders Registered by Admin -->

  <h5>Pending Breeders Registered by Admin</h5>

  <table class="bordered responsive-table">
    <thead>
      <tr>
          <th data-field="name">Name</th>
            <th data-field="name">Email</th>
          <th data-field="type">Account Type</th>
          <th data-field="action">Date Created</th>
      </tr>
    </thead>

    <tbody>
        @forelse($users as $user)
      <tr>
        <td>{{$user->name}}</td>
        <td>{{$user->email}}</td>
        <td>{{ucfirst($user->title)}}</td>
        <td>
            {{$user->created_at}}
        </td>
      </tr>
      @empty
            <tr>
                <td></td>
                <td class="right-align">No users found</td>
                <td></td>
                <td></td>
            </tr>
    @endforelse
    </tbody>
  </table>
  <div class="pagination center"> {{ $users->links() }} </div>

  <br>
  <h5>Pending Self-Registered Breeders</h5>
  <br>
  <!-- Pending Self-Registered Breeders -->
  <table class="bordered responsive-table">
    <thead>
      <tr>
          <th data-field="name">Name</th>
            <th data-field="name">Email</th>
          <th data-field="type">Account Type</th>
          <th data-field="action">Date Created</th>
          <th colspan="2" data-field="action" class="center-align">Action</th>
      </tr>
    </thead>

    <tbody>
      @forelse($selfRegisteredBreeders as $selfRegisteredBreeder)
        <tr>
          <td>{{ $selfRegisteredBreeder['user']->name }}</td>
          <td>{{ $selfRegisteredBreeder['user']->email }}</td>
          <td>Breeder</td>
          <td>{{ $selfRegisteredBreeder['user']->created_at }}</td>
          <td>
            <a 
              href="#confirmation-approve-breeder-modal"
              style="width: 7.5rem;"
              class="approve-breeder-modal-trigger waves-effect waves-light btn"
              data-id={{ $selfRegisteredBreeder['user']->id }}
              data-name={{ $selfRegisteredBreeder['user']->name }}
            >
              Approve
            </a>
          </td>
          <td>
            <a
              href="#self-registered-breeder"
              style="width: 7.5rem;"
              class="view-breeder-modal-trigger waves-effect waves-light btn"
              data-name={{ $selfRegisteredBreeder['user']->name }}
              data-email={{ $selfRegisteredBreeder['user']->email }}
              data-acc-no={{ $selfRegisteredBreeder['farmAddresses']->accreditation_no }}
            >
              View
            </a>
          </td>
        </tr>
      @empty
        <tr>
          <td></td>
          <td class="right-align">No users found</td>
          <td></td>
          <td></td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <div id="self-registered-breeder" class="modal">
    <div class="modal-content">
      {{-- <input id="self-registered-breeder-modal-id" name="content_id" type="hidden" value=""> --}}
      <h5>Pending Self-Registered Breeder:</h5>
      <p id="self-registered-breeder-name"></p>
      <p id="self-registered-breeder-email"></p>
      <p id="self-registered-breeder-acc-no"></p>
    </div>

    <div class="modal-footer">
      <a href="#" class="modal-close waves-effect waves-green btn-flat">Close</a>
    </div>
  </div>

  <!-- Confirmation Approve Modal -->
  <div id="confirmation-approve-breeder-modal" class="modal">
    {!! Form::open([
      'route' => 'selfRegisteredBreeder.update',
      'method' => 'PATCH',
    ]) !!}
    <input id="selfBreederId" name="selfBreederId" type="hidden">
    <div class="modal-content">
      <h4 id="approve-modal-prompt"></h4>
      <blockquote class="warning2" style="font-size: 1.2rem;">
        Only accredited breeders are allowed to register.
      </blockquote>
      <p style="font-size: 1.2rem;">
        Approving this user as a breeder means that this breeder can now sell
        products in the system as a registered accredited breeder.
      </p>
    </div>

    <div class="modal-footer">
      <a href="#" class="modal-close btn-flat">Cancel</a>
      
      <div class="right">
        <button type="submit" class="waves-effect waves-green btn">Yes, approve it</button>
      </div>
    </div>
    {!! Form::close() !!}
  </div>

@endsection

@section('initScript')
    <script type="text/javascript">
      $(document).ready(function () {
        $('.view-breeder-modal-trigger').click(function (e) {
          e.preventDefault();
          
          const vm = $(this);
          const name = vm.attr('data-name');
          const email = vm.attr('data-email');
          const acc_no = vm.attr('data-acc-no');

          $('#self-registered-breeder-name').text(`Name: ${name}`);
          $('#self-registered-breeder-email').text(`Email: ${email}`);
          $('#self-registered-breeder-acc-no').text(`Accreditation number: ${acc_no}`);

        });

        $('.approve-breeder-modal-trigger').click(function (e) {
          e.preventDefault();

          const vm = $(this);
          const id = vm.attr('data-id');
          const name = vm.attr('data-name');

          $('#approve-modal-prompt').text(`Approve: ${name}?`);
          $('#selfBreederId').attr('value', id);
        });
      });
    </script>

    {{-- <script type="text/javascript" src="/js/admin/admin_custom.js"></script> --}}
    <script type="text/javascript" src="/js/admin/users.js"></script>
    <script type="text/javascript" src="/js/admin/userPages_script.js"></script>
    <script type="text/javascript" src="/js/admin/pages.js"></script>
    <script type="text/javascript" src="/js/admin/managePages_script.js"></script>
    @if(Session::has('alert-accept'))
        <script type="text/javascript">
             Materialize.toast('User Successfully Added', 4000)
        </script>
    @elseif (Session::has('alert-reject'))
        <script type="text/javascript">
             Materialize.toast('User Application Rejected', 4000)
        </script>
    @endif
@endsection
