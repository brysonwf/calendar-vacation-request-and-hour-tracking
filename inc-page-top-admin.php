
<div id="nav-admin-bg">
    <ul id="nav-admin-ul">
    	<? if ($_SESSION['admin'] >= 1){ ?>
        	<li class="<? if ($subpage_name == 'Vacation') echo 'active'; ?>"><a href="admin-vacation.php">Vacation</a></li>
        <? } ?>
        <?php if($_SESSION['admin'] >= 1) { ?><li class="nav-admin <? if ($subpage_name == 'Vacation Count') echo 'active'; ?>"><a href="admin-vacation-count.php">Vacation Count</a></li><?php } ?>
        <?php if($_SESSION['admin'] >= 1) { ?><li class="nav-admin <? if ($subpage_name == 'Hours Admin') echo 'active'; ?>"><a href="admin-hours.php">Hours</a></li><?php } ?>
        <?php /*if($_SESSION['admin'] >= 1) { ?><li class="nav-admin <? if ($subpage_name == 'Cleaning Admin') echo 'active'; ?>"><a href="admin-cleaning.php">Cleaning</a></li><?php }*/ ?>
    </ul>
    
    
    <? if ($subpage_name == 'Vacation'){ ?>
        <div id="nav-admin-sub">
            <ul>
                <li class="<? if ($subpage_name == 'Add Time') echo 'active'; ?>"><a id="show-form-add-time-button" href="#show-form-add-time">Add Time</a></li>
                <li class="<? if ($subpage_name == 'Request Vacation') echo 'active'; ?>"><a id="show-form-request-button" href="#show-form-request">Request Vacation</a></li>
                

				<script type="text/javascript">
                    $(function() {
                        $('#show-form-add-time-button').click(function(){
                            $('#add_time_form').toggle();
                            $('#request_form').hide();
                            $('#show-form-request-button').parent().removeClass("active");
                            if($('#show-form-add-time-button').parent().hasClass("active")){
                                $('#show-form-add-time-button').parent().removeClass("active");
                            }else{
                                $('#show-form-add-time-button').parent().addClass("active")
                            }
                        });
                        $('#show-form-request-button').click(function(){
                            $('#request_form').toggle();
                            $('#add_time_form').hide();
                            $('#show-form-add-time-button').parent().removeClass("active");
                            if($('#show-form-request-button').parent().hasClass("active")){
                                $('#show-form-request-button').parent().removeClass("active");
                            }else{
                                $('#show-form-request-button').parent().addClass("active")
                            }
                        });
                    });
                </script>
        	</ul>
    	</div>
    <? } ?>
</div>
