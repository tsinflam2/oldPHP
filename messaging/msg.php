<?php
  include('../header.php');
  echo '<div id="bodyPan"><table><tr><td>';
  echo '<iframe id="msgPanelLeft" style="display: block;margin-left: auto;margin-right: auto;" name="msgPanelLeft" src="msg_folders.php" width="200px" height="500px" frameborder="0"></iframe></td>';
  
  // Show corresponding content on right pane, by default the inbox will be shown
  $right_side_url = 'msg_incoming.php';
  if (isset($_GET['view'])) {
	$right_side = $_GET['view'];
	if ($right_side == 'inbox') {
		$right_side_url = 'msg_incoming.php';
	}
  }
  echo '<td><iframe id="msgPanelRight" style="display: block;margin-left: auto;margin-right: auto;" name="msgPanelRight" src="'.$right_side_url.'" frameborder="0" height="500px" width="500px"></iframe></td></tr></table></div>';
?>
<?php include('../footer.php'); ?>