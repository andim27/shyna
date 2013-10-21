<div class="priceBlock">
	<div class="head_label">Результат обучения</div>
	<div class="priceBox">
		<table class="headerTab" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th>#</th>
					<th>Имя бренда</th>
					<th>Имя бренда (найденное)</th>
					<th>Ширина</th>
					<th>Ширина (найденное)</th>
					<th>Высота</th>
					<th>Высота (найденное)</th>
					<th>Радиус</th>
					<th>Радиус (найденное)</th>
					<th>Индекс скорости</th>
					<th>Индекс скорости (найденное)</th>
					<th>Индекс загрузки</th>
					<th>Индекс загрузки (найденное)</th>
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
				$price_str .= '<td>'.$pdata->{$brand_col}.'</td>';
				$price_str .= '<td>'.$pdata->brand_name.'</td>';
				$price_str .= '<td>'.$pdata->{$width_col}.'</td>';
				$price_str .= '<td>'.$pdata->width_tyre.'</td>';
				$price_str .= '<td>'.$pdata->{$height_col}.'</td>';
				$price_str .= '<td>'.$pdata->height_tyre.'</td>';
				$price_str .= '<td>'.$pdata->{$radius_col}.'</td>';
				$price_str .= '<td>'.$pdata->radius_tyre.'</td>';
				$price_str .= '<td>'.$pdata->{$load_index_col}.'</td>';
				$price_str .= '<td>'.$pdata->load_index.'</td>';
				$price_str .= '<td>'.$pdata->{$speed_index_col}.'</td>';
				$price_str .= '<td>'.$pdata->speed_index.'</td>';
				$price_str .= '</tr>';
				
				$pd_index++;
			}
			echo $price_str;
			?>
			</tbody>
		</table>
	</div>
</div>
