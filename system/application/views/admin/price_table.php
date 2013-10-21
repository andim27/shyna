<?php
	if(isset($price_data) && !empty($price_data)) { ?>
<br/><br/><br/><br/>

	<div class="priceBlockMain">
	    <div>
		<form action="/admin/vendors/parse_price/<?=$vendor->vendor_id; ?>" method="post">
		    <select name="sheet_num">
			<option selected value="0">Please choose</option>
		    <?php
		    foreach ($sheets as $sh)
		    {
			echo '<option value="'.$sh->sheet_id.'">'.$sh->sheet_name.'</option>';
		    }
		    ?>
		</select>
		  
		    <input type="submit" name="submit" value="Change" />
		</form>
	    </div>
		<div class="head_label">Результат загрузки прайса (данные из временной таблицы)</div>
		<div class="priceTable">			
			<table class="headerTab" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th>#</th>
						<?php
							$i = 1;
			            	$columns_a = "";
			            	while ($i <= $functions->columns_count) {
			            		$columns_a .= "<th>A".$i."</th>";
			            		$i++;
			            	}
			            	echo $columns_a;
						?>
					</tr>
				</thead>
				<tbody id="priceTableBody">
				<?php
				$price_str = '';
				foreach ($price_data as $index_price=>$pdata) 
				{
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
				</tbody>
			</table>
		</div>			
	</div>
<?php		
	} else {
		echo 'Похоже, что данных здесь и не предвиделось...';
	}
?>	
