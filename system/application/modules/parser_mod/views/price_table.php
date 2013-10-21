<?php
	/*$res = array();
	$load_index_val = '175/65/14 MA-P1 Maxxis 821H TL';
	preg_match("/\ ([0-9]{2,3})([A-z]{1})/", $load_index_val, $res); 
	echo "<pre>";
		print_r($res);
	echo "</pre>";
	exit;*/
?>
<div style="float:left;margin-bottom:25px;">
	<?php
		if(isset($price_data) && !empty($price_data)) {
//			echo 'Скоро здесь буду данные по прасу из tmp_shyna таблицы...';
	?>
	<div class="priceBlockMain">
	<?php if(!isset($diff_data_table)) { ?>
		<div class="priceBlock">			
			<div class="head_label">Результат загрузки прайса (данные из временной таблицы)</div>
			<div class="priceBox">
				<table class="headerTab" cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th>#</th>
							<th>A1</th>
							<th>A2</th>
							<th>A3</th>
							<th>A4</th>
							<th>A5</th>
							<th>A6</th>
							<th>A7</th>
							<th>A8</th>
							<th>A9</th>
							<th>A10</th>
							<th>A11</th>
							<th>A12</th>
						</tr>
					</thead>
					<tbody>
					<?php
					$price_str = '';
					$pd_index = 1;
					$class = '';
					foreach ($price_data as $index_price=>$pdata) 
					{
						if($index_price < $general_rows) continue;
						
						$price_str .= '<tr>';
						$price_str .= '<th>'.$pd_index.'</th>';
						$price_str .= '<td>'.$pdata->A1.'</td>';
						$price_str .= '<td>'.$pdata->A2.'</td>';
						$price_str .= '<td>'.$pdata->A3.'</td>';
						$price_str .= '<td>'.$pdata->A4.'</td>';
						$price_str .= '<td>'.$pdata->A5.'</td>';
						$price_str .= '<td>'.$pdata->A6.'</td>';
						$price_str .= '<td>'.$pdata->A7.'</td>';
//						$price_str .= '<td>'.$pdata->A8.'</td>';
//						$price_str .= '<td>'.$pdata->A9.'</td>';
//						$price_str .= '<td>'.$pdata->A10.'</td>';
//						$price_str .= '<td>'.$pdata->A11.'</td>';
//						$price_str .= '<td>'.$pdata->A12.'</td>';
						$price_str .= '</tr>';
						
						$pd_index++;
					}
					echo $price_str;
					?>
					</tbody>
				</table>
			</div>
		</div>
		<?php } if(isset($diff_data_table)) echo $diff_data_table; ?>			
	</div>
	<div class="priceTrain">
		<form action="<?=base_url()?>admin/prices/" method="POST">
			<div class="head_label">Обучение парсера</div>	
			<div>
				<div>
					<span style="font-weight: bold;">Общие:</span>
					<span>ряд:</span>
					<input type="text" name="general_row" value="1" style="width: 25px;">
				</div>
				<div>
					<span style="font-weight: bold;">Бренды:</span>
					<span>колонка: </span>
					<select name="brands_col">
						<option value="1">A1</option><option value="2">A2</option>
						<option value="3">A3</option><option value="4">A4</option>
						<option value="5">A5</option><option value="6">A6</option>
						<option value="7">A7</option><option value="8">A8</option>
						<option value="9">A9</option><option value="10">A10</option>
						<option value="11">A11</option><option value="12">A12</option>
					</select><br />
					<div style="margin-top:5px;"><span>функция: </span>
					<input type="text" name="brands_func" value="" style="width:350px;height:20px;" /></div>
				</div>
				<div>
					<span style="font-weight: bold;">Ширина(шины):</span><br />
					<span>колонка: </span>
					<select name="width_col">
						<option value="1">A1</option><option value="2">A2</option>
						<option value="3">A3</option><option value="4">A4</option>
						<option value="5">A5</option><option value="6">A6</option>
						<option value="7">A7</option><option value="8">A8</option>
						<option value="9">A9</option><option value="10">A10</option>
						<option value="11">A11</option><option value="12">A12</option>
					</select>
					<div style="margin-top:5px;"><span>функция ширины: </span>
					<input type="text" name="width_func" value="" style="width:350px;height:20px;" /></div>
				</div>
				<div>
					<span style="font-weight: bold;">Высота(шины):</span><br />
					<span>колонка: </span>
					<select name="height_col">
						<option value="1">A1</option><option value="2">A2</option>
						<option value="3">A3</option><option value="4">A4</option>
						<option value="5">A5</option><option value="6">A6</option>
						<option value="7">A7</option><option value="8">A8</option>
						<option value="9">A9</option><option value="10">A10</option>
						<option value="11">A11</option><option value="12">A12</option>
					</select>
					<div style="margin-top:5px;"><span>функция высоты: </span>
					<input type="text" name="height_func" value="" style="width:350px;height:20px;" /></div>
				</div>
				<div>
					<span style="font-weight: bold;">Радиус(шины):</span><br />
					<span>колонка: </span>
					<select name="radius_col">
						<option value="1">A1</option><option value="2">A2</option>
						<option value="3">A3</option><option value="4">A4</option>
						<option value="5">A5</option><option value="6">A6</option>
						<option value="7">A7</option><option value="8">A8</option>
						<option value="9">A9</option><option value="10">A10</option>
						<option value="11">A11</option><option value="12">A12</option>
					</select>
					<div style="margin-top:5px;"><span>функция радиуса: </span>
					<input type="text" name="radius_func" value="" style="width:350px;height:20px;" /></div>
				</div>
				<div>
					<span style="font-weight: bold;">Индекс скорости шин:</span><br />
					<span>колонка: </span>
					<select name="speed_index_col">
						<option value="1">A1</option><option value="2">A2</option>
						<option value="3">A3</option><option value="4">A4</option>
						<option value="5">A5</option><option value="6">A6</option>
						<option value="7">A7</option><option value="8">A8</option>
						<option value="9">A9</option><option value="10">A10</option>
						<option value="11">A11</option><option value="12">A12</option>
					</select>
					<div style="margin-top:5px;"><span>функция индекса: </span>
					<input type="text" name="speed_index_func" value="" style="width:350px;height:20px;" /></div>
				</div>
				<div>
					<span style="font-weight: bold;">Индекс нагрузки шин:</span><br />
					<span>колонка: </span>
					<select name="load_index_col">
						<option value="1">A1</option><option value="2">A2</option>
						<option value="3">A3</option><option value="4">A4</option>
						<option value="5">A5</option><option value="6">A6</option>
						<option value="7">A7</option><option value="8">A8</option>
						<option value="9">A9</option><option value="10">A10</option>
						<option value="11">A11</option><option value="12">A12</option>
					</select>
					<div style="margin-top:5px;"><span>функция индекса: </span>
					<input type="text" name="load_index_func" value="" style="width:350px;height:20px;" /></div>
				</div>
			</div>
			<!--<div style="clear:left;"></div>
			<div class="fl" style="margin-top:14px;">
				<div class="trainBlockType">Бренды</div>
				<div><input type="text" name="keywords_exc"></div>
			</div>-->
			<div class="fl" style="margin-top:14px;">
				<input type="hidden" name="method_name" value="parce_train" />
				<input type="reset" value="Отмена" />
				<input type="submit" value="Обучить" />
			</div>
		</form>
	</div>	
	<?php		
		} else {
			echo 'Похоже, что данных здесь и не предвиделось...';
		}
	?>	
</div>
