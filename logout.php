<?php
require_once('conn/conn.php');
session_start();

		$sql = sprintf("UPDATE Users SET isLoggedIn=0 WHERE userNo = '%s';", $_SESSION['userNo']);
		mysql_query($sql);

	  $_SESSION = array();  // Unset all of the session variables
	  if (isset($_COOKIE[session_name()]))
		setcookie(session_name(), '', time()-4200, '/');  // delete the cookie for session
	  session_destroy();
	  $query_string = sprintf("?msg=%s", urlencode("You have logged out successfully"));
	  header("location:login.php".$query_string); 

?>