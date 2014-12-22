<?php include 'begin.php'; ?>
<?php $page_name = 'Hours'; ?>

<?
if (isset($_GET['action'])){
	if (($_GET['action'] == 'clockin') && isset($_POST['clockin-form-userid'])){
		mysql_query("INSERT INTO hours (userid, start, end) VALUES ('".$_POST['clockin-form-userid']."', now(), '0')");
	}else if ($_GET['action'] == 'clockout'){
		mysql_query("UPDATE hours SET end = now() WHERE id = '".$_GET['clockoutid']."'");
	}
}

$allUsers = false;
$fulltimeUsers = false;

if (isset($_GET['action']) && $_GET['action'] == 'date-range'){
	if ($_GET['date-range-form-user'] == "all"){
		//select all users with total hours column
		$allUsers = true;
		if ($_GET['date-range-form-dates'] == "all"){
			$result_hours_list = mysql_query("SELECT users.id AS id, email, SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(end,start)))) AS total FROM hours, users WHERE users.id = hours.userid GROUP BY email ORDER BY email");
		}else{
			//date range
			$date_range_dates = explode("^", $_GET['date-range-form-dates']);
			$result_hours_list = mysql_query("SELECT users.id AS id, email, SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(end,start)))) AS total FROM hours, users WHERE users.id = hours.userid AND start>='".$date_range_dates[0]."' AND end<'".$date_range_dates[1]."' GROUP BY email ORDER BY email");
			
			$result_user_email = mysql_query("SELECT * FROM users WHERE id='".$_GET['date-range-form-user']."'");
			$row_user_email = mysql_fetch_array( $result_user_email );
			$begindate = $date_range_dates[0];
			
			
			$fulltimeUsers = true;
			
			//people have vacation within the selected dates
			$query_vacation_people = "SELECT users.id FROM users, requests WHERE hours<0 AND status='ok' AND users.hourly = 0 AND users.id = requests.userid AND datebegin>='".$date_range_dates[0]."' AND (dateend<='".$date_range_dates[1]."' OR dateend='0000-00-00') GROUP BY id";
			
			//total hours 
			//$query_fulltime_total = "SELECT users.id AS id, email, SUM(hours) AS total FROM users,requests WHERE users.id = requests.userid AND users.id IN(".$query_vacation_people.") AND datebegin<='".$date_range_dates[1]."' AND (dateend<='".$date_range_dates[1]."' OR dateend='0000-00-00') GROUP BY email ORDER BY email ASC";
			$query_fulltime_total = "SELECT users.id AS id, email, SUM(hours) AS total FROM users,requests WHERE users.id = requests.userid AND users.id IN(".$query_vacation_people.") AND datebegin>='2012-01-01' AND datebegin<='".$date_range_dates[1]."' AND (dateend<='".$date_range_dates[1]."' OR dateend='0000-00-00') GROUP BY email ORDER BY email ASC";
			
			$query_fulltime_list = "SELECT email, SUM(hours) AS total FROM users,requests WHERE users.id = requests.userid AND users.id IN(".$query_vacation_people.") AND datebegin>='".$date_range_dates[0]."' AND (dateend<='".$date_range_dates[1]."' OR dateend='0000-00-00') GROUP BY email ORDER BY email ASC";
			
			$result_fulltime_total = mysql_query($query_fulltime_total);
			$result_fulltime_list = mysql_query($query_fulltime_list);
			
			/*
			echo $query_vacation_people.'<br><br>';
			echo $query_fulltime_total.'<br><br>';
			echo $query_fulltime_list.'<br><br>';
			*/
			
			
		}
	}else{
		if ($_GET['date-range-form-dates'] == "all"){
			$result_hours_list = mysql_query("SELECT id, start,end, TIMEDIFF(end,start) AS total FROM hours WHERE userid = '".$_GET['date-range-form-user']."' ORDER BY start DESC");
		}else{
			$date_range_dates = explode("^", $_GET['date-range-form-dates']);
			$query = "SELECT id, start,end, TIMEDIFF(end,start) AS total FROM hours WHERE userid = '".$_GET['date-range-form-user']."' AND start>='".$date_range_dates[0]."'  AND end<'".$date_range_dates[1]."' AND end!='0000-00-00 00:00:00' ORDER BY start ASC";
			$result_hours_list = mysql_query($query);
		}
	}
	if (isset($_GET['date-range-form-dates'])){
		if (($_GET['date-range-form-dates'] != 'all') && ($_GET['date-range-form-user'] != 'all')){
			$begindate = $date_range_dates[0];
			$enddate = $date_range_dates[1];
		}
	}
}else{
	$begindate = date( 'Y-m-d', $_SESSION['paydate'] - (60*60*24*14));
	$enddate = date( 'Y-m-d', $_SESSION['paydate']);
	$thequery = "SELECT id, start,end, TIMEDIFF(end,start) AS total FROM hours WHERE userid = '".$_SESSION['userid']."' AND start>='".$begindate."'  AND end<'".$enddate."' ORDER BY start DESC";
	$result_hours_list = mysql_query($thequery);
} ?>


<?php include 'inc-page-top.php'; ?>

