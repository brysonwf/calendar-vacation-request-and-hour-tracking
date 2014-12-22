<? include 'begin.php'; ?>
<? $page_name = 'Vacation'; ?>
<? include 'inc-page-top.php'; ?>

<div class="content">
    <ul id="content-ul" class="flo">
    	<? echo $page_message; ?>        
        <? if ($_SESSION['logged'] == true){ ?>
            <li class="content-li">
                <div class="drawer">
                    <ul id="requests_headings" class="requests flo bor">
                        <li class="h">
                            <div class="time-span">
                                Hours
                            </div>
                            <div class="date">
                                Date
                            </div>
                            <div class="reason">
                                Reason
                            </div>
                            <div class="status">
                                Status
                            </div>
                        </li>
                    </ul>
                    <ul id="requests" class="requests flo bor">
						<? 
						$count = 0;
						
                        $result_hours_list = mysql_query("SELECT * FROM requests WHERE userid='".$_SESSION['userid']."' and datebegin>='2012-01-01' ORDER BY datebegin DESC");
						while($row_hours_list = mysql_fetch_array($result_hours_list)){
							$count ++;
							$hour_list_date_array = explode("-", $row_hours_list['datebegin']);
							$current_li_date = $hour_list_date_array[0].$hour_list_date_array[1].$hour_list_date_array[2];
							
							$status_class = 'orange';
							$hour_list_status = $row_hours_list['status'];
							$undo_class = '';
							$cancel_class = '';
							switch ($hour_list_status){
								case "OK":
									$status_class = 'green';
									break;
								case "NO":
								case "X":
									$status_class = 'red';
									$undo_class = 'visible';
									$cancel_class = 'invisible';
									break;
							}
							$li_type = ($now > $current_li_date)? 'past-date': 'future-date';
							
                            ?>
                            <li id="hours-list-<? echo $row_hours_list['id']; ?>" class=" created-by-<? echo $row_hours_list['requesting_off_created_by'];?> <? echo $li_type; ?> <? if ($count%2==0)echo'libg'; ?> <? echo 'status-'.$status_class; ?>">
								<? $hour_list_hours = $row_hours_list['hours']; ?>
                                <? 
								if (strtotime($hour_list_date_array[1]."/".$hour_list_date_array[2]."/".$hour_list_date_array[0]) > strtotime('now')){ ?>
									<? if (($hour_list_hours<=0) && $row_hours_list['createdby'] == $_SESSION['userid']){ ?>
										<div id="hours-list-<? echo $row_hours_list['id']; ?>_actions" class="actions <? echo $undo_class; ?>">
											<a class="cancel <? echo $cancel_class; ?>" href="#cancel-request-<? echo $row_hours_list['id']; ?>">X</a>
											<a class="undo <? echo $undo_class; ?>" href="#undo-request-<? echo $row_hours_list['id']; ?>"><img src="img/icon-undo.png" alt="Undo" /></a>
										</div>
									<? } ?>
                                <? } ?>
                                <div class="time-span <? echo $hour_list_hours<0 ? 'red' : 'green'; ?>" >
                                    <? echo $hour_list_hours; ?> 
                                </div>
                                <div class="date">
                                    <? echo $hour_list_date_array[1]."/".$hour_list_date_array[2]."/".$hour_list_date_array[0]; ?>
                                    <?
                                    if ($row_hours_list['hours']<0) {
										$cur_hours = ($row_hours_list['hours']*(-1));
										if ( $cur_hours < 8) {
											?>
                                            	<span class="hours">
                                                    <? 
                                                        $begin = strtotime($row_hours_list['timebegin']);
                                                        if (date("i", $begin) == 0){
                                                            $begin_date = date("g", $begin);
                                                        }else{
                                                            $begin_date = date("g:i", $begin);
                                                        }
                                                        echo $begin_date.' - ';
                                                        
                                                        if ($row_hours_list['lunch'] == 1){
                                                            $lunchtime = 1*60*60;
                                                        }else if ($row_hours_list['lunch'] == .5){
                                                            $lunchtime = .5*60*60;
                                                        }else{
                                                            $lunchtime = 0;
                                                        }
                                                        
                                                        $end = $begin + (60*60)*($row_hours_list['hours']*-1) + $lunchtime;
                                                        
                                                        if (date("i", $end) == 0){
                                                            echo date("g", $end);
                                                        }else{
                                                            echo date("g:i", $end);
                                                        }
                                                    ?>
                                                </span>
											<?
                                    	}
									} ?>
                                </div>
                                <div class="reason">
                                    <? echo urldecode($row_hours_list['reason']); ?>
                                </div>
                                <div class="status <? echo $status_class; ?>" <? if($hour_list_status == 'X') echo 'style="display:none;"'; ?>>
                                    <? echo $hour_list_status; ?>
                                </div>
                                <div class="delete-col" <? if($hour_list_status == 'X') echo 'style="display:block;"'; ?>>
                                    <a class="delete" href="#permanatly-delete-<? echo $row_hours_list['id']; ?>">Delete</a>
                                </div>
                            </li>
                        <? } ?>
                    </ul>
                    <script type="text/javascript">
						$(document).ready(function(){
							buttons();
						});
					</script> 
                </div>
            </li>
        <? } ?>
    </ul>
</div>

<div class="sidebar">
    <ul class="flo">
        <? if ($_SESSION['logged']){ ?> 
            <li>
                <? include 'inc-form-request-vacation.php'; ?>
            </li>
		<? }  ?>
    </ul>
</div>

<? include 'inc-page-bottom.php'; ?>
