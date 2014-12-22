<?php include 'begin.php'; ?>

<? 
	$array = unserialize(base64_decode($_POST['array']));

	$to      = 'bryson@mojotone.com';    
	$subject = 'Mojostuff.com - Cleaning Schedule';
	
	$message = '<b>Mens</b><br>'.$array[0]. "<br>";
	mysql_query("UPDATE users SET mens = mens + 1 WHERE email = '".$array[0]."'");
	
	$message.= ''.$array[1]."<br><br>";
	mysql_query("UPDATE users SET mens = mens + 1 WHERE email = '".$array[1]."'");
	
	$message.= "<b>Womens</b><br>";
	$message.= $array[2]."<br>";
	mysql_query("UPDATE users SET womens = womens + 1 WHERE email = '".$array[2]."'");
	
	$message.= $array[3]."<br><br>";
	mysql_query("UPDATE users SET womens = womens + 1 WHERE email = '".$array[3]."'");
	
	$message.= '<b>Phoneroom</b><br>'.$array[4]. "<br>";
	mysql_query("UPDATE users SET phoneroom = phoneroom + 1 WHERE email = '".$array[4]."'");
	
	$message.= $array[5]. "<br><br>";
	mysql_query("UPDATE users SET phoneroom = phoneroom + 1 WHERE email = '".$array[5]."'");
	
	$message.= '<b>Warehouse</b><br>'.$array[6]."<br>";
	mysql_query("UPDATE users SET warehouse = warehouse + 1 WHERE email = '".$array[6]."'");
	
	$message.= $array[7]."<br>";
	mysql_query("UPDATE users SET warehouse = warehouse + 1 WHERE email = '".$array[7]."'");
	
	$message.= $array[8]."<br><br>";
	mysql_query("UPDATE users SET warehouse = warehouse + 1 WHERE email = '".$array[8]."'");
	
	$message.= '<b>Pickup Room</b><br>'.$array[9]. "<br>";
	mysql_query("UPDATE users SET pickuproom = pickuproom + 1 WHERE email = '".$array[9]."'");
	
	$message.= $array[10]. "<br><br>";
	mysql_query("UPDATE users SET pickuproom = pickuproom + 1 WHERE email = '".$array[10]."'");
	
	$message.= '<b>Breakroom</b><br>'.$array[11]. "<br>";
	mysql_query("UPDATE users SET breakroom = breakroom + 1 WHERE email = '".$array[11]."'");
	
	$message.= $array[12]. "<br><br>";
	mysql_query("UPDATE users SET breakroom = breakroom + 1 WHERE email = '".$array[12]."'");
	
	$message.= '<b>Hallways</b><br>'.$array[13]. "<br>";
	mysql_query("UPDATE users SET hallways = hallways + 1 WHERE email = '".$array[13]."'");
	
	$message.= $array[14]. "";
	mysql_query("UPDATE users SET hallways = hallways + 1 WHERE email = '".$array[14]."'");
	
	
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		
	$headers.= 'From: bryson@mojotone.com' . "\r\n" .
		'Reply-To:  bryson@mojotone.com' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
	
	//mail($to, $subject, $message, $headers);
	
	
	mysql_query("INSERT INTO cleaning (text) VALUES ('".htmlspecialchars($message)."')");
	
	
?>