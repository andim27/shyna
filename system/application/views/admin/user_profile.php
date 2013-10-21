<?php require_once('header.php'); ?>
<script type="text/javascript" src="<?=base_url()?>js/js_ajax/ajax_admin.js"></script>
<script type="text/javascript">set_admin_base_url('<?=base_url()?>', 'users');</script>
<?php if(!empty($user) && is_object($user)) { ?>

<div style="width:970px;">
	<div class="vendorProfile">
		<div style="margin-bottom:15px; margin-left:10px;"><span class="linkAction" onclick="javascript:actionForm('user_edit');return false;" style="font-size:12px;">Редактировать</span></div>
		<div id="user_edit" style="display:none;">
			
				<div style="float:left;margin-right:20px;width:700px; margin-left:10px;">
					<h3>Редактирование данных пользователя</h3>
					<br>
					<table border="0">
						<tr>
							<td><div class="title">Логин:</div></td>
							<td><input type="text" id="edit_user_login" name="user_login" value="<?=$user->login?>" /></td>
							<td><div id="user_edit_error"></div></td>

					 	</tr>
						<tr>
							<td><div class="title">Email:</div></td>
							<td><input type="text" id="edit_user_email" name="user_email" value="<?=$user->email?>" /></td>
							<td></td>
					 	</tr>
						<tr>
							<td><div class="title">Город:</div></td>
							<td><input type="text" id="edit_user_city" name="user_city" value="<?=$user->city?>" /></td>
							<td></td>
					 	</tr>
						<tr>
							<td><div class="title">Номер телефона:</div></td>
							<td><input type="text" id="edit_user_tel" name="user_tel" value="<?=$user->tel?>" /></td>
							<td></td>
					 	</tr>
						<tr>
							<td><div class="title">Компания:</div></td>
							<td><input type="text" id="edit_user_company" name="user_company" value="<?=$user->company?>" /></td>
							<td></td>
					 	</tr>
						<tr>
							<td><div class="title">ФИО:</div></td>
							<td><input type="text" id="edit_user_fio" name="user_fio" value="<?=$user->fio?>" /></td>
							<td></td>
					 	</tr>
						<tr>
							<td><div class="title">Активность:</div></td>
							<td><span><?=$user->active?></span></td>
							<td><div id="user_edit_aedata_error"></div></td>
					 	</tr>
						<tr>
							<td><div class="title">Активность до:</div></td>
							<td><input type="text" id="edit_user_aedate" name="user_aedate" value="<?=$user->active_end_date?>" /></td>
							<td><div id="user_edit_aedata_error"></div></td>
					 	</tr>
						<tr>
							<td><div class="title">Группа:</div></td>
							<td><input type="text" id="edit_user_group" name="user_group" value="<?=$user->group_name?>" /></td>
							<td></td>
					 	</tr>
					</table>
				</div>
			    
				<div class="vendorNewLink"  style="float:left; margin-left:10px;"><span class="linkAction" onclick="javascript: edit_user('<?=$user->user_id?>');return false;">Применить</span></div>
	
		</div>




		<div style="float: left; width: 100%; margin-top: 20px; margin-left: 10px;">
	<div>
		<h3>Информация о пользователе</h3>
		<br>
		<table>
		  <tr>
		    <td><span>Логин:</span></td>
			<td><span id="user_login"><?=$user->login?></span></td>
		  </tr>
		  <tr>
		    <td><span>Email:</span></td>
			<td><span id="user_email"><?=$user->email?></span></td>
		  </tr>
		  <tr>
		    <td><span>Дата регистрации:</span></td>
			<td><span id="user_registration"><?=date("d.m.Y", strtotime($user->registration_date))?></span></td>
		  </tr>
		  <tr>
		    <td><span>Дата последнего визита:</span></td>
			<td><span id="user_lastvisit"><?=date("d.m.Y", strtotime($user->last_login_date))?></span></td>
		  </tr>		 
			<tr>
		    <td><span>Активность:</span></td>
			<td><span id="user_lastvisit"><?=$user->active?></span></td>
		  </tr>		  
		  <tr>
		    <td><span>Активность до:</span></td>
			<td><span id="user_aedate"><?=$user->active_end_date?></span></td>
		  </tr>
		  <tr>
		    <td><span>Город:</span></td>
			<td><span id="user_city"><?=$user->city?></span></td>
		  </tr>
		  <tr>
		    <td><span>Номер телефона:</span></td>
			<td><span id="user_tel"><?=$user->tel?></span></td>
		  </tr>
		  <tr>
		    <td><span>Компания:</span></td>
			<td><span id="user_company"><?=$user->company?></span></td>
		  </tr>
		  <tr>
		    <td><span>ФИО:</span></td>
			<td><span id="user_fio"><?=$user->fio?></span></td>
		  </tr>
		  <tr>
		    <td><span>Группа:</span></td>
			<td><span id="user_group"><?=$user->group_name?></span></td>
		  </tr>
		  
		</table>
	</div>
	<div style="float:left;margin-top:10px;width:100%;" onclick="javascript: actionForm('vendorPriceBlock');return false;"><span class="linkAction">Загрузить прайс</span></div>
	<div id="vendorPriceBlock" style="float:left;margin:10px 0;width:100%;display:none;">
		<fieldset>
			<legend>Загрузить прайс</legend>			
			<?php if(isset($upload_form)) echo $upload_form; ?>
		</fieldset>
	</div>
</div>
</div>
</div>

<?php } ?>

<?php require_once('footer.php'); ?>
