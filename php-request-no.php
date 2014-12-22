<?php include 'begin.php'; ?>
<? 
	mysql_query("UPDATE requests SET status='NO' WHERE id = '".$_POST['id']."'");
?>