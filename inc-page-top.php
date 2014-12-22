

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="generator" content="HTML Tidy for Linux/x86 (vers 11 February 2007), see www.w3.org" />
    <meta http-equiv="Content-Type" content="text/html; charset=us-ascii" />

    <title>Mojo Internals - <? echo $page_name; ?></title>
    
    <link type="text/css" href="style.css" rel="stylesheet" />
	<script type="text/javascript" src="script.js"></script>
    
    <link type="text/css" href="css/ui-lightness/jquery-ui-1.8.5.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.8.5.custom.min.js"></script>
    <script type="text/javascript" src="js/jquery.form.js"></script>
    
    <script type="text/javascript" src="js/jquery.jtimepicker.js"></script>
    
	<? include 'functions.php' ?>
    
</head>

<body>

<div class="page-margin">

<div id="head">
    <h1><a href="index.php">Mojo Internals</a></h1>
</div>
<div id="nav">
	<ul>
		<?php if ($_SESSION['logged'] == true){ 
			$hour_sum = '';
			$result_hour_sum = mysql_query("SELECT SUM(hours) as hour_sum FROM requests WHERE userid='".$_SESSION['userid']."' and datebegin>='2012-01-01'");
			$row_hour_sum = mysql_fetch_array( $result_hour_sum );
			$hour_sum = $row_hour_sum['hour_sum'];
			if ($hour_sum == '') $hour_sum = 0;
		?>
    		<li class="nav-home <? if ($page_name == 'Home') echo 'active'; ?>"><a href="index.php"><img src="img/icon-home.png" alt="Home" border="0" /></a></li>
            <? if ($_SESSION['hourly'] || $_SESSION['admin'] >= 1){ ?> <li class="nav-hours <? if ($page_name == 'Hours') echo 'active'; ?>"><a href="hours.php">Hours</a></li><? } ?>
            <li class="nav-vacation <? if ($page_name == 'Vacation') echo 'active'; ?>"><a href="vacation.php">Vacation <span class="vacation-hours">(<span id="vacation-hours-digits"><?php echo $hour_sum; ?></span> hrs)</span></a></li>
            <?php if($_SESSION['admin'] >= 1) { ?><li class="nav-admin <? if ($page_name == 'Admin') echo 'active'; ?>"><a href="admin-vacation.php">Admin</a></li><?php } ?>
            <li class="nav-logout"><a href="logout.php">Logout</a></li>
            <li class="nav-user"><a href="user.php">Account</a></li>
            <li class="nav-user"><a href="user.php"><?php echo $_SESSION['login_email']; ?></a></li>
            <? //if($_SESSION['hourly']){ include 'inc-form-clock.php'; } ?>
        <?php }else{ ?>
            <li class="nav-login <? if ($page_name == 'Clock In') echo 'active'; ?>"><a href="clockonly.php">Clock In</a></li>
            <li class="nav-login <? if ($page_name == 'Home') echo 'active'; ?>"><a href="index.php">Login</a></li>
            <li class="nav-register <? if ($page_name == 'Register') echo 'active'; ?>"><a href="register.php">Register</a></li>
            <li class="nav-forgot-password <? if ($page_name == 'Forgot Password') echo 'active'; ?>"><a href="forgot-password.php">Forgot Password</a></li>
        <?php } ?>
    </ul>
</div>

<div id="page">

<?php if(isset($admin_page) && $admin_page) { include 'inc-page-top-admin.php'; } ?>