<li class="nav-clockin"> 
    <? 
    
    $result_openhourset = mysql_query("SELECT * FROM hours WHERE userid = '".$_SESSION['userid']."' AND end = '0000-00-00 00:00:00'");
    
    $row_openhourset = mysql_fetch_array($result_openhourset);
    
    $openhourset = mysql_num_rows($result_openhourset); 
    
    $isclockedin = ($openhourset > 0)? true : false;
    
    if ($isclockedin){
        $form_action = "clockout";
        $form_submit_text = "Clock Out";
        $extraactions = '&clockoutid='.$row_openhourset['id'];
    }else{
        $form_action = "clockin";
        $form_submit_text = "Clock In";
        $extraactions = '';
    }
    ?>
    
    <form name="clockin_form" id="clockin_form" method="post" action="hours.php?action=<? echo $form_action.$extraactions; ?>">
        <input type="hidden" id="clockin-form-userid" name="clockin-form-userid" value="<? echo $_SESSION['userid']; ?>" />
        <input type="submit" value="<? echo $form_submit_text; ?>" />
    </form>
</li>