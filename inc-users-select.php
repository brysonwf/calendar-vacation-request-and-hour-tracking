
								<? 
                                $result_users_list = mysql_query("SELECT id, email FROM users ORDER BY email");
                                while($row_users_list = mysql_fetch_array($result_users_list)){ ?>
                                	<option value="<? echo $row_users_list['id']; ?>"><? echo $row_users_list['email']; ?></option>
                                <? } ?>