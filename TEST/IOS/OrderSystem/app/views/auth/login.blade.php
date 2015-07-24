@extends('layout.prelogin')
@if(!Sentry::check())
@section('contents')
    {{Form::open(['route'=>'login', 'method'=>'post'])}}
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
    <div class="row form-group">
        <div class="col-md-4">
        </div>
        <div class="col-md-4">
            {{Form::submit('ログイン', ['name' => 'submit', 'class'=>'form-control btn-primary btn-md btn-block'])}}
        </div>
    </div>
    {{Form::close()}}
@stop
@else
@section('contents')
    <h3><span class="label label-success">すでにログインしています。</span></h3>
@stop
@endif
