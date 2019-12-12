<?php
require_once('conn/conn.php');
session_start();
if(isset($_POST['login_form'])) {
	$sql = sprintf("SELECT * FROM Users WHERE loginName = '%s' AND loginPswd = '%s';", $_POST['username'], $_POST['password']);
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	
	echo "test";

	if ($row['isLoggedIn'] == 1) {
		echo "You have logged in the other machine";
	} else {
		if ($row == true) {
			//If the database has the same data with the user input, then set the session variable of "logged" and "userNo"
			//and pass the string via get method(Insert into the url directly) to inform the user he/she logins successfully
			$_SESSION['logged'] = true;
			$_SESSION['userNo'] = $row['userNo'];
			$sql = sprintf("UPDATE Users SET isLoggedIn=1 WHERE loginName = '%s';", $row['loginName']);
			mysql_query($sql);
			$query_string = "Login+Successful";
			header("location:login.php?msg=".$query_string); 
		} else {
			//IF the user type the wrong username or password, then a session variable "log_fail" is declared
			//it is used for the recognition of the login.php
			//and pass the string via get method(Insert into the url directly) to inform the user he/she logins failed
			$_SESSION['log_fail'] = true;
			$query_string = "Login+Failed";
			header("location:login.php?msg=".$query_string); 
		}
	}

}
?>