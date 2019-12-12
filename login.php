<div id="bodyPan" style="text-align:center;">

<?php include('header.php'); ?>
<h1 style="text-align:center;font-family:Calibri;color:darkred">Login System</h1>
<script type="text/javascript" src="RRDE.js"></script>
<script type="text/javascript">
	//Check if the string is blank via regular expression(for the internal function use)
	function isBlank(str) {
		return (!str || /^\s*$/.test(str));
	}

	//Check if login name and password valid, if yes, then send the form, if not, then alert the user
	function check() {
		var flag = true;
		var username = document.getElementById('username').value;
		var password = document.getElementById('password').value;
		if (isBlank(username) || isBlank(password)) {
			if (isBlank(username)) {
				alert("username invalid!");
				flag = false;
			} else if (isBlank(password)) {
				alert("password invalid!");
				flag = false;
			}
		}
		if (flag == true) {
			document.forms["submit_form"].submit();
		}
	}
	
	function redirect_to_register() {
		window.location = "register.php";
	}

</script>
</head>
<?php
	require_once('conn/conn.php');

	
	//If the user has logged out, show the message with "You have logged out successfully "
	//If the user login fail, show the message with "Login Fail"
	if(isset($_GET['msg'])) {
		echo $_GET['msg'];
	} 
	
	
	if(!isset($_SESSION['userNo'])) {
	
			$html_login =<<<EOF

					<body>
					<form id="submit_form" action="login_validate.php" method="POST">
					<br />
					<p>
					<label>Username:</label>
					<input type="text" name="username" id="username" />
					</p>
					<br />

					<p>
					<label>Password:</label>
					<input type="password" name="password" id="password" />
					</p>
					<br />
					
					<p>
					<input type="button" value="Login" name="login" id="login" onClick="check();"/>
					<input type="button" value="Register" name="login" id="login" onClick="redirect_to_register();"/>
					</p>
					<input type="hidden" value="" name="login_form" id="login_form" />
					</form>
EOF;
				echo "$html_login";
} else {
	//do nothing
}
?>
</div>
<?php include('footer.php'); ?>
