@extends('layouts.controlLayout')

@section('title')
    | Add Farm
@endsection

@section('pageId')
    id="admin-add-farm"
@endsection

@section('nav-title')
    Add Farm
@endsection

@section('pageControl')
    <div class="row">
        <div class="col s12 m12 l12 xl12">
            <h4>{{$breeder->name}}</h4>
        </div>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col s12 m12 l12 xl12">
            {!!Form::open(['route'=>'breeder.save_farm', 'method'=>'POST'])!!}
            <input type="hidden" name="id" value="{{ $breeder->id }}">
            <div class="row">
                <div class=" input-field col s6 m6 l6 xl6 offset-s2 offset-m2 offset-l2 offset-xl2">
                    <input id="accreditation_number" type="text" class="validate" name="accreditation_num">
                    <label for="accreditation_number">Accreditation Number</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s6 m6 l6 xl6 offset-s2 offset-m2 offset-l2 offset-xl2">
                    <input id="farm_name" type="text" class="validate" name="farm_name">
                    <label for="farm_name">Farm Name</label>
                </div>
            </div>

            <div class="row">
                <div class="col s12 m12 l12 xl12 offset-s4 offset-m4 offset-l4 offset-xl4">
                    <button class="btn waves-effect waves-light" type="submit" name="action">Submit
                        <i class="material-icons right">send</i>
                    </button>
                </div>
            </div>
            {!!Form::close()!!}
        </div>
    </div>

@endsection

@section('initScript')
    <script type="text/javascript" src="/js/admin/users.js"></script>
    <script type="text/javascript" src="/js/admin/userPages_script.js"></script>
    @if(Session::has('alert-farm-add'))
        <script type="text/javascript">
             Materialize.toast('Farm successfully added to breeder', 4000)
        </script>
    @endif
@endsection
