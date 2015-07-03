<?php

require_once dirname(__FILE__).'/../utils/helper.php';
require_once dirname(__FILE__).'/../utils/connect.php';

class Seller {
    public $chrID;
    public $chrName;
    public $chrShort_Name;
    public $chrPos;
    public $chrAddress;
    public $chrAddress_No;
    public $chrTel;
    public $chrFax;
    public $chrStaff;
    function Seller($id="", $name="", $shortnm="", $pos="", $add="", $addno="", $tel="", $fax="", $staff="") {
        $this->chrID = $id;
        $this->chrName = $name;
        $this->chrShort_Name = $shortnm;
        $this->chrPos = $pos;
        $this->chrAddress = $add;
        $this->chrAddress_No = $addno;
        $this->chrTel = $tel;
        $this->chrFax = $fax;
        $this->chrStaff = $staff;
    }

    public static function get_one_empty_seller() {
        return new Seller("", "", "", "","", "", "", "", "");
    }

    public static function get_all_seller() {
        $connection = new Connection();
        $query = "SELECT * FROM `seller`;";
        $result = $connection->result($query);
        while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $contents[] = new Seller($row['chrID'],
                                     $row['chrName'],
                                     $row['chrShort_Name'],
                                     $row['chrPos'],
                                     $row['chrAddress'],
                                     $row['chrAddress_No'],
                                     $row['chrTel'],
                                     $row['chrFax'],
                                     $row['chrStaff']);
        }
        return $contents;
    }

    public static function get_all_seller_chrID() {
        $connection = new Connection();
        $query = "SELECT `chrID` FROM `seller` ORDER BY `chrID`;";
        $result = $connection->result($query);
        echo mysql_error();
        while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $contents[] = $row['chrID'];
        }
        $connection->close();
        return $contents;
    }
    public static function get_distinct_seller_chrID() {
        $connection = new Connection();
        $query = "SELECT DISTINCT `chrID` FROM `seller` ORDER BY `chrID`;";
        $result = $connection->result($query);
        echo mysql_error();
        while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $contents[] = $row['chrID'];
        }
        $connection->close();
        return $contents;
    }
    public static function search_chrID_chrName_by_word($word){
        $connection = new Connection();
        $query = <<<EOF
SELECT `chrID`,`chrName` FROM `seller` WHERE `chrID` LIKE '%$word%' ORDER BY `chrID`;
EOF;
        $result = $connection->result($query);
        echo mysql_error();
        $htmlString = "";
        while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
//            $contents[] = array($row['chrID'],$row['chrName']);
            $htmlString .= sprintf("仕入先コード：%d 仕入先名：%s", $row['chrID'], $row['chrName']);
        }
        $connection->close();
//        return $contents;
        return $htmlString;
    }

    public static function get_one_seller($chrID) {
        $connection = new Connection();
        $query = "SELECT * FROM `seller` WHERE `chrID`=".$chrID.";";
        $result = $connection->result($query);
        $row = mysql_fetch_array($result, MYSQL_ASSOC);
        $contents = new Seller($row['chrID'],
                               $row['chrName'],
                               $row['chrShort_Name'],
                               $row['chrPos'],
                               $row['chrAddress'],
                               $row['chrAddress_No'],
                               $row['chrTel'],
                               $row['chrFax'],
                               $row['chrStaff']);
        $connection->close();
        return $contents;

    }

    public static function insert_one_seller($id, $name, $shortnm, $pos, $add, $addno, $tel, $fax, $staff) {
        $connection = new Connection();
        $query = <<<SQL
INSERT INTO `seller` (`chrID`,
                      `chrName`,
                      `chrShort_Name`,
                      `chrPos`,
                      `chrAddress`,
                      `chrAddress_No`,
                      `chrTel`,
                      `chrFax`,
                      `chrStaff`)
VALUES ('$id', '$name', '$shortnm', '$pos', '$add', '$addno', '$tel', '$fax', '$staff');
SQL;
        $result = $connection->result($query);
        return $result;

    }

    public static function update_one_seller($id, $name, $shortnm, $pos, $add, $addno, $tel, $fax, $staff) {
        $connection = new Connection();
        $query = <<<EOF
UPDATE `seller` SET `chrName`='$name', `chrShort_Name`='$shortnm', `chrPos`='$pos', `chrAddress`='$add', `chrAddress_No`='$addno', `chrTel`='$tel', `chrFax`='$fax', `chrStaff`='$staff'
WHERE `chrID`='$id';
EOF;
        $result = $connection->result($query);
        $connection->close();
        return $result;

    }

    public static function delete_one_seller($chrID) {
        $connection = new Connection();
        $query = "DELETE FROM `seller` WHERE `chrID`=".$chrID.";";
        $result = $connection->result($query);
        echo mysql_error();
        $connection->close();
        return $result;

    }

    public static function get_new_seller() {
        $id = get_lastet_3_number(self::get_all_seller_chrID());
        $result = new Seller($id,"","","","","","","","");
        return $result;
    }
}
