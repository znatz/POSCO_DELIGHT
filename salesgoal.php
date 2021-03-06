<?php
require_once './utils/password.php';
require_once './utils/connect.php';
require_once './mapping/staff_class.php';
require_once './mapping/salesgoal_class.php';
require_once './mapping/shop_class.php';
require_once './utils/html_parts_generator.php';
require_once './utils/helper.php';
require_once './mapping/menu_class.php';


session_start();
session_check();
global $errorMessage;
global $successMessage;
$errorMessage = "";
$successMessage = "";
$targetSalesgoal = Salesgoal::get_one_empty_salesgoal();


// ログイン状態チェック
if (! isset($_SESSION['staff']))
    header("Location: Login.php");

// 今ログインしている担当をセッションから取り出す、オブジェクトに一旦保存
$staff = unserialize($_SESSION["staff"]);

// リストを表示ため、全担当データを一回取り出す
$contents = Salesgoal::get_all_salesgoal();

// リスト内ラジオボタンの処理
if (isset($_POST["targetID"])) {
    $chrID = $_POST["targetID"];
    $targetSalesgoal = Salesgoal::get_one_salesgoal($chrID);

    // 選択を解除
    unset($_POST["target"]);
    $contents = Salesgoal::get_all_salesgoal();
}

// 削除ボタン処理
for ($i = 0; $i <= 99; $i ++) {
    if (isset($_POST[$i])) {
        if (Salesgoal::delete_one_salesgoal($i)) {
            $contents = Salesgoal::get_all_salesgoal();
            $successMessage = "削除しました。";}
        else {
            $errorMessage = "削除失敗しました。";
        }
        break;
    }
}

// 　登録処理
if (isset($_POST["submit"])) {
    $errorMessage = "";
    $successMessage = "";
    $chrDate = $_POST['year'].'-'.$_POST['month'];
   if(Salesgoal::insert_one_salesgoal(
       $_POST['chrShop_ID'],
       $chrDate ,
       $_POST['intGoal']))
   {
        $successMessage = "追加しました。";
    } else {
        // 更新処理開始
        if  (mysql_errno() == 1062) {
            if(Salesgoal::update_one_salesgoal(
                $_POST['chrID'],
                $_POST['chrShop_ID'],
                $chrDate ,
                $_POST['intGoal']))
            {
                $successMessage = "更新しました。";
            };
        }
    }

    // 再度リストを更新
    $contents = Salesgoal::get_all_salesgoal();
    $_POST["targetID"] = $chrID;
}


$shops = Shop::get_distinct_shop_chrID();
$contents = Salesgoal::get_all_salesgoal();
?>

<!DOCTYPE html>
<head>
    <?php include('./html_parts/css_and_js.html'); ?>
<script type="text/javascript">
    		jQuery(document).ready(function() {

            $("tr").dblclick(function () {
                var chrID = $(this).attr("id");
                console.log(chrID);
                $("input[name=targetID][value=" + chrID + "]").attr('checked', 'checked');
                $("#list").submit();
            });


              $('#myTable').DataTable({
                    "language": {
                        "sProcessing":   "処理中...",
                        "sLengthMenu":   "_MENU_ 件表示",
                        "sZeroRecords":  "データはありません。",
                        "sInfo":         " _TOTAL_ 件中 _START_ から _END_ まで表示",
                        "sInfoEmpty":    " 0 件中 0 から 0 まで表示",
                        "sInfoFiltered": "（全 _MAX_ 件より抽出）",
                        "sInfoPostFix":  "",
                        "sSearch":       "検索:",
                        "sUrl":          "",
                        "oPaginate": {
                        "sFirst":    "先頭",
                            "sPrevious": "前",
                            "sNext":     "次",
                            "sLast":     "最終"
                        }
                    },
                    "aoColumnDefs": [
                        { "bSortable": false, "aTargets": [ 3, 4 ] }
                    ]
            });



                $('#main-menu').smartmenus();
    			jQuery("#user_add_form").validationEngine();
    			$("#user_add_form").bind("jqv.field.result", function(event, field, errorFound, prompText) {
    				console.log(errorFound)
    			});

    	        $("#newID").click(function(){
    	            $('#user_add_form').validationEngine('hideAll');
    	            $('#user_add_form').validationEngine('detach');
    	            return true;
    	        });
                $('#right-menu').sidr({
                    name: 'sidr-right',
                    side: 'right'});
                                $("#jMenu").jMenu({
                    ulWidth : 'auto',
                    effects : {
                        effectSpeedOpen : 300,
                        effectTypeClose : 'slide'
                    },
                    animatedText : false,
                    openClick : true
                });
               $("#myTable").tablesorter({
                    headers:{
                        5:{sorter:false},
                        6:{sorter:false}
                    }});

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
	width: 800px;
	margin: 0 auto;
	clear: both;
}

#buttonlist {
	margin: 10px 0;
}

