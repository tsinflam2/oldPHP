<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Compose Message</title>
<style type="text/css">
textarea, input[type="text"] {
	width: 100%;	
}

.tablecell-align-left {
	text-align: left;
}

.tablecell-align-right {
	text-align: right;
}
</style>
<script>
	function countMessageLength() {
		document.getElementById('msgCount').innerHTML = 'No. of characters: ' + document.getElementById('content').value.length;
	}
</script>
</head>
<body onload="countMessageLength()">
<form action="do_msg_compose_process.php" method="post">
<table>
  <tr>
  	<td>
  		<label for="to">To:</label>
  	</td>
    <td>
  		<input type="text" name="to" id="to" />
    </td>
  </tr>
  <tr>
  	<td>
		<label for="subject">Subject:</label>
    </td>
    <td>
		<input type="text" name="subject" id="subject" />
    </td>
  </tr>
  <tr>
  	<td>
		<label for="content">Message:</label>
    </td>
    <td>
		<textarea name="content" id="content" rows="5" onKeyUp="countMessageLength();"></textarea>
    </td>
  </tr>
  <tr>
  	<td colspan="2">
    	<p id="msgCount"></p>
    </td>
  </tr>
  <tr>
  	<td>
    	<input type="submit" value="Save Draft" name="draft" />
    </td>
  	<td class="tablecell-align-right">
    	<input type="reset" value="Clear" />
        <input type="submit" value="Send" name="send" />
    </td>
  </tr>
</table>
</form>
</body>
</html>