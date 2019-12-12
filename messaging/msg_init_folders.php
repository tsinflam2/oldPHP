<?php
  /*
  	Author: Yick Tsz Ho
	Description: Run this once when new user is created.
  */	   
  require_once('conn/conn.php');
?>
<?php
  mysql_select_db($database_conn, $conn);
  // Newly registered user's userNo is stored in $userNo

  
  // Check if default message folders are created
  $sql = "SELECT COUNT(*) AS count FROM messagefolder WHERE userNo = $userNo";
  $result = mysql_query($sql, $conn) or die (mysql_error());
  $count = (mysql_fetch_assoc($result));
  if ($count['count'] == 3)
  	exit();
  
  // If no, create default message folders
  $folders = array('Inbox', 'Draft', 'Deleted');
  foreach ($folders as $folder) {
	$sql = "INSERT INTO messagefolder (msName, userNo) VALUES ('".$folder."', $userNo)";
	mysql_query($sql, $conn) or die (mysql_error());
  }
?>