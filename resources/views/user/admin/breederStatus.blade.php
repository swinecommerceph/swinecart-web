@extends('layouts.controlLayout')

@section('title')
    | Administrator Breeder Status
@endsection

@section('pageId')
    id="admin-breederstatus-dashboard"
@endsection

@section('nav-title')
    Breeder Status
@endsection

@section('pageControl')

@endsection

@section('content')
    <table class="highlight responsive-table bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Review Count</th>
                <th>Delivery Rating</th>
                <th>Transaction Rating</th>
                <th>Product Quality</th>
                <th>Overall Rating</th>
                <th>Last Accreditation</th>
                <th>Notification Date</th>
                <th>Edit</th>
            </tr>
        </thead>

        <tbody>

            @forelse ($breeders as $breeder)
                <tr>
                    <td>{{$breeder->name}}</td>
                    <td>{{$breeder->review_count}}</td>
                    <td>{{$breeder->delivery}}</td>
                    <td>{{$breeder->transaction}}</td>
                    <td>{{$breeder->quality}}</td>
                    <td>{{$breeder->overall}}</td>
                    <td>{{$breeder->latest_accreditation}}</td>
                    <td>{{$breeder->notification_date}}</td>

                    <td><a class="waves-effect waves-light btn" href="{{ URL::to('admin/home/edit_accreditation/'.$breeder->userable_id ) }}"><i class="material-icons center">settings</i></a></td>
                </tr>
            @empty
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="center-align">No Data To Display</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination center"> {{ $breeders->appends(Request::except('page'))->links() }} </div>

    {{-- <div id="breederstatusmodal" class="modal">
        <div class="modal-content">
            <h4>Edit Breeder Accreditation Status</h4>
            {!!Form::open(['route'=>'admin.editaccreditation', 'method'=>'POST', 'class'=>'row valign-wrapper'])!!}
                <div class="input-field col s12 m12 l12 xl12">
                    <input id="accreditation-number" type="text" class="validate" required="required">
                    <label for="accreditation">Accreditation Number</label>

                </div>
                <div class="input-field col s12 m12 l12 xl12">
                    <input id="newaccreditation" type="date" class="datepicker">
                    <label for="newaccreditation">Accreditation Date</label>
                </div>
                <div class="input-field col s12 m12 l12 xl12">
                    <input id="notificationdate" type="date" class="datepicker">
                    <label for="notificationdate">Accreditation Date</label>
                </div>
            {!!Form::close()!!}
        </div>
            <div class="modal-footer">
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Agree</a>
        </div>
    </div> --}}


@endsection

@section('initScript')

@endsection
