@extends('hello')

@section('content')

    <div class="row">
        <div class="col-xs-12 col-sm-8 col-md-4 col-sm-offset-2 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">ログインしてください
                        <small>znatz</small>
                    </h3>
                </div>
                <div class="panel-body">
                    <form role="form">
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <input type="text" name="first_name" class="form-control input-sm"
                                           placeholder="ログインID">
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <input type="text" name="text" class="form-control input-sm"
                                           placeholder="名前">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <input type="email" name="email" class="form-control input-sm" placeholder="企業名">
                        </div>

                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <input type="password" name="password" class="form-control input-sm"
                                           placeholder="パスワード">
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <input type="password" name="password_confirmation" class="form-control input-sm"
                                           placeholder="パスワード確認">
                                </div>
                            </div>
                        </div>

                        <input type="submit" value="ログイン" class="btn btn-info btn-block">

                    </form>
                </div>
            </div>
        </div>
    </div>

@stop