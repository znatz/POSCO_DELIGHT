<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="3"/>
    <link rel="shortcut icon" href="img/tab_icon.png">
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            setInterval("my_function();", 3000);
            function my_function() {
                $('.reloadpart').load(window.location + ".reloadpart");
            }
        });
    </script>
    {{HTML::style(asset('css/bootstrap.min.css'))}}
    {{HTML::script(asset('js/bootstrap.min.js'))}}
    <title>HandyPOSサーバー</title>
</head>
<body>
<div class="container">
    @include('layout.navbar')
    <div class="reloadpart">
        @include('message.form')
        @yield('contents')
        @yield('contents2')
    </div>
</div>
</body>
</html>