<div class="content-wide">
    <ul id="content-ul" class="flo">
		<?php include 'inc-form-date-range.php'; ?>
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
							<div class="clockin">
								IN
							</div>
							<div class="clockout">
								Out
							</div>
							<div class="total">
                            	Total
							</div>
                        <? } ?>
                    </li>
                </ul>
                <ul id="requests" class="admin_requests requests flo bor">
                	<? 
						$count = 0;
						$daytotal = 0;
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
							$currentday = '';
							?>
								<?
                                while($row_hours_list = mysql_fetch_array($result_hours_list)){
                                    $count ++;
                                    ?>
                                        <?
                                        $thisday = date('l', strtotime ($row_hours_list['start']));
                                        
                                        if ($currentday == ''){
                                            $currentday = $thisday;
											echo '<li class="dayofweek">';
											echo '<h3>'.$thisday." (".date('m/d/Y', strtotime ($row_hours_list['start'])).") ".'</h3>';
											echo '<ul>';
                                        }else if ($currentday != $thisday){
                                            $currentday = $thisday;
											echo '</ul>';
                                            echo '</li>';
                                            echo '<li class="dayofweek">';
											echo '<h3>'.$thisday." (".date('m/d/Y', strtotime ($row_hours_list['start'])).") ".'</h3>';
											echo '<ul>';
                                        }
										$totalseconds += time_to_sec($row_hours_list['total']);
                                        
                                        ?>
                                        <li id="edit-item-<? echo $row_hours_list['id']; ?>" class="<? if ($count%2==0)echo' libg '; ?>">
                                            <div class="clockin">
                                                <a href="admin-hours.php?user=<?php echo $_GET['date-range-form-user']; ?>&timeid=<?php echo $row_hours_list['id']; ?>"><?php echo date('g:i:s', strtotime ($row_hours_list['start'])); ?></a>
                                            </div>
                                            <div class="clockout">
                                                <? if($row_hours_list['end'] == '0000-00-00 00:00:00'){ ?>
                                                        <a href="hours.php?action=clockout&clockoutid=<? echo $row_hours_list['id']; ?>">Clock Out</a>
                                                <? } else {
                                                    echo  date('g:i:s', strtotime ($row_hours_list['end']));
                                                } ?>
                                            </div>
                                            <div class="total">
                                                <? echo sec_to_time(time_to_sec($row_hours_list['total']));?>
                                            </div>
                							<? if (!$allUsers) { ?><div class="running-total"><? echo sec_to_time($totalseconds); ?></div><? } ?>
                                        </li>
                                    <? 
                                }
								?>
                            <?
						}
					?>
                </ul>
				<? if ($fulltimeUsers) { ?>
                <div style="clear:both; float:left; width:100%; padding-top:30px;">
                    <h3>Vacation Overages</h3>
                    <ul id="requests_headings" class="requests flo bor">
                        <li class="h">
                            <? if ($allUsers) { ?>
                                <div class="user">
                                    user
                                </div>
                                <div class="status">
                                    Used
                                </div>
                                <div class="status">
                                    Total
                                </div>
                                <div class="status">
                                    Form
                                </div>
                            <? } ?>
                        </li>
                    </ul>
                    <ul id="requests" class="admin_requests requests flo bor">
                        <?
                            $count = 0;
							if (isset($_GET['date-range-form-dates'])){
								$date_range_dates = explode("^", $_GET['date-range-form-dates']);
							}
                            while($row_fulltime_total = mysql_fetch_array($result_fulltime_total)){
								$row_fulltime_list = mysql_fetch_array($result_fulltime_list);
                                $count ++;
                    			
								if ($row_fulltime_total['email'] == $row_fulltime_list['email']){
									
									?>
										<li id="edit-item-<? echo $row_fulltime_total['id']; ?>" class="<? if ($count%2==0)echo' libg '; ?>">
												<div class="user"><?php echo $row_fulltime_total['email']; ?></div>
                                                <div class="status"><?php echo $row_fulltime_list['total']; ?></div>
                                                <div class="status"><?php echo $row_fulltime_total['total']; ?></div>
                                                <? if ($row_fulltime_total['total'] < 0){ ?>
                                                	<div class="status"> 
                                                   		<form name="request_form" id="request_form_<?php echo $row_fulltime_total['id']; ?>" method="post" class="vacation-overage-form">
                                    						<input id="requesting_off_date" name="requesting_off_date" type="hidden" value="<?php echo $date_range_dates[1]; ?>">
                                    						<input id="requesting_off_user" name="requesting_off_user" type="hidden" value="<?php echo $row_fulltime_total['id']; ?>">
                                                            <input id="request_off_area" name="request_off_area" type="hidden" value="<? echo $page_name; ?>" />
                                                            <input id="requesting_off_created_by" name="requesting_off_created_by" type="hidden" value="<? echo $_SESSION['userid']; ?>" />
                                                            <input id="requesting_off_amount_input" name="requesting_off_amount_input" type="hidden" value="<?php echo $row_fulltime_total['total']*-1; ?>" />
                                                            <input type="submit" value="Add <? echo $row_fulltime_total['total']*-1; ?>" />
                                                        </form>
														<script type="text/javascript">
                                                            $(function() {
                                                                $('#request_form_<?php echo $row_fulltime_total['id']; ?>').ajaxForm({
                                                                    url:		'php-request-vacation-overage.php', 
                                                                    resetForm: 	true        // reset the form after successful submit 
                                                                });
                                                            });
                                                        </script>
                                                    </div>
                                                <? } ?>
										</li>
									<? 
								}else{
									?>
                                        <li id="edit-item-<? echo $row_fulltime_total['id']; ?>" class="<? if ($count%2==0)echo' libg '; ?>">
                                                <?php echo $row_fulltime_total['email'].' <> '.$row_fulltime_list['email']; ?>
                                        </li>
                                    <?
								}
                            }
                        ?>
                    </ul>
                   </div>
                <? } ?>
            </div>
        </li>
    </ul>
</div>
<?php include 'inc-page-bottom.php'; ?>