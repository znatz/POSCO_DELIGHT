<?php

require_once dirname(__FILE__).'/../utils/helper.php';
require_once dirname(__FILE__).'/../utils/connect.php';

class Maker {
    public $chrID;
    public $chrName;
    public $chrShort_Name;
	
    function Maker($id="", $name="", $sname="") {
        $this->chrID = $id;
        $this->chrName = $name;
        $this->chrShort_Name = $sname;
    }

    public static function get_one_empty_maker() {
        return new Maker("", "", "");
    }

    public static function get_all_maker() {
        $connection = new Connection();
        $query = "SELECT * FROM `maker`;";
        $result = $connection->result($query);
        while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $contents[] = new Maker($row['chrID'],
                                     $row['chrName'],
                                     $row['chrShort_Name']);
        }
        return $contents;
    }

    public static function get_all_maker_chrID() {
        $connection = new Connection();
        $query = "SELECT `chrID` FROM `maker` ORDER BY `chrID`;";
        $result = $connection->result($query);
        while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $contents[] = $row['chrID'];
        }
        $connection->close();
        return $contents;
    }

    public static function get_one_maker($chrID) {
        $connection = new Connection();
        $query = "SELECT * FROM `maker` WHERE `chrID`=".$chrID.";";
        $result = $connection->result($query);
        $row = mysql_fetch_array($result, MYSQL_ASSOC);
        $contents = new Maker($row['chrID'],
                               $row['chrName'],
                               $row['chrShort_Name']);
        $connection->close();
        return $contents;

    }

    public static function insert_one_maker($id, $name, $sname) {
        $connection = new Connection();
        $query = <<<SQL
INSERT INTO `maker` (`chrID`,
                    `chrName`,
                    `chrShort_Name`)
VALUES ('$id', '$name', '$sname');
SQL;
        $result = $connection->result($query);
        return $result;

    }

    public static function update_one_maker($id, $name, $sname) {
        $connection = new Connection();
        $query = <<<EOF
UPDATE `maker` SET `chrName`='$name', `chrShort_Name`='$sname'
WHERE `chrID`='$id';
EOF;
        $result = $connection->result($query);
        $connection->close();
        return $result;

    }

    public static function delete_one_maker($chrID) {
        $connection = new Connection();
        $query = "DELETE FROM `maker` WHERE `chrID`=".$chrID.";";
        $result = $connection->result($query);
        $connection->close();
        return $result;

    }

    public static function get_new_maker() {
        $id = get_lastet_3_number(self::get_all_maker_chrID());
        $result = new Maker($id,"","");
        return $result;
    }

    public static function get_distinct_maker_chrID() {
        $connection = new Connection();
        $query = "SELECT DISTINCT `chrID` FROM `maker` ORDER BY `chrID`;";
        $result = $connection->result($query);
        while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $contents[] = $row['chrID'];
        }
        $connection->close();
        return $contents;
    }
}
