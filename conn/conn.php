<?php
$hostname_conn = "127.0.0.1";
$database_conn = "projectDB";
$username_conn = "root";
$password_conn = "";
$conn = mysql_pconnect($hostname_conn, $username_conn, $password_conn) or die(mysql_error());
$db = mysql_select_db($database_conn, $conn) or die(mysql_error());

?>