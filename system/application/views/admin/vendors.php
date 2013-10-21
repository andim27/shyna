<?php require_once('header.php'); ?>
<script type="text/javascript" src="<?=base_url()?>js/js_ajax/ajax_admin.js"></script>
<script type="text/javascript">set_admin_base_url('<?=base_url()?>', 'vendors');</script>

<script type="text/javascript">
	function actionForm(form_id) {
		var el = document.getElementById(form_id).style.display;	
		if(el == 'none') {
			$('#'+form_id).show("blind");
		}
		else {
			$('#'+form_id).hide("blind");
		}
	}

</script>
	<div class="priceBlock" style="float:left;margin:20px 0 5px 20px;width:1140px;">
		<div class="heaLabelBlock">
			<h2>Список поставщиков</h2>
		</div>		
		<div class="priceBox">
			<table class="headerTab" cellpadding="2" cellspacing="0">
				<thead>
					<tr>
						<th>#</th>
						<th style="width:150px;">Имя поставщика</th>
						<th style="width:100px;">Город</th>
						<th style="width:100px;">Телефон</th>
						<th style="width:100px;">Факс</th>
						<th style="width:100px;">E-mail</th>
						<th style="width:100px;">Дата последнего прайса</th>
						<th style="width:140px;">Статистика обучения:</th> 
						<th style="width:140px;">количество позиций в прайсе:</th>
						<th style="width:110px;">Статус прайса</th>
					</tr>
				</thead>
				<tbody id="priceBoxBody">
				<?php
					if(isset($vendors) && !empty($vendors)) {
						$vendor_str = '';
						foreach ($vendors as $vindex=>$vendor) {
							$vindex++;
							$vendor_str .= '<tr>';
							$vendor_str .= '<td>'.$vindex.'</td>';
							$vendor_str .= '<td><a href="'.base_url().'admin/vendors/profile/'.$vendor->vendor_id.'">'.$vendor->vendor_name.'</a></td>';
							$vendor_str .= '<td>'.$vendor->vendor_city.'</td>';
							$vendor_str .= '<td>'.$vendor->vendor_phone.'</td>';
							$vendor_str .= '<td>'.$vendor->vendor_fax.'</td>';
							$vendor_str .= '<td>'.$vendor->vendor_email.'</td>';
							if(empty($vendor->price_date)){
								$vendor_str .= '<td>---</td>';
							}
							else{							
							$vendor_str .= '<td>'.date("d/m/Y", strtotime($vendor->price_date)).'</td>';
							}
							
							$vendor_psum=$vendor->count_not_null+$vendor->count_null;

							$vendor_str .='<td align="left">найдено: '.$vendor->count_not_null.'<br>не найдено: '.$vendor->count_null.'</td>';
							$vendor_str .='<td>'.$vendor_psum.'</td>';
							
							$vendor_str .= '<td>'.$vendor->price_status.'</td>';
							$vendor_str .= '</tr>';
							
						}
						echo $vendor_str;
					}
				?>
				</tbody>
			</table>
		</div>
	</div>
<?php require_once("footer.php"); ?>