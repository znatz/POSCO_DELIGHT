<?php

require_once 'ConstantDb.php';

class Connection {
	public $link; 
	function Connection() {
		$this->link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());

		mysql_query('SET NAMES SJIS');
		mysql_query('SET LC_MESSAGES =  "ja_JP"');
		mysql_set_charset('utf8');
		mysql_select_db(DB_NAME, $this->link);

		$bool = mysql_select_db(DB_NAME, $this->link);
		if ($bool === False) {
			print "DB_NAME存在しません。";
		}

	}

	public function result($query) {
		$result = mysql_query($query);
		if(mysql_error()) return null;
		return $result;
	}
	
	public function close() {
		mysql_close($this->link);
	}

    public static function go_query($query) {
        $conn = new Connection();
        return $conn->result($query);
    }

    public static function get_all_from_table($table_name) {
        $query = "SELECT * FROM ".strtolower($table_name);
        $result = self::go_query($query);
        while ($row = mysql_fetch_object($result, $table_name)) {
            $contents[] = $row;
        }
        print_r($contents);
        return $contents;
    }
}
