{{--
    Displays Account Deletion Notification
--}}
@extends('layouts.messageOneColumn')

@section('title')
    - Email Sent
@endsection

@section('content')
    <div class="row">
        <div class="col s12 m6 offset-m3">
            @if($type == 'deleted')
                <div class="card green darken-1">
                    <div class="card-content white-text">
                      <span class="card-title">Account Notification:</span>
                      @if($approved==1)
                        <p>
                           Your account has been deleted.
                        </p>
                      @else
                        <p>
                           Your breeder application has been rejected.
                        </p>
                      @endif

                    </div>
                </div>
            @elseif($type == 'blocked')
                <div class="card green darken-1">
                    <div class="card-content white-text">
                      <span class="card-title">Account Notification:</span>
                      @(@if ($status == 1)
                        <p>
                          Your account has been blocked.
                        </p>
                        @else
                           <p>
                             Your account has been unblocked.
                           </p>
                      @endif

                    </div>
                </div>
            @elseif($type == 'accepted')
               <div class="card green darken-1">
                   <div class="card-content white-text">
                     <span class="card-title">Account Notification:</span>
                     <p>
                       Your breeder application has been accepted.
                     </p>
                   </div>
               </div>
            @elseif($type == 'rejected')
               <div class="card green darken-1">
                   <div class="card-content white-text">
                    <span class="card-title">Account Notification:</span>
                    <p>
                       Your breeder application has been rejected.
                    </p>
                   </div>
              </div>
            @endif

        </div>
    </div>
@endsection
