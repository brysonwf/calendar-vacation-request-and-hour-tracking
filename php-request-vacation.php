<?php include'begin.php'; ?>
<?php

$dateBeginArray = explode("/", $_POST['request_date_begin']);
$dateBegin = $dateBeginArray[2]."-".$dateBeginArray[0]."-".$dateBeginArray[1];

$dateEndArray = explode("/", $_POST['request_date_end']);
$dateEnd = $dateEndArray[2]."-".$dateEndArray[0]."-".$dateEndArray[1];

$datestamp = date(DATE_RFC822);

$hours = $_POST['requesting_off_amount_input']*-1;

$timeBegin = $_POST['partial_begin_hour'].':'.$_POST['partial_begin_min'];


if ( ($_SESSION['admin']==1 || $_SESSION['admin']==2) && $_POST['request_off_area'] == "Admin") {
	$item_status = 'OK';
	$item_color = 'green';
} else {
	$item_status = '?';
	$item_color = 'orange';
}
$reason = urlencode($_POST['request_reason']);
if(isset($_POST['lunch'])){
	if ($_POST['lunch'] == '0'){
		$lunch = 0;
	}else if ($_POST['lunch'] == '.5'){
		$reason .= ' (including 30 min lunch)';
		$lunch = .5;
	}else{
		$reason .= ' (including 1 hour lunch)';
		$lunch = 1;
	}
}else{
	$lunch = 0;
}

//add to database
$query = "INSERT INTO requests (userid, reason, requestedon, datebegin, dateend, timebegin, hours, status, createdby, lunch) VALUES ('".$_POST['request_day_user_select']."', '".$reason."', '".$datestamp."', '".$dateBegin."', '".$dateEnd."', '".$timeBegin."', '".$hours."', '".$item_status."', '".$_POST['requesting_off_created_by']."', '".$lunch."')";
mysql_query($query);

//pull fresh id
$result_id = mysql_query("SELECT id FROM requests WHERE requestedon = '".$datestamp."'");

$row_id = mysql_fetch_array($result_id);
$item_id = $row_id['id'];

$result_email = mysql_query("SELECT email FROM users WHERE id = '".$_POST['request_day_user_select']."'");

$row_email = mysql_fetch_array($result_email);
$item_email = $row_email['email'];


$result_requestcount = mysql_query("SELECT SUM(hours) AS totalhours FROM requests WHERE userid = '".$_POST['request_day_user_select']."'");

$row_requestcount= mysql_fetch_array($result_requestcount);
$requestcount = $row_requestcount['totalhours'];


?>
<li id="hours_list_<?php echo $item_id; ?>" class="future-date <?php echo $_POST['requesting_off_created_by']; ?>-created">
    <div class="actions">
        <a class="cancel" href="#remove_request_<?php echo $item_id; ?>">X</a>
        <a class="undo" href="#undo_request_<?php echo $item_id; ?>"><img src="img/icon-undo.png" alt="Undo" /></a>
    </div>


	<?
	//this could be cleaner
	if (($_SESSION['admin']==1 || $_SESSION['admin']==2) && $_POST['request_off_area'] == "Admin") { ?>
        <div class="user">
            <?php echo $item_email; ?>
        </div>
    	<? 
	} ?>
    <div class="time-span red">
   		<?php
			switch ($_POST['request_day_type']) {
				case "Partial":
					echo ($_POST['requesting_off_amount_input']*-1)."";
					break;
				case "Single":
					echo ($_POST['requesting_off_amount_input']*-1).""; 
					break;
				case "Multiple":
					echo ($_POST['requesting_off_amount_input']*-1)."";
					break;
			}
		?>
    </div>
    <div class="date">
   		<?php
			$switch_date = '';
			switch ($_POST['request_day_type']) {
				case "Partial":
					$_POST['partial_begin_hour'] > 12 ? ($partial_begin_hour = $_POST['partial_begin_hour']-12) : ($partial_begin_hour = $_POST['partial_begin_hour']);
					$_POST['partial_end_hour'] > 12 ? ($partial_end_hour = $_POST['partial_end_hour']-12) : ($partial_end_hour = $_POST['partial_end_hour']);
					
					$switch_date = $_POST['request_date_begin']." ".$partial_begin_hour.":".$_POST['partial_begin_min']." - ".$partial_end_hour.":".$_POST['partial_end_min'];
					break;
				case "Single":
					$switch_date = $_POST['request_date_begin']; 
					break;
				case "Multiple":
					$switch_date = $_POST['request_date_begin'].' to '.$_POST['request_date_end'];
					break;
			}
			echo $switch_date;
		?>
    </div>
    <div class="reason">
        <?php echo $_POST['request_reason']; ?>
    </div>
    <div class="status <? echo $item_color; ?>">
        <? echo $item_status; ?>
    </div>
    <div class="delete-col">
        <a class="delete" href="#permanatly-delete-<? echo $item_id; ?>">Delete</a>
    </div>
</li>

<? 

	$subject = 'Mojostuff.com - Vacation Request - '.$item_email;
	
	$message = '<a href="http://mojostuff.com/mi">Mojo Internals</a><br><br>';
	$message.= $item_email.' ('.$requestcount.' hrs) requests off for '.$hours.' on '.$switch_date. "<br><br>";
	$message.= 'Reason: '.urldecode($reason). "<br>";
	
	$headers = 'MIME-Version: 1.0' . "\r\n";
	$headers.= 'Content-type: text/html; charset=iso-8859-1' . "<br>";
		
	$headers.= 'From: '.$item_email. "\r\n" .
		'Reply-To: '.$item_email. "\r\n" .
		'X-Mailer: PHP/' . phpversion();
	//mail('bryson@mojotone.com', $subject, $message, $headers);
	
	mail('vacation@mojotone.com', $subject, $message, $headers);
	
	if ($_SESSION['manager'] != ''){
//		echo 'mananger: '.$_SESSION['manager'];
		mail($_SESSION['manager'], $subject, $message, $headers);
	}
	
	mysql_close($con);
?>