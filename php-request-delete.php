<?php include 'begin.php'; ?>
<? 
	mysql_query("DELETE FROM requests WHERE id = '".$_POST['id']."'");
?>