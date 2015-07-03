<?php

require_once dirname(__FILE__).'/../utils/helper.php';
require_once dirname(__FILE__).'/../utils/connect.php';

class Goods {
    public $chrID;
    public $chrClass_ID;
    public $chrCode;
    public $chrName;
    public $chrKana;
    public $chrSeller_ID;
    public $chrMaker_ID;
    public $chrGroup_ID;
    public $chrUnit_ID;
    public $chrColor;
    public $chrSize;
    public $chrComment1;
    public $chrComment2;
    public $intCost;
    public $intPrice;
//    public $intTax_Type;
    function Goods($id="", $class="", $code="", $name="", $kana="", $seller="", $maker="", $group="", $unit="", $color="", $size="", $comme1="", $comme2="", $cost="", $price="") {
        $this->chrID = $id;
        $this->chrClass_ID = $class;
        $this->chrCode = $code;
        $this->chrName = $name;
        $this->chrKana = $kana;
        $this->chrSeller_ID = $seller;
        $this->chrMaker_ID = $maker;
        $this->chrGroup_ID = $group;
        $this->chrUnit_ID = $unit;
        $this->chrColor = $color;
        $this->chrSize = $size;
        $this->chrComment1 = $comme1;
        $this->chrComment2 = $comme2;
        $this->intCost = $cost;
        $this->intPrice = $price;
    }

    public static function get_one_empty_goods() {
        return new Goods("", "", "", "", "", "", "", "", "", "", "", "", "", "", "");
    }

