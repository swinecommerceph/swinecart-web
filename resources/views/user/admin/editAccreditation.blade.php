@extends('layouts.controlLayout')

@section('title')
    | Administrator Edit Breeder Accreditaion
@endsection

@section('pageId')
    id="admin-breederaccreditation-dashboard"
@endsection

@section('nav-title')
    Edit Breeder Accreditation
@endsection

@section('pageControl')
    <h5>{{$breeder->name}}</h5>
@endsection

@section('content')
    <div class="row">
        <div class="col s12 m12 l12 xl12">
            {!!Form::open(['route'=>'admin.editaccreditationaction', 'method'=>'POST'])!!}
                <input type="hidden" name="name" value="{{$breeder->name}}">
                <input type="hidden" name="breeder_id" value="{{$breeder->userable_id}}">
                <div class="row">
                    <div class="input-field col s12 m12 l12 xl12">
                        <select name="farmid">
                            <option value="" disabled selected>Choose farm</option>
                            @foreach ($farms as $farm)
                                <option value="{{$farm->id}}">{{$farm->name}}</option>
                            @endforeach
                        </select>
                        <label>Farm</label>
                    </div>

                </div>
                <div class="row">
                    <div class="input-field col s12 m12 l12 xl12">
                        <input id="accreditation-number" type="text" class="validate" required="required" name="accreditnumber">
                        <label for="accreditation">Accreditation Number</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m12 l12 xl12">
                        <input id="newaccreditation" type="date" class="datepicker" name="accreditdate">
                        <label for="newaccreditation">Accreditation Date</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m12 l12 xl12">
                        <input id="notificationdate" type="date" class="datepicker" name="notifdate">
                        <label for="notificationdate">Accreditation Expiration Notification Date</label>
                    </div>
                </div>
                <div class="center">
                    <button class="btn waves-effect waves-light" type="submit" name="action">Submit
                        <i class="material-icons right">send</i>
                    </button>
                </div>
            {!!Form::close()!!}
        </div>
    </div>
@endsection

@section('initScript')

@endsection
