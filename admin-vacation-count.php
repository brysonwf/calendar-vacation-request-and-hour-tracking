<?php include 'begin.php'; ?>
<?php $page_name = 'Admin'; ?>
<?php $subpage_name = 'Vacation Count'; ?>
<?php $admin_page = true; ?>
<?php include 'inc-page-top.php'; ?>

<div class="content" style="width:100%;">
    <ul id="content-ul" class="flo">
        <?php if ($_SESSION['admin'] >= 1){ ?>
            <li class="content-li">
                <div class="drawer">
                    <ul id="requests" class="admin_requests requests users flo bor">
						<?
						$query_users_list = "SELECT users.id, email, SUM(hours) AS totalhours FROM users, requests WHERE users.id = requests.userid and requests.datebegin>='2012-01-01' ";
						if ($_SESSION['admin'] != 2){
							$query_users_list.= "AND users.manager='".$_SESSION['login_email']."' ";
						}
						$query_users_list.= "GROUP BY users.id ORDER BY email";
						$result_users_list = mysql_query($query_users_list);
						
						while($row_users_list = mysql_fetch_array($result_users_list)){ ?>
							<? echo '<div style="float:left; width:100%; clear:both; padding-bottom:5px; margin-bottom:5px; border-bottom:1px solid #ccc;"><div style="float:left; width:300px;">'.$row_users_list['email'].'</div><div style="float:left; width:100px;">'.$row_users_list['totalhours'].'</div></div>'; ?>
						<? } ?>
                    </ul>
                </div>
            </li>
        <?php } ?>
    </ul>
</div>

<?php include 'inc-page-bottom.php'; ?>