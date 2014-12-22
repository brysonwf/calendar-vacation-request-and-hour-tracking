<?php include 'begin.php'; ?>
<?php $page_name = 'Admin'; ?>
<?php $subpage_name = 'Cleaning Admin'; ?>
<?php $admin_page = true; ?>
<?php include 'inc-page-top.php'; ?>

<div class="content" style="width:100%;">
    <ul id="content-ul" class="flo">
        <?php if ($_SESSION['admin'] >= 1){ ?>
            <li class="content-li">
                <div class="drawer">                 
					<?php 
						$clean_array = array();
						
						
						//list of cleaners for mens bathroom
						$query = "SELECT * FROM users WHERE cleaner=1 AND mens>=1 ORDER BY mens, email";
                        $result_users_list = mysql_query($query);
                        $numrows = mysql_num_rows ($result_users_list);	
						
						///choose first mens cleaner
						$row_mens_1 = mysql_fetch_array($result_users_list);
						$clean_mens_1 = $row_mens_1['email'];
						$clean_mens_1_lang = $row_mens_1['lang'];
						
						echo '<b>Mens Bathroom:</b><br />'.$clean_mens_1.' ('.$row_mens_1['mens'].')'.'<br />';
						$clean_array[] = $clean_mens_1;
						
						
						
						
						//list of cleaners for womens bathroom
                        $result_users_list = mysql_query("SELECT * FROM users where cleaner=1 and womens>=1 ORDER BY womens, email");
                        $numrows = mysql_num_rows ($result_users_list);
						
						///choose first womens cleaner
						$row_womens_1 = mysql_fetch_array($result_users_list);
						$clean_womens_1 = $row_womens_1['email'];
						
						echo '<br /><br /><b>Womens Bathroom:</b><br />'.$clean_womens_1.' ('.$row_womens_1['womens'].')'.'<br />';
						$clean_array[] = $clean_womens_1;
						
						//list of cleaners for phoneroom
                        $result_users_list = mysql_query("SELECT * FROM users where cleaner=1 and phoneroom>=1 
						and email!='".$clean_mens_1."' 
						and email!='".$clean_womens_1."' 
						ORDER BY phoneroom, email");
                        $numrows = mysql_num_rows ($result_users_list);
						
						///choose first phoneroom cleaner
						$row_phoneroom_1 = mysql_fetch_array($result_users_list);
						$clean_phoneroom_1 = $row_phoneroom_1['email'];	
						
						echo '<br /><br /><b>Phoneroom:</b><br />'.$clean_phoneroom_1.' ('.$row_phoneroom_1['phoneroom'].')'.'<br />';
						$clean_array[] = $clean_phoneroom_1;
						
						//list of cleaners for warehouse
                        $result_users_list = mysql_query("SELECT * FROM users where cleaner=1 and warehouse>=1 
						and email!='".$clean_mens_1."' 
						and email!='".$clean_phoneroom_1."' 
						ORDER BY warehouse, email");
                        $numrows = mysql_num_rows ($result_users_list);
						
						///choose first warehouse cleaner
						$row_warehouse_1 = mysql_fetch_array($result_users_list);
						$clean_warehouse_1 = $row_warehouse_1['email'];	
						
						//choose second warehouse cleaner
						$row_warehouse_2 = mysql_fetch_array($result_users_list);
						$clean_warehouse_2 = $row_warehouse_2['email'];
						
						//choose third warehouse cleaner
						$row_warehouse_3 = mysql_fetch_array($result_users_list);
						$clean_warehouse_3 = $row_warehouse_3['email'];
						
						echo '<br /><br /><b>Warehouse:</b><br />'.$clean_warehouse_1.' ('.$row_warehouse_1['warehouse'].')'.'<br />'.$clean_warehouse_2.' ('.$row_warehouse_2['warehouse'].')'.'<br />'.$clean_warehouse_3.' ('.$row_warehouse_3['warehouse'].')'.'<br />';
						$clean_array[] = $clean_warehouse_1;
						$clean_array[] = $clean_warehouse_2;
						$clean_array[] = $clean_warehouse_3;
						
						
						
						
						//list of cleaners for pickup
						$query = "SELECT * FROM users where cleaner=1 and pickuproom>=1 
						and email!='".$clean_mens_1."' 
						and email!='".$clean_warehouse_1."' 
						and email!='".$clean_warehouse_2."' 
						and email!='".$clean_warehouse_3."' 
						ORDER BY pickuproom, email";
                        $result_users_list = mysql_query($query);
                        $numrows = mysql_num_rows ($result_users_list);
						
						
						///choose first pickup cleaner
						$row_pickup_1 = mysql_fetch_array($result_users_list);
						$clean_pickup_1 = $row_pickup_1['email'];
						
						echo '<br /><br /><b>Pickup Room:</b><br />'.$clean_pickup_1.' ('.$row_pickup_1['pickuproom'].')'.'<br />';
						$clean_array[] = $clean_pickup_1;
						
						
						//list of cleaners for breakroom
                        $result_users_list = mysql_query("SELECT * FROM users where cleaner=1 and breakroom>=1 
						and email!='".$clean_mens_1."' 
						and email!='".$clean_womens_1."' 
						and email!='".$clean_warehouse_1."' 
						and email!='".$clean_warehouse_2."' 
						and email!='".$clean_warehouse_3."'
						and email!='".$clean_pickup_1."' 
						and email!='".$clean_phoneroom_1."'
						ORDER BY breakroom, email");
                        $numrows = mysql_num_rows ($result_users_list);
						
						///choose first breakroom cleaner
						$row_breakroom_1 = mysql_fetch_array($result_users_list);
						$clean_breakroom_1 = $row_breakroom_1['email'];	
						
						echo '<br /><br /><b>breakroom:</b><br />'.$clean_breakroom_1.' ('.$row_breakroom_1['breakroom'].')'.'<br />';
						$clean_array[] = $clean_breakroom_1;
						
						
						//list of cleaners for hallways
                        $result_users_list = mysql_query("SELECT * FROM users where cleaner=1 and hallways>=1 
						and email!='".$clean_mens_1."' 
						and email!='".$clean_womens_1."' 
						and email!='".$clean_warehouse_1."' 
						and email!='".$clean_warehouse_2."' 
						and email!='".$clean_warehouse_3."'
						and email!='".$clean_pickup_1."' 
						and email!='".$clean_breakroom_1."' 
						and email!='".$clean_phoneroom_1."'
						ORDER BY hallways, email");
                        $numrows = mysql_num_rows ($result_users_list);
						
						///choose first hallways cleaner
						$row_hallways_1 = mysql_fetch_array($result_users_list);
						$clean_hallways_1 = $row_hallways_1['email'];	
						
						echo '<br /><br /><b>hallways:</b><br />'.$clean_hallways_1.' ('.$row_hallways_1['hallways'].')'.'<br />';
						$clean_array[] = $clean_hallways_1;
						
                    ?><br /><br />

                    <form id="cleaning-email-form" method="post">
                    	<input name="array" type="hidden" value="<? echo base64_encode(serialize($clean_array)); ?>" />
                    	<input type="submit" value="Post this List to Index Page" />
                    </form>
                    <script type="text/javascript">
						$(function() {
							$('#cleaning-email-form').ajaxForm({
								url:		'php-cleaning-email.php', 
								resetForm: 	true        // reset the form after successful submit 
							});
						});
					</script>
                </div>
            </li>
        <?php } ?>
    </ul>
</div>

<?php include 'inc-page-bottom.php'; ?>