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
                      @if($approved!=NULL)
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
                      @if ($status != NULL)
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

            @elseif($type == 'expirationMonth')
                   <div class="card orange lighten-4">
                       <div class="card-content black-text">
                        <span class="card-title">Account Notification:</span>
                        <p>
                           Your breeder accreditation will expire next month. Please consider renewing  your accreditation as soon as possible. Thank you!
                        </p>
                       </div>
                  </div>
            @elseif($type == 'expirationWeek')
                    <div class="card red lighten-4">
                       <div class="card-content black-text">
                        <span class="card-title">Account Notification:</span>
                        <p>
                           Your breeder accreditation will expire about a week. Please consider renewing  your accreditation as soon immediately to continue the use of our services. Thank you!
                        </p>
                       </div>
                  </div>
            @elseif($type == 'dateNeeded')
                    <div class="card red lighten-4">
                       <div class="card-content black-text">
                        <span class="card-title">Account Notification:</span>
                        <p>
                           Product is needed today by a customer. Check your notification in your account.
                        </p>
                       </div>
                  </div>
            @elseif($type == 'productExpiration')
                <div class="card red lighten-4">
                   <div class="card-content black-text">
                    <span class="card-title">Account Notification:</span>
                    <p>
                       Product reservation will expire within this day. Check your notification in your account.
                    </p>
                   </div>
              </div>
            @endif

        </div>
    </div>
@endsection
