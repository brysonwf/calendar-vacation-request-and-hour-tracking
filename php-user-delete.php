<?php include 'begin.php'; ?>
<? 
	mysql_query("DELETE FROM users WHERE id = '".$_POST['id']."'");
	mysql_query("DELETE FROM requests WHERE userid = '".$_POST['id']."'");
?>