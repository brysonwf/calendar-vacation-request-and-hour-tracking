<li id="sidebar-login">
    <form name="login_form" id="login_form" method="post" class="form">
        <input type="hidden" name="login-form-post" value="true" />
        <fieldset>
            <p class="form"><label>Email</label><input name="login_email" type="text" /></p>
            <p class="form"><label>Password</label><input name="login_password" type="password" /></p>
            <p class="submit"><input type="submit" value="Login" class="submit" /> - <a href="forgot-password.php">Forgot Password?</a></p>
            <p><?php echo $page_message; ?></p>
        </fieldset>
    </form>
</li>