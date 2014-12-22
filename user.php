<?php include 'begin.php'; ?>
<?php $page_name = 'user'; ?>
<?php include 'inc-page-top.php'; ?>

<div class="content-wide">
        <ul id="content-ul" class="flo">    
            <li>
				<?
                if (isset($_POST['action']) && $_POST['action'] == 'change-password'){
					$result_login = mysql_query("SELECT * FROM users WHERE email='".$_SESSION['login_email']."' AND password='".$_POST['change-password-old-password']."'");
					$row_login = mysql_fetch_array( $result_login );
					if ( (mysql_num_rows($result_login)) == 1 ){
						if ($_POST['change-password-new-password'] == $_POST['change-password-confirm-new-password']){
							
							if ($_POST['change-password-new-password'] != '' || $_POST['change-password-confirm-new-password'] != ''){
								mysql_query("UPDATE users SET password = '".$_POST['change-password-new-password']."' WHERE id = '".$_POST['userid']."'");
								echo '<p>Your password has been updated.</p>';
							}else{
								echo "<p>Your password was blank.</p>";
							}
						}else{
							echo "<p>Your passwords didn't match.</p>";
						}
					}else{
						echo "<p>Incorrect old password.</p>";
					}
                }
                ?>
            	<form id="change-password-form" name="change-password-form" method="post" class="form">
                	<fieldset>
                    	<input name="userid" type="hidden" value="<? echo $_SESSION['userid']; ?>" />
                    	<input name="action" type="hidden" value="change-password" />
                    	<legend>Change password</legend>
                    	<p class="form"><label>Old</label><input name="change-password-old-password" type="password" /></p>
                    	<p class="form"><label>New</label><input name="change-password-new-password" type="password" /></p>
                    	<p class="form"><label>Confirm</label><input name="change-password-confirm-new-password" type="password" /></p>
                        <p class="submit"><input type="submit" class="submit" value="Change" /></p>
                    </fieldset>
                </form>
            </li>
       </ul>
</div>

<?php include 'inc-page-bottom.php'; ?>