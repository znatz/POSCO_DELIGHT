@section('navbar')
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Handy POS サーバー</a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                    <li><a href="{{URL::to('/')}}">ホーム</a></li>
                    <li><a href="{{URL::route('dataFromIOs.index')}}">最新注文</a></li>
                    <li><a href="#">テスト</a></li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">マスタ管理<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li role="separator" class="divider"></li>
                            <li><a href="{{URL::route('employees.index')}}">担当・部門・店舗</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="{{URL::route('items.index')}}">商品</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="{{URL::route('settings.index')}}">全レジ初期設定</a></li>
                            <li role="separator" class="divider"></li>
                        </ul>
                    </li>

                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="{{URL::route('signup')}}"><span class="glyphicon glyphicon-user"></span>管理者追加</a></li>
                    <li><a href="{{URL::route('logout')}}"><span class="glyphicon glyphicon-log-out"></span>ログアウト</a></li>
                </ul>
            </div>
        </div>
    </nav>
    @show