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