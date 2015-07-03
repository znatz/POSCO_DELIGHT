<?php
require_once './utils/password.php';
require_once './utils/connect.php';
require_once './mapping/seller_class.php';
require_once './mapping/staff_class.php';
require_once './mapping/category_class.php';
require_once './utils/html_parts_generator.php';
require_once './utils/helper.php';
require_once './mapping/menu_class.php';


session_start();
session_check();
global $errorMessage;
global $successMessage;
$errorMessage = "";
$successMessage = "";
$targetSeller = Seller::get_one_empty_seller();;

// ログイン状態チェック
if (!isset($_SESSION['staff']))
    header("Location: Login.php");

// 今ログインしている担当をセッションから取り出す、オブジェクトに一旦保存
$staff = unserialize($_SESSION["staff"]);

// chrID取得の為データを一回取り出す
$contents = Seller::get_all_seller();
$chrID = $targetSeller ->chrID ;

// 新規ボタンの処理
if (isset($_POST['newID'])) {
    $targetSeller = Seller::get_new_seller();
    unset($_POST['newID']);
}

// リスト内ラジオボタンの処理
if (isset($_POST["targetID"])) {

    $targetSeller = Seller::get_one_seller($_POST["targetID"]);

    // 選択を解除
    unset($_POST["target"]);
    $contents = Seller::get_all_seller();

}

// 削除ボタン処理
    if (isset($_POST['delete'])) {
        if (Seller::delete_one_seller($_POST['delete'])) {
            $contents = Seller::get_all_seller();
            $successMessage = "削除しました。";
        } else {
            $errorMessage = "削除失敗しました。";
        }
    }

// 　登録処理
if (isset($_POST["submit"])) {
    if (Seller::insert_one_seller($_POST['chrID'],
        $_POST['chrName'],
        $_POST['chrShort_Name'],
        $_POST['chrPos'],
        $_POST['chrAddress'],
        $_POST['chrAddress_No'],
        $_POST['chrTel'],
        $_POST['chrFax'],
        $_POST['chrStaff'])
    ) {
        $successMessage = "追加しました。";
    } else {
        // 更新処理開始
        if (mysql_errno() == 1062) {
            if (Seller::update_one_seller($_POST['chrID'],
                $_POST['chrName'],
                $_POST['chrShort_Name'],
                $_POST['chrPos'],
                $_POST['chrAddress'],
                $_POST['chrAddress_No'],
                $_POST['chrTel'],
                $_POST['chrFax'],
                $_POST['chrStaff'])
            ) {
                $successMessage = "更新しました。";
            };
        }
    }

    // 再度リストを更新
    $contents = Seller::get_all_seller();
    $_POST["targetID"] = $chrID;
}

//フォーム表示の為データを一回取り出す
$contents = Seller::get_all_seller();

$Pos = $targetSeller ->chrPos;
$address = $targetSell ->chrAddress;
//検索ボタンの処理
if (isset($_POST['SearchPost'])) {
	$Pos = $_POST["chrPos"];
	$connection = new Connection();
	$query = "select * from post Where chrID='" . $Pos ."';";
	$result = $connection->result($query) or die("SQL Error 1: " . mysql_error());
	$rowCount = mysql_num_rows($result);
	if ($rowCount > 0) {
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	$Address = $row['chrPrefecture'] . $row['chrAddress'];
	} else {
	}
	$connection ->close();
}

?>

