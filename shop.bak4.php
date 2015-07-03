<?php
require_once 'password.php';
require_once 'connect.php';
require_once 'shop_class.php';
require_once 'staff_class.php';
require_once 'html_parts_generator.php';
require_once 'helper.php';
require_once 'menu_class.php';

echo var_dump(get_include_path());
echo var_dump(get_included_files());

session_start();
session_check();
global $errorMessage;
global $successMessage;
$errorMessage = "";
$successMessage = "";
$targetShop = Shop::get_one_empty_shop();;

// ログイン状態チェック
if (!isset($_SESSION['staff']))
    header("Location: Login.php");

// 今ログインしている担当をセッションから取り出す、オブジェクトに一旦保存
$staff = unserialize($_SESSION["staff"]);

// リストを表示ため、全店舗データを一回取り出す
$contents = Shop::get_all_shop();

// 新規ボタンの処理
if (isset($_POST['newID'])) {
    $targetShop = Shop::get_new_shop();
    unset($_POST['newID']);
}

// リスト内ラジオボタンの処理
if (isset($_POST["targetID"])) {

    $targetShop = Shop::get_one_shop($_POST["targetID"]);
    $post = $targetShop->chrPost;
    $address = $targetShop->chrAddress;

    // 選択を解除
    unset($_POST["target"]);
    $contents = Shop::get_all_shop();
}

// 削除ボタン処理
if (isset($_POST['delete'])) {
    if (Shop::delete_one_shop($_POST['delete'])) {
        $contents = Shop::get_all_shop();
        $successMessage = "ユーザが削除しました。";
        unset($_POST['delete']);
    } else {
        $errorMessage = "削除失敗しました。";
    }
}

// 　登録処理
if (isset($_POST["submit"])) {
    if (Shop::insert_one_shop($_POST['chrID'],
        $_POST['chrName'],
        $_POST['chrPost'],
        $_POST['chrAddress'],
        $_POST['chrAddressNo'],
        $_POST['chrTel'],
        $_POST['chrFax'],
        $_POST['intDisplayOrder'])
    ) {
        $successMessage = "ユーザが追加しました。";
    } else {
        // 更新処理開始
        if (mysql_errno() == 1062) {
            if (Shop::update_one_shop($_POST['chrID'],
                $_POST['chrName'],
                $_POST['chrPost'],
                $_POST['chrAddress'],
                $_POST['chrAddressNo'],
                $_POST['chrTel'],
                $_POST['chrFax'],
                $_POST['intDisplayOrder'])
            ) {
                $successMessage = "記録すでに存在したため、更新しました。";
            };
        }
    }

    // 再度リストを更新
    $contents = Shop::get_all_shop();
    $_POST["targetID"] = $chrID;
}

$contents = Shop::get_all_shop();

// 検索ボタンの処理
if (isset($_POST['SearchPost'])) {
    $targetShop = new Shop($_POST['chrID'],
        $_POST['chrName'],
        $_POST['chrPost'],
        $_POST['chrAddress'],
        $_POST['chrAddressNo'],
        $_POST['chrTel'],
        $_POST['chrFax'],
        $_POST['intDisplayOrder']);
    $post = $_POST["chrPost"];
    $connection = new Connection();
    $query = "select * from post Where chrID='" . $post . "';";
    $result = $connection->result($query) or die("SQL Error 1: " . mysql_error());
    $rowCount = mysql_num_rows($result);
    if ($rowCount > 0) {
        $row = mysql_fetch_array($result, MYSQL_ASSOC);
        $address = $row['chrPrefecture'] . $row['chrAddress'];
    } else {
    }
    $connection->close();
}

?>

