				
        <?php if (isset($_SESSION['admin'])){ ?>
        <?php if ($_SESSION['admin'] >=2 ){ ?>
				<?php 
                    $count = 0;
                    $daysoff = array();
                    $result_hours_list = mysql_query("SELECT requests.id as id, reason, requestedon, datebegin, dateend, timebegin, hours, status, createdby, email, password, admin, color, lunch FROM requests, users WHERE userid = users.id AND status='OK' AND datebegin > '".date("Y-m-d", strtotime("-40 days"))."' ORDER BY datebegin ASC") or die("Query failed with error: ".mysql_error());
					$count = 0;
                    while($row_hours_list = mysql_fetch_array($result_hours_list)){
                        
                        $hour_list_datebegin_array = explode("-", $row_hours_list['datebegin']);
                        $current_li_datebegin = $hour_list_datebegin_array[1]."/".$hour_list_datebegin_array[2]."/".$hour_list_datebegin_array[0];
                        
                        $hour_list_dateend_array = explode("-", $row_hours_list['dateend']);
                        $current_li_dateend = $hour_list_dateend_array[1]."/".$hour_list_dateend_array[2]."/".$hour_list_dateend_array[0];
						
                        //add date to array
						for($x=strtotime($current_li_datebegin); $x<=strtotime($current_li_dateend); $x+=86400){
							$currentdate = date("m/d/Y", $x);
                        	$daysoff[$currentdate][$count]['email'] = $row_hours_list['email'];
							$daysoff[$currentdate][$count]['color'] = $row_hours_list['color'];
							if ($row_hours_list['hours'] > -8){
                        		$daysoff[$currentdate][$count]['partial'] = true;
                        		$daysoff[$currentdate][$count]['hours'] = $row_hours_list['hours'];
								$daysoff[$currentdate][$count]['timebegin'] = $row_hours_list['timebegin'];
								$daysoff[$currentdate][$count]['lunch'] = $row_hours_list['lunch'];
							}else{
                        		$daysoff[$currentdate][$count]['partial'] = false;
							}
							$count++;
						}
                    }
				?>
                <ul id="calendar" class="">
                	<?
                        $startDay=false;
						if (isset($_GET['currentDate'])){
							$current_date = $_GET['currentDate'];
						}else{
							$current_date = date("Ymd");
						}
						$totaldays = 29;
                        for ($day=-6; $day<$totaldays; $day++){
                            
                            $sign = ($day>=0)? "+" : "";
                             
                            $timechange = strtotime($sign." ".$day." days", strtotime($current_date));
                            $now = date("m/d/Y", $timechange);
                            $now_comparison = date("Ymd", $timechange);
                            $daynum = date("d", $timechange);
                            $monthnum = date("m", $timechange);
                            $weekday = date("l", $timechange);
                            $weekdayshort = date("D", $timechange);
							
                        	$li_type = ($now_comparison == $current_date)? 'current-date': '';
							
                            if (!$startDay){
                                (($weekday == "Sunday")? $startDay=true: $startDay=false);
                            }
							
                            if ($startDay){
                                ?>
                                <li class=" <? echo $li_type; ?> <? if ($count%2==0)echo' libg '; $count++; if( ($weekday == "Sunday") || ($weekday == "Saturday") ){ echo 'weekend'; } ?>">
                                    <div class="date-heading">
                                    	<div class="day"><? echo $weekdayshort; ?></div>
                                    	<div class="date"><?php echo $now; ?></div>
                                    </div>
                                    <ul class="users">
										<? 
                                            if (isset($daysoff[$now])){
                                                foreach ($daysoff[$now] as $userarray){
													?>
                                                    	<li style="color:<? echo $userarray['color'] ?>">
                                                        	<?
																$useremailtruck = explode('@',$userarray['email']);
															?>
															<span class="email"><? echo $useremailtruck[0]; ?></span>
                                                            <? if ($userarray['partial']) { ?>
																<span class="hours">
																	<? 
																		$begin = strtotime($userarray['timebegin']);
																		if (date("i", $begin) == 0){
																			$begin_date = date("g", $begin);
																		}else{
																			$begin_date = date("g:i", $begin);
																		}
																		echo $begin_date.' - ';
																		
																		
																		if ($userarray['lunch'] == 1){
																			$lunchtime = 1*60*60;
																		}else{
																			$lunchtime = 0;
																		}
																		
																		$end = $begin + (60*60)*($userarray['hours']*-1) + $lunchtime;
																		
																		if (date("i", $end) == 0){
																			echo date("g", $end);
																		}else{
																			echo date("g:i", $end);
																		}
																	?>
                                                                </span>
                                                            <? } ?>
                                                        </li>
                                                    <?
                                                }
                                            }
                                        ?>
                                    </ul>
                                </li>
                                <?
                            }else{
								$totaldays++;
							}
                        } 
                    ?>
                </ul>
                <div class="previous-next-month">
                	<?
					
					$dateMinus28 = strtotime("-28 days", strtotime($current_date));
					$datePlus28 = strtotime("+28 days", strtotime($current_date));
					
					$prevMonth = 'http://mojostuff.com/mi/index.php?currentDate='.date("Ymd", $dateMinus28);
					$nextMonth = 'http://mojostuff.com/mi/index.php?currentDate='.date("Ymd", $datePlus28);

					?>
                    <a class="previous" href="<? echo $prevMonth; ?>">Previous Month</a>
                    <a class="next" href="<? echo $nextMonth; ?>">Next Month</a>
                </div>
        <?php } ?>
        <?php } ?>