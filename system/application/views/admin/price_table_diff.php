<?php
	if(isset($price_data) && !empty($price_data)) {	
?>
	<div class="priceBlockMain" style="margin-left:15px;width:1015px;">
		<div style="float: left; width: 1010px;">
			<div class="head_label">Результат обучения прайса</div>
			<div style="float:right">
				<select id="actions" onchange="javascript: actions();">
					<option value="0">Выберите действие</option>
					<option value="show_all">Отобразить все</option>
					<option value="show_founded">Отобразить только найденные</option>
					<option value="show_not_founded">Отобразить только не найденные</option>
					<option value="apply_founded">Применить найденные</option>
					<option value="delete_all">Удалить все значения</option>
				</select>
			</div>
		</div>
		<?php if(isset($statistics) && !empty($statistics)) { ?>
		<div style="float: left; width: 1010px;">
			<div><h2>Статистика обучения: </h2></div>
			<div>
				<span>найдено значений: </span><span id="count_not_null"><?=$statistics->count_not_null?>;</span>
				<span>не найдено: </span><span><span id="count_null"><?=$statistics->count_null?></span>
			</div>
		</div>
		<?php } ?>
		<div class="priceTable">
			<div class="priceTableHeader">
				<div class="colNumRow"># счета</div>
				<div class="colBrandName">Имя бренда</div>
				<div class="colWidth">Ширина</div>
				<div class="colProfile">Профиль</div>
				<div class="colDiameter">Диаметр</div>
				<div class="colModel">Модель</div>
				<div class="colLoadIndex">Индекс нагрузки</div>
				<div class="colSpeedIndex">Индекс скорости</div>
				<div class="colAmount">Количество</div>
				<div class="colPrice">Цена</div>
				<div class="colPrice">Тип цены</div>
				<div class="colComment">Примечания</div>
			</div>
			<div id="priceDiffTableBody" class="priceTableBody">
			<?php
				$price_str = '';				
				foreach ($price_data as $index_price=>$pdata) {					
					if( $index_price < $functions->rows_ignore ) continue;
					
					$price_str .= '<div class="priceTableRow" id="row_'.$pdata->row_id.'">';
					$price_str .= '
						<div class="colNumRow">'.$pdata->row_id.'</div>
						<div class="colBrandName">'.$pdata->brand.'</div>
						<div class="colWidth">'.$pdata->width.'</div>
						<div class="colProfile">'.$pdata->profile.'</div>
						<div class="colDiameter">'.$pdata->diameter.'</div>
						<div class="colModel">'.$pdata->model_name.'</div>
						<div class="colLoadIndex">'.$pdata->load_index.'</div>
						<div class="colSpeedIndex">'.$pdata->speed_index.'</div>
						<div class="colAmount">'.$pdata->amount.'</div>
						<div class="colPrice">'.$pdata->price.'</div>
						<div class="colPrice">'.$pdata->price_type.'</div>
						<div class="colComment">'.$pdata->comment.'</div>';
					$price_str .= '</div>';
				}
				echo $price_str;
			?>
			</div>
		</div>			
	</div>	
<?php
	}
?>