<!DOCTYPE html>
<head>
    <?php include('./html_parts/css_and_js.html'); ?>

    <script type="text/javascript">
        jQuery(document).ready(function () {

            $("#search").on("keyup", function () {
                var keyword = $("#search").val();
                alert(keyword);
                $(".tbl_input").each(function (index) {
                    if (index != 0) {
                        var cont = $.trim($(this).text());
                        var find = Math.ceil(index/9);
                        if (cont.toString().indexOf(keyword) != -1) {
                            console.log("---index:"+index);
                            console.log("find:"+find);
                            $(".tbl_li").eq(find).css("color", "red");
                            find = -1;
                        } else {
                            console.log("NOT index:"+index);
                            console.log("NOT find:"+find);
                             $(".tbl_li").eq(find).css("color", "black");
                            find = -1;
                        }
                    }
                });
            });

            $('#myTable').DataTable({
                "language": {
                    "sProcessing": "処理中...",
                    "sLengthMenu": "_MENU_ 件表示",
                    "sZeroRecords": "データはありません。",
                    "sInfo": " _TOTAL_ 件中 _START_ から _END_ まで表示",
                    "sInfoEmpty": " 0 件中 0 から 0 まで表示",
                    "sInfoFiltered": "（全 _MAX_ 件より抽出）",
                    "sInfoPostFix": "",
                    "sSearch": "検索:",
                    "sUrl": "",
                    "oPaginate": {
                        "sFirst": "先頭",
                        "sPrevious": "前",
                        "sNext": "次",
                        "sLast": "最終"
                    }
                },
                "aoColumnDefs": [
                    {"bSortable": false, "aTargets": [8, 9]}
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

            $("#jMenu").jMenu({
                ulWidth: 'auto',
                effects: {
                    effectSpeedOpen: 300,
                    effectTypeClose: 'slide'
                },
                animatedText: false,
                openClick: true
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

        input[type="text"], input[type="password"], select, button {
            padding: 0 0 0 5px;
            font-size: 14px;
        }

        input[disable="disable"], button {
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

        #buttonlist {
            margin: 10px 0;
        }

        p.list {
            width: 700px;
            height: 37px;
            color: #000000;
        }

        p.list input[type="text"], input[type="password"], select, button {
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

        /* 一覧表 */
        .tbl_container {
            margin: 0 auto;
            width: 1036px;
        }

        .tbl_ul li:nth-child(odd) .tbl_row {
            background: #cdffb9;
        }

        .tbl_ul li:nth-child(even) .tbl_row {
            background: #ffffff;
        }

        .tbl_row_container {
            border-top: 2px solid black;
            border-radius: 5px;
            vertical-align: middle;
        }

        .tbl_row div {
            display: inline-block;
            height: 30px;
            border-left: 1px solid black;
            padding: 2px;
            margin: 0;
            vertical-align: middle;
        }

        .tbl_row:first-child div:first-child {
            border-top-left-radius: 5px;
        }

        .tbl_row:last-child, .tbl_row:first-child {
            border-top: 1px solid black
        }

        .tbl_row div:last-child {
            border-right: 1px solid black
        }

        .tbl_row:first-child div:last-child {
            border-bottom: 1px solid black;
            border-top-right-radius: 5px;
        }

        .tbl_row:last-child div:last-child {
            text-align: center;
            vertical-align: middle;
            -moz-border-radius-bottomright: 5px;
        }

        .tbl_row:last-child div:first-child {
            -moz-border-radius-bottomleft: 5px;
        }

        .tbl_row input, .tbl_row button {
            margin: 0;
            height: 30px;
            padding: 0;
        }

        .tbl_row button {
            width: 100px;
        }

        .tbl_tag {
            display: inline-block;
            text-align: center;
            width: 100px;
            height: 30px;
            margin: 0;
            line-height: 30px;
            background: #394959;
            color: white;
            letter-spacing: 5px;
            -webkit-font-smoothing: subpixel-antialiased;
            -moz-osx-font-smoothing: grayscale;
            font-family: "Arial";
        }

        .tbl_input {
            height: 30px;
            line-height: 30px;
            display: inline-block;
            vertical-align: middle;
            padding-left: 5px;
            text-align: center;
        }

        /* 一覧表　終了　*/
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

            <!-- ********************* フォームの作成 開始　**********************	-->
            <div style="clear: both; float: top;">

                <form class="search_form" style="margin: 0 auto; width: 700px;"
                      method="post" id="user_add_form" action="">
                    <fieldset>
                        <legend>店舗マスタ</legend>
                    </fieldset>
                    <p class="list">
                        <label class="list">コード</label> <input
                            class="validate[custom[integer], custom[required_2_digits],custom[onlyLetterNumber]] text-input"
                            data-prompt-position="topLeft:140"
                            style="width: 290px; margin: 0 0 0 18px;" name="chrID"
                            type="text" size="10" placeholder="00"
                            value='<?php
                            echo $targetShop->chrID;
                            ?>'/> <input class="newID hvr-fade"
                                         style="width: 100px; height: 37px; margin: 0;" type="submit"
                                         name="newID" id="newID" size="10" value="新規"/>
                        <a class="center_button hvr-fade" style="margin:0 auto;float:right;width:10px;" id="right-menu"
                           href="#sidr">入力説明表示／非表示 <i class="fa fa-info-circle"></i></a>
                    </p>

                    <p class="list">
                        <label class="list">店舗名</label><input
                            style="width: 390px; margin: 0 0 0 18px;" type="text" size="10"
                            class="validate[required,maxSize[20]] text-input"
                            data-prompt-position="topLeft:140" name="chrName"
                            value="<?php
                            echo $targetShop->chrName;
                            ?>"/>
                    </p>

                    <p class="list">
                        <label class="list">郵便番号</label><input
                            style="width: 290px; margin: 0 0 0 18px;" type="text" size="10" placeholder="000-0000"
                            class="validate[maxSize[8]] text-input"
                            data-prompt-position="topLeft:140" name="chrPost"
                            value="<?php
                            echo $post;
                            ?>"/>
                        <input class="newID hvr-fade"
                               style="width: 100px; height: 37px; margin: 0;" type="submit"
                               name="SearchPost" id="SearchPost" size="10" value="検索"/>
                    </p>

                    <p class="list">
                        <label class="list">住所</label><input
                            style="width: 390px; margin: 0 0 0 18px;" type="text" size="10"
                            class="validate[maxSize[50]] text-input"
                            data-prompt-position="topLeft:140" name="chrAddress"
                            value="<?php
                            echo $address;
                            ?>"/>
                    </p>

                    <p class="list">
                        <label class="list">番地</label><input
                            style="width: 390px; margin: 0 0 0 18px;" type="text" size="10"
                            class="validate[maxSize[50]] text-input"
                            data-prompt-position="topLeft:140" name="chrAddressNo"
                            value="<?php
                            echo $targetShop->chrAddressNo;
                            ?>"/>
                    </p>

                    <p class="list">
                        <label class="list">電話番号</label><input
                            style="width: 390px; margin: 0 0 0 18px;" type="text" size="10"
                            class="validate[maxSize[13]] text-input"
                            data-prompt-position="topLeft:140" name="chrTel"
                            value="<?php
                            echo $targetShop->chrTel;
                            ?>"/>
                    </p>

                    <p class="list">
                        <label class="list">FAX番号</label><input
                            style="width: 390px; margin: 0 0 0 18px;" type="text" size="10"
                            class="validate[maxSize[13]] text-input"
                            data-prompt-position="topLeft:140" name="chrFax"
                            value="<?php
                            echo $targetShop->chrFax;
                            ?>"/>
                    </p>

                    <p class="list">
                        <label class="list">表示順</label> <input
                            style="width: 100px; margin: 0 0 0 18px;" type="text"
                            size="10"
                            class="chrCategory_ID validate[onlyNumberSp,maxSize[2]]"
                            data-prompt-position="topLeft:140" name="intDisplayOrder"
                            value="<?php
                            echo $targetShop->intDisplayOrder;
                            ?>"/>
                    </p>

                    <p style="float: left; text-align: center; width: 300px;"
                       id="buttonlist">
                        <input class="center_button hvr-fade" type="submit" name="submit"
                               size="10" value="登録"/>
                        <input class="center_button hvr-fade"
                               type="reset" size="10" value="クリア"/><a class="center_button hvr-fade" href="./index.php"
                                                                      style="display: block; float:left;text-decoration: none; width: 88px; margin: 30px 5px; font-size: 14px; padding: 12px 0 12px 0;">戻る</a>
                    </p>

                    <div
                        style="float: right; width: -100%; height: 100px; margin: 10px 0; text-align: center; vertical-align: middle;">

                        <a class="center_button hvr-fade" href="../utils/excel_export.php"
                           style="display: block; text-decoration: none; width: 150px; margin: 30px 5px; font-size: 14px; padding: 12px 0 12px 0;">EXCELへ出力&nbsp;<i
                                class="fa fa-file-text-o"></i>&nbsp;</a>
                        <a class="center_button hvr-fade" href="../utils/csv_export.php"
                           style="display: block; text-decoration: none; width: 130px; margin: 30px 5px; font-size: 14px; padding: 12px 0 12px 0;">CSVへ出力&nbsp;<i
                                class="fa fa-file-text-o"></i>&nbsp;</a>
                    </div>
                </form>
            </div>
            <form action=""></form>
        </div>
        <!-- ********************* フォームの作成 終了　**********************	-->


        <!-- ********************* リストの作成 開始　**********************	-->
        <div class="tbl_container" style="clear:both;float:left;">
            <form>検索：<input id="search" type="text" size="10"/></form>
            <form method="post" id="list" action="">
                <ul class="tbl_ul">
                    <? foreach ((array)$contents as $row) : ?>
                        <li class="tbl_li">
                            <div class="tbl_row_container">
                                <div class="tbl_row">
                                    <div class="tbl_col" style="width:200px;"><label class="tbl_tag">コード:</label><label
                                            class="tbl_input"><? echo $row->chrID; ?></label></div>
                                    <div class="tbl_col" style="width:610px;"><label class="tbl_tag">郵便:</label><label
                                                    class="tbl_input"><? echo $row->chrPost; ?></label></div>
                                    <div class="tbl_col" style="width:200px;"><label class="tbl_tag">表示順:</label><label
                                            class="tbl_input"><? echo $row->intDisplayOrder; ?></label></div>
                                </div>
                                <div class="tbl_row">
                                    <div class="tbl_col" style="width:200px;"><label class="tbl_tag">店舗名:</label><label
                                            class="tbl_input"><? echo $row->chrName; ?></label></div>
                                    <div class="tbl_col" style="width:820px;"><label class="tbl_tag">住所:</label><label
                                            class="tbl_input"
                                            style="font-size: <? $n = 2000 / mb_strlen($row->chrAddress);
                                            if ($n < 14) echo $n; ?>px"><? echo $row->chrAddress; ?></label></div>
                                </div>
                                <div class="tbl_row">
                                    <div class="tbl_col" style="width:300px;"><label class="tbl_tag"
                                                                                     style="width:120px;">電話番号:</label><label
                                            class="tbl_input"><? echo $row->chrTel; ?></label></div>
                                    <div class="tbl_col" style="width:300px;"><label class="tbl_tag">FAX:</label><label
                                            class="tbl_input"><? echo $row->chrFax; ?></label></div>
                                    <div class="tbl_col" style="width:200px;"><label class="tbl_tag">選択:</label><label
                                            class="tbl_input"><input type="radio" onclick="javascript: submit()"
                                                                     name="targetID" id="targetID"
                                                                     value="<? echo $row->chrID; ?>"/></label></div>
                                    <div class="tbl_col" style="width:200px;"><label class="tbl_input">
                                            <button class="center_button hvr-fade delete_button" type="submit"
                                                    name="delete" value="<? echo $row->chrID; ?>">削除
                                            </button>
                                        </label></div>
                                </div>
                            </div>
                        </li>
                    <? endforeach; ?>
                </ul>
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
    $query = 'SELECT txtInstruction FROM form_helper WHERE chrPageName="Shop";';
    $result = $connection->result($query);
    $row = mysql_fetch_array($result, MYSQL_ASSOC);
    echo $row['txtInstruction'];
    ?>
</div>
<!-- ********************  入力規則　終了      *********************** -->
</body>
</html>