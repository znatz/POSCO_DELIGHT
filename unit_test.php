<?php
require_once './utils/password.php';
require_once './utils/connect.php';
require_once './mapping/unit_class.php';
require_once './mapping/staff_class.php';
require_once './utils/html_parts_generator.php';
require_once './utils/helper.php';

session_start();
session_check();
global $errorMessage;
global $successMessage;
$errorMessage = "";
$successMessage = "";
$targetUnit = Unit::get_one_empty_unit();;

// ログイン状態チェック
if (! isset($_SESSION['staff']))
    header("Location: Login.php");
    
// 今ログインしている担当をセッションから取り出す、オブジェクトに一旦保存
$staff = unserialize($_SESSION["staff"]);

// リストを表示ため、全担当データを一回取り出す
$contents = Unit::get_all_unit();

// 新規ボタンの処理
if (isset($_POST['newID'])) {
    $targetUnit = Unit::get_new_unit();
    unset($_POST['newID']);
}

// リスト内ラジオボタンの処理
if (isset($_POST["targetID"])) {
    
    $targetUnit = Unit::get_one_unit($_POST["targetID"]);

    // 選択を解除
    unset($_POST["target"]);
    $contents = Unit::get_all_unit();
}

// 削除ボタン処理
for ($i = 0; $i <= 99; $i ++) {
    $two_digits = str_pad($i, 2, '0', STR_PAD_LEFT);
    if (isset($_POST[$two_digits])) {
        if (Unit::delete_one_unit($two_digits)) {
            $contents = Unit::get_all_unit();
            $successMessage = "ユーザが削除しました。";}
        else {
            $errorMessage = "削除失敗しました。";
        }
        break;
    } 
}

// 　登録処理
if (isset($_POST["submit"])) {
   if(Unit::insert_one_unit($_POST['chrID'],
       $_POST['chrGroup_ID'],
       $_POST['chrName'],
       $_POST['chrShort_Name'],
       $_POST['intDiscount'],
       $_POST['intTax_Type'],
       $_POST['intPoint_Flag']))
   {
        $successMessage = "ユーザが追加しました。";
    } else {
        // 更新処理開始
        if  (mysql_errno() == 1062) {
            if(Unit::update_one_unit($_POST['chrID'],
                $_POST['chrGroup_ID'],
                $_POST['chrName'],
                $_POST['chrShort_Name'],
                $_POST['intDiscount'],
                $_POST['intTax_Type'],
                $_POST['intPoint_Flag'])
                )
            {
                $successMessage = "記録すでに存在したため、更新しました。";
            };
        }
    }

    // 再度リストを更新
    $contents = Unit::get_all_unit();
    $_POST["targetID"] = $chrID;
}

$contents = Unit::get_all_unit();
?>

<!DOCTYPE html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="./css/blended_layout.css">
<link rel="stylesheet" type="text/css" href="./css/search.css">
<link rel="stylesheet" type="text/css"	href="../css/validationEngine.jquery.css">
<link rel="stylesheet" href="../css/template.css" type="text/css">
<link rel="stylesheet" href="../css/jquery.sidr.dark.css">
<link rel="stylesheet" href="../css/button.css">
	<script src="../js/jquery-1.8.2.min.js" type="text/javascript"></script>
	<script src="../js/languages/jquery.validationEngine-ja.js" type="text/javascript" charset="utf-8"></script>
	<script src="../js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
    <script src="../js/jquery.sidr.min.js"></script>
<script type="text/javascript">
    		jQuery(document).ready(function() {
    			jQuery("#user_add_form").validationEngine();
    			$("#user_add_form").bind("jqv.field.result", function(event, field, errorFound, prompText) {
    				console.log(errorFound)
    			})
    			
    	        $("#newID").click(function(){
    	            $('#user_add_form').validationEngine('hideAll');
    	            $('#user_add_form').validationEngine('detach');
    	            return true;                
    	        });
                $('#right-menu').sidr({
                    name: 'sidr-right',
                    side: 'right'});
    		});
    	</script>
