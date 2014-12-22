<?php
	session_start();  
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	
	date_default_timezone_set('America/New_York');
	
	$now = date("Ymd");
	
	$_SESSION['paydate'] = strtotime('11/28/2010');
	
	$nowstring = strtotime('now');
	
	while($_SESSION['paydate'] < $nowstring){
		$_SESSION['paydate']+=(60*60*24*14);
	}
	
	
	if( (isset($_SESSION['logged']) && ($_SESSION['logged'] == true))){
	}else{
		$_SESSION['logged'] = false;
	}
	
	
	$con = mysql_connect("localhost","mojostuff","mojoman2010");

	if (!$con){
		die('Could not connect: ' . mysql_error());
	}
	
	mysql_select_db("mojostuff", $con);
	
	$page_message = '';
	if ( isset($_POST['login-form-post']) && ($_POST['login-form-post'] == true)){
		$_SESSION['admin'] = 0;
		if( ($_POST['login_email'] != '') && ($_POST['login_password'] != '') ){
			
			// Get all the data from the "example" table
			$result_login = mysql_query("SELECT * FROM users WHERE email='".$_POST['login_email']."' AND password='".$_POST['login_password']."'");
			$row_login = mysql_fetch_array( $result_login );
			if ( (mysql_num_rows($result_login)) == 1 ){
				$_SESSION['userid'] = $row_login['id'];
				$_SESSION['logged'] = true;
				$_SESSION['hourly'] = $row_login['hourly'];
				$_SESSION['login_email'] = $_POST['login_email'];
				$_SESSION['login_password'] = $_POST['login_password'];
				$_SESSION['manager'] = $row_login['manager'];
				$page_message.= 'Welcome.';
				if ($row_login['admin'] != 0){
					$_SESSION['admin'] = $row_login['admin'];
				}
			}else{
				$page_message.= 'Invalid credentials.';
			}
		}
		$page_message= 'Invalid credentials.';
	}
?>
