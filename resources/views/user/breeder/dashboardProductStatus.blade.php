{{--
    Displays products of the respective Breeder user
--}}

@extends('user.breeder.home')

@section('title')
    | Breeder - Product Inventory & Status
@endsection

@section('pageId')
    id="page-breeder-product-status"
@endsection

@section('breadcrumbTitle')
    Product Inventory & Status
@endsection

@section('breadcrumb')
    <a href="{{ route('home_path') }}" class="breadcrumb">Home</a>
    <a href="{{ route('dashboard') }}" class="breadcrumb">Dashboard</a>
    <a href="#!" class="breadcrumb">Product Inventory & Status</a>
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
            <table id="product-status-table" class="striped bordered">
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
                                        <span class="teal-text"
                                            style="cursor:pointer;"
                                            @click.prevent="showCustomerInfo(product.customer_id, product.customer_name)"
                                        >
                                            @{{ product.customer_name }}
                                        </span>
                                        <br>
                                        <a href="#"
                                            class="anchor-title teal-text"
                                            @click.prevent="showReservationDetails(product.uuid)"
                                        >
                                            RESERVATION DETAILS
                                        </a> <br>
                                    </template>

                                    <template v-if="product.customer_name">
                                        <a class="btn tooltipped"
                                            :href="'{{ route('breeder.messages') }}/' + product.userid"
                                            :data-breeder-id="product.breeder_id"
                                            :data-customer-id="product.customer_id"
                                            data-position="top"
                                            data-delay="50"
                                            :data-tooltip="'Message ' + product.customer_name"
                                        >
                                            Message
                                        </a>
                                    </template>

                                </div>
                            </div>
                        </td>
                        <td>
                            @{{ product.status | transformToReadableStatus }}
                            <br>
                            <span class="grey-text" v-if="product.status_time">
                                @{{ product.status_time | transformDate }}
                            </span>
                            <template v-if="product.status === 'on_delivery'">
                                <br>
                                Expected to arrive on @{{ product.delivery_date }}
                            </template>
                        </td>
                        <td>
                            {{--  If product's status is requested --}}
                            <a class="btn"
                                href="#"
                                @click.prevent="getProductRequests(product.uuid, $event)"
                                v-if="product.status == 'requested'"
                            >
                                See Requests
                            </a>

                            {{-- If product's status is reserved --}}
                            <template v-if="product.status == 'reserved'">
                                <a class="btn"
                                    style="margin-bottom:1rem;"
                                    href="#"
                                    @click.prevent="setUpConfirmation(product.uuid,'delivery')"
                                >
                                    Send for Delivery
                                </a> <br>
                                <a class="btn red accent-2"
                                    style="margin-bottom:1rem;"
                                    href="#"
                                    @click.prevent="setUpConfirmation(product.uuid,'cancel_transaction')"
                                >
                                    Cancel Transaction
                                </a>
                            </template>

                            {{-- If product's status is on_delivery --}}
                            <template v-if="product.status == 'on_delivery'">
                                <a class="btn"
                                    style="margin-bottom:1rem;"
                                    href="#"
                                    @click.prevent="setUpConfirmation(product.uuid,'sold')"
                                >
                                    Confirm Sold
                                </a> <br>
                                <a class="btn red accent-2"
                                    style="margin-bottom:1rem;"
                                    href="#"
                                    @click.prevent="setUpConfirmation(product.uuid,'cancel_transaction')"
                                >
                                    Cancel Transaction
                                </a>
                            </template>

                            {{-- If product's status is sold --}}
                            <template v-if="product.status == 'sold'">
                                <span class="teal-text">
                                    (SOLD)
                                </span>
                            </template>
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
                                    <span class="teal-text"
                                        style="cursor:pointer;"
                                        @click.prevent="showCustomerInfo(customer.customerId, customer.customerName)"
                                    >
                                        @{{ customer.customerName }}
                                    </span>
                                </td>
                                <td> @{{ customer.customerProvince }} </td>
                                <td>
                                    <p style="max-width:15rem;">
                                        @{{ customer.specialRequest }}
                                    </p>
                                </td>
                                <td class="right-align"> @{{ customer.requestQuantity }} </td>
                                <td class="right-align" v-show="productRequest.type === 'semen'"> @{{ customer.dateNeeded }} </td>
                                <td class="center-align">
                                    <a href="#!"
                                        class="btn tooltipped"
                                        style="margin-bottom:1rem;"
                                        data-position="top"
                                        data-delay="50"
                                        :data-tooltip="'Reserve product to ' + customer.customerName"
                                        @click.prevent="confirmReservation(index)"
                                    >
                                        Reserve
                                    </a> <br>
                                    <a v-bind:href="'{{ route('breeder.messages') }}/' + customer.userId"
                                        class="btn tooltipped"
                                        data-position="top"
                                        data-delay="50"
                                        :data-tooltip="'Send message to ' + customer.customerName"
                                    >
                                        Message
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
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="modal-action modal-close waves-effect waves-green btn-flat reserve-product-buttons">Close</a>
                    <a class="modal-action waves-effect waves-green btn-flat reserve-product-buttons" @click.prevent="reserveToCustomer($event)">Yes</a>
                </div>
            </div>

            {{-- Cancel Transaction Confirmation Modal --}}
            <div id="cancel-transaction-confirmation-modal" class="modal">
                <div class="modal-content">
                    <h4>Cancel Transaction Confirmation</h4>
                    <div>
                        <blockquote class="warning">
                            Once this action is done, it cannot be reverted.
                        </blockquote>
                        <div class="">
                            Are you sure you want to cancel transaction on @{{ productInfoModal.productName }} to @{{ productInfoModal.customerName }}?
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="modal-action modal-close waves-effect waves-green btn-flat cancel-transaction">Close</a>
                    <a class="modal-action waves-effect waves-green btn-flat cancel-transaction" @click.prevent="productCancelTransaction($event)">Yes</a>
                </div>
            </div>

            {{-- Product Delivery Confirmation Modal --}}
            <div id="product-delivery-confirmation-modal" class="modal">
                <div class="modal-content">
                    <h4>@{{ productInfoModal.productName }} Delivery Confirmation</h4>
                    <div>
                        <div class="">
                            Are you sure this product is set for delivery to @{{ productInfoModal.customerName }}?
                        </div>
                        <div class="row">
                           <div class="col s10" style="display:inline-block;">
                               <div class="left" style="display:inline;">
                                   <br>
                                   Product will be delivered to customer on or before
                               </div>

                               <div class="col s3">
                                   <custom-date-select v-model="productInfoModal.deliveryDate" @date-select="dateChange"> </custom-date-select>
                               </div>
                           </div>
                       </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="modal-action modal-close waves-effect waves-green btn-flat delivery-product-buttons">Close</a>
                    <a class="modal-action waves-effect waves-green btn-flat delivery-product-buttons" @click.prevent="productOnDelivery($event)">Yes</a>
                </div>
            </div>

            {{-- Sold Product Confirmation Modal --}}
            <div id="sold-product-confirmation-modal" class="modal">
                <div class="modal-content">
                    <h4>@{{ productInfoModal.productName }} Sold Confirmation</h4>
                    <p>
                        Are you sure this product is sold to @{{ productInfoModal.customerName }}?
                    </p>
                </div>
                <div class="modal-footer">
                    <a class="modal-action modal-close waves-effect waves-green btn-flat sold-product-buttons">Close</a>
                    <a class="modal-action waves-effect waves-green btn-flat sold-product-buttons" @click.prevent="productOnSold($event)">Yes</a>
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

            {{-- Customer info modal --}}
            <div id="customer-info-modal" class="modal">
                <div class="modal-content">
                    <h4>Customer Details</h4>
                    <table>
                        <thead>
                            <tr> </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th> Customer Name </th>
                                <td> @{{ customerInfo.name }} </td>
                            </tr>
                            <tr>
                                <th> Address </th>
                                <td>
                                    @{{ customerInfo.addressLine1 }} <br>
                                    @{{ customerInfo.addressLine2 }} <br>
                                    @{{ customerInfo.province }}
                                </td>
                            </tr>
                            <tr>
                                <th> Mobile </th>
                                <td> @{{ customerInfo.mobile }} </td>
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
