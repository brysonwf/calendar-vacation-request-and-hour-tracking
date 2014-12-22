<?php include 'begin.php'; ?>
<? 
$datestamp = date(DATE_RFC822);


$item_status = 'OK';
$item_color = 'green';

$user_array = array();

if ($_POST['add_time_form_user'] == 'all'){
	
	$result_users_list = mysql_query("SELECT id, email FROM users ORDER BY email");
	while($row_users_list = mysql_fetch_array($result_users_list)){
		$user_array[] = $row_users_list['id'];
	}
	
}else{
	$user_array = array($_POST['add_time_form_user']);
}

foreach ($user_array as $user_id) {

	mysql_query("INSERT INTO requests (userid, hours, createdby, requestedon, datebegin, reason, status) VALUES ('".$user_id."', '".$_POST['add_time_form_hours']."', 'admin', '".$datestamp."', '".date("Y-m-d")."', '".$_POST['add_time_form_reason']."', 'OK')");
		
	//pull fresh id
	
	$result_id = mysql_query("SELECT id FROM requests WHERE requestedon = '".$datestamp."'");
	
	$row_id = mysql_fetch_array($result_id);
	$item_id = $row_id['id'];
	
	$result_email = mysql_query("SELECT email FROM users WHERE id = '".$user_id."'");
	
	$row_email = mysql_fetch_array($result_email);
	$item_email = $row_email['email'];
		
	?>
	<li id="hours_list_<?php echo $item_id; ?>" class="future-date <?php echo $_POST['item_email']; ?>-created">
		<div class="actions">
			<a class="cancel" href="#remove_request_<?php echo $item_id; ?>">X</a>
			<a class="undo" href="#undo_request_<?php echo $item_id; ?>"><img src="img/icon-undo.png" alt="Undo" /></a>
		</div>
		<div class="user">
			<?php echo $item_email; ?>
		</div>
		<div class="time-span green">
			<?php echo $_POST['add_time_form_hours'];	?>
		</div>
		<div class="date">
			<?php echo date("m/d/Y"); ?>
		</div>
		<div class="reason">
			<?php echo $_POST['add_time_form_reason']; ?>
		</div>
		<div class="status <? echo $item_color; ?>">
			<? echo $item_status; ?>
		</div>
		<div class="delete-col">
			<a class="delete" href="#permanatly-delete-<? echo $item_id; ?>">Delete</a>
		</div>
	</li>
	<? 
}

mysql_close($con); ?> 