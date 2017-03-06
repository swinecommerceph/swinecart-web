{{--
    Displays Home page of Customer User
--}}

@extends('layouts.default')

@section('title')
    | Customer
@endsection

@section('pageId')
    id="page-customer-home"
@endsection

@section('breadcrumbTitle')
    Home
@endsection

@section('navbarHead')
    <li><a href="{{ route('products.view') }}"> Products </a></li>
    <li id="message-main-container">
        <a href="{{ route('customer.messages') }}" id="message-icon"
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
    @if(!Auth::user()->update_profile)
        {{-- Swine Cart --}}
        <li><a href="{{ route('view.cart') }}" id="cart-icon" class="dropdown-button" data-beloworigin="true" data-hover="true" data-alignment="right" data-activates="cart-dropdown">
                <i class="material-icons">shopping_cart</i>
                <span></span>
            </a>
            <ul id="cart-dropdown" class="dropdown-content collection">
                <div id="preloader-circular" class="row">
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

                            {{-- <div class="spinner-layer spinner-red">
                                <div class="circle-clipper left">
                                    <div class="circle"></div>
                                </div><div class="gap-patch">
                                    <div class="circle"></div>
                                </div><div class="circle-clipper right">
                                    <div class="circle"></div>
                                </div>
                            </div>

                            <div class="spinner-layer spinner-yellow">
                                <div class="circle-clipper left">
                                    <div class="circle"></div>
                                </div><div class="gap-patch">
                                    <div class="circle"></div>
                                </div><div class="circle-clipper right">
                                    <div class="circle"></div>
                                </div>
                            </div>

                            <div class="spinner-layer spinner-green">
                                <div class="circle-clipper left">
                                    <div class="circle"></div>
                                </div><div class="gap-patch">
                                    <div class="circle"></div>
                                </div><div class="circle-clipper right">
                                    <div class="circle"></div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
                <li>
                    <ul id="item-container" class="collection">
                    </ul>
                </li>

                <li>
                    <a href="{{ route('view.cart') }}" class="center-align">Go to Cart</a>
                </li>
            </ul>
        </li>
        <li id="notification-main-container">
            <a href="#!" id="notification-icon"
                class="dropdown-button"
                data-beloworigin="true"
                data-hover="false"
                data-alignment="right"
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
                    <a href="{{ route('cNotifs') }}" class="center-align">See all Notifications</a>
                </li>
            </ul>
        </li>
    @endif
@endsection

@section('navbarDropdown')
    <li><a href="{{ route('customer.edit') }}"> <i class="material-icons left">people</i> Update Profile</a></li>
@endsection

@section('static')
    <div class="fixed-action-btn" style="bottom: 30px; right: 24px;">
      <a id="back-to-top" class="btn-floating btn-large red tooltipped" style="display:none;" data-position="left" data-delay="50" data-tooltip="Back To Top">
        <i class="material-icons">keyboard_arrow_up</i>
      </a>
    </div>
@endsection

@section('content')
    <div class="row">
    </div>

    {{-- Search bar --}}
    <nav id="search-container">
        <div id="search-field" class="nav-wrapper white">
            <form>
                <div class="input-field">
                    <input id="search" type="search" placeholder="Search for a product" required>
                    <label class="label-icon" for="search"><i class="material-icons teal-text">search</i></label>
                    <i class="material-icons">close</i>
                </div>
            </form>
        </div>
    </nav>

    <div class="row">
    </div>

  {{-- Slider --}}
  <div class="slider home-slider">
      <ul class="slides">
        @forelse($homeContent as $content)
            <li>
              <img src= {{$content->path.$content->name}}>
              <div class="caption center-align">
                <h3>{{$content->title}}</h3>
                <h5 class="light grey-text text-lighten-3 content-text">{{$content->text}}</h5>
              </div>
            </li>
        @empty
            <li>
              <img src="/images/demo/HP1.jpg">
              <div class="caption center-align">
                <h3>Efficiency</h3>
                <h5 class="light grey-text text-lighten-3">Through the internet, the
  system aims for faster and
  hassle-free transaction between
  consumers and retailers.</h5>
              </div>
            </li>
        @endforelse
      </ul>
  </div>

@endsection

@section('initScript')
    <script src="/js/vendor/VueJS/vue.js"></script>
    <script src="/js/vendor/VueJS/vue-resource.min.js"></script>
    <script src="/js/vendor/moment.min.js"></script>
    <script src="/js/customer/swinecart.js"> </script>
    <script src="/js/customer/customer_custom.js"> </script>
@endsection
