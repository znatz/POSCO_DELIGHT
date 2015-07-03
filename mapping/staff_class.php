<?php

//require_once dirname(__FILE__) . '/../utils/connect.php';
require_once  'connect.php';

class Staff
{

    public $chrID;

    public $chrName;

    public $chrLoginID;

    public $intAuthority_ID;

    public $chrPassword_Hash;

    public $chrSession;

function Staff($id, $name, $loginid, $auth, $pass = "", $session = "")
    {
        $this->chrID = $id;
        $this->chrName = $name;
        $this->chrLoginID = $loginid;
        $this->intAuthority_ID = $auth;
        $this->chrPassword_Hash = $pass;
        $this->chrSession = $session;
    }

    public static function get_all_staff()
    {
        $query = "select * from staff";

        $result = Connection::go_query($query);
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $contents[] = new Staff($row['chrID'], $row['chrName'], $row['chrLogin_ID'], $row['intAuthority_ID']);
        }
        return $contents;
    }

    public static function get_all_staff_chrID()
    {
        $query = "select chrID from staff order by chrID";

        $result = Connection::go_query($query);
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $all_chrID[] = $row['chrID'];
        }
        return $all_chrID;
    }

    public static function update_staff_session($staff)
    {
        session_regenerate_id(true);
        $query = "UPDATE staff SET chrSession='" . session_id() . "' WHERE chrLogin_ID= '" . $staff->chrLoginID . "'";

        Connection::go_query($query);
        $staff->chrSession = session_id();
        return $staff;
    }

    public static function get_one_staff($chrID)
    {

        $query = "select * from staff where chrID=" . $chrID . ";";
        $result = Connection::go_query($query);
        $row = mysql_fetch_array($result, MYSQL_ASSOC);

        $staff = new Staff($row['chrID'], $row['chrName'], $row['chrLogin_ID'], $row['intAuthority_ID'], "***", "****");
        return $staff;

    }

    public static function deletete_one_staff($chrID) {
        $connection = new Connection();
        $query = "delete from staff where chrID=" . $chrID. ";";
        $result = $connection->result($query);
        $connection->close();
        return $result;
    }

    public static function insert_one_staff($chrID, $name, $id, $auth, $pass) {
         $query = <<<EOF
	INSERT INTO staff(chrID, chrName, chrLogin_ID, intAuthority_ID, chrPasswordHash)
	VALUES ('$chrID', '$name', '$id', '$auth', '$pass');
EOF;

        $result = Connection::go_query($query);
        return $result;
    }

    public static function update_one_staff($chrID,$name,$id,$auth,$pass) {
            $query = <<<EOF
UPDATE staff SET chrID='$chrID', chrName='$name', chrLogin_ID='$id', intAuthority_ID='$auth', chrPasswordHash='$pass'
WHERE chrID='$chrID' OR chrName='$name' OR chrLogin_ID='$id';
EOF;
        $result = Connection::go_query($query);
        return $result;
    }

}