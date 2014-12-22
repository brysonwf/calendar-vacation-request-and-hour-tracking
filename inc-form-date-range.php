        <li id="payday-ul">
        	<form id="date-range-form" action="hours.php" class="form">
            	<fieldset>
                	<input name="action" type="hidden" value="date-range" />
                    <p class="form">
                        <? if ( ($_SESSION['admin'] == 1) || ($_SESSION['admin'] == 2) ) { ?>
                                <label>User:</label>
                                <select id="date-range-form-user" name="date-range-form-user">
                        			<? if ($_SESSION['admin'] == 2) { ?> <option value="all">All</option> <? } ?>
                                    <? 
									$query_users_list = "SELECT id, email FROM users WHERE hourly='1' ";
									if ($_SESSION['admin'] != 2){
										$query_users_list.= "AND users.manager='".$_SESSION['login_email']."' ";
									}
									$query_users_list.= "ORDER BY email";
                                    $result_users_list = mysql_query($query_users_list);
                                    while($row_users_list = mysql_fetch_array($result_users_list)){
										$selected = '';
										if (isset($_GET['date-range-form-user']) && $_GET['date-range-form-user'] == $row_users_list['id']){ 
											$selected = ' selected="selected" ';
										} ?>
                                        <option value="<? echo $row_users_list['id']; ?>" <? echo $selected; ?> ><? echo $row_users_list['email']; ?></option>
                                    <? } ?>
                                </select>
                        <? } else{ ?>
                                <input id="date-range-form-user" name="date-range-form-user" type="hidden" value="<? echo $_SESSION['userid']; ?>">
                        <? } ?> 
                        <label>Dates:</label> 
                        <select id="date-range-form-dates" name="date-range-form-dates">
                            <?	
								$curr = date( 'Y-m-d', $_SESSION['paydate']);
								$prev= "";
								for ($x=1; $x<=10; $x++){
									
									$prev = date( 'Y-m-d', strtotime($curr) - (60*60*24*14));
									if ($prev == $begindate){
										$selected = ' selected="selected" ';
									}else{ 
										$selected = '';
									}
                                    ?>
                                        <option value="<? echo $prev; ?>^<? echo $curr; ?>" <? echo $selected; ?> ><? echo date( 'm/d/Y', strtotime($prev))." - ".date( 'm/d/Y', (strtotime($curr) - (60*60*24))); ?></option>
                                    <?
									$curr = $prev;
								}
                            ?>
                        	<option value="all">All Time</option>
                        </select>
                        <input class="submit" type="submit" value="Go" />
                    </p>
                </fieldset>
            </form>
        </li>