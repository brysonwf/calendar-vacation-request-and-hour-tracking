<?php include 'begin.php'; ?>
<?php $page_name = 'Admin'; ?>
<?php $subpage_name = 'Vacation'; ?>
<?php $admin_page = true; ?>
<?php include 'inc-page-top.php'; ?>

<div class="content" style="width:100%;">
    <ul id="content-ul" class="flo">        
        <?php if ($_SESSION['admin'] >=1 ){ ?>
            <li class="content-li">
            	<form name="add_time_form" id="add_time_form" method="post" class="form" style="display:none;">
                	<fieldset>
                    	<legend>Add Time</legend>
                        <p class="form">
							<? 
                                $query_users_list = "SELECT id, email FROM users ";
                                if ($_SESSION['admin'] != 2){
                                    $query_users_list.= "WHERE users.manager='".$_SESSION['login_email']."' ";
                                }
                                $query_users_list.= "ORDER BY email";
                                $result_users_list = mysql_query($query_users_list);
                            ?>
                            <label>User</label><select id="add_time_form_user" name="add_time_form_user">
                                <!-- <option value="all">All</option> -->
								<? while($row_users_list = mysql_fetch_array($result_users_list)){ ?>
                                	<option value="<? echo $row_users_list['id']; ?>"><? echo $row_users_list['email']; ?></option>
                                <? } ?>
                            </select>
                        </p>
                        <p class="form">
                            <label>Amount</label><input id="add_time_form_hours" name="add_time_form_hours" />
                        </p>
                        <p class="form">
                            <label>Reason</label><textarea id="add_time_form_reason" name="add_time_form_reason"></textarea>
                        </p>
                        <p class="submit"><input type="submit" value="Add" class="submit" /></p>
                    </fieldset>
                </form>
				<script type="text/javascript">
                    $(function() {
                        $('#add_time_form').ajaxForm({
                            url:		'php-time-add.php', 
							beforeSubmit:    requestTimeAddValidate,  // post-submit callback
							success:    requestTimeAddResponse,  // post-submit callback
                            resetForm: 	true        // reset the form after successful submit 
                        });
						$('#request_form').hide();
						buttons();
                    });
					function requestTimeAddValidate(formData, jqForm, options) {
						
						var form = jqForm[0];
						
						var y=parseInt(form.add_time_form_hours.value)
						
						if (!form.add_time_form_hours.value) {
							alert('Please enter an amount.'); 
							return false;
						}else if(!form.add_time_form_reason.value) {
							alert('Please enter a reason.');
							return false;
						}else if(isNaN(y)) {
							alert('Please enter a number for the amount.');
							return false;
						}
						return true;
					}
					function requestTimeAddResponse(responseText, statusText, xhr, $form)  {
						
						//add time to myself if i've added time to myself
						//$("#vacation-hours-digits").html(parseFloat($("#vacation-hours-digits").html()) - parseFloat($("#requesting_off_amount_input").get(0).value));
						
						//$("#request_day_type_single").click();
						
						$("ul#requests").prepend(responseText);
						
						//$('#requesting_off_amount').get(0).value = "Request to take off 0 hours";
						buttons();
					} 
                </script>
            </li>
            <li class="content-li">
            	<? include 'inc-form-request-vacation.php'; ?>
            </li>
            <li class="content-li">
                <div class="drawer">
                    <ul id="requests_headings" class="admin_requests requests flo bor">
                        <li class="h">
                            <div class="user">
                                User
                            </div>
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
						<?php 
						$count = 0;
						$now = date("Ymd");
						$query_hours_list = "SELECT requests.id as id, reason, requestedon, datebegin, dateend, hours, status, createdby, email, password, admin, timebegin, hours, lunch FROM requests, users WHERE userid = users.id ";
						if ($_SESSION['admin'] != 2){
							$query_hours_list.= "AND users.manager='".$_SESSION['login_email']."' ";
						}
						$query_hours_list.= "ORDER BY datebegin DESC LIMIT 100";
						$result_hours_list = mysql_query($query_hours_list);
						while($row_hours_list = mysql_fetch_array($result_hours_list)){
							$count ++;
							
							$hour_list_datebegin_array = explode("-", $row_hours_list['datebegin']);
							$current_li_datebegin = $hour_list_datebegin_array[0].$hour_list_datebegin_array[1].$hour_list_datebegin_array[2];
							
							$hour_list_dateend_array = explode("-", $row_hours_list['dateend']);
							$current_li_dateend = $hour_list_dateend_array[1]."/".$hour_list_dateend_array[2]."/".$hour_list_dateend_array[0];
							
							$li_type = ($now > $current_li_datebegin)? 'past-date': 'future-date';
							
							if ($li_type == 'past-date'){
								//break;
							}
							
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
							
                            ?>
                             <?php $status = $row_hours_list['status']; ?>
                            <li id="edit-item-<? echo $row_hours_list['id']; ?>" class=" created-by-<?php echo $row_hours_list['createdby'];?> <? echo $li_type; ?> <? if ($count%2==0)echo' libg '; ?>  <? echo ($status=='OK')? 'approved': 'unapproved'; ?>">
                                <div id="hours-list-<? echo $row_hours_list['id']; ?>_actions" class="actions <? echo $undo_class; ?>">
                                    <a class="cancel <? echo $cancel_class; ?>" href="#cancel-request-<? echo $row_hours_list['id']; ?>">X</a>
                                    <a class="undo <? echo $undo_class; ?>" href="#undo-request-<? echo $row_hours_list['id']; ?>"><img src="img/icon-undo.png" alt="Undo" /></a>
                                </div>
                                <div class="user">
                                    <?php echo $row_hours_list['email']; ?>
                                </div>
                                <?php $hour_list_hours = $row_hours_list['hours']; ?>
                                <div class="time-span <?php echo $hour_list_hours<0 ? 'red' : 'green'; ?>" >
                                    <?php echo $hour_list_hours; ?> 
                                </div>
                                <div class="date">
									<?php 
									
									echo $hour_list_datebegin_array[1]."/".$hour_list_datebegin_array[2]."/".$hour_list_datebegin_array[0];
									
									
									if ($hour_list_datebegin_array[1]."/".$hour_list_datebegin_array[2]."/".$hour_list_datebegin_array[0] !=  $hour_list_dateend_array[1]."/".$hour_list_dateend_array[2]."/".$hour_list_dateend_array[0]){
										if (isset($hour_list_dateend_array[1]) && $hour_list_dateend_array[1] != 0){
											?>
											<span class="request-multi-day-span" > - <?php echo $hour_list_dateend_array[1]."/".$hour_list_dateend_array[2]."/".$hour_list_dateend_array[0]; ?></span>
											<?
										}
									}
									
                                    if ($row_hours_list['hours']<0) {
										if ( ($row_hours_list['hours']*(-1)) < 8) {
											?> 
                                           		<span class="hours">
                                                    (<? 
                                                        $begin = strtotime($row_hours_list['timebegin']);
                                                        if (date("i", $begin) == 0){
                                                            $begin_date = date("g", $begin);
                                                        }else{
                                                            $begin_date = date("g:i", $begin);
                                                        }
                                                        echo $begin_date.' - ';
                                                        
                                                        
                                                        if ($row_hours_list['lunch'] == 1){
                                                            $lunchtime = 1*60*60;
                                                        }else{
                                                            $lunchtime = 0;
                                                        }
                                                        
                                                        $end = $begin + (60*60)*($row_hours_list['hours']*-1) + $lunchtime;
                                                        
                                                        if (date("i", $end) == 0){
                                                            echo date("g", $end);
                                                        }else{
                                                            echo date("g:i", $end);
                                                        }
                                                    ?>)
                                                </span>
                                            <?
                                    	}
									} ?>
                                            	
                                </div>
                                <div class="reason">
                                    <?php echo $row_hours_list['reason']; ?>
                                </div>
                                <div class="status" style=" <? if ($hour_list_hours>0){ echo 'display:none;'; } ?> ">
                                    <a class="admin-NO status-link <? echo ($status=='NO')? 'NO_active': 'NO_status'; ?>" href="#item-<? echo $row_hours_list['id']; ?>">NO</a> <a class="admin-UK status-link <? echo ($status=='?')? 'unknown_active': 'unknown_status'; ?>" href="#item-<? echo $row_hours_list['id']; ?>">?</a> <a class="admin-OK status-link <? echo ($status=='OK')? 'OK_active': 'OK_status'; ?>" href="#item-<? echo $row_hours_list['id']; ?>">OK</a>
                                </div>
                                <div class="delete-col">
                                    <a class="delete" href="#permanatly-delete-<? echo $row_hours_list['id']; ?>">Delete</a>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </li>
        <?php } ?>
    </ul>
</div>

<?php include 'inc-page-bottom.php'; ?>