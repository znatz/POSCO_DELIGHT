<?php

require_once dirname(__FILE__) . '/../utils/helper.php';
require_once dirname(__FILE__) . '/../utils/connect.php';

class Invoice
{
    public $chrID;
    public $chrShop_ID;
    public $intLine_Count;
    public $chrComment;

    function Invoice($id = "", $shopid = "", $count = "", $comment = "")
    {
        $this->chrID = $id;
        $this->chrShop_ID = $shopid;
        $this->intLine_Count = $count;
        $this->chrComment = $comment;
    }

    public static function get_one_empty_invoice()
    {
        return new Invoice("", "", "", "");
    }

    public static function get_all_invoice()
    {
        $connection = new Connection();
        $query = <<<EOF
SELECT * FROM `invoice` ORDER BY `chrShop_ID` , `intLine_Count`;
EOF;
        $result = $connection->result($query);
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $contents[] = new Invoice($row['chrID'], $row['chrShop_ID'], $row['intLine_Count'], $row['chrComment']);
        }
        return $contents;
    }


    public static function get_one_invoice($id)
    {
        $connection = new Connection();
        $query = <<<EVB
SELECT * FROM `invoice` WHERE `chrID`='$id';
EVB;
        $result = $connection->result($query);
        $row = mysql_fetch_array($result, MYSQL_ASSOC);
        $contents = new Invoice($row['chrID'], $row['chrShop_ID'], $row['intLine_Count'], $row['chrComment']);
        $connection->close();
        return $contents;

    }

    public static function insert_one_invoice($shopid, $count, $comment)
    {
        $connection = new Connection();
        $query = <<<EOF
INSERT INTO `invoice` (`chrShop_ID`, `intLine_Count`, `chrComment`)
VALUES ('$shopid', '$count', '$comment');
EOF;
        $result = $connection->result($query);
        return $result;

    }

    /*
    @param: Shop ID, Line Count, Comment
    */
    public static function update_one_invoice($shopid, $count, $comment)
    {
        $connection = new Connection();
        $query = <<<EOF
UPDATE `invoice` SET `chrShop_ID`='$shopid', `intLine_Count`='$count', `chrComment`='$comment'
WHERE `chrShop_ID`='$shopid' AND `intLine_Count`='$count';
EOF;
        $result = $connection->result($query);
        $connection->close();
        return $result;

    }

    public static function delete_one_invoice($id)
    {
        $connection = new Connection();
        $query = <<<EOF
DELETE FROM `invoice` WHERE `chrID`='$id'
EOF;
        $result = $connection->result($query);
        echo mysql_error();
        $connection->close();
        return $result;

    }
}
