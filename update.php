<div id="bodyPan" style="text-align:center;">
<?php include('header.php'); ?>
<?php
	require_once('conn/conn.php');
	
	//Message is for "Please login first"
	if(isset($_GET['msg'])) {
		echo $_GET['msg'];
	}
	
	if (!isset($_SESSION['userNo'])) {
		echo "Please Login First";
	} 
?>
<?php
if (isset($_SESSION['userNo'])) {
			
			$sql = sprintf("SELECT * FROM Users WHERE userNo = '%s'", $_SESSION['userNo']);
			$result = mysql_query($sql);
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			
		$html=<<<EOFF
			<div style="text-align:center;" id="A">
			<form action="update_validate.php" method="POST" enctype="multipart/form-data">
			<h1 style="text-align:center;font-family:Calibri;color:darkred">Update System</h1>
			<p><label>Change Your Full Name: </label><input type="text" name="username" id="username" value="%s"/></p><br/>
EOFF;

		echo sprintf($html,$row['userName']);
		
		if ($row['userGender'] == "M" || $row['userGender'] == "m") {
			echo '<p><label>Change Your Gender: </label><input type="radio" name="gender" value="M" CHECKED>Male<input type="radio" name="gender" value="F">Female</p><br/>';
		} else {
			echo '<p><label>Change Your Gender: </label><input type="radio" name="gender" value="M" >Male<input type="radio" name="gender" value="F" CHECKED>Female</p><br/>';
		}
		$html=<<<EOF
			<p><label>Change Your Password: </label><input type="password" name="password" id="password"/></p><br/>
			<p><label>Confirm Changed Password: </label><input type="password" name="confirm_password" id="confirm_password" /></p><br/>
			<p><label>Change Photo: </label><input type="file" name="photo" id="photo" /></p><br/>
EOF;

		echo $html;
		$html=<<<EOKK
			<label>Your Current Photo: </label><img src="%s"></img>
EOKK;
		if ($row['memNo'] !=null) {
			echo sprintf($html,"images/upload/member/".$row['userPhoto']);
		} else if($row['insNo'] !=null) {
			echo sprintf($html,"images/upload/instructor/".$row['userPhoto']);
		}
		$html=<<<EOLL
			<p><input type="submit" name="update" id="update" value="Update" />
			<input type="reset" name="reset" id="reset" value="Reset" /> </p>
			<input type="hidden" name="MAX_FILE_SIZE">
			<input type="hidden" name="update_form">
			</form>
			</div>
EOLL;
		echo $html;
		


		
	}
?>
</div>
<?php include('footer.php'); ?>