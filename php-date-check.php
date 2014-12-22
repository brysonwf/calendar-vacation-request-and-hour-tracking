<?php include 'begin.php'; ?>
<? 

$item_id = '';

$dateBeginArray = explode("/", $_POST['date']);
$dateBegin = $dateBeginArray[2]."-".$dateBeginArray[0]."-".$dateBeginArray[1];

$result_id = mysql_query("SELECT * FROM requests WHERE datebegin='".$dateBegin."' AND userid='".$_POST['id']."' AND hours<0");

$rows = mysql_num_rows($result_id);

echo ($rows > 0)? 'f' : 't'; ?> 