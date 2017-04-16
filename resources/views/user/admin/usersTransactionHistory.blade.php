@extends('layouts.controlLayout')

@section('title')
    | User Transaction History
@endsection

@section('pageId')
    id="admin-transaction-history"
@endsection

@section('nav-title')
    User Transactions
@endsection

{{-- @section('header')
    <div class="row valign-wrapper">
        <div class="col s12 m12 l5">
            <div class="row">
                <div class="col s12 m12 l12">
                    <h4 id='admin-content-panel-header'>Transaction History</h4>
                </div>
                <div class="col s12 m12 l12 truncate admin-transaction-history-name grey-text push-s1 push-m1 push-l1">
                    {{$username}}
                </div>
            </div>


        </div>
        <div class="col s12 m12 l7 valign">
            <div class="row">
                {!!Form::open(['route'=>'admin.userlist.transactionHistory.search', 'method'=>'GET', 'class'=>'search-user-form col s12 m12 l12'])!!}
                    <div class="input-field col s12 m12 l12">
                        <div class="col s12 m12 l12">
                            <input type="hidden" name="name" value="{{$username}}">
                            <input type="hidden" name="userable" value="{{$userable}}">
                            <input type="hidden" name="role" value="{{$role}}">
                            <input id="search-input" class="validate" type="text" name="search">
                            <label for="search-input">Search</label>
                        </div>
                        <div class="col s12 m12 l12">
                            <select multiple name="option[]">
                                <option disabled selected>Choose category</option>
                                <option value="requested" name="requested">Requested</option>
                                <option value="reserved" name="reserved">Reserved</option>
                                <option value="paid" name="paid">Paid</option>
                                <option value="on_delivery" name="delivery">On Delivery</option>
                                <option value="sold" name="sold">Sold</option>
                            </select>
                        </div>
                        <div class="col s12 m12 l12 center">
                            <button id="search-button" class="btn waves-effect waves-light" type="submit">Search</button>
                        </div>
                    </div>


                {!!Form::close()!!}
            </div>
        </div>
    </div>

@endsection --}}

@section('pageControl')
    <div class="row">
        <div class="col s12 m12 l12 xl12 admin-transaction-history-name">
            <h5>{{$username}}</h5>
        </div>
    </div>
    <div class="divider"></div>
    <div class="row">
        <div class="col s12 m12 l12 xl12">
            {!!Form::open(['route'=>'admin.userlist.transactionHistory.search', 'method'=>'GET', 'class'=>'input-field col s12 m12 l12'])!!}
                {{-- <div class="input-field col s12 m12 l12"> --}}
                    <div class="col s12 m12 l6 xl6">
                        <input type="hidden" name="name" value="{{$username}}">
                        <input type="hidden" name="userable" value="{{$userable}}">
                        <input type="hidden" name="role" value="{{$role}}">
                        <input id="search-input" class="validate" type="text" name="search">
                        <label for="search-input">Search</label>
                    </div>
                    <div class="col s12 m6 l4 xl4">
                        <select multiple name="option[]">
                            <option disabled selected>Choose category</option>
                            <option value="requested" name="requested">Requested</option>
                            <option value="reserved" name="reserved">Reserved</option>
                            <option value="paid" name="paid">Paid</option>
                            <option value="on_delivery" name="delivery">On Delivery</option>
                            <option value="sold" name="sold">Sold</option>
                        </select>
                    </div>
                    <div class="col s12 m6 l2 xl2">
                        <button id="search-button" class="btn waves-effect waves-light" type="submit">Search</button>
                    </div>
                {{-- </div> --}}
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col s12 m12 l12">
            <table class="responsive-table highlight">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product ID</th>
                        <th>Product Name</th>
                        <th>Seller/Customer</th>
                        <th>Status</th>
                        <th>Date Added</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td>{{$transaction->transaction_id}}</td>
                            <td>{{$transaction->product_id}}</td>
                            <td>{{$transaction->product_name}}</td>
                            <td>{{$transaction->dealer_name}}</td>
                            <td>{{ucfirst($transaction->order_status)}}</td>
                            <td>{{$transaction->date}}</td>
                        </tr>
                    @empty
                        <div class="col s12 m12 l12">
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>No Transactions Found</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </div>
                    @endforelse

                </tbody>
            </table>
            <div class="pagination center"> {{ $transactions->appends(Request::except('page'))->links() }} </div>
        </div>
    </div>
@endsection