    public static function get_all_goods() {
        $connection = new Connection();
        $query = "SELECT * FROM `goods`;";
        $result = $connection->result($query);
        while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $contents[] = new Goods($row['chrID'],
                                    $row['chrClass_ID'],
                                    $row['chrCode'],
                                    $row['chrName'],
                                    $row['chrKana'],
                                    $row['chrSeller_ID'],
                                    $row['chrMaker_ID'],
                                    $row['chrGroup_ID'],
                                    $row['chrUnit_ID'],
                                    $row['chrColor'],
                                    $row['chrSize'],
                                    $row['chrComment1'],
                                    $row['chrComment2'],
                                    $row['intCost'],
                                    $row['intPrice']);
        }
        return $contents;
    }

    public static function get_all_goods_chrID() {
        $connection = new Connection();
        $query = "SELECT `chrID` FROM `goods` ORDER BY `chrID`;";
        $result = $connection->result($query);
        echo mysql_error();
        while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $contents[] = $row['chrID'];
        }
        $connection->close();
        return $contents;
    }

    public static function get_one_goods($chrID) {
        $connection = new Connection();
        $query = "SELECT * FROM `goods` WHERE `chrID`=".$chrID.";";
        $result = $connection->result($query);
        $row = mysql_fetch_array($result, MYSQL_ASSOC);
        $contents = new Goods($row['chrID'],
                                    $row['chrClass_ID'],
                                    $row['chrCode'],
                                    $row['chrName'],
                                    $row['chrKana'],
                                    $row['chrSeller_ID'],
                                    $row['chrMaker_ID'],
                                    $row['chrGroup_ID'],
                                    $row['chrUnit_ID'],
                                    $row['chrColor'],
                                    $row['chrSize'],
                                    $row['chrComment1'],
                                    $row['chrComment2'],
                                    $row['intCost'],
                                    $row['intPrice']);
        $connection->close();
        return $contents;

    }

    public static function insert_one_goods($id, $class, $code, $name, $kana, $seller, $maker, $group, $unit, $color, $size, $comme1, $comme2, $cost, $price) {
	$today = date("Y/m/d");
        $connection = new Connection();
        $query = <<<SQL
INSERT INTO `goods` (`chrID`,
                    `chrClass_ID`,
                    `chrCode`,
                    `chrName`,
                    `chrKana`,
                    `chrSeller_ID`,
                    `chrMaker_ID`,
                    `chrGroup_ID`,
                    `chrUnit_ID`,
                    `chrColor`,
                    `chrSize`,
                    `chrComment1`,
                    `chrComment2`,
                    `intCost`,
                    `intRetailPrice`,
                    `intPrice`,
                    `chrRegisterDate`,
                    `chrUpdateDate`)
VALUES ('$id', '$class', '$code', '$name', '$kana', '$seller', '$maker', '$group', '$unit', '$color', '$size', '$comme1', '$comme2', '$cost', '$price', '$price', '$today', '$today');
SQL;
        $result = $connection->result($query);
        return $result;

    }

    public static function update_one_goods($id, $class, $code, $name, $kana, $seller, $maker, $group, $unit, $color, $size, $comme1, $comme2, $cost, $price) {
	$today = date("Y/m/d");
        $connection = new Connection();
        $query = <<<EOF
UPDATE `goods` SET `chrClass_ID`='$class', `chrCode`='$code', `chrName`='$name', `chrKana`='$kana', `chrSeller_ID`='$seller', `chrMaker_ID`='$maker', `chrGroup_ID`='$group', `chrUnit_ID`='$unit', `chrColor`='$color', `chrSize`='$size', `chrComment1`='$comme1', `chrComment2`='$comme2', `intCost`='$cost', `intRetailPrice`='$price', `intPrice`='$price', `chrUpdateDate`='$today' WHERE `chrID`='$id';
EOF;
        $result = $connection->result($query);
        $connection->close();
        return $result;

    }

    public static function delete_one_goods($chrID) {
        $connection = new Connection();
        $query = "DELETE FROM `goods` WHERE `chrID`=".$chrID.";";
        $result = $connection->result($query);
        echo mysql_error();
        $connection->close();
        return $result;

    }

    public static function get_new_goods() {
        $connection = new Connection();
        $query = "SELECT `chrID` FROM `goods` Where Left(`chrID`,2) ='20' ORDER BY `chrID` DESC LIMIT 0, 1;";
        $result = $connection->result($query);
        $row = mysql_fetch_array($result, MYSQL_ASSOC);
	if ( is_null($row['chrID']) ) {
		$row['chrID']="200000000001" . self::calcJanCodeDigit("200000000001");
	} else {
		$newbar=str_pad(intval(mb_substr($row['chrID'],2,10,"UTF-8"))+1, 10, "0", STR_PAD_LEFT);
		$row['chrID']="20". $newbar . self::calcJanCodeDigit("20". $newbar);
	}
        $contents = new Goods($row['chrID'],'','','','','','','','','','','','','','');
        $connection->close();
        return $contents;
    }

    public static function get_distinct_goods_chrID() {
        $connection = new Connection();
        $query = "SELECT DISTINCT `chrID` FROM `goods` ORDER BY `chrID`;";
        $result = $connection->result($query);
        echo mysql_error();
        while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $contents[] = $row['chrID'];
        }
        $connection->close();
        return $contents;
    }
    public static function get_distinct_class_chrID() {
        $connection = new Connection();
        $query = "SELECT DISTINCT `chrID`,`chrName` FROM `class` ORDER BY `chrID`;";
        $result = $connection->result($query);
        echo mysql_error();
        while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $contents[] = $row['chrID'].":".$row['chrName'];
        }
        $connection->close();
        return $contents;
    }
    private function calcJanCodeDigit($num) {
        $arr = str_split($num);
        $odd = 0;
        $mod = 0;
        for($i=0;$i<count($arr);$i++){
           if(($i+1) % 2 == 0) {
              //偶数の総和
              $mod += intval($arr[$i]);
           } else {
              //奇数の総和
              $odd += intval($arr[$i]);               
           }
        }
        //偶数の和を3倍+奇数の総和を加算して、下1桁の数字を10から引く
        $cd = 10 - intval(substr((string)($mod * 3) + $odd,-1));
        //10なら1の位は0なので、0を返す。
        return $cd === 10 ? 0 : $cd;
    }   
}
