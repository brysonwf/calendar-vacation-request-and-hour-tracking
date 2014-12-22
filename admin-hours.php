<?php include 'begin.php'; ?>
<?php $page_name = 'Admin'; ?>
<?php $subpage_name = 'Hours Admin'; ?>
<?php $admin_page = true; ?>
<?php include 'inc-page-top.php'; ?>

<div class="content" style="width:100%;">
    <ul id="content-ul" class="flo">
        <?php if ($_SESSION['admin'] >= 1){ ?>
            <li class="content-li">
                <div class="drawer">
					<?php 
					$count = 0;
					$now = date("Ymd");
					
					if (isset($_GET['user']) && $_GET['user']!='') {
						
						if (isset($_GET['timeid']) && $_GET['timeid']!='') {
							
							if (isset($_GET['update']) && $_GET['update']=='true') {
								$result_users_list = mysql_query("UPDATE hours SET start='".$_POST['start']."', end='".$_POST['end']."' WHERE hours.userid=".$_GET['user']." AND hours.id=".$_GET['timeid']."");
							}
							
							$query_users_list = "SELECT hours.id as hourid, userid, start, end, email FROM hours,users WHERE hours.userid=".$_GET['user']." AND hours.id=".$_GET['timeid']." AND hours.userid=users.id ORDER BY email,start";
							$result_users_list = mysql_query($query_users_list);
							$row_users_list = mysql_fetch_array($result_users_list);
							
							if (isset($_GET['delete']) && $_GET['delete']=='true') {
								mysql_query("DELETE FROM hours WHERE hours.userid=".$_GET['user']." AND hours.id=".$_GET['timeid']);
								?>
                                The item has been removed. <a href="admin-hours.php?user=<?php echo $row_users_list['userid']; ?>">Go back to <?php echo $row_users_list['email']; ?>'s hours list</a>
                                <?
							}else{
								
								//has time id
								?>
								
								<form action="admin-hours.php?user=<?php echo $row_users_list['userid']; ?>&timeid=<?php echo $row_users_list['hourid']; ?>&update=true" name="update-hours-form" class="update-hours-form" method="post">
									<label>Start</label> <input name="start" type="input" value="<?php echo $row_users_list['start']; ?>" />
									<label>End</label> <input name="end" type="input" value="<?php echo $row_users_list['end']; ?>" />
									<input type="submit" value="Update" />
								</form>
								
								<p><a href="admin-hours.php?user=<?php echo $row_users_list['userid']; ?>&timeid=<?php echo $row_users_list['hourid']; ?>&delete=true">Delete</a></p>
								
								<?
							}
						}else{
							
							//has user no time id
							$query_users_list = "SELECT hours.id as hourid, userid, start, end, email FROM hours,users WHERE hours.userid=".$_GET['user']." AND hours.userid=users.id ORDER BY email,start DESC";
							$result_users_list = mysql_query($query_users_list);
							?>
							<ul id="requests_headings" class="requests flo bor">
								<li class="h">
									<div class="user">
										ID
									</div>
									<div class="time">
										Time
									</div>
								</li>
							</ul>
							<ul id="requests" class="admin_requests requests users flo bor">
								<?php 
								while($row_users_list = mysql_fetch_array($result_users_list)){
									$count ++;
									?>
									<li id="edit-item-<? echo $row_users_list['userid']; ?>" class="<? echo $li_type; ?> <? if ($count%2==0)echo' libg '; ?>">
										
										<div class="user">
                                        	<?php echo $row_users_list['hourid']; ?>
                                        </div>
										<div class="time">
											<a href="admin-hours.php?user=<?php echo $row_users_list['userid']; ?>&timeid=<?php echo $row_users_list['hourid']; ?>"><?php echo $row_users_list['start'].' - '.$row_users_list['end']; ?></a>
										</div>
									</li>
								<?php } ?>
							</ul>
                        	<?
						}
					}else{
						
						//default
						$query_users_list = "SELECT * FROM users WHERE hourly=1 ";
						if ($_SESSION['admin'] != 2){
							$query_users_list.= "AND users.manager='".$_SESSION['login_email']."' ";
						}
						$query_users_list.= "ORDER BY email";
						$result_users_list = mysql_query($query_users_list);
						?>
                        <ul id="requests_headings" class="requests flo bor">
                            <li class="h">
                                <div class="user">
                                    User
                                </div>
                            </li>
                        </ul>
                        <ul id="requests" class="admin_requests requests users flo bor">
                            <?php 
                            while($row_users_list = mysql_fetch_array($result_users_list)){
                                $count ++;
                                ?>
                                <li id="edit-item-<? echo $row_users_list['id']; ?>" class="<? echo $li_type; ?> <? if ($count%2==0)echo' libg '; ?>">
                                    
                                    <div class="user">
                                        <a href="admin-hours.php?user=<?php echo $row_users_list['id']; ?>"><?php echo $row_users_list['email']; ?></a>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                    	<?
					}
					?>
                    <script type="text/javascript">
						$(document).ready(function(){
							buttons();
						});
					</script>
                </div>
            </li>
        <?php } ?>
    </ul>
</div>

<?php include 'inc-page-bottom.php'; ?>