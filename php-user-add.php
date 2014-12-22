<?php include 'begin.php'; ?>
<? 


$count = mysql_num_rows(mysql_query("SELECT * FROM users WHERE email ='".$_POST['add_user_email']."'"));


if (isset($_POST['add_user_hourly']) && $_POST['add_user_hourly'] == 'on'){
	$hourly_user = 1;
}else{
	$hourly_user = 0;
}


if ($count == 0){

	$datestamp = date('Ymd');
	
	mysql_query("INSERT INTO users (email, password, hourly, manager, createdon) VALUES ('".$_POST['add_user_email']."', '".$_POST['add_user_password']."','".$hourly_user."', '".$_POST['add_user_manager']."', '".$datestamp."')");
		
	//pull fresh id
	
	$result_id = mysql_query("SELECT id FROM users WHERE createdon = '".$datestamp."'");
	
	$row_id = mysql_fetch_array($result_id);
	$user_id = $row_id['id'];
	
	mysql_close($con); 
	
	echo 'You should recieve a registration email.';
	
	$to      = $_POST['add_user_email'];
	$subject = 'Mojostuff.com - Mojo Internals Registration';
	
	$message = 'Welcome to mojostuff.com/mi!'. "\r\n". "\r\n";
	$message.= 'Username: '.$_POST['add_user_email']. "\r\n";
	$message.= 'Password: '.$_POST['add_user_password']. "\r\n";
	$message.= 'Password: '.$_POST['add_user_manager']. "\r\n";
	
	$headers = 'From: bryson@mojotone.com' . "\r\n" .
		'Reply-To: bryson@mojotone.com' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
	
	mail($to, $subject, $message, $headers);
	

}else{
	echo 'User "'.$_POST['add_user_email'].'" already exists.';
}

?> 
