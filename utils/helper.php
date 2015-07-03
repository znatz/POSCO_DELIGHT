<?php

require_once 'connect.php';
function get_lastet_number($sorted_ary)
{
    $i = 1;
    foreach ((array)$sorted_ary as $chrID => $ID_value) {
        $two_digits = str_pad($i, 2, "0", STR_PAD_LEFT);
        if ($ID_value != $two_digits) return $two_digits;
        $i++;
    }
    return str_pad($i, 2, "0", STR_PAD_LEFT);
}
function get_lastet_3_number($sorted_ary)
{
    $i = 1;
    foreach ((array)$sorted_ary as $chrID => $ID_value) {
        $three_digits = str_pad($i, 3, "0", STR_PAD_LEFT);
        if ($ID_value != $three_digits) return $three_digits;
        $i++;
    }
    return str_pad($i, 3, "0", STR_PAD_LEFT);
}

function session_check()
{
    $connection = new Connection();
    $query = "SELECT * FROM staff WHERE chrSession = '" . session_id() . "'";
    $result = $connection->result($query);
    $row = mysql_fetch_array($result, MYSQL_ASSOC);
    if (!$row) {
        $connection->close();
        header("Location: ./logout.php?LOGOUT_MSG=強制に");
    }
}

function plain_url_to_link($string)
{
    return preg_replace(
        '%(https?|ftp)://([-A-Z0-9./_*?&;=#]+)%i',
        '<a target="blank" rel="nofollow" href="$0" target="_blank">$0</a>', $string);
}

function get_post($post) {
    $connection = new Connection();
    $query = "select * from post Where chrID='" . $post ."';";
    $result = $connection->result($query) or die("SQL Error 1: " . mysql_error());
    $rowCount = mysql_num_rows($result);
    if ($rowCount > 0) {
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	$address = $row['chrPrefecture'] . $row['chrAddress'];
    } else {
    }
    $connection->close();
    return $address;
}