p.list {
	width: 700px;
	height: 37px;
	color: #000000;
    padding-left:40px;
}

p.list input[type="text"], input[type="password"], select {
	float: left;
	height: 35px;
	border: 1px solid #555555;
	background: #faffbd;
    transition: border 0.3s;
}

p.list input[type="text"]:focus, input[type="password"]:focus, select:focus
	{
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

input.delete_button {
    background-image: -webkit-gradient(linear, left top, left bottom, from(#FFFFFF), to(#c2c2c2));
    background-image: -webkit-linear-gradient(top, #FFFFFF, #c2c2c2);
    background-image: -moz-linear-gradient(top, #FFFFFF, #c2c2c2);
    background-image: -ms-linear-gradient(top, #FFFFFF, #c2c2c2);
    background-image: -o-linear-gradient(top, #FFFFFF, #c2c2c2);
    background-image: linear-gradient(to bottom, #FFFFFF, #c2c2c2);filter:progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr=#FFFFFF, endColorstr=#c2c2c2);
}


</style>
</head>
<body>
	<div class="blended_grid">
		<div class="pageHeader">
<?php include('./html_parts/header.html');?>
        </div>
        <div class="pageContent">
                <?php include('./html_parts/top_menu.html');?>
    <div class="main">
            <?php include('./html_parts/warning.html');?>

					<!-- ********************* マスタの作成 開始　**********************	-->
					<div style="clear: both; float: top;">

					<form class="search_form" style="margin: 0 auto; width: 700px; height:220px;"
							method="post" id="user_add_form" action="">
                        <fieldset><legend>目標設定マスタ</legend></fieldset>
                        <a class="center_button hvr-fade" style="margin:0 auto;float:right;width:10px;" id="right-menu" href="#sidr">入力説明表示／非表示 <i class="fa fa-info-circle"></i></a>
							<p class="list" style="margin-top: 30px;">
								<label class="list">店舗コード</label>
                                <select
                                    class="validate[required]"
                                    name="chrShop_ID" style="float:left;height:33px;width:150px;">
                                    <option/>
                                    <?php foreach($shops as $c) : ?>
                                        <option value="<?php echo $c[0] ?>" <? if($targetSalesgoal->chrShop_ID==$c[0]) echo "selected"; ?>><?php echo $c[0]." ".$c[1]; ?></option>
                                    <?php endforeach; ?>
                                </select>
							</p>
							<p class="list">
								<label class="list">年度</label>
                                <input
									style="width: 200px; margin: 0 0 0 18px;text-align: right;padding-right:5px;" type="text" size="10"
                                    class="year validate[custom[integer],max[10000],min[1],custom[required_4_digits] text-input"
									data-prompt-position="topLeft:40" name="year"
									value="<?php
        echo explode("-", $targetSalesgoal->chrDate)[0];
        ?>" />
                                <label class="list" style="text-align:left;margin-left:5px;">年</label>
							</p>
							<p class="list">
								<label class="list">月</label>
                                <input
									style="width: 200px; margin: 0 0 0 18px;text-align: right;padding-right:5px;" type="text" size="10"
                                    class="month validate[custom[integer],max[13],min[1],custom[required_2_digits] text-input"
									data-prompt-position="topLeft:40" name="month"
									value="<?php
        echo explode("-", $targetSalesgoal->chrDate)[1];
        ?>" />
                                <label class="list" style="text-align:left;margin-left:5px;">月</label>
							</p>
							<p class="list">
								<label class="list">年間目標金額</label>
                                <input
									style="width: 200px; margin: 0 0 0 18px;text-align: right;padding-right:5px;" type="text" size="10"
                                    class="money validate[custom[integer],max[1000000],min[0] text-input"
									data-prompt-position="topLeft:40" name="intGoal"
									value="<?php
        echo $targetSalesgoal->intGoal;
        ?>" />
                                <label class="list" style="text-align:left;margin-left:5px;">円</label>
							</p>

							<p style="float: left; text-align: center; width: 300px; margin-top:70px;"
								id="buttonlist">
								<input class="center_button hvr-fade" type="submit" name="submit"
									size="10" value="登録"/>
                                <a class="center_button hvr-fade" href="./salesgoal.php" style="display: block; float:left;text-decoration: none; width: 88px; margin: 30px 5px; font-size: 14px; padding: 12px 0 12px 0;">クリア</a>
                                <a class="center_button hvr-fade" href="./index.php" style="display: block; float:left;text-decoration: none; width: 88px; margin: 30px 5px; font-size: 14px; padding: 12px 0 12px 0;">戻る</a>
							</p>
							<div
								style="float: right; width: -100%; height: 100px; margin-top:70px; text-align: center; vertical-align: middle;">

								<a class="center_button hvr-fade" href="../utils/excel_export.php"
									style="display: block; text-decoration: none; width: 150px; margin: 30px 5px; font-size: 14px; padding: 12px 0 12px 0;">EXCELへ出力&nbsp;<i class="fa fa-file-text-o"></i>&nbsp;</a>
								<a class="center_button hvr-fade" href="../utils/csv_export.php"
									style="display: block; text-decoration: none; width: 130px; margin: 30px 5px; font-size: 14px; padding: 12px 0 12px 0;">CSVへ出力&nbsp;<i class="fa fa-file-text-o"></i>&nbsp;</a>
							</div>
						</form>
					</div>
					<form action=""></form>
				</div>
				<!-- ********************* マスタの作成 終了　**********************	-->


				<!-- ********************* リストの作成 開始　**********************	-->
				<div id="user_list">
					<form method="post" id="list" action="">
<?php
$header = [
    "店舗コード" => 120,
    "年月" => 100,
    "目標金額" => 250,
    "選択" => 52,
    "削除" => 70
];
echo '<table id="myTable" class="tablesorter search_table" style="border:0;padding:0;border-radius:5px;table-layout:fixed;" >';
echo '<thead><tr>';
foreach ($header as $name => $width)
    echo '<th width="' . $width . 'px">' . $name . '</th>';
echo '</tr></thead><tbody>';


foreach ((array)$contents as $row) {
    echo '<tr class="not_header" id="' . $row->chrID . '">';
    echo '<td width="120px;" style="text-align: center;">' . $row->chrShop_ID . '</td>';
    echo '<td width="102px;" style="text-align: center;">' . $row->chrDate . '</td>';
    echo '<td width="250px;" style="text-align:right;">' . $row->intGoal . '</td>';
    echo '<td style="width:52px;text-align:center;"><input type="radio" onclick="javascript: submit()" name="targetID" id="targetID" value="' . $row->chrID . '"/></td>';
    echo '<td style="width:70px;padding:2px;text-align: center;"><input class="center_button hvr-fade delete_button" style="width:70px; height:25px; margin:0;padding:0;font-weight:normal;" type="submit" name="' . $row->chrID . '" value="削除"/></td>';
    echo '</tr>';
}
echo '</tbody></table>';

$_SESSION["sheet"] = serialize($contents);
array_pop($header);
array_pop($header);
$_SESSION["sheet_header"] = array_keys($header);
?>
			<input type="submit" name="target" style="display: none" />
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
                                    $query = 'SELECT txtInstruction FROM form_helper WHERE chrPageName="salesgoal";';
                                    $result = $connection->result($query);
                                    $row = mysql_fetch_array($result, MYSQL_ASSOC);
                                    echo $row['txtInstruction'];
                            ?>
                        </div>
    <!-- ********************  入力規則　終了      *********************** -->
</body>
