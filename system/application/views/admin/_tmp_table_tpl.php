<?php
	$price_str = '';
	foreach ($price_data as $index_price=>$pdata) {
		$index_price++;
		$i = 1;
				
		$price_str .= '<tr>';
		$price_str .= '<th>'.$pdata->row_id.'</th>';
		while ($i <= $functions->columns_count) {
    		$price_str .= '<td>'.$pdata->{'A'.$i}.'</td>';
    		$i++;
    	}
		$price_str .= '</tr>';
	}
	echo $price_str;
?>