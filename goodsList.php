<?php
require_once './utils/password.php';
require_once './utils/connect.php';
require_once './mapping/goods_class.php';
require_once './mapping/staff_class.php';
require_once './utils/html_parts_generator.php';
require_once './utils/helper.php';
require_once './mapping/menu_class.php';


session_start();
session_check();
global $errorMessage;
global $successMessage;
$errorMessage = "";
$successMessage = "";
$targetGoods = Goods::get_one_empty_goods();;

// ログイン状態チェック
if (!isset($_SESSION['staff']))
    header("Location: Login.php");

// 今ログインしている担当をセッションから取り出す、オブジェクトに一旦保存
$staff = unserialize($_SESSION["staff"]);

// リストを表示ため、全商品データを一回取り出す
$contents = Goods::get_all_goods();

// 商品区分のIDを取り出す
$classids = Goods::get_distinct_class_chrID();


$contents = Goods::get_all_goods();
?>

<!DOCTYPE html>
<head>
    <?php include('./html_parts/css_and_js.html'); ?>

    <script type="text/javascript">
        jQuery(document).ready(function () {

            $('#ref_seller').focusout(function () {
                queryParam = $(this).val();
                phpCode = 'require_once dirname(__FILE__)."/../mapping/seller_class.php";echo Seller::search_chrID_chrName_by_word('+
                    queryParam+
                ');';
                $.post('utils/show_list.php', {"phpCode":phpCode},function (data) {
                    console.log(data);
                    $("#sidr-right-seller").text(data);
                })
            });

            $("tr").dblclick(function(){
                var chrID = $(this).attr("id");
                console.log(chrID);
                $("input[name=targetID][value=" + chrID + "]").attr('checked', 'checked');
                $("#list").submit();
            });

//            echo '<td style="width:52px;text-align:center;"><input type="radio" onclick="javascript: submit()" name="targetID" id="targetID" value="' . $row->chrID . '"/></td>';

            $('#myTable').DataTable({
                "language": {
                    "sProcessing": "処理中...",
                    "sLengthMenu": "_MENU_ 件表示",
                    "sZeroRecords": "データはありません。",
                    "sInfo": " _TOTAL_ 件中 _START_ から _END_ まで表示",
                    "sInfoEmpty": " 0 件中 0 から 0 まで表示",
                    "sInfoFiltered": "（全 _MAX_ 件より抽出）",
                    "sInfoPostFix": "",
                    "sSearch": "キーワード検索:",
                    "sUrl": "",
                    "oPaginate": {
                        "sFirst": "先頭",
                        "sPrevious": "前",
                        "sNext": "次",
                        "sLast": "最終"
                    }
                },
                "aoColumnDefs": [
                    {"bSortable": false, "aTargets": [5, 6]}
                ]
            });


            $('#main-menu').smartmenus();
            jQuery("#user_add_form").validationEngine();
            $("#user_add_form").bind("jqv.field.result", function (event, field, errorFound, prompText) {
                console.log(errorFound)
            });

            $("#newID").click(function () {
                $('#user_add_form').validationEngine('hideAll');
                $('#user_add_form').validationEngine('detach');
                return true;
            });
            $('#right-menu').sidr({
                name: 'sidr-right',
                side: 'right'
            });
            $('#right-menu-seller').sidr({
                name: 'sidr-right-seller',
                side: 'right'
            });


        });
    </script>
    <title>POSCO</title>
    <meta name="description" content="POSCO">
    <style type="text/css">
        * {
            font-family: Verdana;
        }

        input {
            border: 1px solid #000000;
        }

        input[type="text"], input[type="password"], select {
            padding: 0 0 0 5px;
            font-size: 14px;
        }

        input[disable="disable"] {
            font-size: 14px;
            padding: 0 0 0 5px;
        }

        select {
            float: left;
            border: 1px solid #555555;
            margin: 0 0 0px 18px;
            width: 199px;
            font-size: 14px;
            background: #faffbd;
        }

        #user_list {
            clear: both;
            overflow: auto !important;
        }

        #buttonlist {
            margin: 10px 0;
        }

        p.list {
            width: 700px;
            height: 37px;
            color: #000000;
        }

        p.list input[type="text"], input[type="password"], select {
            float: left;
            height: 35px;
            border: 1px solid #555555;
            background: #faffbd;
            transition: border 0.3s;
        }

        p.list input[type="text"]:focus, input[type="password"]:focus, select:focus {
            background: #ffffff;
            border-bottom: solid 1px #FDAB07;
        }

        label.list {
            display: block;
            float: left;
            margin: 10px 0 5px 0;
            height: 20px;
            width: 150px;
            text-align: right;
            font-size: 14px;
        }

        button {
            background-image: -webkit-gradient(linear, left top, left bottom, from(#FFFFFF), to(#c2c2c2));
            background-image: -webkit-linear-gradient(top, #FFFFFF, #c2c2c2);
            background-image: -moz-linear-gradient(top, #FFFFFF, #c2c2c2);
            background-image: -ms-linear-gradient(top, #FFFFFF, #c2c2c2);
            background-image: -o-linear-gradient(top, #FFFFFF, #c2c2c2);
            background-image: linear-gradient(to bottom, #FFFFFF, #c2c2c2);
            filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0, startColorstr=#FFFFFF, endColorstr=#c2c2c2);
        }


    </style>
</head>
<body>
<div class="blended_grid">
    <div class="pageHeader">
        <?php include('./html_parts/header.html'); ?>
    </div>
    <div class="pageContent">
        <?php include('./html_parts/top_menu.html'); ?>
        <div class="main">
            <?php include('./html_parts/warning.html'); ?>

            <!-- ********************* マスタ一覧の作成 開始　**********************	-->
            <div style="clear: both; float: top;">

                <form class="search_form" style="margin: 0 auto; width: 1000px;"
                      method="post" id="user_add_form" action="">
                    <fieldset>
                        <legend>商品マスタ一覧</legend>
                    </fieldset>
                    <p class="list">
                        <label class="list">仕入先</label>
                        <input
                            tabindex="1"
                            class="chrID validate[custom[integer],custom[required_2_digits] text-input"
                            data-prompt-position="topLeft:140"
                            style="width: 290px; margin: 0 0 0 18px;" name="chrSeller_ID"
                            type="text" size="10" placeholder="00"
                            value='<?php
                            echo $targetGroup->chrSeller_ID;
                            ?>'/>
                    </p>

                    <p class="list">
                        <label class="list">メーカー</label>
                        <input
                            tabindex="2"
                            style="width: 290px; margin: 0 0 0 18px;" type="text" size="10"
                            class="validate[required,maxSize[20]] text-input"
                            data-prompt-position="topLeft:140" name="chrMaker_ID"
                            value="<?php
                            echo $targetGroup->chrMaker_ID;
                            ?>"/>
                    </p>

                    <p class="list">
                        <label class="list">部門</label>
                        <input
                            tabindex="3"
                            style="width: 290px; margin: 0 0 0 18px;" type="text" size="10"
                            class="validate[required,maxSize[20]] text-input"
                            data-prompt-position="topLeft:140" name="chrGroup_ID"
                            value="<?php
                            echo $targetGroup->chrGroup_ID;
                            ?>"/>
                    </p>

                    <p class="list">
                        <label class="list">商品区分</label>
                        <input
                            tabindex="4"
                            style="width: 290px; margin: 0 0 0 18px;" type="text" size="10"
                            class="validate[required,maxSize[20]] text-input"
                            data-prompt-position="topLeft:140" name="chrItem_ID"
                            value="<?php
                            echo $targetGroup->chrItem_ID;
                            ?>"/>
                    </p>

                    <p style="float: left; text-align: center; width: 300px;"
                       id="buttonlist">
                        <input
                            tabindex="5"
                            class="center_button hvr-fade" type="submit" name="submit"
                               size="10" value="検索"/>
                        <a
                            tabindex="6"
                            class="center_button hvr-fade" href="./group.php"
                            style="display: block; float:left;text-decoration: none; width: 88px; margin: 30px 5px; font-size: 14px; padding: 12px 0 12px 0;">クリア</a>
                    </p>

                </form>
            </div>
            <form action=""></form>
        </div>
        <!-- ********************* マスタの作成 終了　**********************	-->

        <!-- ********************* リストの作成 開始　**********************	-->
        <div id="user_list" style="overflow:auto !important;">
            <form method="post" id="list" action="">
                <?php
                $header = [
                    "コード" => 50,
                    "品番" => 80,
                    "商品名" => 350,
                    "仕入先" => 50,
                    "メーカー" => 50,
                    "部門" => 50,
                    "品種" => 50,
                    "カラー" => 100,
                    "サイズ" => 100,
                    "原価" => 50,
                    "売価" => 50,
                    "備考1" => 150,
                    "備考2" => 150,
                ];

                echo '<table id="myTable" style="width:1500px;border:0; padding:0;border-radius:5px;" class="search_table tablesorter">';
                echo '<thead><tr>';
                foreach ($header as $name => $width)
                    echo '<th width="' . $width . '">' . $name . '</th>';
                echo '</tr></thead><tbody>';


                foreach ((array)$contents as $row) {
                    echo '<tr class="not_header" id="'.$row->chrID.'">';
                    echo '<td  style="text-align:center;">' . $row->chrID . '</td>';
                    echo '<td  style="text-align:left;">' . $row->chrCode . '</td>';
                    echo '<td  style="text-align:left;">' . $row->chrName . '</td>';
                    echo '<td  style="text-align:center;">' . $row->chrSeller_ID . '</td>';
                    echo '<td  style="text-align:center;">' . $row->chrMaker_ID . '</td>';
                    echo '<td  style="text-align:center;">' . $row->chrGroup_ID . '</td>';
                    echo '<td  style="text-align:center;">' . $row->chrUnit_ID . '</td>';
                    echo '<td  style="text-align:left;">' . $row->chrColor . '</td>';
                    echo '<td  style="text-align:left;">' . $row->chrSize . '</td>';
                    echo '<td  style="text-align:right;">' . $row->intCost . '</td>';
                    echo '<td  style="text-align:right;">' . $row->intPrice . '</td>';
                    echo '<td  style="text-align:left;">' . $row->chrComment1 . '</td>';
                    echo '<td  style="text-align:left;">' . $row->chrComment2 . '</td>';
                    echo '</tr>';
                }
                echo '</tbody></table>';

                $_SESSION["sheet"] = serialize($contents);
                ?>
                <input type="submit" name="target" style="display: none"/>
            </form>
        </div>
    </div>
    <!-- ********************* リストの作成  終了　********************** -->
    <div class="pageFooter">
        <h4 style="color: #ffffff; text-align: center; padding: 4px 0 0 0;">CopyRight
            2015 POSCO Co.Ltd All Rights Reserved</h4>
    </div>
</div>
</div>
<!-- ********************  入力規則　開始      *********************** -->
<div id="sidr-right">
    <?php
    $connection = new Connection();
    $query = 'SELECT txtInstruction FROM form_helper WHERE chrPageName="goods";';
    $result = $connection->result($query);
    $row = mysql_fetch_array($result, MYSQL_ASSOC);
    echo $row['txtInstruction'];
    ?>
</div>
<div id="sidr-right-seller">
</div>
<!-- ********************  入力規則　終了      *********************** -->
</body>
</html>