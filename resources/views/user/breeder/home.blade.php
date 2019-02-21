{{--
    Displays Home page of Breeder User
--}}

@extends('layouts.default')

@section('title')
    | Breeder
@endsection

@section('globalVariables')
    <script type="text/javascript">
        window.hostUrl = '{{ env('APP_URL') }}';
        window.pubsubTopic = '{{ crypt(Auth::user()->email, md5(Auth::user()->email)) }}';
    </script>
@endsection

@section('pageId')
    id="page-breeder-home"
@endsection

@section('breadcrumbTitle')
    Home
@endsection

@section('navbarHead')
    @if(!Auth::user()->update_profile)
        <li 
            id="message-main-container"
            class="tooltipped"
            data-position="bottom"
            data-tooltip="Messages"
        >
            <a v-cloak href="{{ route('breeder.messages') }}" id="message-icon"
                data-alignment="right"
            >
                <i class="material-icons left">message</i>
                <span class="badge"
                    v-if="unreadCount > 0  && unreadCount <= 99"
                >
                    @{{ unreadCount }}
                </span>
                <span class="badge"
                    v-if="unreadCount > 99"
                >
                    99+
                </span>
            </a>
        </li>
        
        {{-- Notifications --}}
        <li id="notification-main-container">
            <a v-cloak href="#!" id="notification-icon"
                class="dropdown-button tooltipped"
                data-beloworigin="true"
                data-hover="false"
                data-alignment="right"
                data-position="bottom"
                data-tooltip="Notifications"
                data-activates="notification-dropdown"
                @click.prevent="getNotificationInstances"
            >
                <i class="material-icons"
                    :class="notificationCount > 0 ? 'left' : '' "
                >
                    notifications
                </i>
                <span class="badge"
                    v-if="notificationCount > 0 && notificationCount <= 99"
                >
                    @{{ notificationCount }}
                </span>
                <span class="badge"
                    v-if="notificationCount > 99"
                >
                    99+
                </span>
            </a>
            {{-- Notification --}}
            <ul id="notification-dropdown" class="dropdown-content collection">
                <div id="notification-preloader-circular" class="row">
                    <div class="center-align">
                        <div class="preloader-wrapper small active">
                            <div class="spinner-layer spinner-blue-only">
                                <div class="circle-clipper left">
                                    <div class="circle"></div>
                                </div>
                                <div class="gap-patch">
                                    <div class="circle"></div>
                                </div>
                                <div class="circle-clipper right">
                                    <div class="circle"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <li>
                    <ul id="notification-container" class="collection">
                        <li v-for="(notification,index) in notifications"
                            style="overflow:auto;"
                            class="collection-item"
                        >
                            <a class="black-text"
                                :href="notification.url"
                                @click.prevent="goToNotification(index)"
                            >
                                <span class="left" v-if="!notification.read_at">
                                    <i class="material-icons indigo-text text-darken-2" style="font-size:1rem;">radio_button_checked</i>
                                </span>
                                <span class="left" v-else >
                                    <i class="material-icons indigo-text text-darken-2" style="font-size:1rem;">radio_button_unchecked</i>
                                </span>
                                <p style="margin-left:1.5rem;" :class=" (notification.read_at) ? 'grey-text' : '' ">
                                    <span v-html="notification.data.description"></span>
                                </p>
                                <p class="right-align grey-text text-darken-1" style="margin-left:1.5rem; font-size:0.8rem;"> @{{ notification.data.time.date | transformToReadableDate }} </p>
                            </a>
                        </li>

                    </ul>
                </li>
                <li>
                    <a href="{{ route('bNotifs') }}" class="center-align">See all Notifications</a>
                </li>
            </ul>
        </li>
    @endif
@endsection

@section('navbarDropdown')
    @if(!Auth::user()->update_profile)
        <li><a href="{{ route('products') }}"> <i class="material-icons left">store</i> Manage Products </a></li>
        <li><a href="{{ route('dashboard.productStatus') }}"> <i class="material-icons left">list</i> Product Inventory </a></li>
        <li><a href="{{ route('dashboard.reviews') }}"> <i class="material-icons left">grade</i> Reviews </a></li>
        <li><a href="{{ route('breeder.edit') }}"> <i class="material-icons left">mode_edit</i> Update Profile </a></li>
    @endif
@endsection

@section('static')
    {{-- Floating Action Button --}}
    <div class="fixed-action-btn" style="bottom: 30px; right: 24px;">
      <a id="action-button" class="btn-floating btn-large waves-effect waves-light red" style="display:none;" data-position="left" data-delay="50" data-tooltip="More Actions">
        <i class="material-icons">more_vert</i>
        <ul>
            <li><a class="btn-floating waves-effect waves-light red darken-4 tooltipped delete-selected-button" data-position="left" data-delay="50" data-tooltip="Delete all chosen"><i class="material-icons">delete</i></a></li>
            @if(!empty($filters['hidden']))
                {{-- Only show when products are unshowcased --}}
                <li><a class="btn-floating waves-effect waves-light teal ligthen-2 tooltipped display-selected-button" data-position="left" data-delay="50" data-tooltip="Display all chosen"><i class="material-icons">visibility</i></a></li>
            @elseif(!empty($filters['displayed']))
                {{-- Only show when products are showcased --}}
                <li><a class="btn-floating waves-effect waves-light teal ligthen-2 tooltipped hide-selected-button" data-position="left" data-delay="50" data-tooltip="Hide all chosen"><i class="material-icons">visibility_off</i></a></li>
            @endif
            <li><a href="#" class="btn-floating modal-trigger waves-effect waves-light green tooltipped select-all-button" data-tooltip="Select all Products" data-position="left" data-delay="50"><i class="material-icons">event_available</i></a></li>
            <li><a href="#" class="btn-floating modal-trigger waves-effect waves-light blue tooltipped add-product-button" data-position="left" data-delay="50" data-tooltip="Add product"><i class="material-icons">add</i></a></li>
        </ul>
      </a>
    </div>
@endsection

@section('initScript')
    <script src="{{ elixir('/js/breeder/custom.js') }}"></script>
@endsection
