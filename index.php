<?php include 'begin.php'; ?>
<?php $page_name = 'Home'; ?>
<?php include 'inc-page-top.php'; ?>

<div class="content-wide">
		<?php if ($_SESSION['logged'] == false){ ?>
            <ul id="content-ul" class="flo">    
                <?php include 'inc-form-login.php'; ?>
            </ul>
        <?php } ?>
        <?php include 'inc-calendar.php'; ?>
</div>

<?php include 'inc-page-bottom.php'; ?>