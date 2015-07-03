<?php

require_once dirname(__FILE__).'/../utils/helper.php';
require_once dirname(__FILE__).'/../utils/connect.php';

class Priceband {
    public $chrID;
    public $chrName;
    public $intUnder_Bound;
    public $intUpper_Bound;
    function Priceband($id="", $name="", $under="", $upper="") {
        $this->chrID = $id;
        $this->chrName = $name;
        $this->intUnder_Bound = $under;
        $this->intUpper_Bound = $upper;
    }

    public static function get_one_empty_priceband() {
        return new Priceband("", "", "", "");
    }

    public static function get_all_priceband() {
        $connection = new Connection();
        $query = "SELECT * FROM `priceband` ;";
        $result = $connection->result($query);
        while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $contents[] = new Priceband($row['chrID'], $row['chrName'], $row['intUnder_Bound'], $row['intUpper_Bound']);
        }
        return $contents;
    }

    public static function get_all_priceband_chrID() {
        $connection = new Connection();
        $query = "SELECT `chrID` FROM `priceband` ORDER BY `chrID`;";
        $result = $connection->result($query);
        echo mysql_error();
        while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $contents[] = $row['chrID'];
        }
        $connection->close();
        return $contents;
    }

    public static function get_one_priceband($chrID) {
        $connection = new Connection();
        $query = "SELECT * FROM `priceband` WHERE `chrID`=".$chrID.";";
        $result = $connection->result($query);
        $row = mysql_fetch_array($result, MYSQL_ASSOC);
        $contents = new Priceband($row['chrID'],
                                    $row['chrName'],
                                    $row['intUnder_Bound'],
                                    $row['intUpper_Bound']);
        $connection->close();
        return $contents;

    }

    public static function insert_one_priceband($id, $name, $under, $upper) {
        $connection = new Connection();
        $query = <<<SQL
INSERT INTO `priceband` (`chrID`,
                    `chrName`,
                    `intUnder_Bound`,
                    `intUpper_Bound`)
VALUES ('$id', '$name', '$under', '$upper');
SQL;
        $result = $connection->result($query);
        return $result;

    }

    public static function update_one_priceband($id, $name, $under, $upper) {
        $connection = new Connection();
        $query = <<<EOF
UPDATE `priceband` SET `chrName`='$name', `intUnder_Bound`='$under', `intUpper_Bound`='$upper'
WHERE `chrID`='$id';
EOF;
        $result = $connection->result($query);
        $connection->close();
        return $result;

    }

    public static function delete_one_priceband($chrID) {
        $connection = new Connection();
        $query = "DELETE FROM `priceband` WHERE `chrID`=".$chrID.";";
        $result = $connection->result($query);
        echo mysql_error();
        $connection->close();
        return $result;

    }

    public static function get_new_priceband() {
        $id = get_lastet_number(self::get_all_priceband_chrID());
        $result = new Priceband($id,"","","","");
        return $result;
    }

    public static function get_distinct_priceband_chrID() {
        $connection = new Connection();
        $query = "SELECT DISTINCT `chrID` FROM `priceband` ORDER BY `chrID`;";
        $result = $connection->result($query);
        echo mysql_error();
        while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $contents[] = $row['chrID'];
        }
        $connection->close();
        return $contents;
    }
}
