<?php require_once('../conn/conn.php'); ?>
<h3>Manage Custom-created Folders</h3>
<?php
	// Get list of custom folders
	mysql_select_db($database_conn, $conn);
	// userNo of message sender
	session_start();
	$userNo = $_SESSION['userNo'];
	$sql = "SELECT mfNo, msName FROM messagefolder WHERE userNo = $userNo AND msName NOT IN('Inbox', 'Draft', 'Deleted')";
	$result = mysql_query($sql, $conn) or die (mysql_error());
	if (mysql_num_rows($result) == 0)
		die('You need to have at least one folder created by yourself.');
	$folder_arr = array();
	while ($result_assoc = mysql_fetch_assoc($result))
		$folder_arr[array_shift($result_assoc)] = $result_assoc;
?>
<h4>Rename Folder</h4>
<form action="do_folder_rename.php" method="post">
<p>Rename: <select name="folder">
<?php
	foreach ($folder_arr as $key => $value) {
		echo '<option value="'.$key.'">'.$folder_arr["$key"]['msName'].'</option>';
	}
?>
</select>
to <input type="text" name="newname" /></p>
<input type="submit" value="OK" />
</form>
<hr />
<h4>Delete Folder</h4>
<form action="do_folder_delete.php" method="post">
Delete: <select name="folder">
<?php
	foreach ($folder_arr as $key => $value) {
		echo '<option value="'.$key.'">'.$folder_arr["$key"]['msName'].'</option>';
	}
?>
</select><br />
<p><label><input type="radio" name="type" value="deleteinside" checked="checked" />Remove all messages inside that folder.</label><br />
<label><input type="radio" name="type" value="toinbox" />Move all messages in that folder to Inbox.</label></p>

<input type="submit" value="OK" />
</form>