<?php
session_start();



	if (isset($_POST['logout'])) {
	  $_SESSION = array();  // Unset all of the session variables
	  if (isset($_COOKIE[session_name()]))
		setcookie(session_name(), '', time()-4200, '/');  // delete the cookie for session
	  session_destroy();
	  $query_string = sprintf("?msg=%s", urlencode("You have logged out successfully"));
	  header("location:login.php".$query_string); 
	}

/*
	if (isset($_SESSION['logged'])) {
		$html_logout_form = <<<EOD
			<input type="submit" name="logout" value="Log Out" />
		</form>
EOD;
	$php_self = $_SERVER['PHP_SELF'];
	echo "<form action=\"$php_self\" method=\"POST\">";

	echo "$html_logout_form ";
	echo "User ID: " . $_SESSION['userNo'];
	} else {
		echo "You have to login first!";
	}
	*/
	
?>