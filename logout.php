<?php include 'begin.php'; ?>
<?php session_destroy(); $_SESSION['logged'] = false ?>
<?php $page_name = 'Home'; ?>
<?php include 'inc-page-top.php'; ?>

<div class="content">
    <ul id="content-ul" class="flo">    
        <?php if ($_SESSION['logged'] == false){ ?>
    		<p>You have been logged out. <a href="index.php">Login</a>.</p>
        <?php } ?>
    </ul>
</div>

<div class="sidebar">
    <ul class="flo">
    </ul>
</div>

<?php include 'inc-page-bottom.php'; ?>
