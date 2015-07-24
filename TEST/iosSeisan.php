<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta http-equiv="refresh" content="3" />
<link rel="stylesheet" type="text/css" href="./css/blended_layout.css">
<link rel="stylesheet" type="text/css" href="./css/search.css">
<script src="../js/jquery-1.11.3.min.js" type="text/javascript"></script>
<title>POSCO IOS</title>
<script type="text/javascript">
        jQuery(document).ready(function () {
            setTimeout(function(){
                window.location.reload(1);
            }, 3000);
        });
</script>
<body>
<?php

function showtransfer($f) {
  try {
      $file_db = new PDO('sqlite:'.$f);
      $file_db->setAttribute(PDO::ATTR_ERRMODE,
          PDO::ERRMODE_EXCEPTION);
      $result = $file_db->query('SELECT * FROM DataFromAllIOS;');

      echo '<table>';
      echo '<tr><th>担当ID</th><th>商品名</th><th>個数</th><th>時間</th><th>レシート番号</th>';
      foreach ($result as $row) {
          echo '<tr>';
          echo '<td>' . $row['tantoID']. '</td>';
          echo '<td>' . $row['goodsTitle']. '</td>';
          echo '<td>' . $row['kosu']. '</td>';
          echo '<td>' . $row['time']. '</td>';
          echo '<td>' . $row['receiptNo']. '</td>';
          echo '</tr>';
      }
      echo '<table>';
  }
     catch(PDOException $e) {
         echo $e->getMessage();
     }
}

foreach (glob("*DataFromAllIOS.sqlite") as $filename) {
    showtransfer($filename);
}
?>
</body></html>
