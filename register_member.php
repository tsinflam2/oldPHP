<?php include('header.php'); ?>
<div id="bodyPan" style="text-align:center;">
<?php
	require_once('conn/conn.php');


		if(isset($_POST['reg_mem_form_hidden'])) {
			if (!empty($_POST['text_SUsername']) && !empty($_POST['text_SLoginnames']) && !empty($_POST['text_SPassword']) && !empty($_POST['gender']) && !empty($_POST['text_SInterest'])) {
				if ($_FILES['photo']['size'] < $_POST['MAX_FILE_SIZE']) {
					$uploaddir = 'images/upload/member/';
					$uploadfile = $uploaddir . basename($_FILES['photo']['name']);
					
					if (!file_exists($uploadfile)) {

						if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadfile)) {
							echo "File is valid, and was successfully uploaded.\n";
						} else {
							echo "Possible file upload attack!\n";
							echo '<br>ERROR MESSAGE:';
							print_r($_FILES);
						}
				
						//Insert data to the table of member, and retrieve the memNo
						$sql = sprintf("INSERT INTO member VALUES (null, '%s');", $_POST['text_SInterest']);

						mysql_query($sql);
						$mem_no = mysql_insert_id();	//memNo

						//Insert data to the table of users
						
						if ($_POST['gender'] == "M" || $_POST['gender'] == "m") {
							$sql = sprintf("INSERT INTO users VALUES (null, '%s', '%s', '0', '%s', '%s', '%s',%d, null);",
											$_POST['text_SLoginnames'], $_POST['text_SPassword'], $_POST['text_SUsername'],
											$_POST['gender'], basename($_FILES['photo']['name']), $mem_no);
							echo "<br/>";
							
						} else if ($_POST['gender'] == "F" || $_POST['gender'] == "f"){
							$sql = sprintf("INSERT INTO users VALUES (null, '%s', '%s', '0', '%s', '%s', '%s',%d, null);",
											$_POST['text_SLoginnames'], $_POST['text_SPassword'], 
											$_POST['text_SUsername'], $_POST['gender'], basename($_FILES['photo']['name']), $mem_no);

						}
						
						

						mysql_query($sql);
						$sql_retrieve_user_no = sprintf("SELECT userNo FROM USERS WHERE loginName = '%s'",  $_POST['text_SLoginnames']);
						$userNo = mysql_query($sql_retrieve_user_no);
						$assoc = mysql_fetch_assoc($userNo);
						$userNo = $assoc['userNo'];
						
						echo "<div style='text-align:center; color:red;'>Register success!</div>";
						require_once('messaging/msg_init_folders.php');
						
					} else {
						echo "Fail: The photo name is already exists in server.";
						echo "<br />";
						echo "Please upload the other photo or rename the photo before upload!";
					}
					
					
					
						
					
					
				} else {
					echo "Upload Failed, the photo size is too big!";
				}
			} else {
				echo "Something Missed";
			}
			
			
			
	}
	?>
    	<script type="text/javascript" src="RRDE.js"></script>
    
		<h1 style="text-align:center;font-family:Calibri;color:darkred">Register System</h1>

			<div style="text-align:center;" id="A">
				<form id="register_form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
					<label>Full Name:</label>
					<input type="text" name="text_SUsername" id="text_SUsername" onblur="showinfo('SUsername')" /><br/>
					<span id="msgback_SUsername"></span><br/>
					<label>Login Name:</label>
					<input type="text" name="text_SLoginnames" id="text_SLoginnames" onblur="showinfo('SLoginnames')" /><br/>
					<span id="msgback_SLoginnames"></span><br/>

					<label>Password:</label>
					<input type="password" name="text_SPassword" id="text_SPassword"  onblur="showinfo('SPassword')"/><br/>
					<span id="msgback_SPassword"></span><br/> 
					<label>Gender:</label>
					</label><input type="radio" name="gender" id="gender" value="M" CHECKED>Male<input type="radio" name="gender" id="gender" value="F">Female<br/>
					<span id="msgback_SGender"></span><br/> 
					<label>Interest:</label>
					<input type="text" name="text_SInterest" id="text_SInterest" onblur="showinfo('SInterest')"/><br/>
					<span id="msgback_SInterest"></span><br/>
					<label>Personal Photo:</label>
					<input type="file" name="photo" />
					<span id="msgback_SPhoto"></span><br/>
				<br/>
				<input type="submit" id="button_submit" value="Submit"  size="20"/>
				<input type="reset" id="button_reset" value="Reset" onclick="showinfo('Reset')">
				<input type="hidden" name="MAX_FILE_SIZE" value="30000" />
				<input type="hidden" name="reg_mem_form_hidden" />
				
				</form>
				<p></b><span style="color: red" id="msgback_Submit"></span></p>
			</div>
</div>
<?php include('footer.php'); ?>
