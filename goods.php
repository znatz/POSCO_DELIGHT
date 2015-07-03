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

// 新規ボタンの処理
if (isset($_POST['newID'])) {
    $targetGoods = Goods::get_new_goods();
    unset($_POST['newID']);
}

// リスト内ラジオボタンの処理
if (isset($_POST["targetID"])) {

    $targetGoods = Goods::get_one_goods($_POST["targetID"]);

    // 選択を解除
    unset($_POST["target"]);
    $contents = Goods::get_all_goods();
}

// 削除ボタン処理
if (isset($_POST['delete'])) {
        if (Goods::delete_one_goods($_POST['delete'])) {
            $contents = Goods::get_all_goods();
            $successMessage = "ユーザが削除しました。";
        } else {
            $errorMessage = "削除失敗しました。";
        }
}

// 　登録処理
if (isset($_POST["submit"])) {
    if (Goods::insert_one_goods($_POST['chrID'],
        $_POST['chrClass_ID'],
        $_POST['chrCode'],
        $_POST['chrName'],
        $_POST['chrKana'],
        $_POST['chrSeller_ID'],
        $_POST['chrMaker_ID'],
        $_POST['chrGroup_ID'],
        $_POST['chrUnit_ID'],
        $_POST['chrColor'],
        $_POST['chrSize'],
        $_POST['chrComment1'],
        $_POST['chrComment2'],
        $_POST['intCost'],
        $_POST['intPrice'])
    ) {
        $successMessage = "追加しました。";
    } else {
        // 更新処理開始
        if (mysql_errno() == 1062) {
            if (Goods::update_one_goods($_POST['chrID'],
 	        $_POST['chrClass_ID'],
        	$_POST['chrCode'],
        	$_POST['chrName'],
        	$_POST['chrKana'],
        	$_POST['chrSeller_ID'],
        	$_POST['chrMaker_ID'],
        	$_POST['chrGroup_ID'],
        	$_POST['chrUnit_ID'],
        	$_POST['chrColor'],
        	$_POST['chrSize'],
        	$_POST['chrComment1'],
        	$_POST['chrComment2'],
        	$_POST['intCost'],
        	$_POST['intPrice'])
            ) {
                $successMessage = "更新しました。";
            };
        }
    }

    // 再度リストを更新
    $contents = Goods::get_all_goods();
    $_POST["targetID"] = $chrID;
}

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

            <!-- ********************* マスタの作成 開始　**********************	-->
            <div style="clear: both; float: top;">

                <form class="search_form" style="margin: 0 auto; width: 700px;"
                      method="post" id="user_add_form" action="">
                    <fieldset>
                        <legend>商品マスタ</legend>
                    </fieldset>
                    <p class="list">
                        <label class="list">コード</label>
                        <input
                            class="validate[required,onlyNumberSp,maxSize[13]] text-input"
                            data-prompt-position="topLeft:140"
                            style="width: 290px; margin: 0 0 0 18px;" name="chrID"
                            type="text" size="10" placeholder="0000000000000"
                            value='<?php
                            echo $targetGoods->chrID;
                            ?>'/>
                        <input class="newID hvr-fade"
                                         style="width: 100px; height: 37px; margin: 0;" type="submit"
                                         name="newID" id="newID" size="10" value="新規"/>
                        <a class="center_button hvr-fade" style="margin:0 auto;float:right;width:10px;" id="right-menu" href="#sidr">入力説明表示／非表示 <i class="fa fa-info-circle"></i></a>
                    </p>
                    <p class="list">
                        <label class="list">商品区分</label>
                        <select name="chrClass_ID" style="float:left;height:37px;width:150px;">
                            <?php foreach ($classids as $c) : ?>
                                <option 
				<? if (mb_substr($c,0,1,"UTF-8") == $targetGoods->chrClass_ID) { ?>
					selected 
				<? } ?>
				value="<?php echo $c ?>"><?php echo $c ?></option>
                            <?php endforeach; ?>
                        </select>
                    </p>

                    <p class="list">
                        <label class="list">品番</label><input
                            style="width: 390px; margin: 0 0 0 18px;" type="text" size="10"
                            class="validate[required,maxSize[30]] text-input"
                            data-prompt-position="topLeft:140" name="chrCode"
                            value="<?php
                            echo $targetGoods->chrCode;
                            ?>"/>
                    </p>
                    <p class="list">
                        <label class="list">商品名</label><input
                            style="width: 390px; margin: 0 0 0 18px;" type="text" size="10"
                            class="validate[required,maxSize[50]] text-input"
                            data-prompt-position="topLeft:140" name="chrName"
                            value="<?php
                            echo $targetGoods->chrName;
                            ?>"/>
                    </p>
                    <p class="list">
                        <label class="list">商品名カナ</label><input
                            style="width: 390px; margin: 0 0 0 18px;" type="text" size="10"
                            class="validate[required,maxSize[50]] text-input"
                            data-prompt-position="topLeft:140" name="chrKana"
                            value="<?php
                            echo $targetGoods->chrKana;
                            ?>"/>
                    </p>
                    <p class="list">
                        <label class="list">仕入先</label><input
                            style="width: 390px; margin: 0 0 0 18px;" type="text" size="10"
                            class="validate[required,maxSize[3]] text-input" id="ref_seller"
                            data-prompt-position="topLeft:140" name="chrSeller_ID onblur="blurA(this);""
                            value="<?php
                            echo $targetGoods->chrSeller_ID;
                            ?>"/>
                        <a class="center_button hvr-fade" style="margin:0 auto;float:right;width:80px;" id="right-menu-seller" href="#sidr">仕入れリスト</a>
                    </p>
                    <p class="list">
                        <label class="list">メーカー</label><input
                            style="width: 390px; margin: 0 0 0 18px;" type="text" size="10"
                            class="validate[required,maxSize[3]] text-input"
                            data-prompt-position="topLeft:140" name="chrMaker_ID"
                            value="<?php
                            echo $targetGoods->chrMaker_ID;
                            ?>"/>
                    </p>
                    <p class="list">
                        <label class="list">部門</label><input
                            style="width: 390px; margin: 0 0 0 18px;" type="text" size="10"
                            class="validate[required,maxSize[2]] text-input"
                            data-prompt-position="topLeft:140" name="chrGroup_ID"
                            value="<?php
                            echo $targetGoods->chrGroup_ID;
                            ?>"/>
                    </p>
                    <p class="list">
                        <label class="list">品種</label><input
                            style="width: 390px; margin: 0 0 0 18px;" type="text" size="10"
                            class="validate[required,maxSize[2]] text-input"
                            data-prompt-position="topLeft:140" name="chrUnit_ID"
                            value="<?php
                            echo $targetGoods->chrUnit_ID;
                            ?>"/>
                    </p>
                    <p class="list">
                        <label class="list">カラー</label><input
                            style="width: 390px; margin: 0 0 0 18px;" type="text" size="10"
                            class="validate[required,maxSize[20]] text-input"
                            data-prompt-position="topLeft:140" name="chrColor"
                            value="<?php
                            echo $targetGoods->chrColor;
                            ?>"/>
                    </p>
                    <p class="list">
                        <label class="list">サイズ・規格</label><input
                            style="width: 390px; margin: 0 0 0 18px;" type="text" size="10"
                            class="validate[required,maxSize[20]] text-input"
                            data-prompt-position="topLeft:140" name="chrSize"
                            value="<?php
                            echo $targetGoods->chrSize;
                            ?>"/>
                    </p>
                    <p class="list">
                        <label class="list">備考1</label><input
                            style="width: 390px; margin: 0 0 0 18px;" type="text" size="10"
                            class="validate[required,maxSize[50]] text-input"
                            data-prompt-position="topLeft:140" name="chrComment1"
                            value="<?php
                            echo $targetGoods->chrComment1;
                            ?>"/>
                    </p>
                    <p class="list">
                        <label class="list">備考2</label><input
                            style="width: 390px; margin: 0 0 0 18px;" type="text" size="10"
                            class="validate[required,maxSize[50]] text-input"
                            data-prompt-position="topLeft:140" name="chrComment2"
                            value="<?php
                            echo $targetGoods->chrComment2;
                            ?>"/>
                    </p>
                    <p class="list">
                        <label class="list">原価</label> <input
                            style="width: 150px; margin: 0 0 0 18px;text-align:right;" type="text" size="10"
                            class="validate[required,onlyNumberSp,maxSize[7]] text-input"
                            data-prompt-position="topLeft:140" name="intCost"
                            value="<?php
                            echo $targetGoods->intCost;
                            ?>"/>
                    </p>
                    <p class="list">
                        <label class="list">売価</label> <input
                            style="width: 150px; margin: 0 0 0 18px;text-align:right;" type="text" size="10"
                            class="validate[required,onlyNumberSp,maxSize[7]] text-input"
                            data-prompt-position="topLeft:140" name="intPrice"
                            value="<?php
                            echo $targetGoods->intPrice;
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
        <!-- ********************* マスタの作成 終了　**********************	-->


        <!-- ********************* リストの作成 開始　**********************	-->
        <div id="user_list" style="overflow:auto !important;">
            <form method="post" id="list" action="">
                <?php
                $header = [
                    "コード" => 50,
                    "商品区分" => 100,
                    "品番" => 80,
                    "商品名" => 550,
                    "商品名カナ" => 350,
                    "仕入先" => 50,
                    "メーカー" => 50,
                    "部門" => 50,
                    "品種" => 50,
                    "カラー" => 50,
                    "サイズ" => 50,
                    "備考1" => 50,
                    "備考2" => 50,
                    "原価" => 50,
                    "売価" => 50,
                    "選択" => 52,
                    "削除" => 70
                ];

                echo '<table id="myTable" style="width:1500px;border:0; padding:0;border-radius:5px;" class="search_table tablesorter">';
                echo '<thead><tr>';
                foreach ($header as $name => $width)
                    echo '<th width="' . $width . '">' . $name . '</th>';
                echo '</tr></thead><tbody>';


                foreach ((array)$contents as $row) {
                    echo '<tr class="not_header" id="'.$row->chrID.'">';
                    echo '<td  style="text-align:center;">' . $row->chrID . '</td>';
                    echo '<td  style="text-align:center;">' . $row->chrClass_ID . '</td>';
                    echo '<td  style="text-align:left;">' . $row->chrCode . '</td>';
                    echo '<td  style="text-align:left;">' . $row->chrName . '</td>';
                    echo '<td  style="text-align:left;">' . $row->chrKana . '</td>';
                    echo '<td  style="text-align:center;">' . $row->chrSeller_ID . '</td>';
                    echo '<td  style="text-align:center;">' . $row->chrMaker_ID . '</td>';
                    echo '<td  style="text-align:center;">' . $row->chrGroup_ID . '</td>';
                    echo '<td  style="text-align:center;">' . $row->chrUnit_ID . '</td>';
                    echo '<td  style="text-align:left;">' . $row->chrColor . '</td>';
                    echo '<td  style="text-align:left;">' . $row->chrSize . '</td>';
                    echo '<td  style="text-align:left;">' . $row->chrComment1 . '</td>';
                    echo '<td  style="text-align:left;">' . $row->chrComment2 . '</td>';
                    echo '<td  style="text-align:right;">' . $row->intCost . '</td>';
                    echo '<td  style="text-align:right;">' . $row->intPrice . '</td>';
                    echo '<td style="width:52px;text-align:center;"><input type="radio" onclick="javascript: submit()" name="targetID" id="targetID" value="' . $row->chrID . '"/></td>';
                    echo '<td style="width:70px;padding:2px;"><button class="center_button hvr-fade delete_button" style="width:65px; height:25px; margin:0;padding:0;font-weight:normal;" type="submit" name="delete" value="' . $row->chrID .'">削除</button></td>';
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