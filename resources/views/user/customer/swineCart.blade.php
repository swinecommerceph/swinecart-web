{{--
    Displays Home page of Customer User
--}}

@extends('user.customer.home')

@section('title')
    | Customer
@endsection

@section('pageId')
    id="page-customer-swine-cart"
@endsection

@section('breadcrumbTitle')
    Swine Cart
@endsection

@section('breadcrumb')
    <a href="{{ route('home_path') }}" class="breadcrumb">Home</a>
    <a href="#!" class="breadcrumb">Swine Cart</a>
@endsection

@section('navbarHead')
    <li><a href="{{ route('products.view') }}"> Products </a></li>
    <li id="message-main-container">
        <a v-cloak href="{{ route('customer.messages') }}" id="message-icon"
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
        <li><a id="cart-icon" class="dropdown-button" data-beloworigin="true" data-hover="true" data-alignment="right" data-activates="cart-dropdown">
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
                    <a class="center-align">Go to Cart</a>
                </li>
            </ul>
        </li>

        {{-- Notifications --}}
        <li id="notification-main-container">
            <a v-cloak href="#!" id="notification-icon"
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

            {{-- Notification Dropdown --}}
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
                    {{-- Notification List --}}
                    <ul id="notification-container" class="collection">
                        <li v-for="(notification,index) in notifications"
                            style="overflow:auto;"
                            class="collection-item"
                        >
                            <a class="black-text"
                                :href="notification.url"
                                @click.prevent="goToNotification(index)"
                            >
                                {{-- Radio Button Icon --}}
                                <span class="left" v-if="!notification.read_at">
                                    <i class="material-icons indigo-text text-darken-2" style="font-size:1rem;">radio_button_checked</i>
                                </span>
                                <span class="left" v-else >
                                    <i class="material-icons indigo-text text-darken-2" style="font-size:1rem;">radio_button_unchecked</i>
                                </span>

                                {{-- Notification Description--}}
                                <p style="margin-left:1.5rem;" :class=" (notification.read_at) ? 'grey-text' : '' ">
                                    <span v-html="notification.data.description"></span>
                                </p>

                                {{-- Timestamp --}}
                                <p class="right-align grey-text text-darken-1" style="font-size:0.8rem;"> @{{ notification.data.time.date | transformToReadableDate }} </p>
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

