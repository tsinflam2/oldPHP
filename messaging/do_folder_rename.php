<?php require_once('../conn/conn.php'); ?>
<?php
	extract($_POST);
	if (!isset($newname) || empty(trim($newname)))
		die("<script>alert('Please enter a new name for the folder!');window.location.href='msg_folders_manage.php';</script>");
	$sql = "UPDATE messagefolder SET msName = '$newname' WHERE mfNo = $folder";
	mysql_query($sql, $conn) or die (mysql_error());
	echo "<script>alert('The folder has been renamed successfully!');window.open('msg.php', '_parent');</script>";
?>