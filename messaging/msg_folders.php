<?php require_once('../conn/conn.php'); ?>
<?php
  mysql_select_db($database_conn, $conn);
  session_start();
  $userNo = $_SESSION['userNo'];
  
  // Process add new folder request if needed
  if (isset($_POST['newFolder'])) {
	  // Only add the new folder if it does not eixist.
	  $f_name = $_POST['newFolder'];
	  $sql = "SELECT 1 FROM messagefolder WHERE userNo = $userNo AND LOWER(msName) = LOWER('$f_name')";
	  $rs = mysql_query($sql, $conn) or die(mysql_error());
	  $folder_exists = mysql_fetch_assoc($rs);
	  if ($folder_exists['1'] == 1)
	  	echo "<script>alert('Folder already exists!');</script>";
	  else {
		$sql = "INSERT INTO messagefolder (msName, userNo) VALUES ('$f_name', $userNo)";
		$rs = mysql_query($sql, $conn) or die(mysql_error());
	  }
  }
  
  $query_rs = "SELECT * FROM messagefolder WHERE userNo = $userNo";
  $rs = mysql_query($query_rs, $conn) or die(mysql_error());
  $totalRows_rs = mysql_num_rows($rs);
  
  // Show compose message button
  echo '<p><a href="msg_compose.php" target="msgPanelRight">Compose Message</a></p>';
  
  // Display folders
  echo '<table>';
  while ($row_rs = mysql_fetch_assoc($rs)) {
	echo '<tr><td>';
	echo '<a href="msg_incoming.php?folder='.$row_rs['mfNo'].'" target="msgPanelRight">'.$row_rs['msName'].'</a>'; 
	echo '</tr></td>';
  }
  echo '</table>';
  
  $html = <<<EOD
	<p>
	<form action="" method="post">
	  <label for="newFolder">Create folder:</label><br />
	  <input type="text" name="newFolder" id="newFolder"/>
	  <input type="submit" value="Go"/>
	</form>
	</p>
	<p>
	  <a href="msg_folders_manage.php" target="msgPanelRight">Manage folders...</a>
	</p>
EOD;
  
echo $html;
?>