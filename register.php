<?php include 'begin.php'; ?>
<?php $page_name = 'Register'; ?>
<?php include 'inc-page-top.php'; ?>

<div class="content-wide">
    <ul id="content-ul" class="flo">     
        <?php if ($_SESSION['logged'] == false){ ?>
            <li id="sidebar-login">
                <form name="add_user_form" id="add_user_form" method="post" class="form">
                	<fieldset>
                        <p class="form"><label>Email</label><input id="add_user_email" name="add_user_email" /></p>
                        <p class="form"><label>Password</label><input id="add_user_password" name="add_user_password" type="password" /></p>
                        <p class="form"><label>Confirm</label><input id="add_user_password_confirm" name="add_user_password_confirm" type="password" /></p>
                        <p class="form">
                        	<label>Confirm</label>
                            <select id="add_user_manager" name="add_user_manager">
                            	<option selected="selected" value="andrew@mojotone.com">Andrew</option>
                                <option value="vanessa@mojotone.com">Vanessa</option>
                                <option value="ajohnson@mojotone.com">Andy Johnson</option>
                                <option value="jimmy@mojotone.com">Jimmy</option>
                                <option value="marshall@audioengineusa.com">Marshall</option>
                            </select>
                        </p>
                        <p class="checkbox"><input id="add_user_hourly" name="add_user_hourly" type="checkbox" /> <label>I am a hourly worker.</label> </p>
                        <p class="submit"><input type="submit" value="Register" class="submit" /> <span id="add_user_form_responce" style="display:none;"></span></p>
                    </fieldset>
                </form>
				<script type="text/javascript">
                    $(function() {
                        $('#add_user_form').ajaxForm({
                            url:		'php-user-add.php', 
							beforeSubmit:    requestTimeAddValidate,  // post-submit callback
							success:    requestTimeAddResponse,  // post-submit callback
                            resetForm: 	true        // reset the form after successful submit 
                        });
                    });
					function requestTimeAddValidate(formData, jqForm, options) {
						
						var form = jqForm[0];
						var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
						
						if (!form.add_user_email.value) {
							alert('Please enter an email.'); 
							return false;
						}else if(reg.test(form.add_user_email.value) == false) {
							alert('Invalid Email Address');
							return false;
						}else if(!form.add_user_password.value) {
							alert('Please enter a Password.');
							return false;
						}else if(!form.add_user_password_confirm.value) {
							alert('Please confirm your password.');
							return false;
						}else if(form.add_user_password.value != form.add_user_password_confirm.value) {
							alert('Passwords must match.');
							return false;
						}
					}
					function requestTimeAddResponse(responseText, statusText, xhr, $form)  {
						$("#add_user_form_responce").show();
						$("#add_user_form_responce").html(responseText);
					} 
                </script>
            </li>
        <?php } ?>
    </ul>
</div>

<div class="sidebar">
    <ul class="flo">
    </ul>
</div>

<?php include 'inc-page-bottom.php'; ?>