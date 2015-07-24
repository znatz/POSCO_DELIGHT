<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="img/tab_icon.png">
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    {{HTML::style(asset('css/bootstrap.css'))}}
{{--    {{HTML::style(asset('css/bootstrap-table.css'))}}--}}
    {{HTML::script(asset('js/bootstrap.js'))}}
{{--    {{HTML::script(asset('js/bootstrap-table.js'))}}--}}
{{--    {{HTML::script(asset('js/locale/bootstrap-table-ja-JP.js'))}}--}}
    <title>HandyPOSサーバー</title>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            $("input[type='radio']").change(function(){
                $(this).attr('checked', 'checked');
                  $('#item_form').submit();
            });
            $('.pop').on('click', function() {
                $('.imagepreview').attr('src', $(this).children().attr('src'));
                $('#imagemodal').modal('show');
            });
        });
    </script>
</head>
<body>
<div class="container">
    @include('layout.navbar')
    <div class="row well well-lg">
        @section('lightbox')
        <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <img src="" class="imagepreview" style="width: 100%;" >
                    </div>
                </div>
            </div>
        </div>
        @show
       <div class="col-md-4">
            @yield('contents4')
        </div>
        <div class="col-md-8">
            @yield('contents5')
        </div>
    </div>
</div>
</body>
</html>