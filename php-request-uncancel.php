<?php include 'begin.php'; ?>
<? 
	mysql_query("UPDATE requests SET status='?' WHERE id = '".$_POST['id']."'");
?>