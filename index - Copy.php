<?php include 'begin.php'; ?>
<?php $page_name = 'Home'; ?>
<?php include 'inc-page-top.php'; ?>

<div class="content-wide">
        <ul id="content-ul" class="flo">    
			<?php if ($_SESSION['logged'] == false){ ?>
				<?php include 'inc-form-login.php'; ?>
			<?php } ?>
            <li>
				<?php 
                    $count = 0;
                    $daysoff = array();;
                    $result_hours_list = mysql_query("SELECT requests.id as id, reason, requestedon, datebegin, dateend, timebegin, hours, status, createdby, email, password, admin FROM requests, users WHERE userid = users.id AND status='OK' ORDER BY datebegin ASC");
                    while($row_hours_list = mysql_fetch_array($result_hours_list)){
                    
                        
                        $hour_list_datebegin_array = explode("-", $row_hours_list['datebegin']);
                        $current_li_datebegin = $hour_list_datebegin_array[1]."/".$hour_list_datebegin_array[2]."/".$hour_list_datebegin_array[0];
                        
                        $hour_list_dateend_array = explode("-", $row_hours_list['dateend']);
                        $current_li_dateend = $hour_list_dateend_array[1]."/".$hour_list_dateend_array[2]."/".$hour_list_dateend_array[0];
                        
                        $li_type = ($now > $current_li_datebegin)? 'past-date': 'future-date';
						
                        //add date to array
						for($x=strtotime($current_li_datebegin); $x<=strtotime($current_li_dateend); $x+=86400){
                        	$daysoff[date("m/d/Y", $x)][] = .' - '.$row_hours_list['hours'].' starting at '.$row_hours_list['timebegin'];
						}
                    }
				?>
                <ul id="calendar" class="">
                	<?
                        $startDay=false;
                        for ($day=-7; $day<28; $day++){
                            
                            $sign = ($day>=0)? "+" : "";
                            
                            $timechange = strtotime($sign." ".$day." days");
                            $now = date("m/d/Y", $timechange);
                            $weekday = date("l", $timechange);
                            if (!$startDay){
                                (($weekday == "Sunday")? $startDay=true: $startDay=false);
                            }
							
                            if ($startDay){
                                ?>
                                <li class="<? if ($count%2==0)echo' libg '; $count++; if( ($weekday == "Sunday") || ($weekday == "Saturday") ){ echo 'weekend'; } ?>">
                                    <div class="day"><? echo $weekday; ?></div>
                                    <div class="date"><?php echo $now; ?></div>
                                    <ul class="users">
										<? 
                                            if (isset($daysoff[$now])){
                                                foreach ($daysoff[$now] as $var){
                                                    echo "<li>".$var."</li>";
                                                }
                                            }
                                        ?>
                                    </ul>
                                </li>
                                <?
                            }
                        } 
                    ?>
                </ul>
            </li>
       </ul>
</div>

<?php include 'inc-page-bottom.php'; ?>