<?php

require_once dirname(__FILE__).'/../utils/helper.php';
require_once dirname(__FILE__).'/../utils/connect.php';

class Group {
    public $chrID;
    public $chrName;
    public $intCost_Rate;
    public $chrCategory_ID;
    public $intTax_Rate;
    function Group($id="", $name="", $cost="", $catid="", $tax="") {
        $this->chrID = $id;
        $this->chrName = $name;
        $this->intCost_Rate = $cost;
        $this->chrCategory_ID = $catid;
        $this->intTax_Rate = $tax;
    }

    public static function get_one_empty_group() {
        return new Group("", "", "", "","");
    }

    public static function get_all_group() {
        $connection = new Connection();
        $query = "SELECT * FROM `group`;";
        $result = $connection->result($query);
        while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $contents[] = new Group($row['chrID'],
                                    $row['chrName'],
                                    $row['intCost_Rate'],
                                    $row['chrCategory_ID'],
                                    $row['intTax_Rate']);
        }
        return $contents;
    }

    public static function get_all_group_chrID() {
        $connection = new Connection();
        $query = "SELECT `chrID` FROM `group` ORDER BY `chrID`;";
        $result = $connection->result($query);
        echo mysql_error();
        while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $contents[] = $row['chrID'];
        }
        $connection->close();
        return $contents;
    }

    public static function get_one_group($chrID) {
        $connection = new Connection();
        $query = "SELECT * FROM `group` WHERE `chrID`=".$chrID.";";
        $result = $connection->result($query);
        $row = mysql_fetch_array($result, MYSQL_ASSOC);
        $contents = new Group($row['chrID'],
                                    $row['chrName'],
                                    $row['intCost_Rate'],
                                    $row['chrCategory_ID'],
                                    $row['intTax_Rate']);
        $connection->close();
        return $contents;

    }

    public static function insert_one_group($id, $name, $cost, $catid, $tax) {
        $connection = new Connection();
        $query = <<<SQL
INSERT INTO `group` (`chrID`,
                    `chrName`,
                    `intCost_Rate`,
                    `chrCategory_ID`,
                    `intTax_Rate`)
VALUES ('$id', '$name', '$cost', '$catid', '$tax');
SQL;
        $result = $connection->result($query);
        return $result;

    }

    public static function update_one_group($id, $name, $cost, $catid, $tax) {
        $connection = new Connection();
        $query = <<<EOF
UPDATE `group` SET `chrName`='$name', `intCost_Rate`='$cost', `chrCategory_ID`='$catid', `intTax_Rate`='$tax'
WHERE `chrID`='$id';
EOF;
        $result = $connection->result($query);
        $connection->close();
        return $result;

    }

    public static function delete_one_group($chrID) {
        $connection = new Connection();
        $query = "DELETE FROM `group` WHERE `chrID`=".$chrID.";";
        $result = $connection->result($query);
        echo mysql_error();
        $connection->close();
        return $result;

    }

    public static function get_new_group() {
        $id = get_lastet_number(self::get_all_group_chrID());
        $result = new Group($id,"","","","");
        return $result;
    }

    public static function get_distinct_group_chrID() {
        $connection = new Connection();
        $query = "SELECT DISTINCT `chrID`, `chrName` FROM `group` ORDER BY `chrID`;";
        $result = $connection->result($query);
        echo mysql_error();
        while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $contents[] = array($row['chrID'], $row['chrName']);
        }
        $connection->close();
        return $contents;
    }
}
