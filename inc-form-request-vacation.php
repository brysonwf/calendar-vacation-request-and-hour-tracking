				<form name="request_form" id="request_form" method="post" class="form">
                    <fieldset>
                        <legend>Vacation Request Form</legend>
                       	<p class="">
							<?
                            $query_totalhours = "SELECT users.id, email, SUM(hours) AS totalhours, hourly FROM users LEFT JOIN requests ON users.id = requests.userid WHERE users.id='".$_SESSION['userid']."' GROUP BY users.id ORDER BY email";
                            $result_totalhours = mysql_query($query_totalhours);
                            $row_totalhours = mysql_fetch_array($result_totalhours); ?>
							<? if ( ($_SESSION['admin'] >= 1) && $page_name == "Admin") { ?>
                            		<input name="area" type="hidden" value="admin" />
                                    <label>For:</label>
                                    <select id="request_day_user_select" name="request_day_user_select">
                                        <?
										$query_users_list = "SELECT users.id, email, SUM(hours) AS totalhours FROM users LEFT JOIN requests ON users.id = requests.userid ";
										if ($_SESSION['admin'] != 2){
											$query_users_list.= "WHERE users.manager='".$_SESSION['login_email']."' ";
										}
										$query_users_list.= "GROUP BY users.id ORDER BY email";
										$result_users_list = mysql_query($query_users_list);
                                        while($row_users_list = mysql_fetch_array($result_users_list)){ ?>
                                            <option value="<? echo $row_users_list['id']; ?>"><? echo $row_users_list['email'].'  ('.$row_users_list['totalhours'].')'; ?></option>
                                        <? } ?>
                                    </select>
                            <? } else{ ?>
                                        <input type="hidden" name="totalhours" value="<? if ($row_totalhours['hourly'] = '1'){ echo 'Hourly'; }else{ echo $row_totalhours['totalhours']; } ?>" />
                            		<input name="area" type="hidden" value="user" />
                                    <input id="request_day_user_select" name="request_day_user_select" type="hidden" value="<? echo $_SESSION['userid']; ?>">
                            <? } ?>
                        </p>
                        <p class="form request_day_type_p">
                        	<div class="request_day_type_div">
    <div class="col"><input id="request_day_type_partial" type="radio" name="request_day_type" value="Partial" class="pointer" /><label for="request_day_type_partial" class="pointer">Partial Day</label></div>
    <div class="col"><input id="request_day_type_single" type="radio" name="request_day_type" value="Single" checked="checked" class="pointer" /><label for="request_day_type_single" class="pointer">Single Day</label></div>
    <div class="col"><input id="request_day_type_multi" type="radio" name="request_day_type" value="Multiple" class="pointer" /><label for="request_day_type_multi" class="pointer">Multiple Day</label></div>
                                <script type="text/javascript">
                                    $(document).ready(function(){
                                        $("#request_day_type_partial").click(function(event){
                                            $("#request-partial-day-div").show();
                                            $("#request-multi-day-span").hide();
                                            $('.time-select').change();
											$("#request_date_end").get(0).value = $("#request_date_begin").get(0).value;
                                        });
                                        $("#request_day_type_single").click(function(event){
                                            $("#request-multi-day-span").hide();
                                            $("#request-partial-day-div").hide();
                                            $("#request_date_begin").click();
                                            calculateHours("single", $('#request_form input:radio[name=request_day_type]:checked').val(), <? echo $now; ?>, $("#request_date_begin").get(0).value, $("#request_date_end").get(0).value, <? echo $row_totalhours['totalhours']; ?>);
                                        });
                                        $("#request_day_type_multi").click(function(event){
                                            $("#request-multi-day-span").show();
                                            $("#request-partial-day-div").hide();
                                            $("#request_date_begin").click();
                                            calculateHours("multi", $('#request_form input:radio[name=request_day_type]:checked').val(), <? echo $now; ?>, $("#request_date_begin").get(0).value, $("#request_date_end").get(0).value, <? echo $row_totalhours['totalhours']; ?>);
                                        });
                                    });
                                </script>
                            </div>
                        </p>
                        <p class="">
                        	<label>Date</label> <br/>
                            <input id="request_date_begin" name="request_date_begin" type="text" />
                            <span id="request-multi-day-span"> to <input id="request_date_end" name="request_date_end" type="text" /></span>
                            
							<script type="text/javascript">
                                $(function() {									
                                    $("#request_date_begin").datepicker({
                                        onSelect: function(dateText, inst) {
											//$.post('php-date-check.php', { id: $("#request_day_user_select").get(0).value, date: $("#request_date_begin").get(0).value }, function(data) {
												//isGoodDate == 'f';
												//var isGoodDate = ""+$.trim(data).toString().substr(0,1);
												//if (isGoodDate == 't'){
													calculateHours("begin", $('#request_form input:radio[name=request_day_type]:checked').val(), <? echo $now; ?>, $("#request_date_begin").get(0).value, $("#request_date_end").get(0).value, <? echo $row_totalhours['totalhours']; ?>);
												//}else{
													//alert('Please enter a date not already selected.');
													//$("#request_date_begin").get(0).value = '';
												//}
											//});
                                        }
                                    });
                                    $("#request_date_end").datepicker({
                                        onSelect: function(dateText, inst) {
											calculateHours("end", $('#request_form input:radio[name=request_day_type]:checked').val(), <? echo $now; ?>, $("#request_date_begin").get(0).value, $("#request_date_end").get(0).value, <? echo $row_totalhours['totalhours']; ?>);
										}
                                    });
									$('#request-multi-day-span').hide();
                                });
                            </script>
                        </p>
                        <div id="request-partial-day-div" >
                            <p>
                                <select id="partial_begin_hour" name="partial_begin_hour" class="time-select">
                                	<option value="8">08</option>
                                	<option value="9">09</option>
                                	<option value="10">10</option>
                                	<option value="11">11</option>
                                	<option value="12">12</option>
                                	<option value="13">01</option>
                                	<option value="14">02</option>
                                	<option value="15">03</option>
                                	<option value="16">04</option>
                                	<option value="17">05</option>
                                </select>
                                <select id="partial_begin_min" name="partial_begin_min" class="time-select">
                                	<option>00</option>
                                	<option>15</option>
                                	<option>30</option>
                                	<option>45</option>
                                </select> -  
                                <select id="partial_end_hour" name="partial_end_hour" class="time-select">
                                	<option value="8">08</option>
                                	<option value="9">09</option>
                                	<option value="10">10</option>
                                	<option value="11">11</option>
                                	<option value="12">12</option>
                                	<option value="13">01</option>
                                	<option value="14">02</option>
                                	<option value="15">03</option>
                                	<option value="16">04</option>
                                	<option value="17">05</option>
                                </select>
                                <select id="partial_end_min" name="partial_end_min" class="time-select">
                                	<option>00</option>
                                	<option>15</option>
                                	<option>30</option>
                                	<option>45</option>
                                </select>
                                
								<script type="text/javascript">
                                    $(function() {
										var requesting_off_amount = 0;
										$('.time-select').change(function() {
											if (parseInt($('#partial_begin_hour').get(0).value) > parseInt($('#partial_end_hour').get(0).value) ){
												$('#partial_end_hour').get(0).value = parseInt($('#partial_begin_hour').get(0).value);
											}else if ($('#partial_begin_hour').get(0).value == $('#partial_end_hour').get(0).value ){
												if ($('#partial_begin_min').get(0).value > $('#partial_end_min').get(0).value ){
													$('#partial_end_min').get(0).value = parseInt($('#partial_begin_min').get(0).value);
												}
											}
											
											/*
											
											add function:
											on change of end and it is less than begin then change begin
											
											*/
											
											var requesting_off_amount_hour = parseFloat($('#partial_end_hour').get(0).value) - parseFloat($('#partial_begin_hour').get(0).value);
											var requesting_off_amount_min = ( parseFloat($('#partial_end_min').get(0).value) - parseFloat($('#partial_begin_min').get(0).value) ) /60 ;
											
											requesting_off_amount = requesting_off_amount_hour + requesting_off_amount_min;
											requesting_off_amount -=$('input:radio[name=lunch]:checked').val();
											
											/*
											if ($('#request_off_lunch_hour').val() == '30'){
												requesting_off_amount-=0.5;
											}else if ($('#request_off_lunch_hour').val() == '60'){
												requesting_off_amount-=1;
											}
											*/
											
											$('#requesting_off_amount').get(0).value = "Request to take off "+requesting_off_amount+" hours";
											$("#requesting_off_amount_input").get(0).value = requesting_off_amount;
										});
										$('#request-partial-day-div').hide();
										$('input:radio[name=lunch]').click(function() {
											$('.time-select').change();
										});
                                    });
                           	 	</script>
                                <div>
                                   <input name="lunch" type="radio" value="0" checked="checked" /><label for="request_off_lunch_hour">No Lunch Included</label><br />
                                   <input name="lunch" type="radio" value=".5" /><label for="request_off_lunch_hour">half hour Lunch Included</label><br />
                                   <input name="lunch" type="radio" value="1" /><label for="request_off_lunch_hour">1 hour Lunch Included</label>
                                </div>
                             </p>
                        </div>
                        
                        <p class="textarea"><label>Reason</label> <textarea id="request_reason" name="request_reason"></textarea></p>
                        
                        <input id="request_off_area" name="request_off_area" type="hidden" value="<? echo $page_name; ?>" />
                        <input id="requesting_off_created_by" name="requesting_off_created_by" type="hidden" value="<? echo $_SESSION['userid']; ?>" />
                        <input id="requesting_off_amount_input" name="requesting_off_amount_input" type="hidden" value="0" />
                        <p><input id="requesting_off_amount" type="submit" value="Request to take off 0 hours" /></p>
						<script type="text/javascript">
                            $(function() {
								$('#request_form').ajaxForm({
									url:		'php-request-vacation.php', 
									beforeSubmit:    requestValidate,  // post-submit callback
									success:    requestResponse,  // post-submit callback
									resetForm: 	true        // reset the form after successful submit 
								});
							});
							function requestValidate(formData, jqForm, options) {
								var form = jqForm[0];
								if (!form.request_date_begin.value) {
									alert('Please enter a date'); 
									return false;
								}else if(!form.request_reason.value) {
									alert('Please enter a reason');
									return false;
								}/*else if(parseFloat(form.requesting_off_amount_input.value) > parseFloat($("#vacation-hours-digits").html())) {
									alert('Not enough vacation time.');
									return false;
								}*/
								return true;
							}
							function requestResponse(responseText, statusText, xhr, $form)  { 
								$("#vacation-hours-digits").html(parseFloat($("#vacation-hours-digits").html()) - parseFloat($("#requesting_off_amount_input").get(0).value));
								$("#request_day_type_single").click();
								$("ul#requests").prepend(responseText);
								$('#requesting_off_amount').get(0).value = "Request to take off 0 hours";
								buttons();
							} 
                        </script>
                    	<div id="overage-message"></div>
                    </fieldset>
                </form>