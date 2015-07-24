<html><body>
<form action="" method="post" enctype="multipart/form-data">
<input type="file" name="fromIOS" />
<input type="submit" value="Send" />
</p>
</form></body></html>
<?php

   $file = basename($_FILES['file']['name']);
    $uploadfile = $_POST['tanto'].$file;

    echo "file=".$file;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
        echo $file;
    }
    else {
        echo "error";
    }




function showtransfer($f) {
  try {
      $destination = new PDO('sqlite:DataFromAllIOS.sqlite');
      $destination->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $file_db = new PDO('sqlite:'.$f);
      $file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $result = $file_db->query('SELECT * FROM transfer;');


      foreach ($result as $m) {
          $qry = $destination->prepare( 'INSERT INTO DataFromAllIOS VALUES (?, ?, ?, ?, ?)');
          $qry->execute(array($m['tantoID'], $m['goodsTitle'], $m['kosu'], $m['time'], $m['receiptNo']));
      }
  }
     catch(PDOException $e) {
         echo $e->getMessage();
     }
}
/*
foreach (glob("*BurData.sqlite") as $filename) {
    showtransfer($filename);
}
*/
showtransfer($uploadfile);

?>
