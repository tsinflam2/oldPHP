<?php require_once('../conn/conn.php'); ?>
<?php
  mysql_select_db($database_conn, $conn);
  session_start();
  $userNo = $_SESSION['userNo'];
  
  // Load user inbox if query string is empty
  if (empty($_GET)) {
	$query_rs = "SELECT mfNo FROM messagefolder WHERE userNo = $userNo AND msName = 'Inbox'";
 	$rs = mysql_query($query_rs, $conn) or die(mysql_error());  
	$rs_assoc = mysql_fetch_assoc($rs);
	header("location: msg_incoming.php?folder={$rs_assoc['mfNo']}");
	exit();  
  }
  
  // Sort messages by which column, default is by msgDateTime
  $msg_orderby_col = ' message.msgDateTime';
  $msg_orderby_order = ' DESC';
  
  if (isset($_GET['sortBy']) && isset($_GET['order'])) {
  	switch ($_GET['sortBy']) {
		case 'received':
			$msg_orderby_col = ' message.msgDateTime';
			break;
		case 'subject':
			$msg_orderby_col = ' message.msgSubject';
			break;
		case 'from':
			$msg_orderby_col = ' users.userName';
			break;	
		default:
			$msg_orderby_col = ' message.msgDateTime';
	}
	
	// Sort messages by asc or desc order, default is by desc
	switch ($_GET['order']) {
		case 'asc':
			$msg_orderby_order = ' ASC';
			break;
		case 'desc':
		default:
			$msg_orderby_order = ' DESC';
	}
  }
  
  // Get current folder name
  $sql = "SELECT msName FROM messagefolder WHERE mfNo = {$_GET['folder']}";
  $result = mysql_query($sql, $conn) or die(mysql_error());
  $result_assoc = mysql_fetch_assoc($result);
  $msg_current_folder_name = $result_assoc['msName'];
  
  if ($msg_current_folder_name == 'Draft') {
	  // If this is a drft folder
	  $sql = "SELECT users.loginName, messagedraft.draftNo, messagedraft.msgTo, messagedraft.msgSubject FROM messagedraft, users WHERE messagedraft.msgFrm = '$userNo' AND messagedraft.msgTo = users.userNo ORDER BY draftNo DESC";
	  $rs = mysql_query($sql, $conn) or die(mysql_error());
	  $row_rs = mysql_fetch_assoc($rs);
	  echo '<h3>Draft</h3><form method="post" action="do_del_draft.php">';
	  
	  // Show message if previously deleted some drafts
	  if (isset($_GET['pDelDraftCount']))
			echo $_GET['pDelDraftCount'] . ($_GET['pDelDraftCount'] > 1 ? ' drafts are' : ' draft is ').' permanently deleted.';
			
	  echo '<p><input type="submit" value="Delete Permanently" /></p>';
	  $html = <<<EOD
			<table border="1">
			  <tr>
				<td><input type="checkbox" onclick="setCheckboxes(this);" /></td>
				<td>To</td>
				<td>Subject</td>
			  </tr>
EOD;
	  echo $html;
	  do {
	  		echo '<tr><td><input type="checkbox" name="draftNo[]" value="'.$row_rs['draftNo'].'" /></td>';
			echo "<td>{$row_rs['loginName']}</td>";
			echo "<td>{$row_rs['msgSubject']}</td></tr>";
	  } while ($row_rs = mysql_fetch_assoc($rs));
	  echo '</table></form>';
  } else {
	  // If this is not a draft folder
	  // Get all msgNo to a user
	  $query_rs = "SELECT message.msgNo, message.msgFrm, users.userName, message.msgDateTime, message.msgSubject, receivedmessage.msgStatus FROM message, receivedmessage, users, messagefolder WHERE receivedmessage.msgNo = message.msgNo AND receivedmessage.msgTo = $userNo AND messagefolder.mfNo = receivedmessage.mfNo AND users.userNo = message.msgFrm AND messagefolder.mfNo = ";
	  
	  if (isset($_GET['folder'])) {
		$query_rs .= $_GET['folder'];
		$msg_current_folder = $_GET['folder'];
	  }
	  //else
		//$query_rs .= ;
	  
	  // Concat ORDER BY clause
	  $query_rs .= ' ORDER BY ';
	  $query_rs .= $msg_orderby_col;
	  $query_rs .= $msg_orderby_order;
	  
	  // debug
	  //print($query_rs);
	  
	  
	  //$query_rs = "SELECT * FROM receivedmessage, message WHERE message.msgNo = $userNo";
	  $rs = mysql_query($query_rs, $conn) or die(mysql_error());
	  $row_rs = mysql_fetch_assoc($rs);
		//print_r($row_rs);
	  $totalRows_rs = mysql_num_rows($rs);
	  
	  // Get current folder name
	  $sql = "SELECT msName FROM messagefolder WHERE mfNo = $msg_current_folder";
	  $result = mysql_query($sql, $conn) or die(mysql_error());
	  $result_assoc = mysql_fetch_assoc($result);
	  $msg_current_folder_name = $result_assoc['msName'];
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	  <head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Untitled Document</title>
		<script language="JavaScript" type="text/javascript">
		  // Vars to store current information about the display order of messages
		  current_orderby_col = ''; 
		  current_orderby_order = '';
		  current_viewing_folder = '';
		
		  function setCheckboxes(cbObj) {
			// getElementsByName() works in IE/FF
			// objsCollection = document.getElementsByName('custID[]');  // objsCollection.length returns 9
			
			// getElementsByTagName() works in IE/FF
			objsCollection = document.forms[0].getElementsByTagName('INPUT');  // objsCollection.length returns 12
			show_indexes(objsCollection);
			// for (idx in objsCollection) {   // it works in FF only
			for (idx=0; idx<objsCollection.length; idx++) {  // it works in IE/FF
			  obj = objsCollection[idx];
			  if (obj.type == "checkbox" && obj != cbObj)
				obj.checked = cbObj.checked;
			}
		  }
		  
		  function show_indexes(arrObj) {
			s = '';
			for (idx in arrObj) {
			  s += idx + ' ';
			}
			window.status = s;
			return true;
		  }
		  
		  // Sort messages in the order that the user wants
		  function sortMessages(clickedColumn) {
			  
			  reload_to = document.URL;
			  reload_to = reload_to.split('?')[0];
			  reload_to += '?sortBy=';
			  
			  switch (clickedColumn) {
				  case 'received':
				  case 'subject':
				  case 'from':
					reload_to += clickedColumn;
					current_orderby_col = clickedColumn;
					break;
				  default:
					reload_to += 'received';
					current_orderby_col = 'received';
			  }
			  
			reload_to += '&order=';
			
			if (current_orderby_order == 'desc') {
			  reload_to += 'asc'; 
			  current_orderby_order = 'asc';
			} else {
			  reload_to += 'desc';
			  current_orderby_order = 'desc';
			}
			
			// Append folder information
			reload_to += '&folder=' + current_viewing_folder;
			 
			 
			//alert('reload to ' + reload_to);
			location.replace(reload_to);
		  }
		  
		  // Get the current values of sort by and order 
		  function checkCurrentOrder() {
			temp = document.URL;
			
			
			// Remember which folder is being looked at at the moment
			if (temp.split('&folder=')[1] == undefined) 
			  current_viewing_folder = temp.split('?folder=')[1].split('&')[0];
			else
			  current_viewing_folder = temp.split('&folder=')[1].split('&')[0];
			  
			//alert(current_viewing_folder + 'cuurr viewing folder');
			
			// Gives undefined if no query string is present
			temp = temp.split('?sortBy=')[1];
	
			// If no query string present, then its currently ordering by received column DESC
			if (temp == undefined) {
				current_orderby_col = 'received';
				current_orderby_order = 'desc';	
				//alert('undefind');
			} else {
				current_orderby_col = temp.split('&order=')[0];
				//current_orderby_order = temp.split('&order=')[1];
				current_orderby_order = temp.split('&order=')[1].split('&folder=')[0];
				//alert(current_orderby_order);
				//current_viewing_folder = temp.split('&order=')[1].
			}
			
			//alert('now is order by ' + current_orderby_col + ' '+ current_orderby_order);
		  }
		  
		  
		</script>
	  </head>
	  <body onload="checkCurrentOrder()">
		<?php
		  // Display info message if needed
		  if (isset($_GET['delCount']))
			 echo $_GET['delCount'] . ($_GET['delCount'] > 1 ? ' messages are' : ' message is ').' deleted.';
		  else if (isset($_GET['pDelCount'])) 
			 echo $_GET['pDelCount'] . ($_GET['pDelCount'] > 1 ? ' messages are' : ' message is ').' permanently deleted.';
		  else if (isset($_GET['moveCount']) && isset($_GET['destination']))
			 echo $_GET['moveCount'] . ($_GET['moveCount'] > 1 ? ' messages are' : ' message is ')." moved to your '{$_GET['destination']}' folder.";
		  else if (isset($_GET['restoreToInbox'])) 
			 echo $_GET['restoreToInbox'] . ($_GET['restoreToInbox'] > 1 ? ' messages are' : ' message is ').' restored to your Inbox.';
			 
		  // Display current folder name
		  echo "<h3>$msg_current_folder_name</h3>";
		?>
		<form id="msgList" method="post" action="do_msg_process.php">
		 <p>
			<?php
			  // Show buttons depending on which folder the user is in now
			  switch ($msg_current_folder_name) {
				case 'Draft':
					echo '<input type="submit" name="pDelSelected" value="Delete Permanently" />';
					break;
				case 'Deleted':
					echo '<input type="submit" name="restoreToInbox" value="Restore to Inbox" />';
					echo '<input type="submit" name="pDelSelected" value="Delete Permanently" />';
					break;
				default:
					echo '<input type="submit" name="moveToFolder" value="Move" />';
					echo '<input type="submit" name="delSelected" value="Delete" />';
					echo '<input type="hidden" name="currFolder" value="'.$msg_current_folder_name.'" />';
			  }
			  //<input type="submit" name="moveToFolder" value="Move To Folder" />
			  //<input type="submit" name="delSelected" value="Delete Selected" />
			  
			?>
		  </p>
		  <table border="1">
			<tr>
			  <td>(ICON)</td>
			  <td><input type="checkbox" onclick="setCheckboxes(this);" /></td>
			  <td><a href="" onclick="sortMessages('from'); return false;">From</a></td>
			  <td><a href="" onclick="sortMessages('subject'); return false;">Subject</a></td>
			  <td><a href="" onclick="sortMessages('received'); return false;">Received</a></td>
			</tr>
			<?php 
				if (mysql_num_rows($rs) > 0) {
				do { ?>
			  <tr>
				<td><?php echo $row_rs['msgStatus']; ?></td>
				<td>
				<?php
				  printf('<input type="checkbox" name="msgNo[]" value="%s" />', $row_rs['msgNo']);
				?>
				</td>
				<td><?php echo $row_rs['userName']; ?></td>
				<td><?php echo $row_rs['msgSubject']; ?></td>
				<td><?php echo $row_rs['msgDateTime']; ?></td>
			  </tr>
			  <?php } while ($row_rs = mysql_fetch_assoc($rs)); 
				} else {
					echo '<tr><td colspan="5" style="text-align: center;">This message folder is currently empty.</td></tr>';
				}
			  ?>
		  </table>
		</form>
	  </body>
	</html>
<?php
  }
  mysql_free_result($rs);
?>

