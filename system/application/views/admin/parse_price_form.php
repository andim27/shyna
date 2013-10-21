<?php require_once('header.php'); ?>
<script type="text/javascript" src="<?=base_url()?>js/js_ajax/ajax_admin.js"></script>
<script type="text/javascript">set_admin_base_url('<?=base_url()?>', 'vendors');</script>

<script type="text/javascript">
$('.priceBlockMain').ready(function() {
	$("#actions :first").attr("selected", "selected");
	actions();
});
function action_functions(action) {
	if(action == 'close') {
		$('div[id^="form_"]').each(function () {
			$(this).hide();
			var record_id = $(this).attr('id');
			actionImage(record_id);
		});
		var linkAction = '<span class="linkAction" onclick="javascript:action_functions(\'open\');">Раскрыть все</span>';
		$('#linkAction').html(linkAction);

	} else if(action == 'open') {
		$('div[id^="form_"]').each(function () {
			$(this).show();
			var record_id = $(this).attr('id');
			actionImage(record_id);
		});
		var linkAction = '<span class="linkAction" onclick="javascript:action_functions(\'close\');">Свернуть все</span>';
		$('#linkAction').html(linkAction);
	}
}
</script>
<div class="vendorProfile">
<?php 
	if(isset($price_table)) { 
?>
	<div style="float:left;width:100%;">			
	<?php echo $price_table; ?>		
		<div class="priceTrain">
			<div class="head_label" style="width:100%;">
				<div style="float:left;">Обучение парсера</div>
				<div id="linkAction" style="float:right;"><span class="linkAction" onclick="javascript:action_functions('open');">Раскрыть все</span></div>
			</div>
			<form action="<?=base_url()?>admin/vendors/train_price/<?=$vendor->vendor_id?>/<?=$cur_sheet_num?>" method="POST">
				<input type="hidden" name="vendor_id" id="vendor_id" value="<?=$vendor->vendor_id?>" />			
				<div>
					<div style="margin:5px 0;">
						<span style="font-weight: bold;">Число игнорируемых строк:</span>
						<input type="text" name="rows_ignore" value="<?=isset($functions->rows_ignore) ? $functions->rows_ignore : ''?>" style="width:30px;" /><br />
					</div>
					<div style="margin:5px 0;">
						<span style="font-weight: bold;">Бренды:</span>
						<span>колонка: </span>
						<select id="brands_list" name="brands_col">
						<?php
							$i = 0; $columns_a = "";
					    	while ($i <= $functions->columns_count) {
					    		$selected = ($functions->brand_column == ("A".$i)) ? 'selected' : '';
					    		$columns_a .= "<option ".$selected." value='".$i."'>A".$i."</option>";
					    		$i++;
					    	} echo $columns_a;
						?>
						</select>
						<span class="linkAction" style="float:right;" onclick="javacript:actionForm('form_brand_func');actionImage('form_brand_func');">функция
							<img id="form_brand_func_img" src="<?=base_url()?>images/icons/selector-left.png" style="position:relative;top:3px;"/>
						</span>
						<div id="form_brand_func" style="display:none;"><span>функция для нахождения брендов: </span>
						<textarea class="function_area" name="brand_func"><?=isset($functions->brand_func) ? $functions->brand_func : ''?></textarea></div>
					</div>
					<div style="margin:5px 0;">
						<span style="font-weight: bold;">Ширина(шины):</span>
						<span>колонка: </span>
						<select id="width_list" name="width_col">
						<?php
							$i = 0; $columns_a = "";
					    	while ($i <= $functions->columns_count) {
					    		$selected = ($functions->width_column == ("A".$i)) ? 'selected' : '';
					    		$columns_a .= "<option ".$selected." value='".$i."'>A".$i."</option>";
					    		$i++;
					    	} echo $columns_a;
						?>
						</select>
						<span class="linkAction" style="float:right;" onclick="javacript:actionForm('form_width_func');actionImage('form_width_func');">функция
							<img id="form_width_func_img" src="<?=base_url()?>images/icons/selector-left.png" style="position:relative;top:3px;"/>
						</span>
						<div id="form_width_func" style="display:none;"><span>функция для нахождения ширины: </span>
						<textarea class="function_area" name="width_func"><?=isset($functions->width_func) ? $functions->width_func : ''?></textarea></div>
					</div>
					<div style="margin:5px 0;">
						<span style="font-weight: bold;">Высота(шины):</span>
						<span>колонка: </span>
						<select id="height_list" name="profile_col">
						<?php
							$i = 0; $columns_a = "";
					    	while ($i <= $functions->columns_count) {
					    		$selected = ($functions->profile_column == ("A".$i)) ? 'selected' : '';
					    		$columns_a .= "<option ".$selected." value='".$i."'>A".$i."</option>";
					    		$i++;
					    	} echo $columns_a;
						?>
						</select>
						<span class="linkAction" style="float:right;" onclick="javacript:actionForm('form_profile_func');actionImage('form_profile_func');">функция
							<img id="form_profile_func_img" src="<?=base_url()?>images/icons/selector-left.png" style="position:relative;top:3px;"/>
						</span>
						<div id="form_profile_func" style="display:none;"><span>функция для нахождения высоты: </span>
						<textarea class="function_area" name="profile_func"><?=isset($functions->profile_func) ? $functions->profile_func : ''?></textarea></div>
					</div>
					<div style="margin:5px 0;">
						<span style="font-weight: bold;">Радиус(шины):</span>
						<span>колонка: </span>
						<select id="radius_list" name="diameter_col">
						<?php
							$i = 0; $columns_a = "";
					    	while ($i <= $functions->columns_count) {
					    		$selected = ($functions->diameter_column == ("A".$i)) ? 'selected' : '';
					    		$columns_a .= "<option ".$selected." value='".$i."'>A".$i."</option>";
					    		$i++;
					    	} echo $columns_a;
						?>
						</select>
						<span class="linkAction" style="float:right;" onclick="javacript:actionForm('form_diameter_func');actionImage('form_diameter_func');">функция
							<img id="form_diameter_func_img" src="<?=base_url()?>images/icons/selector-left.png" style="position:relative;top:3px;"/>
						</span>
						<div id="form_diameter_func" style="display:none;"><span>функция для нахождения радиуса: </span>
						<textarea class="function_area" name="diameter_func"><?=isset($functions->diameter_func) ? $functions->diameter_func : ''?></textarea></div>
					</div>
					<div style="margin:5px 0;">
						<span style="font-weight: bold;">Модель:</span>
						<span>колонка: </span>
						<select id="model_list" name="model_col">
						<?php
							$i = 0; $columns_a = "";
					    	while ($i <= $functions->columns_count) {
					    		$selected = ($functions->model_column == ("A".$i)) ? 'selected' : '';
					    		$columns_a .= "<option ".$selected." value='".$i."'>A".$i."</option>";
					    		$i++;
					    	} echo $columns_a;
						?>
						</select>
						<span class="linkAction" style="float:right;" onclick="javacript:actionForm('form_model_func');actionImage('form_model_func');">функция
							<img id="form_model_func_img" src="<?=base_url()?>images/icons/selector-left.png" style="position:relative;top:3px;"/>
						</span>
						<div id="form_model_func" style="margin-top:5px;display:none;"><span>функция модели: </span>
						<textarea class="function_area" name="model_func"><?=isset($functions->model_func) ? $functions->model_func : ''?></textarea></div>
					</div>
					<div style="margin:5px 0;">
						<span style="font-weight: bold;">Индекс скорости шин:</span>
						<span>колонка: </span>
						<select id="speed_list" name="speed_index_col">
						<?php
							$i = 0; $columns_a = "";
					    	while ($i <= $functions->columns_count) {
					    		$selected = ($functions->speed_index_column == ("A".$i)) ? 'selected' : '';
					    		$columns_a .= "<option ".$selected." value='".$i."'>A".$i."</option>";
					    		$i++;
					    	} echo $columns_a;
						?>
						</select>
						<span class="linkAction" style="float:right;" onclick="javacript:actionForm('form_speed_index_func');actionImage('form_speed_index_func');">функция
							<img id="form_speed_index_func_img" src="<?=base_url()?>images/icons/selector-left.png" style="position:relative;top:3px;"/>
						</span>
						<div id="form_speed_index_func" style="display:none;"><span>функция для нахождения индекса: </span>
						<textarea class="function_area" name="speed_index_func"><?=isset($functions->speed_index_func) ? $functions->speed_index_func : ''?></textarea></div>
					</div>
					<div style="margin:5px 0;">
						<span style="font-weight: bold;">Индекс нагрузки шин:</span>
						<span>колонка: </span>
						<select id="load_list" name="load_index_col">
						<?php
							$i = 0; $columns_a = "";
					    	while ($i <= $functions->columns_count) {
					    		$selected = ($functions->load_index_column == ("A".$i)) ? 'selected' : '';
					    		$columns_a .= "<option ".$selected." value='".$i."'>A".$i."</option>";
					    		$i++;
					    	} echo $columns_a;
						?>
						</select>
						<span class="linkAction" style="float:right;" onclick="javacript:actionForm('form_load_index_func');actionImage('form_load_index_func');">функция
							<img id="form_load_index_func_img" src="<?=base_url()?>images/icons/selector-left.png" style="position:relative;top:3px;"/>
						</span>
						<div id="form_load_index_func" style="display:none;"><span>функция для нахождения индекса: </span>
						<textarea class="function_area" name="load_index_func"><?=isset($functions->load_index_func) ? $functions->load_index_func : ''?></textarea></div>
					</div>
					<div style="margin:5px 0;">
						<span style="font-weight: bold;">Количество:</span>
						<span>колонка: </span>
						<select id="amount_list" name="amount_col">
						<?php
							$i = 0; $columns_a = "";
					    	while ($i <= $functions->columns_count) {
					    		$selected = ($functions->amount_column == ("A".$i)) ? 'selected' : '';
					    		$columns_a .= "<option ".$selected." value='".$i."'>A".$i."</option>";
					    		$i++;
					    	} echo $columns_a;
						?>
						</select>
						<span class="linkAction" style="float:right;" onclick="javacript:actionForm('form_amount_func');actionImage('form_amount_func');">функция
							<img id="form_amount_func_img" src="<?=base_url()?>images/icons/selector-left.png" style="position:relative;top:3px;"/>
						</span>
						<div id="form_amount_func" style="display:none;"><span>функция для нахождения количества: </span>
						<textarea class="function_area" name="amount_func"><?=isset($functions->amount_func) ? $functions->amount_func : ''?></textarea></div>
					</div>
					<div style="margin:5px 0;">
						<span style="font-weight: bold;">Цена:</span>
						<span>колонка: </span>
						<select id="price_list" name="price_col">
						<?php
							$i = 0; $columns_a = "";
					    	while ($i <= $functions->columns_count) {
					    		$selected = ($functions->price_column == ("A".$i)) ? 'selected' : '';
					    		$columns_a .= "<option ".$selected." value='".$i."'>A".$i."</option>";
					    		$i++;
					    	} echo $columns_a;
						?>
						</select>
						<span class="linkAction" style="float:right;" onclick="javacript:actionForm('form_price_func');actionImage('form_price_func');">функция
							<img id="form_price_func_img" src="<?=base_url()?>images/icons/selector-left.png" style="position:relative;top:3px;"/>
						</span>
						<div id="form_price_func" style="display:none;"><span>функция для нахождения цены: </span>
						<textarea class="function_area" name="price_func"><?=isset($functions->price_func) ? $functions->price_func : ''?></textarea></div>
					</div>
					<div style="margin:5px 0;">
						<span style="font-weight: bold;">Тип цены:</span>
						<select id="price_type_list" name="price_type_col">
						<?php
							$i = 0; $columns_a = "";
					    	while ($i <= $functions->columns_count) {
					    		$selected = ($functions->price_type_column == ("A".$i)) ? 'selected' : '';
					    		$columns_a .= "<option ".$selected." value='".$i."'>A".$i."</option>";
					    		$i++;
					    	} echo $columns_a;
						?>
						</select>
						<span class="linkAction" style="float:right;" onclick="javacript:actionForm('form_price_type_func');actionImage('form_price_type_func');">функция
							<img id="form_price_type_func_img" src="<?=base_url()?>images/icons/selector-left.png" style="position:relative;top:3px;"/>
						</span>
						<div id="form_price_type_func" style="display:none;"><span>функция для определения типа цены: </span>
						<textarea class="function_area" name="price_type_func"><?=isset($functions->price_type_func) ? $functions->price_type_func : ''?></textarea></div>
					</div>
					<div style="margin:5px 0;">
						<span style="font-weight: bold;">Примечания:</span>
						<span>колонка: </span>
						<select id="comment_list" name="comment_col">
						<?php
							$i = 0; $columns_a = "";
					    	while ($i <= $functions->columns_count) {
					    		$selected = ($functions->comment_column == ("A".$i)) ? 'selected' : '';
					    		$columns_a .= "<option ".$selected." value='".$i."'>A".$i."</option>";
					    		$i++;
					    	} echo $columns_a;
						?>
						</select>
						<span class="linkAction" style="float:right;" onclick="javacript:actionForm('form_comment_func');actionImage('form_comment_func');">функция
							<img id="form_comment_func_img" src="<?=base_url()?>images/icons/selector-left.png" style="position:relative;top:3px;"/>
						</span>
						<div id="form_comment_func" style="display:none;"><span>функция для нахождения примечания: </span>
						<textarea class="function_area" name="comment_func"><?=isset($functions->comment_func) ? $functions->comment_func : ''?></textarea></div>
					</div>
					
					<div style="margin:5px 0;">
						<div style="float:left;margin-bottom:7px;width:100%;"><span style="font-weight:bold;">Обучение закончено? </span></div>
						<div><input type="radio" name="parsed" <?=(isset($functions->delete_tmp_table) && $functions->delete_tmp_table == true) ? 'checked' : ''?> value="0" /> Нет
						<input type="radio" name="parsed" <?=(isset($functions->delete_tmp_table) && $functions->delete_tmp_table == false) ? 'checked' : ''?> value="1" /> Да</div>
					</div>
				</div>
				<div class="fl" style="margin-top:14px;">
					<input type="hidden" id="is_parsed" name="is_parsed" value="<?=$is_parsed?>" />
					<input type="reset" value="Отмена" />
					<input type="submit" value="Обучить" />
				</div>
			</form>
		</div>
	</div>
	<?php if (isset($paginate_args) && !empty($paginate_args)) echo paginate_ajax($paginate_args); ?>
	<div style="float:left;width:100%;">
		<?php if(isset($price_table_diff)) echo $price_table_diff; ?>
	</div>
<?php } else { ?>
	<h1>На текущий момент данных по прайсу этого поставщика нет.</h1>
<?php } ?>
</div>
<?php require_once('footer.php'); ?>