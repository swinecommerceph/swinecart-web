{{--
    Displays products of the respective Breeder user
--}}

@extends('user.breeder.home')

@section('title')
    | Breeder - Product Status
@endsection

@section('pageId')
    id="page-breeder-product-status"
@endsection

@section('breadcrumbTitle')
    Product Status
@endsection

@section('breadcrumb')
    <a href="{{ route('home_path') }}" class="breadcrumb">Home</a>
    <a href="{{ route('dashboard') }}" class="breadcrumb">Dashboard</a>
    <a href="#!" class="breadcrumb">Product Status</a>
@endsection

@section('content')
    <div class="row">
        <div class="col s12">
            <p class="caption">
                See what's happening with your products. <br>
            </p>
        </div>
    </div>

    <div id="product-status-container" class="row">
        <div class="row">
            <div id="status-select" class="input-field col left">
                <custom-status-select v-model="statusFilter" @status-select="statusChange"> </custom-status-select>
            </div>
            <form class="col s3 right">
                <div class="input-field col s12">
                    <input type="text" id="search" name="search" v-model="searchQuery">
                    <label for="search">Search</label>
                </div>
            </form>
        </div>
        <status-table :token="'{{ $token }}'"
            :products="products"
            :filter-query="searchQuery"
            :status-filter="statusFilter"
            @update-product="updateProduct"
        >
        </status-table>
    </div>

    {{-- Template for the <status-table> component --}}
    <template id="status-table-template">
        <div class="">
            <table id="product-status-table" class="bordered highlight">
                <thead>
                    <tr>
                        <th @click="sortBy('name')" :class="sortKey == 'name' ? 'red-text' : '' ">
                            <span class="">
                                Product Information
                                <i class="material-icons right" v-if="sortOrders['name'] > 0">keyboard_arrow_up</i>
                                <i class="material-icons right" v-else>keyboard_arrow_down</i>
                            </span>
                        </th>
                        <th @click="sortBy('status')" :class="sortKey == 'status' ? 'red-text' : '' ">
                            <span class="">
                                Status
                                <i class="material-icons right" v-if="sortOrders['status'] > 0">keyboard_arrow_up</i>
                                <i class="material-icons right" v-else>keyboard_arrow_down</i>
                            </span>
                        </th>
                        <th> Actions </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="product in filteredProducts">
                        <td>
                            <div class="row">
                                <div class="col s2 center-align">
                                    <a href="#!">
                                        <img v-bind:src="product.img_path" width="75" height="75" class="circle"/>
                                    </a>
                                </div>
                                <div class="col s4 valign-wrapper">
                                    @{{ product.name }}<br>
                                    @{{ product.type | capitalize }} - @{{ product.breed }} <br>
                                    <template v-if="product.reservation_id && product.type === 'semen'">
                                        Quantity: @{{ product.quantity }}
                                    </template>
                                </div>
                                <div class="col s6">
                                    <template v-if="product.customer_name">
                                        @{{ product.customer_name }} <br>
                                        <a href="#"
                                            class="anchor-title teal-text"
                                            @click.prevent="showReservationDetails(product.uuid)"
                                        >
                                            RESERVATION DETAILS
                                        </a> <br>
                                    </template>

                                    <span v-if="product.expiration_date" class="grey-text">
                                        Expires after: @{{ product.expiration_date | transformDate }}
                                    </span>

                                </div>
                            </div>
                        </td>
                        <td>
                            @{{ product.status | transformToReadableStatus }}
                        </td>
                        <td>
                            {{--  If product's status is requested --}}
                            <a class="tooltipped"
                                href="#"
                                data-position="top"
                                data-delay="50"
                                data-tooltip="See product requests"
                                @click.prevent="getProductRequests(product.uuid, $event)"
                                v-if="product.status == 'requested'"
                            >
                                <i class="material-icons teal-text">face</i>
                            </a>

                            {{-- If product's status is reserved --}}
                            <div class="row"
                                v-if="product.status == 'reserved'"
                            >
                                <a class="col s2 tooltipped"
                                    href="#"
                                    data-position="top"
                                    data-delay="50"
                                    data-tooltip="Confirm Delivery"
                                    @click.prevent="setUpConfirmation(product.uuid,'delivery')"
                                >
                                    <i class="material-icons teal-text">local_shipping</i>
                                </a>
                                <a class="col s2 tooltipped"
                                    href="#"
                                    data-position="top"
                                    data-delay="50"
                                    data-tooltip="Confirm Payment"
                                    @click.prevent="setUpConfirmation(product.uuid,'paid')"
                                >
                                    <i class="material-icons teal-text">credit_card</i>
                                </a>
                                <a class="col s2 tooltipped"
                                    :href="'{{ route('breeder.messages') }}/' + product.userid" //cjd
                                    :data-breeder-id="product.breeder_id"
                                    :data-customer-id="product.customer_id"
                                    data-position="top"
                                    data-delay="50"
                                    :data-tooltip="'Message ' + product.customer_name"
                                >
                                    <i class="material-icons teal-text">message</i>
                                </a>
                            </div>

                            {{-- If product's status is on_delivery --}}
                            <div class="row"
                                v-if="product.status == 'on_delivery'"
                            >
                                <a class="col s2 tooltipped left"
                                    href="#"
                                    data-position="top"
                                    data-delay="50"
                                    data-tooltip="Confirm Sold"
                                    @click.prevent="setUpConfirmation(product.uuid,'sold')"
                                >
                                    <i class="material-icons teal-text">thumb_up</i>
                                </a>
                                <span>(Awaiting Payment)</span>
                            </div>

                            {{-- If product's status is paid --}}
                            <div class="row"
                                v-if="product.status == 'paid'"
                            >
                                <a class="col s2 tooltipped left"
                                    href="#"
                                    data-position="top"
                                    data-delay="50"
                                    data-tooltip="Confirm Sold"
                                    @click.prevent="setUpConfirmation(product.uuid,'sold')"
                                >
                                    <i class="material-icons teal-text">thumb_up</i>
                                </a>
                                <span>(Awaiting Delivery)</span>
                            </div>

                            {{-- If product's status is sold --}}
                            <div v-if="product.status == 'sold'">
                                (SOLD)
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            {{-- Product Requests Modal --}}
            <div id="product-requests-modal" class="modal modal-fixed-footer">
                <div class="modal-content">
                    <h4>@{{ productRequest.productName }} Product Requests</h4>
                    <p>
                        @{{ productRequest.type | capitalize }} - @{{ productRequest.breed }}
                    </p>
                    <table class="responsive-table bordered highlight">
                        <thead>
                            <tr>
                                <th> Name </th>
                                <th> Province </th>
                                <th> Special Request </th>
                                <th class="right-align"> Quantity </th>
                                <th class="right-align" v-show="productRequest.type === 'semen'"> Date Needed </th>
                                <th class="center-align"> Actions </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(customer, index) in productRequest.customers">
                                <td>
                                    @{{ customer.customerName }}
                                </td>
                                <td> @{{ customer.customerProvince }} </td>
                                <td>
                                    <p style="max-width:15rem;">
                                        @{{ customer.specialRequest }}
                                    </p>
                                </td>
                                <td class="right-align"> @{{ customer.requestQuantity }} </td>
                                <td class="right-align" v-show="productRequest.type === 'semen'"> @{{ customer.dateNeeded }} </td>
                                <td class="row center-align">
                                    <a href="#!"
                                        class="tooltipped"
                                        data-position="top"
                                        data-delay="50"
                                        :data-tooltip="'Reserve product to ' + customer.customerName"
                                        @click.prevent="confirmReservation(index)"
                                    >
                                        <i class="material-icons teal-text">add_to_photos</i>
                                    </a>
                                    <a v-bind:href="'{{ route('breeder.messages') }}/' + customer.userId"
                                        style="margin-left:0.5rem;"
                                        data-position="top"
                                        data-delay="50"
                                        :data-tooltip="'Send message to ' + customer.customerName"
                                    >
                                        <i class="material-icons teal-text">message</i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <a class="modal-action modal-close waves-effect waves-green btn-flat ">Close</a>
                </div>
            </div>

            {{-- Reserve Product Confirmation Modal --}}
            <div id="reserve-product-confirmation-modal" class="modal">
                <div class="modal-content">
                    <h4>Reserve Product Confirmation</h4>
                    <div>
                        <div class="">
                            Are you sure you want to reserve @{{ productRequest.productName }} to @{{ productReserve.customerName }}?
                        </div>
                        <div class="row">
                            <div class="col s6" style="display:inline-block;">
                                <div class="left" style="display:inline;">
                                    <br>
                                    Reservation expires after
                                </div>

                                <div class="col s2">
                                    <day-expiration-input v-model="productReserve.daysAfterExpiration"> </day-expiration-input>
                                </div>

                                <div class="col s2" style="padding:0px;">
                                    <br>
                                    day/s
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="modal-action modal-close waves-effect waves-green btn-flat ">Close</a>
                    <a class="modal-action waves-effect waves-green btn-flat" @click.prevent="reserveToCustomer">Yes</a>
                </div>
            </div>

            {{-- Product Delivery Confirmation Modal --}}
            <div id="product-delivery-confirmation-modal" class="modal">
                <div class="modal-content">
                    <h4>@{{ productInfoModal.productName }} Delivery Confirmation</h4>
                    <p>
                        Are you sure the product is on delivery to @{{ productInfoModal.customerName }}?
                    </p>
                </div>
                <div class="modal-footer">
                    <a class="modal-action modal-close waves-effect waves-green btn-flat ">Close</a>
                    <a class="modal-action waves-effect waves-green btn-flat" @click.prevent="productOnDelivery">Yes</a>
                </div>
            </div>

            {{-- Paid Product Confirmation Modal --}}
            <div id="paid-product-confirmation-modal" class="modal">
                <div class="modal-content">
                    <h4>@{{ productInfoModal.productName }} Pay Confirmation</h4>
                    <p>
                        Are you sure the product is already paid by @{{ productInfoModal.customerName }}?
                    </p>
                </div>
                <div class="modal-footer">
                    <a class="modal-action modal-close waves-effect waves-green btn-flat">Close</a>
                    <a class="modal-action waves-effect waves-green btn-flat" @click.prevent="productPaid">Yes</a>
                </div>
            </div>

            {{-- Sold Product Confirmation Modal --}}
            <div id="sold-product-confirmation-modal" class="modal">
                <div class="modal-content">
                    <h4>@{{ productInfoModal.productName }} Sold Confirmation</h4>
                    <p>
                        Are you sure the product is already sold to @{{ productInfoModal.customerName }}?
                    </p>
                </div>
                <div class="modal-footer">
                    <a class="modal-action modal-close waves-effect waves-green btn-flat">Close</a>
                    <a class="modal-action waves-effect waves-green btn-flat" @click.prevent="productOnSold">Yes</a>
                </div>
            </div>

            {{--  Product Reservation Details modal --}}
            <div id="product-reservation-details-modal" class="modal">
                <div class="modal-content">
                    <h4>@{{ reservationDetails.productName }} Reservation Details</h4>
                    <p>
                        @{{ reservationDetails.type | capitalize }} - @{{ reservationDetails.breed }}
                    </p>
                    <table>
                        <thead>
                            <tr> </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th> Customer Name </th>
                                <td> @{{ reservationDetails.customerName }} </td>
                            </tr>
                            <tr v-show="reservationDetails.type === 'semen'">
                                <th> Date Needed </th>
                                <td> @{{ reservationDetails.dateNeeded }} </td>
                            </tr>
                            <tr>
                                <th> Special Request </th>
                                <td> @{{ reservationDetails.specialRequest }} </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <a class="modal-action modal-close waves-effect waves-green btn-flat ">Close</a>
                </div>
            </div>
        </div>
    </template>
@endsection

@section('customScript')
    <script src="/js/vendor/lodash.min.js"></script>
    <script type="text/javascript">
        // Variables
        var rawProducts = {!! $products !!};
    </script>
    <script src="/js/breeder/dashboardProductStatus.js"></script>
@endsection
