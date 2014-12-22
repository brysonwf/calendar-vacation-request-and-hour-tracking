<?php include 'begin.php'; ?>
<?php $page_name = 'Hours'; ?>

<?
if (isset($_GET['action'])){
	if (($_GET['action'] == 'clockin') && isset($_POST['clockin-form-userid'])){
		mysql_query("INSERT INTO hours (userid, start, end) VALUES ('".$_POST['clockin-form-userid']."', date_add(now(), interval 10800 second), '0')");
	}else if ($_GET['action'] == 'clockout'){
		mysql_query("UPDATE hours SET end = date_add(now(), interval 10800 second) WHERE id = '".$_GET['clockoutid']."'");
	}
}

$allUsers = false;

if (isset($_GET['action']) && $_GET['action'] == 'range'){
	if ($_POST['date-range-form-user'] == "all"){
		//select all users with total hours column
		$allUsers = true;
		if ($_POST['date-range-form-dates'] == "all"){
			$result_hours_list = mysql_query("SELECT users.id AS id, email, SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(end,start)))) AS total FROM hours, users WHERE users.id = hours.userid GROUP BY email ORDER BY email DESC");
		}else{
			$date_range_dates = explode("^", $_POST['date-range-form-dates']);
			$result_hours_list = mysql_query("SELECT users.id AS id, email, SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(end,start)))) AS total FROM hours, users WHERE users.id = hours.userid AND start>='".$date_range_dates[0]."' AND end<'".$date_range_dates[1]."' GROUP BY email ORDER BY email DESC");
		}
	}else{
		if ($_POST['date-range-form-dates'] == "all"){
			$result_hours_list = mysql_query("SELECT id, start,end, TIMEDIFF(end,start) AS total FROM hours WHERE userid = '".$_POST['date-range-form-user']."' ORDER BY start DESC");
		}else{
			$date_range_dates = explode("^", $_POST['date-range-form-dates']);
			$result_hours_list = mysql_query("SELECT id, start,end, TIMEDIFF(end,start) AS total FROM hours WHERE userid = '".$_POST['date-range-form-user']."' AND start>='".$date_range_dates[0]."'  AND end<'".$date_range_dates[1]."' ORDER BY start DESC");
		}
	}
}else{
	$begindate = date( 'Y-m-d', strtotime($lastpayday) - (60*60*24*14));
	$enddate = date( 'Y-m-d', strtotime($lastpayday));
	$result_hours_list = mysql_query("SELECT id, start,end, TIMEDIFF(end,start) AS total FROM hours WHERE userid = '".$_SESSION['userid']."' AND start>='".$begindate."'  AND end<'".$enddate."' ORDER BY start DESC");
} ?>


<?php include 'inc-page-top.php'; ?>

<div class="content-wide">
    <ul id="content-ul" class="flo">
		<?php include 'inc-form-date-range.php'; ?>
        <? if ( ($_SESSION['admin'] == 1 || $_SESSION['admin'] == 2) && (isset($_GET['action']) && $_GET['action'] == 'range') ) { ?>
            <li class="content-li">
				<? if ($_POST['date-range-form-user'] != "all"){
					$result_user_email = mysql_query("SELECT * FROM users WHERE id='".$_POST['date-range-form-user']."'");
					$row_user_email = mysql_fetch_array( $result_user_email ); ?>
                	<p>User: <? echo $row_user_email['email']; if (isset($date_range_dates[0])){ echo '<br> Dates: '.$date_range_dates[0].' to '.$date_range_dates[1]; } ?></p>
				<? } else { ?>
                	<p>All users. <? if (isset($date_range_dates[0])){ echo '<br> Dates: '.$date_range_dates[0].' to '.$date_range_dates[1]; } ?></p>
				<? } ?>
            </li>
        <? } else{ ?>
            <li class="content-li">
            	<? if (isset($_POST['date-range-form-user'])){
                	$begindate = $date_range_dates[0];
					$enddate = $date_range_dates[1];
                } ?>
                
                <p>Hours starting from: <? echo $begindate; ?> to <? echo $enddate; ?></p>
            </li>
        <? } ?>
        <li class="content-li">
            <div class="drawer">
                <ul id="requests_headings" class="requests flo bor">
                    <li class="h">
                    	<? if ($allUsers) { ?>
							<div class="user">
								user
							</div>
							<div class="total">
                            	hours
							</div>
                        <? } else { ?>
							<div class="countcol">
								#
							</div>
							<div class="clockin">
								IN
							</div>
							<div class="clockout">
								Out
							</div>
							<div class="total">
								total
							</div>
                        <? } ?>
                    </li>
                </ul>
                <ul id="requests" class="admin_requests requests flo bor">
                	<? 
						$count = 0;
						if ($allUsers) {
							while($row_hours_list = mysql_fetch_array($result_hours_list)){
								$count ++;
					
								?>
									<li id="edit-item-<? echo $row_hours_list['id']; ?>" class="<? if ($count%2==0)echo' libg '; ?>">
										<div class="user">
											<?php echo $row_hours_list['email']; ?>
										</div>
										<div class="total">
											<? 
												echo $row_hours_list['total'];
											?>
										</div>
									</li>
								<? 
							}
						}else{
							$now = date("Ymd");
							$totalseconds = 0;
							while($row_hours_list = mysql_fetch_array($result_hours_list)){
								$count ++;
					
								?>
									<li id="edit-item-<? echo $row_hours_list['id']; ?>" class="<? if ($count%2==0)echo' libg '; ?>">
										<div class="countcol">
											<?php echo $count; ?>
										</div>
										<div class="clockin">
											<?php echo $row_hours_list['start']; ?>
										</div>
										<div class="clockout">
											<? if($row_hours_list['end'] == '0000-00-00 00:00:00'){ ?>
													<a href="hours.php?action=clockout&clockoutid=<? echo $row_hours_list['id']; ?>">Clock Out</a>
											<? } else {
												echo $row_hours_list['end'];
											} ?>
										</div>
										<div class="total">
											<? 
												echo $row_hours_list['total'];
												$totalseconds += time_to_sec($row_hours_list['total']);
											?>
										</div>
									</li>
								<? 
							}
						}
					?>
                </ul>
                <? if (!$allUsers) { ?><div id="total-hours"><? echo sec_to_time($totalseconds); ?></div><? } ?>
            </div>
        </li>
    </ul>
</div>
<?php include 'inc-page-bottom.php'; ?>