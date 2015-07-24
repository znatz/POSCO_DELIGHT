@extends('layout.prelogin')
@section('contents')
    {{Form::open(['route'=>'signup', 'method'=>'post'])}}
    <div class="row form-group">
        <div class="col-md-4 text-right">
            {{Form::label('email','Eメール',['class'=>'control-label'])}}
        </div>
        <div class="col-md-4">
            {{Form::text('email', '', ['class' => 'form-control col-md-4'])}}
        </div>
    </div>
    <div class="row form-group">
        <div class="col-md-4 text-right">
            {{Form::label('password','パスワード',['class'=>'control-label'])}}
        </div>
        <div class="col-md-4">
            {{Form::password('password', ['class'=>'form-control'])}}
        </div>
    </div>
    <div class="row form-group text-center">
        <div class="col-md-4">
        </div>
        <div class="col-md-2">
            {{Form::submit('登録', ['name' => 'submit', 'class'=>'form-control btn-primary btn-md btn-block'])}}
        </div>
    </div>
    {{Form::close()}}
    @include('message.form')
@stop
