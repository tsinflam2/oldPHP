<?php require_once('../conn/conn.php'); ?>
<?php
  extract($_POST);
  $explode_dest = explode('|', $destination);
  $sql = "UPDATE receivedmessage SET mfNo = ".$explode_dest[0]." WHERE msgNo IN(";
  $sql .= $inclause;
  $sql .= ")";
  //echo $sql;
  mysql_query($sql, $conn) or die (mysql_error());
  header('Location: '.$referrer.'&moveCount='.mysql_affected_rows().'&destination='.$explode_dest[1]);
?>