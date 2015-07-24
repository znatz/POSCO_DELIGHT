<?php

class iosReceiver extends \BaseController
{
    public static function showtransfer($f)
    {
        $file_db = new PDO('sqlite:' . $f);
        $file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $result = $file_db->query('SELECT * FROM transfer;');
       foreach ($result as $m) {
            $d = new Datafromio();
            $d->tanto = $m["tantoID"];
            $d->goodsTitle = $m["goodsTitle"];
            $d->kosu = $m["kosu"];
            $d->time = $m["time"];
            $d->receiptNo = $m["receiptNo"];
            $d->save();
        }
        unlink($f);
    }

    public function index()
    {
        $file = basename($_FILES['file']['name']);
        $uploadfile = $_POST['tanto'] . $file;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
            echo $file;
        } else {
            echo "error";
        }


        self::showtransfer($uploadfile);

    }

}
