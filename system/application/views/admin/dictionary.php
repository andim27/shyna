<?php require_once('header.php'); ?>
<script type="text/javascript" src="<?=base_url()?>js/js_ajax/ajax_admin.js"></script>
<script type="text/javascript">set_admin_base_url('<?=base_url()?>', 'dictionary');</script>

<script type="text/javascript">
$('.dictBlockMain').ready(function() {
	$("#brands :first").attr("selected", "selected");
	$("#models :first").attr("selected", "selected");
});
</script>
<div class="dictBlockMain">
	<div class="elementLoading" style="display:none;">
		<img src="<?=base_url()?>images/add-note-loader.gif" />
	</div>
	<div class="elementsGroup">
		<div class="elementsSection elementsBrand">
			<div>
				<span>Бренды:</span>
				<select id="brands" onchange="javascript: get_dicts('brands', 'models'); return false;">
				<option value="all">показать все</option>
				<?php
				$brands_str = '';
				if(isset($brands) && !empty($brands)) {
					foreach ($brands as $index=>$brand) {
						$selected = ($index == 0) ? 'selected' : '';
						$brands_str .= '<option '.$selected.' value="'.$brand->id.'">'.$brand->name.'</option>';
					}
				}
				echo $brands_str;
				?>
				</select>
				<span class="elementsImg">
					<img src="<?=base_url()?>images/icons/add.png" onclick="javascript: actionForm('brands_new', '');" title="Добавить"/>
					<img class="elementDel" src="<?=base_url()?>images/icons/delete.png" onclick="javascript: delete_type('brands');return false;" title="Удалять"/>
					<img  src="<?=base_url()?>images/icons/edit.png" onclick="javascript: edit_type('brands');return false;" title="Редактировать бренд"/>
				</span>
			</div>
            <div id="brands_edit" class="elementsSection" style="display:none;">
            </div>
			<div id="brands_new" class="elementsSection" style="display:none;">
				<div class="newElementsSection">
					<div class="elementLabel">название бренда:</div>
					<div>
						<input type="text" id="brands_keyword" value="" />
					</div>
				</div>
				<div class="newElementAdd">
					<span class="linkAction" onclick="javascript: add_brand(); return false;">добавить бренд</span>
				</div>
			</div>
		</div>
		<div class="elementsSection">
			<div>
				<span>Синонимы брендов:</span>
				<select id="brands_syn">				
				<?php
				$synonyms_str = '';
				if(isset($brands_syn) && !empty($brands_syn)) {
					foreach ($brands_syn as $index=>$synonym) {
						$selected = ($index == 0) ? 'selected' : '';
						$synonyms_str .= '<option '.$selected.' value="'.$synonym->id.'">'.$synonym->name.'</option>';
					}
				}
				echo $synonyms_str;
				?>
				</select>
				<span class="elementsImg">
					<img src="<?=base_url()?>images/icons/add.png" onclick="javascript: actionForm('brands_syn_new', '');" />
					<img class="elementDel" src="<?=base_url()?>images/icons/delete.png" style="position:relative;right:5px;" onclick="javascript: delete_synonym('brands');return false;" />
                    <img  src="<?=base_url()?>images/icons/edit.png" onclick="javascript: edit_synonym('brands');return false;" title="Редактировать синоним бренда"/>
				</span>
			</div>
            <div id="brands_syn_edit" class="elementsSection" style="display:none;">
            </div>
			<div id="brands_syn_new" class="elementsSection" style="display:none;">
				<div class="newElementsSection">
					<div class="elementLabel">название синонима:</div>
					<div>
						<input type="text" id="brands_syn_keyword" value="" />
					</div>
				</div>
				<div class="newElementAdd">
					<span class="linkAction" onclick="javascript: add_synonym('brands'); return false;">добавить синоним</span>
				</div>
			</div>
		</div>		
	</div>
	<div class="elementsGroup">	
		<div class="elementsSection elementsBrand">
			<div>
				<span>Модели:</span>
				<select id="models" onchange="javascript: get_dicts('models', 'brands'); return false;">
				<option value="all">показать все</option>
				<?php
					$models_str = '';
					if(isset($models) && !empty($models)) {
						foreach ($models as $index=>$model) {
							$selected = ($index == 0) ? 'selected' : '';
							$models_str .= '<option '.$selected.' value="'.$model->id.'">'.$model->name.'</option>';
						}
					}
					echo $models_str;
				?>
				</select>
				<span class="elementsImg">
					<img src="<?=base_url()?>images/icons/add.png" onclick="javascript: actionForm('models_new', '');" />
					<img class="elementDel" src="<?=base_url()?>images/icons/delete.png" onclick="javascript: delete_type('models');return false;" />
                    <img  src="<?=base_url()?>images/icons/edit.png" onclick="javascript: edit_type('models');return false;" title="Редактировать модель"/>
				</span>
			</div>
           	<div id="models_edit" class="elementsSection" style="display:none;">
            </div>
			<div id="models_new" class="elementsSection" style="display:none;">
				<div class="newElementsSection">
					<div class="elementLabel">тип машины:</div>
					<div>
						<select id="model_car">
							<option value="0">легковой</option>
							<option value="1">легкогрузовой</option>
							<option value="2">грузовой</option>
							<option value="3">внедорожник</option>
						</select>
					</div>
				</div>
				<div class="newElementsSection">
					<div class="elementLabel">сезон:</div>
					<div>
						<select id="model_season">
							<option value="0">лето</option>
							<option value="1">зима</option>
							<option value="2">всесезонная</option>
						</select>
					</div>
				</div>
				<div class="newElementsSection">
					<div class="elementLabel">имя модели:</div>
					<input type="text" id="models_keyword" value="" />
				</div>
				<div class="newElementAdd">
					<span class="linkAction" onclick="javascript: add_model(); return false;">добавить модель</span>
				</div>				
			</div>
		</div>
		<div class="elementsSection">
			<div>
				<span>Синонимы моделей:</span>
				<select id="models_syn">
				<?php
				$synonyms_str = '';
				if(isset($models_syn) && !empty($models_syn)) {
					foreach ($models_syn as $index=>$synonym) {
						$selected = ($index == 0) ? 'selected' : '';
						$synonyms_str .= '<option '.$selected.' value="'.$model->id.'">'.$synonym->name.'</option>';
					}
				}
				echo $synonyms_str;
				?>
				</select>
				<span class="elementsImg">
					<img src="<?=base_url()?>images/icons/add.png" onclick="javascript: actionForm('models_syn_new', '');" />
					<img class="elementDel" src="<?=base_url()?>images/icons/delete.png" onclick="javascript: delete_synonym('models');return false;" />
                    <img  src="<?=base_url()?>images/icons/edit.png" onclick="javascript: edit_synonym('models');return false;" title="Редактировать синоним модели"/>
				</span>
			</div>
            <div id="models_syn_edit" class="elementsSection" style="display:none;">
            </div>
			<div id="models_syn_new" class="elementsSection" style="display:none;">
				<div class="newElementsSection">
					<div class="elementLabel">название синонима:</div>
					<div>
						<input type="text" id="models_syn_keyword" value="" />
					</div>
				</div>
				<div class="newElementAdd">
					<span class="linkAction" onclick="javascript: add_synonym('models'); return false;">добавить синоним</span>
				</div>
			</div>
		</div>
	</div>
</div>
<?php require_once('footer.php'); ?>