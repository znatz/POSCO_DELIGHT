<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="./css/blended_layout.css">
<link rel="stylesheet" type="text/css" href="./css/search.css">
<title>POSCO IOS</title>
<style>
    input {
        height: 50px;
    }

    .form_label {
        display: inline-block;
        width:100px;
    }

</style>
<body>
<section>
<form method="POST">
    <fieldset>
    <label class="form_label" for="id"> ID : </label><input type="text" name="id" size="100"/> <br/>
    <label class="form_label" for="name">担当者 :　</label><input type="text" name="name" size="100"/><br/>
    <input type="submit" name="submit" value="追加"/>
    <input type="submit" name="clearTransfer" value="注文記録を消す"/>
    </fieldset>
</form>
<?php
if(isset($_POST["clearTransfer"])) {
    foreach (glob("*BurData.sqlite") as $filename) {
        unlink($filename);
    }
    $db = new PDO('sqlite:DataFromAllIOS.sqlite');

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec("DELETE FROM DataFromAllIOS;");

}
if(isset($_POST["submit"])) {

       $file_db = new PDO('sqlite:Master.sqlite');
       $file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $insert = "INSERT INTO BTAMAS (id, name) VALUES (:id, :name)";
      $stmt = $file_db->prepare($insert);

      $stmt->bindParam(':id', $_POST['id']);
      $stmt->bindParam(':name', $_POST['name']);



    $stmt->execute();

}
showUsers();
function showUsers() {
  try {
      $file_db = new PDO('sqlite:Master.sqlite');
      $file_db->setAttribute(PDO::ATTR_ERRMODE,
          PDO::ERRMODE_EXCEPTION);
      $result = $file_db->query('SELECT * FROM BTAMAS;');

      foreach ($result as $row) {
          echo "<p>" . $row['id'] . " 担当者名 " . $row['name'] . "</p>";
      }
  }
     catch(PDOException $e) {
         echo $e->getMessage();
     }
}
?>
</section>
</body></html>
