<?php
if(isset($persons) && !empty($persons)) {
	if(is_array($persons)) $persons = array_shift($persons);	
?>
	<div id="personBlock">
		<div class="vendorNewSection">
			<div class="title">Имя*</div>
			<input type="text" id="edit_person_name" name="person_name" value="<?=$persons->name?>" />
			<div id="vendor_edit_person_error"></div>
		</div>
		<div class="vendorNewSection">
			<div class="title">Должность</div>
			<input type="text" id="edit_person_posada" name="person_posada" value="<?=$persons->posada?>" />
		</div>
		<div class="vendorNewSection">
			<div class="title">Телефон</div>
			<input type="text" id="edit_person_phone" name="person_phone" value="<?=$persons->phone?>" />
		</div>
		<div class="vendorNewSection">
			<div class="title">email</div>
			<input type="text" id="edit_person_email" name="person_email" value="<?=$persons->email?>" />
		</div>
	</div>
<?php	
}
?>