@section('content')
    
    {{-- Swinecart Container --}}
    <div class="container" id="swine-cart-container">

        {{-- Tabs --}}
        <ul class="tabs tabs-fixed-width">
          <li class="tab col s6"><a href="#swine-cart">Orders</a></li>
          <li class="tab col s6 teal-text"><a href="#transaction-history" @click="getTransactionHistory({{ $customerId }})">Transaction History</a></li>
        </ul>

        <order-details :products="products"
            :token="'{{ $token }}'"
            @subtract-quantity="subtractProductQuantity"
            @add-quantity="addProductQuantity"
            @update-history="updateHistory"
            @remove-product="removeProduct"
            @product-requested="productRequested"
        >
        </order-details>
        <transaction-history :history="history"></transaction-history>

        {{-- Product Info Modal in Transaction History Tab--}}
        <div id="info-modal" class="modal" style="width: 70% !important;
                max-height: 80% !important;">
          <div class="modal-content">
            <h4 style="overflow:auto"><a href="#"><i class="material-icons black-text right modal-close">close</i></a></h4>
            <div class="row">
              <div class="image col s12 m7">
                <div class="row">
                  <div class="col s12">
                    <div class="row">
                        <div class="card">
                            <div class="card-image">
                                <img id="modal-img" :src="productInfoModal.imgPath" style="width: 38vw; height: 50vh;">
                            </div>
                        </div>
                    </div>
                  </div>
                  <div class="other-details col s12">
                      <div class="card">
                          <div class="card-content">
                              <span class="card-title">Other Details</span>
                              <template v-for="detail in productInfoModal.otherDetails">
                                  <p> @{{ detail }} </p>
                              </template>
                          </div>
                      </div>
                  </div>
                </div>
              </div>
              {{-- Info in modal --}}
              <div class="cart-details col s12 m5 ">
                <ul class="collection with-header">
                    <li class="collection-header">
                        <h4 class="row">
                            <div class="col product-name"  style="color: hsl(0, 0%, 13%); font-weight: 700">
                                {{-- productName --}}
                                @{{ productInfoModal.name }}
                            </div>
                        </h4>
                        <div class="row">
                            <div class="col breeder product-farm">
                              {{-- Breeder and farm_province --}}
                              Breeder:
                              <span class="blue-text" style="font-weight: 700;">
                                @{{ productInfoModal.breeder }}
                              </span><br>
                              Farm Province: 
                              <span>@{{ productInfoModal.province }}</span>
                            </div>
                        </div>
                    </li>

                    {{-- Details --}}
                    <table class="white highlight responsive-table">
                        <tr>
                            <td style="color: hsl(0, 0%, 13%); font-weight: 600;">
                                @{{ capitalizedProductType }} - @{{ productInfoModal.breed }}
                            </td>
                        </tr>
                        <tr>
                            <td style="color: hsl(0, 0%, 45%);">
                                Born on @{{ productInfoModal.birthdate }} (@{{ productInfoModal.age }} days old)
                            </td>
                        </tr>
                        <tr>
                            <td style="color: hsl(0, 0%, 45%);">
                                Average Daily Gain: @{{ productInfoModal.adg }} g
                            </td>
                        </tr>
                        <tr>
                            <td style="color: hsl(0, 0%, 45%);">
                                Feed Conversion Ratio: @{{ productInfoModal.fcr }}
                            </td>
                        </tr>
                        <tr>
                            <td style="color: hsl(0, 0%, 45%);">
                                Backfat Thickness: @{{ productInfoModal.bft }} mm
                            </td>
                        </tr>
                    </table>                            
                    
                    <!-- 
                    <li class="collection-item product-type">{{--{{$product->type}} - {{$product->breed}} --}}
                        <span class="type"> @{{ capitalizedProductType }} </span> -
                        <span class="breed"> @{{ productInfoModal.breed }} </span>
                    </li>
                    <li class="collection-item product-age">{{--{{$product->birthdate}} and {{$product->age}} --}}
                        <span> Born on @{{ productInfoModal.birthdate }} (@{{ productInfoModal.age }} days old)</span>
                    </li>
                    <li class="collection-item product-adg">{{--Average Daily Gain: {{$product->adg}} g--}}
                        Average Daily Gain: <span> @{{ productInfoModal.adg }} </span> g
                    </li>
                    <li class="collection-item product-fcr">{{--Feed Conversion Ratio: {{$product->fcr}}--}}
                        Feed Conversion Ratio: <span> @{{ productInfoModal.fcr }} </span>
                    </li>
                    <li class="collection-item product-backfat_thickness">{{--Backfat Thickness: {{$product->backfat_thickness}} --}}
                        Backfat Thickness: <span> @{{ productInfoModal.bft }} </span> mm
                    </li>
                    -->
                    
                    {{-- Breeder Ratings --}}
                    <li class="row collection-item rating  grey lighten-4">
                        <span style="color: hsl(0, 0%, 13%); font-weight: 700;">Breeder Ratings</span>
                        <div class="delivery-rating">
                            <span class="col s6"> Delivery: </span>
                            <span class="col s6"> <average-star-rating :rating="productInfoModal.avgDelivery | round"></average-star-rating> </span>
                        </div>
                        <div class="transaction-rating">
                            <span class="col s6"> Transaction: </span>
                            <span class="col s6"> <average-star-rating :rating="productInfoModal.avgTransaction | round"></average-star-rating> </span>
                        </div>
                        <div class="product-quality-rating">
                            <span class="col s6"> Product Quality: </span>
                            <span class="col s6"> <average-star-rating :rating="productInfoModal.avgProductQuality | round"></average-star-rating> </span>
                        </div>
                    </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
    </div>

    {{-- Template for <order-details> component --}}
    <template id="order-details-template">

        <div class="">
            {{-- Swine Cart --}}
            <div id="swine-cart">

                <div id="card-container" class="row">

                    {{-- Card --}}
                    <div class="col m4" v-for="(product, index) in sortedProducts">
                        <div class="card hoverable sticky-action" :class="(product.request_status) ? 'teal darken-2' : 'blue-grey lighten-5'">
                            {{-- Product Image --}}
                            <div class="card-image">
                                <img class="activator" :src="product.img_path">

                                {{-- Show FAB for specific actions --}}
                                <a class="btn-floating btn-large halfway-fab waves-effect waves-light red tooltipped"
                                    data-position="top"
                                    data-delay="50"
                                    data-tooltip="Send message to Breeder"
                                    :href="'/customer/messages/' + product.user_id"
                                    v-if="product.status === 'reserved' | product.status === 'on_delivery' | product.status === 'paid'"
                                >
                                    <i class="material-icons">message</i>
                                </a>

                                <a class="btn-floating btn-large halfway-fab waves-effect waves-light red tooltipped"
                                    data-position="top"
                                    data-delay="50"
                                    data-tooltip="Rate Breeder"
                                    v-if="product.status === 'sold'"
                                    @click.prevent="showRateModal(product.item_id)"
                                >
                                    <i class="material-icons">grade</i>
                                </a>

                            </div>
                            {{-- Product Card --}}
                            <div style="height: 35vh !important;" class="card-content" :class="(product.request_status) ? 'white-text' : 'blue-grey-text text-darken-4'">
                                {{-- Title --}}
                                <span class="card-title">
                                    <a href="#"
                                        class="anchor-title"
                                        :class="(product.request_status) ? 'white-text' : 'blue-grey-text text-darken-4'"
                                        @click.prevent="viewProductModalFromCart(product.item_id)"
                                        style="font-weight: 700;"
                                    >
                                        @{{ product.product_name }}
                                    </a>
                                </span>

                                {{-- Product Info --}}
                                <p class="row" style="min-height:100px;">
                                    <span class="col s12">
                                        <span style="font-weight: 600;">@{{ product.product_type | capitalize }} - @{{ product.product_breed }}</span>
                                        <br>
                                        Breeder: @{{ product.breeder }}
                                    </span>

                                    {{-- product not yet requested--}}
                                    <span class="col s12 input-quantity-container" v-if="product.product_type === 'semen' && !product.request_status">
                                        {{-- Request Quantity for semen --}}
                                        <span class="col s6">
                                            Quantity:
                                        </span>
                                        <span class="col s6">

                                            {{-- minus button--}}
                                            <span class="col s4 center-align">
                                                <a href="#"
                                                    class="btn col s12" 
                                                    style="padding:0; width: 2vw;"
                                                    @click.prevent="subtractQuantity(product.item_id)"
                                                >
                                                    <i class="material-icons">remove</i>
                                                </a>
                                            </span>

                                            {{-- product quantity --}}
                                            <span class="col s4 center-align" style="padding:0;">
                                                <quantity-input v-model="product.request_quantity"> </quantity-input>
                                            </span>
                                            
                                            {{-- plus button--}}
                                            <span class="col s4 center-align">
                                                <a href="#"
                                                    class="btn col s12"
                                                    style="padding:0; width: 2vw;"
                                                    @click.prevent="addQuantity(product.item_id)"
                                                >
                                                    <i class="material-icons">add</i>
                                                </a>
                                            </span>
                                        </span>
                                    </span>

                                    {{-- product was requested --}}
                                    <span class="col s6" v-else>
                                        Quantity: @{{ product.request_quantity }}
                                    </span>
                                    
                                    {{-- Show Request Details if product is already requested --}}
                                    <span class="col s12"
                                        v-if="product.request_status && product.status === 'requested'"
                                    >
                                        <a href="#"
                                            class="anchor-title white-text"
                                            @click.prevent="viewRequestDetails(product.item_id)"
                                            style="font-weight: 600; text-decoration: underline;" 
                                        >
                                            Request Details
                                        </a>
                                    </span>

                                    {{-- Show expected date to be delivered if product is already On Delivery --}}
                                    <span class="col s12"
                                        v-if="product.status === 'on_delivery'"
                                        style="font-weight: 700; font-size: 2vh;"
                                    >
                                        Expected to arrive on: @{{ product.delivery_date }}
                                    </span>
                                </p>

                            </div>
                            <div style="height: 10vh !important;" class="card-action">
                                <span class="status-icons-container">
                                    {{-- Product Status icons --}}

                                    {{-- Not yet Requested --}}
                                    <div class="row">
                                        <template v-if="!product.request_status">
                                            <a class="btn blue"
                                                href="#!"
                                                style="font-weight: 700;" 
                                                @click.prevent="confirmRequest(product.item_id)"
                                            >
                                                Request
                                            </a>
                                            <a href="#!"
                                                @click.prevent="confirmRemoval(product.item_id)"
                                                class="blue-grey lighten-5"
                                                style="color: #37474f; font-weight: 700; padding-left: 2vw;"
                                            >
                                                Remove
                                            </a>
                                        </template>
                                    </div>

                                    {{-- Requested --}}
                                    <div class="row">
                                    <template v-if="product.request_status && product.status === 'requested'">
                                        <i class="material-icons tooltipped white-text" data-position="top" data-delay="50" :data-tooltip="product.status_transactions.requested | transformToDetailedDate('Requested')">queue</i>
                                        <i class="material-icons tooltipped grey-text text-lighten-1" data-position="top" data-delay="50" data-tooltip="Not yet Reserved">save</i>
                                        <i class="material-icons tooltipped grey-text text-lighten-1" data-position="top" data-delay="50" data-tooltip="Not yet On Delivery">local_shipping</i>
                                    </template>                                        
                                    </div>

                                    {{-- Reserved --}}
                                    <div class="row">
                                    <template v-if="product.status === 'reserved'">
                                        <i class="material-icons tooltipped white-text" data-position="top" data-delay="50" :data-tooltip="product.status_transactions.requested | transformToDetailedDate('Requested')">queue</i>
                                        <i class="material-icons tooltipped white-text" data-position="top" data-delay="50" :data-tooltip="product.status_transactions.reserved | transformToDetailedDate('Reserved')">save</i>
                                        <i class="material-icons tooltipped grey-text text-lighten-1" data-position="top" data-delay="50" data-tooltip="Not yet On Delivery">local_shipping</i>
                                    </template>
                                    </div>

                                    {{-- On Delivery --}}
                                    <div class="row">
                                    <template v-if="product.status === 'on_delivery'">
                                        <i class="material-icons tooltipped white-text" data-position="top" data-delay="50" :data-tooltip="product.status_transactions.requested | transformToDetailedDate('Requested')">queue</i>
                                        <i class="material-icons tooltipped white-text" data-position="top" data-delay="50" :data-tooltip="product.status_transactions.reserved | transformToDetailedDate('Reserved')">save</i>
                                        <i class="material-icons tooltipped white-text" data-position="top" data-delay="50" :data-tooltip="product.status_transactions.on_delivery | transformToDetailedDate('On Delivery')">local_shipping</i>
                                    </template>
                                    </div>

                                    {{-- Sold --}}
                                    <div class="row">
                                    <template v-if="product.status === 'sold'">
                                        <i class="medium material-icons tooltipped white-text" data-position="top" data-delay="50" :data-tooltip="product.status_transactions.sold | transformToDetailedDate('Sold')">local_offer</i>
                                    </template>
                                    </div>
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Show the following if there are no products in Swine Cart --}}
                    <div class="center-align col s12" v-show="products.length === 0">
                        <h5>Your swine cart is empty.</h5>
                    </div>
                </div>

            </div>

            {{--  Remove Product Confirmation Modal --}}
            <div id="remove-product-confirmation-modal" class="modal">
                <div class="modal-content">
                    <h4 class="grey-text text-darken-2">Remove Product?</h4>
                    <p class="grey-text text-darken-2">
                        Removing @{{ productRemove.name}} will be removed from your Swine Cart.
                    </p>
                </div>
                <div class="modal-footer">
                    <a class="modal-action waves-effect
                        waves-green btn-flat remove-product-button
                        grey-text"
                        style="text-transform: none;  font-weight: 700;"
                        @click.prevent="removeProduct"
                    >
                        Yes, Remove the product
                    </a>
                    <a class="modal-action modal-close
                        waves-effect waves-green btn-flat blue white-text"
                        style="text-transform: none; font-weight: 700;"
                    >
                        No, Keep the product
                    </a>
                </div>
            </div>

            {{--  Request Product Confirmation Modal--}}
            <div id="request-product-confirmation-modal" class="modal"
                style="width: 60% !important;
                max-height: 100% !important;">
                <div class="modal-content">
                    <h4 class="grey-text text-darken-2">Request Product?</h4>
                    <p class="grey-text text-darken-2">    
                        Requesting @{{ productRequest.name }} sends a request to the breeder for buying the product.
                        <blockquote style="background-color:#ffcdd2; border-left: 3px solid red;" class="info" v-if="productRequest.type === 'semen'">
                            Once requested, request quantity can never be changed. Also, this product cannot be removed from the Swine Cart unless it will be reserved to another customer.
                        </blockquote>
                        <blockquote style="background-color:#ffcdd2; border-left: 3px solid red;" class="info" v-else>
                            Once requested, this product cannot be removed from the Swine Cart unless it will be reserved to another customer.
                        </blockquote>
                    </p>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s10"
                                v-show="productRequest.type === 'semen'"
                            >
                                <custom-date-select v-model="productRequest.dateNeeded" @date-select="dateChange"></custom-date-select>
                            </div>
                            <div class="input-field col s12">
                                <textarea id="special-request" class="materialize-textarea" v-model="productRequest.specialRequest"></textarea>
                                <label for="special-request">Message / Special Request</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="modal-action waves-effect waves-green btn blue request-product-buttons"
                        @click.prevent="requestProduct($event)"
                        style="text-transform: none;  font-weight: 700;"
                    >
                        Yes, Confirm Request Product
                    </a>
                    <a class="modal-action modal-close waves-effect waves-green btn-flat grey-text request-product-buttons"
                        style="text-transform: none; font-weight: 700;"
                    >Cancel</a>
                </div>
            </div>

            {{--  Product Request Details modal --}}
            <div id="product-request-details-modal" class="modal">
                <div class="modal-content">
                    <h4 class="grey-text text-darken-2">@{{ requestDetails.name }} Request Details</h4>
                    <table class="grey-text">
                        <thead>
                            <tr> </tr>
                        </thead>
                        <tbody>
                            <tr v-show="requestDetails.type === 'semen'">
                                <th> Date Needed </th>
                                <td> @{{ requestDetails.dateNeeded }} </td>
                            </tr>
                            <tr>
                                <th> Special Request </th>
                                <td> @{{ requestDetails.specialRequest }} </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <a class="modal-action modal-close waves-effect waves-green btn-flat ">Ok</a>
                </div>
            </div>

            {{-- Rating Modal --}}
            <div id="rate-modal" class="modal">
              <div class="modal-content">
                <h4 class="flow-text grey-text text-darken-2">Breeder: @{{ breederRate.breederName }} <i class="material-icons right modal-close">close</i></h4>
                <div class="divider"></div>
                <div>
                    <br>
                    <span class="row grey-text text-darken-2">
                        <span class="col s6">Delivery</span>
                        <span id="delivery" class="col s6 right-align">
                            <star-rating ref="delivery"
                                :type="'delivery'"
                                v-on:set-delivery-rating="setDeliveryRating"
                            >
                            </star-rating>
                        </span>
                    </span>
                    <span class="row grey-text text-darken-2">
                        <span class="col s6">Transaction</span>
                        <span id="transaction" class="col s6 right-align">
                            <star-rating ref="transaction"
                                :type="'transaction'"
                                v-on:set-transaction-rating="setTransactionRating"
                            >
                            </star-rating>
                        </span>
                    </span>
                    <span class="row grey-text text-darken-2">
                        <span class="col s6">Product Quality</span>
                        <span id="productQuality" class="col s6 right-align">
                            <star-rating ref="productQuality"
                                :type="'productQuality'"
                                v-on:set-product-rating="setProductRating"
                            ></star-rating>
                        </span>
                    </span>
                    <div class="row">
                        <div id="comment-container" class="input-field col s12">
                            <textarea id="comment" class="materialize-textarea" v-model="breederRate.commentField"></textarea>
                            <label for="comment">Comment</label>
                        </div>
                    </div>
                </div>
              </div>
              <div class="modal-footer">
                    <a class="modal-action waves-effect waves-green btn blue rate-breeder-buttons"
                        @click.prevent="rateAndRecord($event)"
                        style="font-weight: 700;"
                    >
                        Rate
                    </a>
              </div>
            </div>
        </div>

    </template>

    {{-- Template for <transaction-history> component --}}
    <template id="transaction-history-template">

        <div class="row">
            {{-- Transaction History --}}
            <div id="transaction-history">
                <div class="row">
                    <div class="col s5 center-align" style="font-weight: 700;">
                        ITEM(S)
                    </div>
                    <div class="col s3" style="font-weight: 700;">
                        BREEDER
                    </div>
                    <div class="col s4" style="font-weight: 700;">
                        LOGS
                    </div>
                </div>
                <ul id="transaction-cart" class="collection cart">
                    <li class="collection-item"
                        v-for="(item,key) in history"
                    >
                        <div class="row swine-cart-item valign-wrapper">
                            <div class="col s2 center-align">
                                <a><img :src="item.product_details.s_img_path" width="75" height="75" class="circle"></a>
                            </div>
                            <div class="col s3 verticalLine valign-wrapper">
                                <div class="valign">

                                    {{-- Name --}}
                                    <a href="#" class="anchor-title blue-text"
                                        @click.prevent="viewProductModalFromHistory(key)"
                                        style="font-weight: 700;"
                                    >
                                        <span class="col s12">@{{ item.product_details.name }}</span>
                                    </a>

                                    {{-- Type --}}
                                    <span class="col s12">
                                        @{{ item.product_details.type | capitalize }} - @{{ item.product_details.breed }}
                                    </span>

                                    {{-- Quanitity --}}
                                    <span class="col s12"
                                        v-if="item.product_details.type === 'semen' && item.product_details.quantity"
                                    >
                                        Quantity: @{{ item.product_details.quantity }}
                                    </span>
                                </div>
                            </div>

                            {{-- Breeder and Farm --}}
                            <div class="col s3 verticalLine valign-wrapper">
                                <div class="valign">
                                    <span style="color:hsl(0, 0%, 10%);">@{{ item.product_details.breeder_name }}</span><br>
                                    <span style="color:hsl(0, 0%, 45%);">@{{ item.product_details.farm_from }}</span>
                                </div>
                            </div>
                            <div class="col s4">
                                {{-- Just show three of the recent logs --}}
                                <div style="color:hsl(0, 0%, 45%);" v-for="log in reverseArray(item.logs)">
                                    <span class="col s6"> @{{ log.status | transformToReadableStatus }} </span>
                                    <span class="col s6 left-align grey-text"> @{{ log.created_at | transformToDetailedDate }} </span>
                                </div>

                                {{-- Extra logs if there are any --}}
                                <div style="color:hsl(0, 0%, 30%);"
                                    v-show="item.showFullLogs"
                                    v-for="log in trimmedArray(item.logs)"
                                >
                                    <span class="col s6"> @{{ log.status | transformToReadableStatus }} </span>
                                    <span class="col s6 left-align grey-text"> @{{ log.created_at | transformToDetailedDate }} </span>
                                </div>

                                {{-- Show toggle button to show more than three recent logs --}}
                                <div class="" v-if="(item.logs.length > 3)">
                                    <span class="col s6 left-align">
                                        <a href="#"
                                            @click.prevent="toggleShowFullLogs(key)"
                                        >
                                            @{{ (!item.showFullLogs) ? 'Show More' : 'Show Less' }}
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </li>

                    {{-- If there is no item in the transacion history --}}
                    <div class="center-align" v-if="history.length === 0">
                        <h5>Your history is empty.</h5>
                    </div>
                </ul>
            </div>
        </div>

    </template>

    <template id="star-rating-template">
        <div class="">
            <a href="#"
                class="transaction"
                v-for="(star, index) in starValues"
                @click.prevent="getValue(index)"
                @mouseover="animateHover(index)"
                @mouseout="deanimateHover"
            >
                <i class="material-icons" :class="star.class">@{{ star.icon }}</i>
            </a>
        </div>
    </template>

    <script type="text/x-template" id="average-star-rating">
        <div class="ratings-container" style="padding:0; position:relative; display:inline-block">
            <div class="star-ratings-top" style="position:absolute; z-index:1; overflow:hidden; display:block; white-space:nowrap;" :style="{ width: ratingToPercentage + '%' }">
                <i class="material-icons yellow-text"> star </i>
                <i class="material-icons yellow-text"> star </i>
                <i class="material-icons yellow-text"> star </i>
                <i class="material-icons yellow-text"> star </i>
                <i class="material-icons yellow-text"> star </i>
            </div>
            <div class="star-ratings-bottom" style="padding:0; z-index:0; display:block;">
                <i class="material-icons yellow-text"> star_border </i>
                <i class="material-icons yellow-text"> star_border </i>
                <i class="material-icons yellow-text"> star_border </i>
                <i class="material-icons yellow-text"> star_border </i>
                <i class="material-icons yellow-text"> star_border </i>
            </div>
        </div>
    </script>
@endsection

@section('customScript')
    <script type="text/javascript">
        // Variables
        var rawProducts = {!! $products !!};
        console.log(rawProducts);
    </script>
    <script src="{{ elixir('/js/customer/swinecartPage.js') }}"></script>
@endsection
