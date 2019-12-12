<?php require_once('../conn/conn.php'); ?>
<?php
  mysql_select_db($database_conn, $conn);
  extract($_POST);
  // userNo of message sender
  session_start();
  $userNo = $_SESSION['userNo'];
  
  $toUser = $_POST['to'];

  // Get receipient userNo using loginName
  $sql = "SELECT userNo FROM Users WHERE loginName="."'".$toUser."'";
  $result = mysql_query($sql, $conn) or die (mysql_error());
  $result_assoc = mysql_fetch_assoc($result);
  $to_userNo = $result_assoc['userNo'];
 
  if (empty($to_userNo))
	exit('Receipient does not exist.');

  if (!empty($_REQUEST['send'])) {
	// Send message
	// Insert message record
	$sql = "INSERT INTO message (msgDateTime, msgSubject, msgContent, msgFrm) VALUES (now(), '$subject', '$content', $userNo)";
	mysql_query($sql, $conn) or die (mysql_error());
	$msgno_insert_id = mysql_insert_id($conn);
	
	// Get recepient's Inbox folder mfNo
	$sql = "SELECT mfNo FROM messagefolder WHERE userNo = $to_userNo AND msName = 'Inbox'";
	$result = mysql_query($sql, $conn) or die (mysql_error());
	$result_assoc = (mysql_fetch_assoc($result));
	$inboxmfNo = $result_assoc['mfNo'];
	
	// Insert received message record, put message in recepient's Inbox folder
	$sql = "INSERT INTO receivedmessage (msgNo, msgTo, mfNo, msgStatus) VALUES ($msgno_insert_id, $to_userNo, $inboxmfNo, 'unread')";
	mysql_query($sql, $conn) or die (mysql_error());
	//header("Location: msg_incoming.php?sent=done");
	echo "<script>alert('Message sent!');window.location.href='msg_incoming.php';</script>";
  } else if (!empty($_REQUEST['draft'])) {
	// Save message as draft
	$sql = "INSERT INTO messagedraft (msgSubject, msgContent, msgFrm, msgTo) VALUES ('$subject', '$content', $userNo, $to_userNo)";  
	mysql_query($sql, $conn) or die (mysql_error());
	echo "<script>alert('Message saved to Draft!');window.location.href='msg_incoming.php';</script>";
  }
?>