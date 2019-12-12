<?php require_once('../conn/conn.php'); ?>
<?php
	mysql_select_db($database_conn, $conn);
	// Permanently delete message
	$sql = "DELETE FROM messagedraft WHERE draftNo IN(";
	foreach ($_POST['draftNo'] as $draftNoToDel)
	$sql .= "'$draftNoToDel',";
	$sql = substr($sql,0,strlen($sql) -1);
	$sql .= ")";
	mysql_query($sql, $conn) or die (mysql_error());
	header('Location: '.$_SERVER['HTTP_REFERER'].'&pDelDraftCount='.mysql_affected_rows());
?>