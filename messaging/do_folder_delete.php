<?php require_once('../conn/conn.php'); ?>
<?php
	session_start();
	$userNo = $_SESSION['userNo'];
	extract($_POST);
	if ($type == 'deleteinside') {
		// Delete folder and all messages inside it
		$sql = "DELETE FROM receivedmessage WHERE mfNo = $folder";
		mysql_query($sql, $conn) or die (mysql_error());
		$sql = "DELETE FROM messagefolder WHERE mfNo = $folder";
		mysql_query($sql, $conn) or die (mysql_error());
		echo "<script>alert('The folder and all messages inside it have been deleted.');window.open('msg.php', '_parent');</script>";
	} else if ($type == 'toinbox') {
		// Delete folder and move all messages inside it to Inbox
		// Find out user inbox folder id	
		$sql = "SELECT mfNo FROM messagefolder WHERE userNo = $userNo AND msName = 'Inbox'";
		$result = mysql_query($sql, $conn) or die (mysql_error());
		$result_assoc = mysql_fetch_assoc($result);
		$inbox_no = $result_assoc['mfNo'];
		// Move messages to inbox folder
		$sql = "UPDATE receivedmessage SET mfNo = $inbox_no WHERE mfNo = $folder";
		mysql_query($sql, $conn) or die (mysql_error());
		// Delete folder
		$sql = "DELETE FROM messagefolder WHERE mfNo = $folder";
		mysql_query($sql, $conn) or die (mysql_error());
		echo "<script>alert('The folder has been deleted and messages inside it have been moved to Inbox.');window.open('msg.php', '_parent');</script>";
	}
?>