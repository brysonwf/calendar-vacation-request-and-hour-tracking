<?php include 'begin.php'; ?>
<? 
	$id = mysql_real_escape_string($_POST['id']);
	mysql_query("DELETE FROM users WHERE id = '".$id."'");
	mysql_query("DELETE FROM requests WHERE userid = '".$id."'");
?>