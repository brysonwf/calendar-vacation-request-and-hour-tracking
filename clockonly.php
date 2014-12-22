<?php include 'begin.php'; ?>

<?

if (isset($_GET['action'])){
	if (($_GET['action'] == 'clockin') && isset($_POST['clockin-form-userid'])){
		mysql_query("INSERT INTO hours (userid, start, end) VALUES ('".$_POST['clockin-form-userid']."', now(), '0')");
	}else if ($_GET['action'] == 'clockout'){
	}
}
	if ( isset($_POST['clockonly-form-post']) && ($_POST['clockonly-form-post'] == true)){
		if( ($_POST['clockonly_email'] != '') && ($_POST['clockonly_email'] != '') ){
			
			// Get all the data from the "example" table
			$result_login = mysql_query("SELECT * FROM users WHERE id='".$_POST['clockonly_email']."' AND password='".$_POST['clockonly_password']."'");
			$row_login = mysql_fetch_array( $result_login );
			if ( (mysql_num_rows($result_login)) == 1 ){
				
				$result_openhourset = mysql_query("SELECT * FROM hours WHERE userid = '". $_POST['clockonly_email']."' AND end = '0000-00-00 00:00:00'");
				$row_openhourset = mysql_fetch_array($result_openhourset);
				$openhourset = mysql_num_rows($result_openhourset); 
				$isclockedin = ($openhourset > 0)? true : false;
				
				if (!$isclockedin){
					mysql_query("INSERT INTO hours (userid, start, end) VALUES ('".$_POST['clockonly_email']."', now(), '0')");
					$page_message = 'Clocked <b>IN</b> at '.$row_openhourset['start'].' ('.date('g:i').')';
				}else{
					mysql_query("UPDATE hours SET end = now() WHERE userid = '".$_POST['clockonly_email']."' AND end = '0000-00-00 00:00:00'");
					$page_message = ' <h1 style="color:black;">Clocked OUT</h1><p>'.$row_openhourset['start'].' to '.date('Y-m-d G:i').'<p>';
				}
				
			}else{
				$page_message.= 'Invalid credentials.';
			}
		}
	}

?>

<?php $page_name = 'Clock In'; ?>
<?php include 'inc-page-top.php'; ?>

<div class="content-wide">
        <ul id="content-ul" class="flo">    
			<?php if ($_SESSION['logged'] == false){ 
					if (('98.101.185.253' == $_SERVER['REMOTE_ADDR']) || ('98.101.10.178' == $_SERVER['REMOTE_ADDR']) || ('65.184.144.98' == $_SERVER['REMOTE_ADDR'])){
						?>
                        <li id="sidebar-login">
                            <form name="clockonly_form" id="clockonly_form" method="post" class="form">
                                <input type="hidden" name="clockonly-form-post" value="true" />
                                <fieldset>
                                    <p class="form">
                                        <label>Username</label><select id="clockonly_email" name="clockonly_email">
                                            <?
                                            $result_users_list = mysql_query("SELECT users.id, email FROM users WHERE hourly=1 ORDER BY email");
                                            while($row_users_list = mysql_fetch_array($result_users_list)){ ?>
                                                <option value="<? echo $row_users_list['id']; ?>"><? echo $row_users_list['email']; ?></option>
                                            <? } ?>
                                        </select>
                                    </p>
                                    <p class="form"><label>Password</label><input name="clockonly_password" type="password" /></p>
                                    <p class="submit"><input type="submit" value="Clockin/Clockout" class="submit" /> - <a href="forgot-password.php">Forgot Password?</a></p>
                                    <p><?php echo $page_message; ?></p>
                                </fieldset>
                            </form>
                        </li>
			<?php	} 
			} ?>
       	</ul>
		<?php include 'inc-calendar.php'; ?>
</div>

<?php include 'inc-page-bottom.php'; ?>