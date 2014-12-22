<?php include 'begin.php'; ?>
<? 



$result_item_edit = mysql_query("SELECT requests.id AS id, users.email AS email, datebegin, dateend, createdby, hours FROM requests,users WHERE requests.id= '".$_POST['id']."' AND requests.userid = users.id");

$row_item_edit = mysql_fetch_array($result_item_edit);


//print out edit form

$hour_list_datebegin_array = explode("-", $row_item_edit['datebegin']);
$current_li_datebegin = $hour_list_datebegin_array[1]."/".$hour_list_datebegin_array[2]."/".$hour_list_datebegin_array[0];

$hour_list_dateend_array = explode("-", $row_item_edit['dateend']);
$current_li_dateend = $hour_list_dateend_array[1]."/".$hour_list_dateend_array[2]."/".$hour_list_dateend_array[0];

?> 
    
<div class="user">
    <?php echo $row_item_edit['email']; ?>
</div>
<?php $hour_list_hours = $row_item_edit['hours']; ?>
<div class="time-span <?php echo $hour_list_hours<0 ? 'red' : 'green'; ?>" >
    <?php echo $hour_list_hours; ?> 
</div>
<div class="date-edit">
    <input type="text" value="<?php echo $current_li_datebegin; ?>" /> : <input type="text" value="<?php echo $current_li_dateend; ?>" /> <input type="submit" value="Save" />
</div>