<title>POSCO</title>
<meta name="description" content="POSCO">
<style type="text/css">
* {
	font-family: Verdana;
}

input {
/*	border: 1px solid #000000;*/
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
	width: 900px;
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

input.center_button {
	width: 90px;
	height: 35px;
	margin: 30px 5px 30px 5px;
}

a.center_button {
	float: right;
}

input.center_button, input.newID, a.center_button {
	display: block;
	text-align: center;
	width: 80px;
	font-size: 14px;
	font-family: Verdana;
    padding: 9px 18px;
	text-decoration: none;


   font-style:normal;
   cursor:pointer;
   float:left;

    border:1px solid #e3e3e3;
    -webkit-box-shadow: #B4B5B5 2px 2px 2px;
    -moz-box-shadow: #B4B5B5 2px 2px 2px ;
    box-shadow: #B4B5B5 2px 2px 2px;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;border-radius: 5px;
    font-family:verdana, sans-serif;
    text-shadow: 0px 0px 0 rgba(0,0,0,0.3);
    font-weight:normal;
    color: #030303;

   background-image: -webkit-gradient(linear, left top, left bottom, from(#FFFFFF), to(#969696));
    background-image: -webkit-linear-gradient(top, #FFFFFF, #969696);
    background-image: -moz-linear-gradient(top, #FFFFFF, #969696);
    background-image: -ms-linear-gradient(top, #FFFFFF, #969696);
    background-image: -o-linear-gradient(top, #FFFFFF, #969696);
    background-image: linear-gradient(to bottom, #FFFFFF, #969696);filter:progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr=#FFFFFF, endColorstr=#969696);
}

input.center_button:active {
    cursor:pointer;
    position:relative;
    top:2px;
}

input.delete_button {
    background-image: -webkit-gradient(linear, left top, left bottom, from(#FFFFFF), to(#c2c2c2));
    background-image: -webkit-linear-gradient(top, #FFFFFF, #c2c2c2);
    background-image: -moz-linear-gradient(top, #FFFFFF, #c2c2c2);
    background-image: -ms-linear-gradient(top, #FFFFFF, #c2c2c2);
    background-image: -o-linear-gradient(top, #FFFFFF, #c2c2c2);
    background-image: linear-gradient(to bottom, #FFFFFF, #c2c2c2);filter:progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr=#FFFFFF, endColorstr=#c2c2c2);
}

/* button fade */
.hvr-fade {
  display: inline-block;
  vertical-align: middle;
    /* -webkit-transform: translateZ(0); transform: translateZ(0);*/
  box-shadow: 0 0 1px rgba(0, 0, 0, 0);
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -moz-osx-font-smoothing: grayscale;
  overflow: hidden;
    /* -webkit-transition-duration: 0.01s; transition-duration: 0.01s; */
  -webkit-transition-property: color, background-color;
   /* transition-property: color, background-colo */
}
.hvr-fade:hover, .hvr-fade:focus, .hvr-fade:active {
    border:1px solid #6B81BC;
    background-color: #4FE1FF; background-image: -webkit-gradient(linear, left top, left bottom, from(#4FE1FF), to(#4299F8));
    background-image: -webkit-linear-gradient(top, #4FE1FF, #4299F8);
    background-image: -moz-linear-gradient(top, #4FE1FF, #4299F8);
    background-image: -ms-linear-gradient(top, #4FE1FF, #4299F8);
    background-image: -o-linear-gradient(top, #4FE1FF, #4299F8);
    background-image: linear-gradient(to bottom, #4FE1FF, #4299F8);filter:progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr=#4FE1FF, endColorstr=#4299F8);
}

/* end of button fade */

.main {
}

form.search_form {
	padding: 10px 0;
	border: 1px solid #555;
	-webkit-border-radius: 6px;
	-moz-border-radius: 6px;
	border-radius: 6px;
	-webkit-box-shadow: 0px 1px 10px #000000;
	-moz-box-shadow: 0px 1px 10px #000000;
	box-shadow: 0px 1px 10px #000000;
	border: solid #000000 1px;
	background: #fafafa;
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

					<!-- ********************* フォームの作成 開始　**********************	-->
					<div style="clear: both; float: top;">

					<form class="search_form" style="margin: 0 auto; width: 700px;"
							method="post" id="user_add_form" action="">
							<p class="list">
								<label class="list">部門コード</label> <input
									class="validate[required, custom[required_2_digits],custom[onlyLetterNumber]] text-input"
									data-prompt-position="topLeft:140"
									style="width: 290px; margin: 0 0 0 18px;" name="chrGroup_ID"
									type="text" size="10"
									value='<?php
        echo $targetUnit->chrGroup_ID;
        ?>' />
                            <div id="pagegradient" style="display:inline-block;float:right;width:49px;height:37px;margin:0;"><input class="button"
									style="width: 70px; height: 27px; margin: 0;" type="submit"
									name="newID" id="newID" size="10" value="新規"><span></span></div>
                                <a class="center_button hvr-fade" style="margin:0 auto;float:right;width:10px;" id="right-menu" href="#sidr">入力説明表示／非表示 <i class="fa fa-info-circle"></i></a>
							</p>
							<p class="list">
								<label class="list">品種コード</label><input
									style="width: 390px; margin: 0 0 0 18px;" type="text" size="10"
									class="validate[required,maxSize[20]] text-input"
									data-prompt-position="topLeft:140" name="chrID"
									value="<?php
        echo $targetUnit->chrID;
        ?>" />
							</p>
							<p class="list">
								<label class="list">品種名</label> <input
									style="width: 200px; margin: 0 0 0 18px;" type="text" size="10"
									class="chrName validate[required,onlyNumberSp,custom[required_3_digits]] text-input"
									data-prompt-position="topLeft:140" name="chrName"
									value="<?php
        echo $targetUnit->chrName;
        ?>" />
							</p>
 							<p class="list">
								<label class="list">略称</label> <input
									style="width: 200px; margin: 0 0 0 18px;" type="text" size="10"
									class="chrName validate[required,onlyNumberSp,custom[required_3_digits]] text-input"
									data-prompt-position="topLeft:140" name="chrShort_Name"
									value="<?php
        echo $targetUnit->chrShort_Name;
        ?>" />
							</p>
						    <p class="list">
 								<label class="list">割引率</label> <input
									style="width: 200px; margin: 0 0 0 18px;" type="text"
									size="10"
									class="chrShort_Name validate[required,onlyNumberSp,maxSize[2],custom[required_2_digits]]"
									data-prompt-position="topLeft:140" name="intDiscount"
                                    value="<?php
        echo $targetUnit->intDiscount;
         ?>"/>
							</p>
 							<p class="list">
								<label class="list">税区</label>
                                 <select name="intTax_Type" style="float:left;height:37px;width:207px;">
									<option value="0"
										<?php if($targetUnit->intTax_Type ==0) echo "selected"; ?>>０：外税</option>
									<option value="1"
										<?php if($targetUnit->intTax_Type ==1) echo "selected"; ?>>１：内税</option>
									<option value="2"
										<?php if($targetUnit->intTax_Type ==2) echo "selected"; ?>>２：非課税</option>
								</select>
							</p>
							<p class="list">
								<label class="list">点区</label>
                                 <select name="intPoint_Flag" style="float:left;height:37px;width:207px;">
									<option value="0"
										<?php if($targetUnit->intPoint_Flag ==0) echo "selected"; ?>>０：対象</option>
									<option value="1"
										<?php if($targetUnit->intPoint_Flag ==1) echo "selected"; ?>>１：対象外</option>
								</select>
							</p>

							<p style="float: left; text-align: center; width: 200px;"
								id="buttonlist">
                                <div id="pagegradient" style="float:left;width:50px;">
								    <input class="button" type="submit" name="submit"
									size="10" value="登録"/><span></span></div>
                        <div id="pagegradient" style="float:left;widht:50px;">
                                <input class="button"
									type="reset" size="10" value="クリア" /><span></span></div>
							</p>
							<div
								style="float: right; width: -100%; height: 100px; margin: 10px 0; text-align: center; vertical-align: middle;">
								<a class="center_button hvr-fade" href="./index.php"
									style="display: block; text-decoration: none; width: 88px; height: 8px; margin: 30px 5px; font-size: 14px; padding: 12px 0 12px 0;">戻る</a>
								<a class="center_button hvr-fade" href="../utils/excel_export.php"
									style="display: block; text-decoration: none; width: 150px; height: 8px; margin: 30px 5px; font-size: 14px; padding: 12px 0 12px 0;">EXCELへ出力&nbsp;<i class="fa fa-file-text-o"></i>&nbsp;</a>
								<a class="center_button hvr-fade" href="../utils/csv_export.php"
									style="display: block; text-decoration: none; width: 130px; height: 8px; margin: 30px 5px; font-size: 14px; padding: 12px 0 12px 0;">CSVへ出力&nbsp;<i class="fa fa-file-text-o"></i>&nbsp;</a>
							</div>
						</form>
					</div>
					<form action=""></form>
				</div>
				<!-- ********************* フォームの作成 終了　**********************	-->


				<!-- ********************* リストの作成 開始　**********************	-->
				<div id="user_list">
					<form method="post" id="list" action="">
<?php
$header = [
    "品種コード" => 160,
    "部門コード" => 160,
    "品種名" => 250,
    "略称" => 200,
    "割引率" => 150,
    "税区" => 120,
    "点区" => 120,
    "選択" => 52,
    "削除" => 70
];
echo '<table id="myTable" style="border:0;margin:0 auto; position:relative;padding:0;border-radius:5px;width:900px;table-layout:fixed;" class="CSSTableGenerator tablesorter">';
echo '<tr>';
foreach ($header as $name => $width)
    echo '<td width="' . $width . 'px">' . $name . '</td>';
echo '</tr>';


foreach ($contents as $row) {
    echo '<tr class="not_header">';
    echo '<td width="167px;">' . $row->chrID . '</td>';
    echo '<td width="164px;">' . $row->chrGroup_ID . '</td>';
    echo '<td width="250px;">' . $row->chrName . '</td>';
    echo '<td width="200px;">' . $row->chrShort_Name . '</td>';
    echo '<td width="150px;">' . $row->intDiscount . '</td>';
    echo '<td width="120px;">' . $row->intTax_Type . '</td>';
    echo '<td width="120px;">' . $row->intPoint_Flag . '</td>';
    echo '<td style="width:52px;text-align:center;"><input type="radio" onclick="javascript: submit()" name="targetID" id="targetID" value="' . $row->chrID . '"/></td>';
    echo '<td style="width:70px;padding:0 0 0 2px;"><input class="center_button hvr-fade delete_button" style="width:65px; height:30px; margin:0;padding:0;font-weight:normal;" type="submit" name="' . $row->chrID . '" value="削除"/></td>';
    echo '</tr>';
}
echo '</table>';

$_SESSION["sheet"] = serialize($contents);
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
                                    $query = 'SELECT txtInstruction FROM form_helper WHERE chrPageName="unit";';
                                    $result = $connection->result($query);
                                    $row = mysql_fetch_array($result, MYSQL_ASSOC);
                                    echo $row['txtInstruction'];
                            ?>
                        </div>
    <!-- ********************  入力規則　終了      *********************** -->
</body>
</html>