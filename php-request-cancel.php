<?php include 'begin.php'; ?>
<? 
	mysql_query("UPDATE requests SET status='X' WHERE id = '".$_POST['id']."'");
?>