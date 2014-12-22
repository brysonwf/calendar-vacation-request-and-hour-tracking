<?php include 'begin.php'; ?>
<?php $page_name = 'Admin'; ?>
<?php $subpage_name = 'Users'; ?>
<?php $admin_page = true; ?>
<?php include 'inc-page-top.php'; ?>

<div class="content" style="width:100%;">
    <ul id="content-ul" class="flo">
        <?php if ($_SESSION['isAdmin'] == true){ ?>
            <li class="content-li">
                <div class="drawer">
                    <ul id="requests_headings" class="requests flo bor">
                        <li class="h">
                            <div class="user">
                                User
                            </div>
                            <div class="date">
                                Created On
                            </div>
                            <div class="status">
                                Delete
                            </div>
                        </li>
                    </ul>
                    <ul id="requests" class="admin_requests requests users flo bor">
						<?php 
						$count = 0;
						$now = date("Ymd");
                        $result_users_list = mysql_query("SELECT * FROM users ORDER BY email");
						while($row_users_list = mysql_fetch_array($result_users_list)){
							$count ++;
                            ?>
                            <li id="edit-item-<? echo $row_users_list['id']; ?>" class="<? echo $li_type; ?> <? if ($count%2==0)echo' libg '; ?>">
                                
                                <div class="user">
                                    <?php echo $row_users_list['email']; ?>
                                </div>
                                <div class="date">
                                    <?php echo $row_users_list['createdon']; ?>
                                </div>
                                <div class="delete-col">
                                	<a class="delete" href="#permanatly-delete-user-<? echo $row_users_list['id']; ?>">Delete</a>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
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