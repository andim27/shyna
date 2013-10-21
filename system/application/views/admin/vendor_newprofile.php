<?php require_once('header.php'); ?>
<script type="text/javascript" src="<?=base_url()?>js/js_ajax/ajax_admin.js"></script>
<script type="text/javascript">set_admin_base_url('<?=base_url()?>', 'vendors');</script>
<script type="text/javascript">
$('.vendorProfile').ready(function() {
	$("#vendor_person :first").attr("selected", "selected");
});
</script>
<?php if(!empty($vendor) && is_object($vendor)) { ?>
<div style="width:970px;">
	<div class="vendorProfile">
		<div style="margin-bottom:15px; margin-left:10px;"><span class="linkAction" onclick="javascript:actionForm('vendor_edit');return false;" style="font-size:12px;">Редактировать</span></div>
		<div id="vendor_edit" style="display:none;">
			<div style="float:left;width:100%;">
				<div style="float:left;margin-right:20px;width:325px; margin-left:10px;">
					<h3>Редактирование данных поставщика</h3>
					<div class="vendorNewSection">
						<div class="title">Имя компании*</div>
						<input type="text" id="edit_vendor_name" name="vendor_name" value="<?=$vendor->vendor_name?>" />
						<div id="vendor_edit_error"></div>
					</div>
					<div class="vendorNewSection">
						<div class="title">Имя(короткое название)</div>
						<input type="text" id="edit_vendor_name_short" name="vendor_name_short" value="<?=$vendor->short_name?>" />
					</div>
					<div class="vendorNewSection">
						<div class="title">Город</div>
						<input type="text" id="edit_vendor_city" name="vendor_city" value="<?=$vendor->vendor_city?>" />
					</div>
					<div class="vendorNewSection">
						<div class="title">Город(короткое название)</div>
						<input type="text" id="edit_vendor_city_short" name="vendor_city_short" value="<?=$vendor->short_city?>" />
					</div>
					<div class="vendorNewSection">
						<div class="title">Телефон</div>
						<input type="text" id="edit_vendor_phone" name="vendor_phone" value="<?=$vendor->vendor_phone?>" />
					</div>
					<div class="vendorNewSection">
						<div class="title">Факс</div>
						<input type="text" id="edit_vendor_fax" name="vendor_fax" value="<?=$vendor->vendor_fax?>" />
					</div>
					<div class="vendorNewSection">
						<div class="title">Электронный ящик (email)</div>
						<input type="text" id="edit_vendor_email" name="vendor_email" value="<?=$vendor->vendor_email?>" />
					</div>
					<div class="vendorNewSection">
						<div class="title">Сайт</div>
						<input type="text" id="edit_vendor_www" name="vendor_www" value="<?=$vendor->vendor_www?>" />
					</div>
				</div>
				<div style="float:left;margin-right:20px;width:325px;">
					<h3>Контактное лицо</h3>
					<span class="linkAction" onclick="javascropt:actionForm('newContact');return false;">Новый контакт</span>
					<div id="newContact" style="display:none;margin:10px 0;">
						<div class="vendorNewSection">
							<div class="title">Имя*</div>
							<input type="text" id="new_person_name" name="new_person_name" value="" />
							<div id="new_vendor_edit_person_error"></div>
						</div>
						<div class="vendorNewSection">
							<div class="title">Должность</div>
							<input type="text" id="new_person_posada" name="new_person_posada" value="" />
						</div>
						<div class="vendorNewSection">
							<div class="title">Телефон</div>
							<input type="text" id="new_person_phone" name="new_person_phone" value="" />
						</div>
						<div class="vendorNewSection">
							<div class="title">email</div>
							<input type="text" id="new_person_email" name="new_person_email" value="" />
						</div>
						<div class="vendorNewSection">
							<span class="linkAction" onclick="javascript: set_person('<?=$vendor->vendor_id?>');return false;">Добавить контакт</span>
						</div>
					</div>
					<?php
					$persons_str = "";
					if(!empty($persons)) {
						foreach ($persons as $iperson=>$person) {
							$persons_str .= '<option value="'.$person->id.'">'.$person->name.'</option>';
						}
					?>
					<select id="vendor_person" onchange="javascript:get_person('<?=$vendor->vendor_id?>');" style="width:325px;"><?=$persons_str?></select>										
					<div id="personsBlocks"><?php if(!empty($persons_form)) { echo $persons_form; } ?></div>
					<div class="vendorNewLink" id="vendorNewLink" style="float:left;"><span class="linkAction" onclick="javascript: edit_person('<?=$vendor->vendor_id?>');return false;">Применить</span></div>
					<?php } ?>
				</div>
				<div style="float:left;margin-right:20px;width:415px;">
					<h3>Данные аккаунта</h3>
					<span class="linkAction" onclick="javascropt:actionForm('newAccount');return false;">Новый аккаунт</span>
					<div id="newAccount" style="display:none;margin:10px 0;">
						<div class="vendorNewSection">
							<span>Email:</span><input type="text" id="new_account_email" name="account_email" value="" />							
						</div>
						<div class="vendorNewSection">
							<span class="linkAction" onclick="javascript: add_account('<?=$vendor->vendor_id?>');return false;">Добавить аккаунт</span>
						</div>
					</div>
					<div id="accountBoxes" style="padding-top:10px;">
					<?php 
						if(isset($accounts) && !empty($accounts)) { 
							foreach ($accounts as $aindex=>$account) {
								$aindex++;
						?>
							<div class="accountBox">
								<span>Email <?=$aindex?>:</span><input type="text" id="<?=$account->account_id?>" class="edit_account_email" name="account_email" value="<?=$account->account_email?>" />
							</div>
					<?php 
							}
					?>					
							<div class="vendorNewLink" id="vendorNewLink" style="float:left;"><span class="linkAction" onclick="javascript: edit_accounts('<?=$vendor->vendor_id?>');return false;">Применить</span></div>
					<?php
						} 
					?>					
					</div>
				</div>
			</div>
			<div style="float:left; margin-left:10px;">
				<div class="vendorNewLink"><span class="linkAction" onclick="javascript: edit_vendor('<?=$vendor->vendor_id?>');return false;">Применить</span></div>
			</div>
		</div>
		<div style="float: left; width: 100%; margin-left:10px;">
			<div style="float: left;">
				<span>Компания:</span>
				<span id="vendor_name"><?=$vendor->vendor_name?></span>
			</div>
			<div class="datePriceUploaded">Дата последнего прайса: <?php echo (empty($vendor->price_date)) ? '---' : date("d/m/Y", strtotime($vendor->price_date))?></div>
		</div>	
		<div style="float: left; width: 100%; margin-left:10px;">
			<div style="float: left;">
				<span>Город:</span>
				<span id="vendor_city"><?=$vendor->vendor_city?></span>
			</div>
			<div>
				<div class="statusPriceUploaded">Статус: 
				<?php 
					if($vendor->price_status == 'not parsed') echo "Не разобран"; 
					elseif($vendor->price_status == 'parsed') echo 'Разобран'; ?>
				</div>
			</div>
		</div>
		<div style="float: left; width: 100%; margin-left:10px;">
			<div>
				<span>Телефон:</span><span>&nbsp;</span>
				<span id="vendor_phone"><?=$vendor->vendor_phone?></span>
			</div>
			<div>
				<span>Факс:</span><span>&nbsp;</span>
				<span id="vendor_fax"><?=$vendor->vendor_fax?></span>
			</div>
			<div>
				<span>Email:</span><span>&nbsp;</span>
				<span id="vendor_email"><?=$vendor->vendor_email?></span>
			</div>
			<div>
				<span>Сайт:</span><span>&nbsp;</span>
				<span id="vendor_www"><?=$vendor->vendor_www?></span>
			</div>
		</div>	
		<div style="float: left; width: 100%;margin-top:20px; margin-left:10px;">
			<h3>Контактное лицо</h3><br>
			<div>
				<div style="float: left;">
					<span>ФИО:</span>
					<span>&nbsp;</span>
				</div>
				<div>
					<div class="statusPriceUploaded"> 
					<?php 
					if(isset($file_exist) && $file_exist == true) {
						echo '<div>Действие: <a href="'.base_url().'admin/vendors/parse_price/'.$vendor->vendor_id.'" class="linkAction">разобрать прайс</a></div>';
						if(isset($functions) && !empty($functions)) {
							if($functions->delete_tmp_table == 1) { ?>
								<div style="margin-top:10px;">Обучение прайса не завершено,<br/>продолжить обучение 
								(<a href="<?=base_url().'admin/vendors/train_continue/'.$vendor->vendor_id?>" class="linkAction">да</a> | 
								<a href="<?=base_url().'admin/vendors/train_cancel/'.$vendor->vendor_id?>" class="linkAction">нет</a>)?</div>
						<?php
							}
						}
					}
					?>
					</div>
				</div>
			</div><br>
			<div>
				<span>Должность:</span>
				<span>&nbsp;</span>
			</div>
			<div>
				<span>Телефон:</span>
				<span>&nbsp;</span>
				<span><?=$vendor->vendor_phone?></span>
			</div>
			<div>
				<span>Факс:</span>
				<span>&nbsp;</span>
				<span><?=$vendor->vendor_fax?></span>
			</div>
			<div>
				<span>Email:</span>
				<span>&nbsp;</span>
				<span><?=$vendor->vendor_email?></span>
			</div>
		</div>
		<div style="float:left;margin-top:20px;margin-left:10px;width:100%;" onclick="javascript: actionForm('vendorPriceBlock');return false;"><span class="linkAction">Загрузить прайс</span></div>
		<div id="vendorPriceBlock" style="float:left;margin:10px 0;width:100%;display:none;">
			<fieldset>
				<legend>Загрузить прайс</legend>			
				<?php if(isset($upload_form)) echo $upload_form; ?>
			</fieldset>
		</div>
	</div>
</div>

<?php } ?>

<?php require_once('footer.php'); ?>
