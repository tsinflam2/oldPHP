<?php require_once('../conn/conn.php'); ?>
<?php
  session_start();
  $userNo = $_SESSION['userNo'];

  if (!empty($_REQUEST['moveToFolder'])) {
	  // Get and show list of possible folders to move message to
	  // Ensure draft folder and folder user is in currently is not shown as an option to move message to
	  $sql = "SELECT mfNo, msName FROM messagefolder WHERE msName NOT IN('Draft','".$_POST['currFolder']."') AND userNo = ".$userNo;
	  $result = mysql_query($sql, $conn) or die (mysql_error());
	  echo '<h2>Move message(s) to folder</h2><form method="post" action="do_move_msg.php"><select name="destination">';
	  while ($row = mysql_fetch_assoc($result))
	  	echo '<option value="'.$row["mfNo"].'|'.$row["msName"].'">'.$row["msName"].'</option>';
	  echo '</select><input type="submit" value="Move" />';
	  $sql_in = '';
	  foreach ($_POST['msgNo'] as $msgNoToMove)
	  	$sql_in .= "'$msgNoToMove',";
	  $sql_in = substr($sql_in,0,strlen($sql_in) -1);
	  echo '<input type="hidden" name="inclause" value="'.$sql_in.'" />';
	  echo '<input type="hidden" name="referrer" value="'.$_SERVER['HTTP_REFERER'].'" />';
	  echo '</form>';
	  
	  mysql_free_result($result);
	
  } else if (!empty($_REQUEST['delSelected'])) {
	  // Delete message
	  // Locate "delete" folder
	  $sql = "SELECT mfNo FROM messagefolder WHERE msName = 'Deleted' AND userNo = ".$userNo;
	  $result = mysql_query($sql, $conn) or die (mysql_error());
	  $result_assoc = mysql_fetch_assoc($result);
	  $deleted_box_mfNo = $result_assoc['mfNo'];
	  
	  // Move message(s) to "delete" folder
	  $sql = "UPDATE receivedmessage SET mfNo = ".$deleted_box_mfNo." WHERE msgNo IN(";
	  foreach ($_POST['msgNo'] as $msgNoToDel)
	  	$sql .= "'$msgNoToDel',";
		$sql = substr($sql,0,strlen($sql) -1);
	  $sql .= ")";
	  //echo $sql;
	  mysql_query($sql, $conn) or die (mysql_error());
	  header('Location: '.$_SERVER['HTTP_REFERER'].'&delCount='.mysql_affected_rows());
	  
  } else if (!empty($_REQUEST['pDelSelected'])) {
	  // Permanently delete message
	  $sql = "DELETE FROM receivedmessage WHERE msgNo IN(";
	  foreach ($_POST['msgNo'] as $msgNoToDel)
	  	$sql .= "'$msgNoToDel',";
		$sql = substr($sql,0,strlen($sql) -1);
	  $sql .= ")";
	 // echo $sql;
	  mysql_query($sql, $conn) or die (mysql_error());
	  header('Location: '.$_SERVER['HTTP_REFERER'].'&pDelCount='.mysql_affected_rows());
  } else if (!empty($_REQUEST['restoreToInbox'])) {
	  // Restore message from "delete" folder to "inbox" folder
	  // Locate "inbox" folder
	  $sql = "SELECT mfNo FROM messagefolder WHERE msName = 'Inbox' AND userNo = ".$userNo;
	  $result = mysql_query($sql, $conn) or die (mysql_error());
	  $result_assoc = mysql_fetch_assoc($result);
	  $inbox_box_mfNo = $result_assoc['mfNo'];
	  
	  // Move message(s) to "delete" folder
	  $sql = "UPDATE receivedmessage SET mfNo = ".$inbox_box_mfNo." WHERE msgNo IN(";
	  foreach ($_POST['msgNo'] as $msgNoToRestore)
	  	$sql .= "'$msgNoToRestore',";
		$sql = substr($sql,0,strlen($sql) -1);
	  $sql .= ")";
	  //echo $sql;
	  mysql_query($sql, $conn) or die (mysql_error());
	  header('Location: '.$_SERVER['HTTP_REFERER'].'&restoreToInbox='.mysql_affected_rows());
  }
  
 
?> 