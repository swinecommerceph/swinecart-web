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
    <li><a href="{{ route('home_path') }}"> <i class="material-icons">message</i></a></li>
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
                                <p style="margin-left:1.5rem;"> @{{ notification.data.description }} </p>
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

    <div class="" id="swine-cart-container">

        {{-- Tabs --}}
        <ul class="tabs">
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

        {{-- Product Info Modal --}}
        <div id="info-modal" class="modal">
          <div class="modal-content">
            <h4 style="overflow:auto"><a href="#"><i class="material-icons black-text right modal-close">close</i></a></h4>
            <div class="row">
              <div class="image col s12 m7">
                <div class="row">
                  <div class="col s12">
                    <div class="row">
                        <div class="card">
                            <div class="card-image">
                                <img id="modal-img" :src="productInfoModal.imgPath">
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
                            <div class="col product-name">
                                {{-- productName --}}
                                @{{ productInfoModal.name }}
                            </div>
                        </h4>
                        <div class="row">
                            <div class="col breeder product-farm">
                              {{-- Breeder and farm_province --}}
                              @{{ productInfoModal.breeder }} <br>
                              @{{ productInfoModal.province }}
                            </div>
                        </div>
                    </li>
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
                    <li class="collection-item rating">
                        <div class="delivery-rating">Delivery:<span class="col right"><span> @{{ productInfoModal.avgDelivery | round }} </span>/5</span></div>
                        <div class="transaction-rating">Transaction:<span class="col right"><span> @{{ productInfoModal.avgTransaction | round }} </span>/5</span></div>
                        <div class="product-quality-rating">Product Quality:<span class="col right"><span> @{{ productInfoModal.avgProductQuality | round }} </span>/5</span></div>
                    </li>
                </ul>
              </div>
            </div>
          </div>
        </div>

    </div>

    {{-- Template for <order-details> component --}}
    <template id="order-details-template">

        <div class=""
            @set-delivery-rating="setDeliveryRating"
            @set-transaction-rating="setTransactionRating"
            @set-product-rating="setProductRating"
        >
            {{-- Swine Cart --}}
            <div id="swine-cart">
                <div class="row">
                    <span class="col s2 left-align">
                        STATUS
                    </span>
                    <div class="col s6">
                        ITEM(S)
                    </div>
                    <div class="col s2">
                        QUANTITY
                    </div>
                    <div class="col s2">
                        ACTION
                    </div>
                </div>
                <ul id="cart" class="collection cart">

                    {{-- Original Content --}}
                    <li class="collection-item" :data-product-id="product.item_id" v-for="(product, index) in products">
                        <div class="row swine-cart-item valign-wrapper">
                            {{-- Product Status Icons --}}

                            {{-- Requested --}}
                            <div class="status col s2 m2 verticalLine valign-wrapper"
                                v-if="product.request_status && product.status === 'requested'"
                            >
                                <div class="">
                                    <span class="col s6 right-align">
                                        <a href="#">
                                            <i class="request material-icons teal-text tooltipped" data-position="top" data-delay="50" :data-tooltip="'Requested, ' + product.status_transactions.requested | transformToDetailedDate">queue</i>
                                        </a>
                                    </span>
                                    <span class="col s6 left-align">
                                        <a href="#">
                                            <i class="reserved material-icons tooltipped grey-text text-darken-4" data-position="top" data-delay="50" data-tooltip="Not Reserved">save</i>
                                        </a>
                                    </span>
                                    <span class="col s6 right-align">
                                        <a href="#">
                                            <i class="on-delivery material-icons tooltipped grey-text text-darken-4" data-position="bottom" data-delay="50" data-tooltip="Not on Delivery">local_shipping</i>
                                        </a>
                                    </span>
                                    <span class="col s6 left-align">
                                        <a href="#">
                                            <i class="paid material-icons tooltipped grey-text text-darken-4" data-position="bottom" data-delay="50" data-tooltip="Not Paid">payment</i>
                                        </a>
                                    </span>
                                </div>
                            </div>

                            {{-- Reserved --}}
                            <div class="status col s2 m2 verticalLine valign-wrapper"
                                v-if="product.status === 'reserved'"
                            >
                                <div class="">
                                    <span class="col s6 right-align">
                                        <a href="#">
                                            <i class="request material-icons teal-text tooltipped" data-position="top" data-delay="50" :data-tooltip="'Requested, ' + product.status_transactions.requested | transformToDetailedDate">queue</i>
                                        </a>
                                    </span>
                                    <span class="col s6 left-align">
                                        <a href="#">
                                            <i class="reserved material-icons teal-text tooltipped" data-position="top" data-delay="50" :data-tooltip="'Reserved, ' + product.status_transactions.reserved | transformToDetailedDate">save</i>
                                        </a>
                                    </span>
                                    <span class="col s6 right-align">
                                        <a href="#">
                                            <i class="on-delivery material-icons grey-text text-darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Waiting Delivery">local_shipping</i>
                                        </a>
                                    </span>
                                    <span class="col s6 left-align">
                                        <a href="#">
                                            <i class="paid material-icons grey-text text-darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Not Paid">payment</i>
                                        </a>
                                    </span>
                                </div>
                            </div>

                            {{-- Paid --}}
                            <div class="status col s2 m2 verticalLine valign-wrapper"
                                v-if="product.status === 'paid'"
                            >
                                <div class="">
                                    <span class="col s6 right-align">
                                        <a href="#">
                                            <i class="request material-icons teal-text tooltipped" data-position="top" data-delay="50" :data-tooltip="'Requested, ' + product.status_transactions.requested | transformToDetailedDate">queue</i>
                                        </a>
                                    </span>
                                    <span class="col s6 left-align">
                                        <a href="#">
                                            <i class="reserved material-icons teal-text tooltipped" data-position="top" data-delay="50" :data-tooltip="'Reserved, ' + product.status_transactions.reserved | transformToDetailedDate">save</i>
                                        </a>
                                    </span>
                                    <span class="col s6 right-align">
                                        <a href="#">
                                            <i class="on-delivery material-icons teal-text tooltipped" data-position="bottom" data-delay="50" data-tooltip="Awaiting Delivery">local_shipping</i>
                                        </a>
                                    </span>
                                    <span class="col s6 left-align">
                                        <a href="#">
                                            <i class="paid material-icons teal-text tooltipped" data-position="bottom" data-delay="50" :data-tooltip="'Paid, ' + product.status_transactions.paid | transformToDetailedDate">payment</i>
                                        </a>
                                    </span>
                                </div>
                            </div>

                            {{-- On Delivery --}}
                            <div class="status col s2 m2 verticalLine valign-wrapper"
                                v-if="product.status === 'on_delivery'"
                            >
                                <div class="">
                                    <span class="col s6 right-align">
                                        <a href="#">
                                            <i class="request material-icons teal-text tooltipped" data-position="top" data-delay="50" :data-tooltip="'Requested, ' + product.status_transactions.requested | transformToDetailedDate">queue</i>
                                        </a>
                                    </span>
                                    <span class="col s6 left-align">
                                        <a  href="#">
                                            <i class="reserved material-icons teal-text tooltipped" data-position="top" data-delay="50" :data-tooltip="'Reserved, ' + product.status_transactions.reserved | transformToDetailedDate">save</i>
                                        </a>
                                    </span>
                                    <span class="col s6 right-align">
                                        <a href="#">
                                            <i class="on-delivery material-icons teal-text tooltipped" data-position="bottom" data-delay="50" :data-tooltip="'On Delivery, ' + product.status_transactions.on_delivery | transformToDetailedDate">local_shipping</i>
                                        </a>
                                    </span>
                                    <span class="col s6 left-align">
                                        <a href="#">
                                            <i class="paid material-icons grey-text text-darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Awaiting Payment">payment</i>
                                        </a>
                                    </span>
                                </div>
                            </div>

                            {{-- Sold --}}
                            <div class="status col s2 m2 verticalLine valign-wrapper"
                                v-if="product.status === 'sold'"
                            >
                                <div class="col s12 center-align">
                                    <a href="#">
                                      <i class="material-icons md teal-text tooltipped" data-position="top" data-delay="50" :data-tooltip="'Sold, ' + product.status_transactions.sold | transformToDetailedDate">attach_money</i>
                                    </a>
                                </div>
                            </div>

                            {{-- Not yet requested --}}
                            <div class="status col s2 m2 verticalLine valign-wrapper"
                                v-if="!product.request_status"
                            >
                                <div class="">
                                    <span class="col s6 right-align">
                                        <a href="#">
                                            <i class="request material-icons grey-text text-darken-4 tooltipped" data-position="top" data-delay="50" data-tooltip="Not Requested">queue</i>
                                        </a>
                                    </span>
                                    <span class="col s6 left-align">
                                        <a href="#">
                                            <i class="reserved material-icons grey-text text-darken-4 tooltipped" data-position="top" data-delay="50" data-tooltip=" Not Reserved">save</i>
                                        </a>
                                    </span>
                                    <span class="col s6 right-align">
                                        <a href="#">
                                            <i class="on-delivery material-icons grey-text text-darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip=" Not On Delivery">local_shipping</i>
                                        </a>
                                    </span>
                                    <span class="col s6 left-align">
                                        <a href="#">
                                            <i class="paid material-icons grey-text text-darken-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Not Paid">payment</i>
                                        </a>
                                    </span>
                                </div>
                            </div>

                            {{-- Product image --}}
                            <div class="col s2 m2 center-align">
                                <a href="#"><img :src="product.img_path" width="75" height="75" class="circle"></a>
                            </div>

                            {{-- Product Info --}}
                            <div class="info col s4 verticalLine valign-wrapper">
                                <div class="valign">
                                    <a href="#"
                                        class="anchor-title teal-text"
                                        @click.prevent="viewProductModalFromCart(index)"
                                    >
                                        <span class="col s12">@{{ product.product_name }}</span>
                                    </a>
                                    <span class="col s12">
                                        @{{ product.product_type | capitalize }} - @{{ product.product_breed }}
                                    </span>
                                    <span class="col s12">
                                        @{{ product.breeder }}
                                    </span>
                                </div>
                            </div>

                            {{-- Quantity Check --}}
                            {{-- If product is semen show quantity --}}
                            <div class="quantity col s2 m2 verticalLine valign-wrapper"
                                v-if="product.product_type === 'semen' && !product.request_status"
                            >
                                <div class="col s4 center-align">
                                    <a href="#"
                                        class="btn col s12"
                                        style="padding:0;"
                                        @click.prevent="subtractQuantity(index)"
                                    >
                                        <i class="material-icons">remove</i>
                                    </a>
                                </div>
                                <div class="col s4 center-align" style="padding:0;">
                                    <quantity-input v-model="product.request_quantity"> </quantity-input>
                                </div>
                                <div class="col s4 center-align">
                                    <a href="#"
                                        class="btn col s12"
                                        style="padding:0;"
                                        @click.prevent="addQuantity(index)"
                                    >
                                        <i class="material-icons">add</i>
                                    </a>
                                </div>
                            </div>

                            <div class="quantity col s2 m2 verticalLine" v-else>
                                <div class="col s12 center-align" style="padding-top:2.2rem;">
                                    @{{ product.request_quantity }}
                                </div>
                            </div>


                            {{-- Actions --}}
                            <div class="action col s2 m2 center-align">

                                {{-- Not yet requested --}}
                                <span v-if="!product.request_status || product.status === 'displayed'">
                                    <a href="#"
                                        class="delete-from-swinecart btn"
                                        @click.prevent="confirmRemoval(index)"
                                    >
                                        Remove
                                    </a>
                                    <br><br>
                                    <a href="#"
                                        class="request-product btn"
                                        @click.prevent="confirmRequest(index)"
                                    >
                                        Request
                                    </a>
                                </span>

                                {{-- Requested --}}
                                <span v-if="product.request_status && product.status === 'requested'">
                                    (For Approval)
                                    <a href="#"
                                        class="anchor-title teal-text"
                                        @click.prevent="viewRequestDetails(index)"
                                    >
                                        REQUEST DETAILS
                                    </a>
                                </span>

                                {{-- Reserved --}}
                                <span v-if="product.status === 'reserved'">
                                    <a class="message-button btn-large" :data-breeder-id="product.breeder_id" :data-customer-id="product.customer_id">
                                        Message
                                    </a>
                                </span>

                                {{-- On Delivery --}}
                                <span v-if="product.status === 'on_delivery'">
                                    (Awaiting Payment)
                                </span>

                                {{-- Paid --}}
                                <span v-if="product.status === 'paid'">
                                    (Awaiting Delivery)
                                </span>

                                {{-- Sold --}}
                                <span class="col s12 center-align"
                                    v-if="product.status === 'sold'"
                                >
                                    <a id="rate-button"
                                        class="btn-large"
                                        @click.prevent="showRateModal(index)"
                                    >
                                        Rate
                                    </a>
                                </span>
                            </div>
                        </div>

                        {{-- Show the following if there are no products in Swine Cart --}}
                        <div class="center-align" v-show="!products">
                            <h5>Your swine cart is empty.</h5>
                        </div>
                    </li>

                </ul>
            </div>

            {{--  Remove Product Confirmation Modal --}}
            <div id="remove-product-confirmation-modal" class="modal">
                <div class="modal-content">
                    <h4>Remove Product Confirmation</h4>
                    <p>
                        Are you sure you want to remove @{{ productRemove.name }} from your Swine Cart?
                    </p>
                </div>
                <div class="modal-footer">
                    <a class="modal-action modal-close waves-effect waves-green btn-flat ">Close</a>
                    <a class="modal-action waves-effect waves-green btn-flat remove-product-button"
                        @click.prevent="removeProduct"
                    >
                        Yes
                    </a>
                </div>
            </div>

            {{--  Request Product Confirmation Modal--}}
            <div id="request-product-confirmation-modal" class="modal">
                <div class="modal-content">
                    <h4>Request Product Confirmation</h4>
                    <p>
                        Are you sure you want to request @{{ productRequest.name }}?
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
                    <a class="modal-action modal-close waves-effect waves-green btn-flat ">Close</a>
                    <a class="modal-action waves-effect waves-green btn-flat request-product-button"
                        @click.prevent="requestProduct"
                    >
                        Yes
                    </a>
                </div>
            </div>

            {{--  Product Request Details modal --}}
            <div id="product-request-details-modal" class="modal">
                <div class="modal-content">
                    <h4>@{{ requestDetails.name }} Request Details</h4>
                    <table>
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
                    <a class="modal-action modal-close waves-effect waves-green btn-flat ">Close</a>
                </div>
            </div>

            {{-- Rating Modal --}}
            <div id="rate-modal" class="modal">
              <div class="modal-content">
                <h4 class="flow-text">@{{ breederRate.breederName }} <i class="material-icons right modal-close">close</i></h4>
                <div class="divider"></div>
                <div>
                    <br>
                    <span class="row">
                        <span class="col s6">Delivery</span>
                        <span id="delivery" class="col s6 right-align">
                            <star-rating ref="delivery" :type="'delivery'"></star-rating>
                        </span>
                    </span>
                    <span class="row">
                        <span class="col s6">Transaction</span>
                        <span id="transaction" class="col s6 right-align">
                            <star-rating ref="transaction" :type="'transaction'"></star-rating>
                        </span>
                    </span>
                    <span class="row">
                        <span class="col s6">Product Quality</span>
                        <span id="productQuality" class="col s6 right-align">
                            <star-rating ref="productQuality" :type="'productQuality'"></star-rating>
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
                    <a class="modal-action waves-effect waves-green btn-flat"
                        @click.prevent="rateAndRecord"
                    >
                        Submit
                    </a>
              </div>
            </div>
        </div>

    </template>

    {{-- Template for <transaction-history> component --}}
    <template id="transaction-history-template">

        <div class="">
            {{-- Transaction History --}}
            <div id="transaction-history">
                <div class="row">
                    <div class="col s5">
                        ITEM(S)
                    </div>
                    <div class="col s3">
                        BREEDER
                    </div>
                    <div class="col s4">
                        TIME
                    </div>
                </div>
                <ul id="transaction-cart" class="collection cart">
                    <li class="collection-item"
                        v-for="log in history"
                    >
                        <div class="row  swine-cart-item valign-wrapper">
                            <div class="col s2 center-align">
                                <a href="#"><img :src="log.product_details.img_path" width="75" height="75" class="circle"></a>
                            </div>
                            <div class="col s3 verticalLine valign-wrapper">
                                <div class="valign">
                                    {{-- Name --}}
                                    <a href="#" class="anchor-title teal-text"
                                        @click.prevent="viewProductModalFromHistory(index)"
                                    >
                                        <span class="col s12">@{{ log.product_details.name }}</span>
                                    </a>

                                    {{-- Type --}}
                                    <span class="col s12">
                                        @{{ log.product_details.breed }} <br>
                                        @{{ log.product_details.type | capitalize }}
                                    </span>

                                    {{-- Quanitity --}}
                                    <span class="col s12" v-if="log.product_type === 'semen'">
                                        @{{ log.product_quantity }}
                                    </span>
                                </div>
                            </div>
                            <div class="col s3 verticalLine valign-wrapper">
                                <div class="valign">
                                    @{{ log.product_details.breeder_name }} <br>
                                    @{{ log.product_details.farm_from }}
                                </div>
                            </div>
                            <div class="col s4">
                                <div v-if="log.requested">
                                    Requested <span class="right"> @{{ log.requested | transformToDetailedDate }} </span>
                                </div>
                                <div v-if="log.reserved">
                                    Reserved <span class="right"> @{{ log.reserved | transformToDetailedDate }} </span>
                                </div>
                                <div v-if="log.on_delivery">
                                    On Delivery <span class="right"> @{{ log.on_delivery | transformToDetailedDate }} </span>
                                </div>
                                <div v-if="log.paid">
                                    Paid <span class="right"> @{{ log.paid | transformToDetailedDate }} </span>
                                </div>
                                <div v-if="log.sold">
                                    Sold <span class="right"> @{{ log.sold | transformToDetailedDate }} </span>
                                </div>
                                <div v-if="log.rated">
                                    Rated <span class="right"> @{{ log.rated | transformToDetailedDate }} </span>
                                </div>
                            </div>
                        </div>
                    </li>

                    {{-- If there is no item in the transacion history --}}
                    <div class="center-align" v-show="history.length === 0">
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

@endsection

@section('customScript')
    <script src="/js/vendor/lodash.min.js"></script>
    <script type="text/javascript">
        // Variables
        var rawProducts = {!! $products !!};
    </script>
    <script src="/js/customer/swinecartPage.js"> </script>
@endsection
