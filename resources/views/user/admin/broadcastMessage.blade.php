@extends('layouts.controlLayout')

@section('resources')

@endsection

@section('title')
    | Broadcast Message
@endsection

@section('pageId')
    id="admin-broadcast-message"
@endsection

@section('nav-title')
    Broadcast Message
@endsection

@section('pageControl')

@endsection

@section('content')

    {!!Form::open(['route'=>'admin.broadcast.send', 'method'=>'POST', 'class'=>'row', 'files' => true])!!}
        <div class="col s12 m12 l12 xl12">
            <div class="row">
                <div class="col s12 m12 l12 xl12">
                    <div class="input-field col s12 m12 l12 xl12">
                        <select name="sendto">
                            <option select="selected" value=0>All Users</option>
                            <option value=1>All Breeders</option>
                            <option value=2>All Customers</option>
                        </select>
                        <label>Send to</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12 l12 xl12">
                    <textarea id="announcement" name="announcement"></textarea>
                    {{-- <input id="image" type="file" name="image" style="display: none;" onchange="" /> --}}
                </div>
            </div>
            <div class="row">
                {{-- <div class="col s12 m12 l12 xl12">
                    <div class="file-field input-field">
                        <div class="btn">
                            <span><i class="material-icons center">attachment</i></span>
                            <input type="file" multiple>
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text" name="attachment" placeholder="Add Attachments">
                        </div>
                    </div>
                </div> --}}
                {!! Form::file('attachment[]', array('multiple'=>true)) !!}
            </div>
            <div class="row right">
                <div class="col s12 m12 l12 xl12">
                    <button class="btn waves-effect waves-light" type="submit" name="action">Send
                    <i class="material-icons right">mail</i>
                    </button>
                </div>
            </div>
        </div>

    {!!Form::close()!!}
@endsection

@section('initScript')
    <script src="/js/vendor/tinymce/js/tinymce/tinymce.min.js"></script>
    <script src="/js/admin/broadcastMessage_script.js"></script>
@endsection
