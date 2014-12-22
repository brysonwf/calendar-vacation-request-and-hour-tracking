<?php include 'begin.php'; ?>
<?php session_destroy(); $_SESSION['logged'] = false ?>
<?php $page_name = 'Forgot Password'; ?>
<?php include 'inc-page-top.php'; ?>

<div class="content-wide">
    <ul id="content-ul" class="flo">
        <li class="content-li">
			<?
            if (isset($_POST['action']) && $_POST['action'] == 'forgot-password'){
                
                
                $result_forgot_password = mysql_query("SELECT * FROM users WHERE email ='".$_POST['forgot-password-email']."'");
                if (mysql_num_rows($result_forgot_password)>0){
                    $row_forgot_password = mysql_fetch_array($result_forgot_password);
                    $user_email = $row_forgot_password['email'];
                    
                    
                    echo '<p>Your password has been emailed to you.</p>';
                    
                    $to      = $user_email;
                    $subject = 'Mojostuff.com - Mojo Internals';
                    
                    $message = 'Username: '.$row_forgot_password['email']. "\r\n";
                    $message.= 'Password: '.$row_forgot_password['password']. "\r\n";
                    
                    $headers = 'From: bryson@mojotone.com' . "\r\n" .
                        'Reply-To: bryson@mojotone.com' . "\r\n" .
                        'X-Mailer: PHP/' . phpversion();
                    
                    mail($to, $subject, $message, $headers);
                }else{
                    echo '<p>Email does not exist.</p>';
                }
            }
            
            ?>
        	<form id="forgot-password-form" method="post" class="form">
            	<fieldset>
                	<input name="action" type="hidden" value="forgot-password" />
                    <p class="form"><label>Email</label><input id="forgot-password-email" name="forgot-password-email" type="text" /></p>
                    <p class="submit"><input type="submit" value="submit" class="submit" /></p>
                </fieldset>
            </form>
        </li>
    </ul>
</div>

<?php include 'inc-page-bottom.php'; ?>