<!DOCTYPE html>
<head>
    <?php include('./html_parts/css_and_js.html'); ?>
    <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
    <script type="text/javascript">
        jQuery(document).ready(function () {

            $("tr").dblclick(function () {
                var chrID = $(this).attr("id");
                console.log(chrID);
                $("input[name=targetID][value=" + chrID + "]").attr('checked', 'checked');
                $("#list").submit();
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
                    {"bSortable": false, "aTargets": [5, 6]}
                ]
            });


            $('#main-menu').smartmenus();
            jQuery("#user_add_form").validationEngine();
            $("#user_add_form").bind("jqv.field.result", function (event, field, errorFound, prompText) {
                console.log(errorFound)
            })

            $("#newID").click(function () {
                $('#user_add_form').validationEngine('hideAll');
                $('#user_add_form').validationEngine('detach');
                return true;
            });
            $('#right-menu').sidr({
                name: 'sidr-right',
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
            width:1100px;
            clear: both;
            overflow:auto !important;
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
                        <legend>仕入先マスタ</legend>
                    </fieldset>
                    <p class="list">
                        <label class="list">コード</label>
                        <input
                            tabindex="1"
                            class="chrID validate[custom[integer],custom[required_3_digits]] text-input"
                            data-prompt-position="topLeft:140"
                            style="width: 290px; margin: 0 0 0 18px;" name="chrID"
                            type="text" size="10" placeholder="000"
                            value='<?php
                            echo $targetSeller->chrID;
                            ?>'/>
                        <input
                            tabindex="2"
                            class="newID hvr-fade" style="width: 100px; height: 37px; margin: 0;" type="submit"
                            name="newID" id="newID" size="10" value="新規"/>
                        <a
                           tabindex="12"
                           class="center_button hvr-fade" style="margin:0 auto;float:right;width:10px;" id="right-menu"
                           href="#sidr">入力説明表示／非表示 <i class="fa fa-info-circle"></i></a>
                    </p>

                    <p class="list">
                        <label class="list">仕入先名</label>
                        <input
                            tabindex="3"
                            style="width: 390px; margin: 0 0 0 18px;" type="text" size="10"
                            class="validate[required,maxSize[30]] text-input"
                            data-prompt-position="topLeft:140" name="chrName"
                            value="<?php
                            echo $targetSeller->chrName;
                            ?>"/>
                    </p>

                    <p class="list">
                        <label class="list">カナ</label>
                        <input
                            tabindex="4"
                            style="width: 390px; margin: 0 0 0 18px;" type="text" size="10"
                            class="validate[katagana,maxSize[20]] text-input"
                            data-prompt-position="topLeft:140" name="chrShort_Name"
                            value="<?php
                            echo $targetSeller->chrShort_Name;
                            ?>"/>
					</p>
                    <p class="list">
                        <label class="list">郵便番号</label>
                        <input
                            tabindex="5"
                            style="width: 100px; margin: 0 0 0 18px;" type="text" size="10"
                            class="validate[onlyLetterNumber,maxSize[8]] text-input"
                            data-prompt-position="topLeft:140" name="chrPos"
                            value="<?php
                            echo $targetSeller->chrPos;
                            ?>"
                            onKeyUp="AjaxZip3.zip2addr(this,'','chrAddress','chrAddress');">
                        <input class="newID hvr-fade"
								style="width: 100px; height: 37px; margin: 0;" type="submit"
								name="SearchPost" id="SearchPost" size="10" value="検索"/>
					</p>
                    <p class="list">
                        <label class="list">住所</label>
                        <input
                            tabindex="6"
                            style="width: 390px; margin: 0 0 0 18px;" type="text" size="10"
                            class="validate[required,maxSize[50]] text-input"
                            data-prompt-position="topLeft:140" name="chrAddress"
                            value="<?php
                            echo $targetSeller->chrAddress;
                            ?>"/>

					</p>
                    <p class="list">
                        <label class="list">番地</label>
                        <input
                            tabindex="7"
                            style="width: 390px; margin: 0 0 0 18px;" type="text" size="10"
                            class="validate[required,maxSize[50]] text-input"
                            data-prompt-position="topLeft:140" name="chrAddress_No"
                            value="<?php
                            echo $targetSeller->chrAddress_No;
                            ?>"/>
					</p>
                    <p class="list">
                        <label class="list">電話番号</label>
                        <input
                            tabindex="8"
                            style="width: 390px; margin: 0 0 0 18px;" type="text" size="10"
                            class="validate[phone,maxSize[13]] text-input"
                            data-prompt-position="topLeft:140" name="chrTel"
                            value="<?php
                            echo $targetSeller->chrTel;
                            ?>"/>
					</p>
                    <p class="list">
                        <label class="list">FAX番号</label>
                        <input
                            tabindex="10"
                            style="width: 390px; margin: 0 0 0 18px;" type="text" size="10"
                            class="validate[phone,maxSize[13]] text-input"
                            data-prompt-position="topLeft:140" name="chrFax"
                            value="<?php
                            echo $targetSeller->chrFax;
                            ?>"/>
					</p>
                    <p class="list">
                        <label class="list">担当者名</label>
                        <input
                            tabindex="11"
                            style="width: 390px; margin: 0 0 0 18px;" type="text" size="10"
                            class="validate[required,maxSize[10]] text-input"
                            data-prompt-position="topLeft:140" name="chrStaff"
                            value="<?php
                            echo $targetSeller->chrStaff;
                            ?>"/>
					</p>
                    <p style="float: left; text-align: center; width: 300px;"
                       id="buttonlist">
                        <input class="center_button hvr-fade" type="submit" name="submit"
                               size="10" value="登録"/>
                        <a class="center_button hvr-fade" href="./seller.php" style="display: block; float:left;text-decoration: none; width: 88px; margin: 30px 5px; font-size: 14px; padding: 12px 0 12px 0;">クリア</a>
                        <a class="center_button hvr-fade" href="./index.php" style="display: block; float:left;text-decoration: none; width: 88px; margin: 30px 5px; font-size: 14px; padding: 12px 0 12px 0;">戻る</a>
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
                    "コード" => 80,
                    "仕入先名" => 300,
                    "カナ" => 150,
                    "郵便番号" => 80,
                    "住所" => 150,
                    "番地" => 150,
                    "電話番号" => 150,
                    "FAX番号" => 120,
                    "担当者名" => 120,
                    "選択" => 50,
                    "削除" => 70
                ];

                echo '<table id="myTable" style="width:1500px;border:0;padding:0;border-radius:5px;" class="search_table">';
                echo '<thead><tr>';
                foreach ($header as $name => $width)
                    echo '<th width="' . $width . '">' . $name . '</th>';
                echo '</tr></thead><tbody>';


                foreach ((array)$contents as $row) {
                    echo '<tr class="not_header" id="' . $row->chrID . '">';
                    echo '<td>' . $row->chrID . '</td>';
                    echo '<td>' . $row->chrName . '</td>';
                    echo '<td>' . $row->chrShort_Name . '</td>';
                    echo '<td>' . $row->chrPos . '</td>';
                    echo '<td>' . $row->chrAddress . '</td>';
                    echo '<td>' . $row->chrAddress_No . '</td>';
                    echo '<td>' . $row->chrTel . '</td>';
                    echo '<td>' . $row->chrFax . '</td>';
                    echo '<td>' . $row->chrStaff . '</td>';
                    echo '<td style="text-align:center;"><input type="radio" onclick="javascript: submit()" name="targetID" id="targetID" value="' . $row->chrID . '"/></td>';
                    echo '<td style="padding:2px;"><button class="center_button hvr-fade delete_button" style="width:65px; height:25px; margin:0;padding:0;font-weight:normal;" type="submit" name="delete" value="'.$row->chrID.'">削除</button></td>';
                    echo '</tr>';
                }
                echo '</tbody></table>';

                $_SESSION["sheet"] = serialize($contents);
                array_pop($header);
                array_pop($header);
                $_SESSION["sheet_header"] = array_keys($header);
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
    $query = 'SELECT txtInstruction FROM form_helper WHERE chrPageName="seller";';
    $result = $connection->result($query);
    $row = mysql_fetch_array($result, MYSQL_ASSOC);
    echo $row['txtInstruction'];
    ?>
</div>
<!-- ********************  入力規則　終了      *********************** -->
</body>
</html>
