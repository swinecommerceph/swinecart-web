{{--
    Displays notifications of the respective Breeder user
--}}

@extends('user.breeder.home')

@section('title')
    | Breeder - Notifications
@endsection

@section('pageId')
    id="page-breeder-notifications"
@endsection

@section('breadcrumbTitle')
    Notifications
@endsection

@section('breadcrumb')
    <a href="{{ route('home_path') }}" class="breadcrumb">Home</a>
    <a href="#!" class="breadcrumb">Notifications</a>
@endsection

@section('content')
    <div class="row">
        <div class="col s12">
            <p class="caption">
                Your Notifications. <br>
            </p>
        </div>
    </div>

    <div class="row">
        <div id="notification-page-collection" class="collection">
            <a class="black-text collection-item"
                v-for="(notification,index) in notifications"
                :href="notification.data.url"
                @click.prevent="goToNotification(index)"
            >

                <span class="left" v-if="!notification.read_at">
                    <i class="material-icons indigo-text text-darken-2" style="font-size:1rem; margin-top:1rem;">radio_button_checked</i>
                </span>
                <span class="left" v-else>
                    <i class="material-icons indigo-text text-darken-2" style="font-size:1rem; margin-top:1rem;">radio_button_unchecked</i>
                </span>
                <p style="margin-left:1.5rem;" :class=" (notification.read_at) ? 'grey-text' : '' ">
                    <span v-html="notification.data.description"></span>
                </p>
                <p class="left-align grey-text text-darken-1" style="margin-left:1.5rem; font-size:0.8rem;"> @{{ notification.data.time.date | transformToReadableDate }} </p>
            </a>

        </div>
    </div>
@endsection

@section('customScript')
    <script src="/js/breeder/notifications.js"> </script>
@endsection
