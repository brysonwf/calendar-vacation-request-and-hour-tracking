<?php include 'begin.php'; ?>

<? 
	$result_email = mysql_query("SELECT email, datebegin FROM requests, users WHERE userid = users.id AND requests.id = '".$_POST['id']."'");
	$row_email = mysql_fetch_array($result_email);

	$to      = $row_email['email'];    
	$subject = 'Mojostuff.com - Vacation Request has been approved - Vacation starting on '.$row_email['datebegin'];
	
	$message = '<a href="http://mojostuff.com/mi">Mojo Internals</a><br><br>';
	$message.= 'Vacation approved starting on '.$row_email['datebegin']."<br>";
	
	$headers = 'MIME-Version: 1.0' . "\r\n";
	$headers.= 'Content-type: text/html; charset=iso-8859-1' . "<br>";
		
	$headers.= 'From: bryson@mojotone.com'. "\r\n" .
		'Reply-To:  bryson@mojotone.com'. "\r\n" .
		'X-Mailer: PHP/' . phpversion();
	
	mail($to, $subject, $message, $headers);

	mysql_query("UPDATE requests SET status='OK' WHERE id = '".$_POST['id']."'");
?>