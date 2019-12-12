<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Fitness Center</title>
<link href="/webasgm/css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="topPan">
<div id="lr" style="float:right;">
<?php 	
session_start();
if(!isset($_SESSION['logged'])) {
	 echo '<a href="/webasgm/login.php">Login</a>'; 
	 echo "|";
	 echo '<a href="register.php">Register</a>';
	 }
if(isset($_SESSION['logged'])) {
	 echo '<a href="/webasgm/logout.php">Logout</a>';
	 echo "|";
	 echo '<a href="update.php">Personal Profile</a>';
	 } 

?>

</div>
  <div id="ImgPan"><a href="index.html"><img src="/webasgm/images/logo.gif" title="Coporate Profiles" alt="Coporate Profiles" width="201" height="52" border="0" /></a> </div>
  <ul>
		<li class="home">home</li>
		<li><a href="#">about us</a></li>
		<li><a href="#">support</a></li>
		<li><a href="#">solutions</a></li>
		<li><a href="#">testimonials</a></li>
		<li><a href="/webasgm/messaging/msg.php">Messages</a></li>
  </ul>	
</div>
<br />