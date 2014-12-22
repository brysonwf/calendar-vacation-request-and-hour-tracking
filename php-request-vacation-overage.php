<?php include'begin.php'; ?>
<?php

$reason = 'Approved Vacation Overage';

$dateBegin = $_POST['requesting_off_date'];
$dateEnd = $_POST['requesting_off_date'];

$datestamp = date(DATE_RFC822);

$timeBegin = 0;

$hours = $_POST['requesting_off_amount_input'];

$item_status = 'OK';
$item_color = 'green';

$lunch = 0;

//add to database
mysql_query("INSERT INTO requests (userid, reason, requestedon, datebegin, dateend, timebegin, hours, status, createdby, lunch) VALUES ('".$_POST['requesting_off_user']."', '".$reason."', '".$datestamp."', '".$dateBegin."', '".$dateEnd."', '".$timeBegin."', '".$hours."', '".$item_status."', '".$_POST['requesting_off_created_by']."', '".$lunch."')");

?>