<div class="cleaning-information">
	<h2>Cleaning Assignments</h2>
<?php 
	$count = 0;
	$daysoff = array();
	$result_hours_list = mysql_query("SELECT * FROM cleaning ORDER BY id DESC");
	$row_hours_list = mysql_fetch_array($result_hours_list);
	echo htmlspecialchars_decode($row_hours_list['text']);
?>
</div>