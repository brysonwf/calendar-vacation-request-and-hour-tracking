<?php include 'begin.php'; ?>
<?php $page_name = 'Login'; ?>
<?php include 'inc-page-top.php'; ?>

<div class="content">
    <ul id="content-ul" class="flo">     
    	<li><p>Jesse's Internal Application for all your mojo wants and needs.</p></li>
    </ul>
</div>

<div class="sidebar">
    <ul class="flo">
        <?php if ($_SESSION['logged'] == false){ ?>
            <li id="sidebar-login">
                <form name="login_form" id="login_form" method="post">
                    <input type="hidden" name="login-form-post" value="true" />
                    <fieldset>
                        <legend>Login</legend>
                        <p><label>Email</label> <input name="login_email" type="text" /></p>
                        <p><label>Password</label> <input name="login_password" type="password" /></p>
                        <p><input type="submit" value="Login" /> <?php echo $page_message; ?></p>
                    </fieldset>
                </form>
            </li>
        <?php } else { ?>
            <li id="sidebar-clockin">
                <form name="clockin_form" id="clockin_form" method="post">
                    <input type="hidden" name="clockin-form-post" value="true" />
                    <fieldset>
                        <legend>Clock In</legend>
                        <p><input type="submit" value="Clock In" /></p>
                    </fieldset>
                </form>
            </li>
        <?php } ?>
    </ul>
</div>

<?php include 'inc-page-bottom.php'; ?>