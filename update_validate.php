<div id="bodyPan" style="text-align:center;">
<?php include('header.php'); ?>

<?php
		require_once('conn/conn.php');
		
	if (isset($_POST['update_form'])) {
		if ($_FILES['photo']['size'] < $_POST['MAX_FILE_SIZE']) {
			//**************For Upload Photo***************
			$uploaddir = 'images/upload/member/';
			$uploadfile = $uploaddir . basename($_FILES['photo']['name']);
			
				
			if (!file_exists($uploadfile)) {
				
				if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadfile)) {
					echo "File is valid, and was successfully uploaded.\n";
				} else {
					echo "Possible file upload attack!\n";
				}
			}
		}
	
		$username = $_POST['username'];
		if ($_POST['gender'] == "M" || $_POST['gender'] == "m") {
			$gender = "M";
			} else if($_POST['gender'] == "F" || $_POST['gender'] == "f") {
			$gender = "F";
			}
		$password = $_POST['password'];
		$photo_name = basename($_FILES['photo']['name']);
		$sql = sprintf("UPDATE Users SET userName='%s', loginPswd='%s', userGender='%s', userPhoto='%s' WHERE userNo=%s;", $username, $password, $gender, $photo_name , $_SESSION['userNo']);
		mysql_query($sql);
		
	
	}
	

?>
<?php include('footer.php'); ?>
