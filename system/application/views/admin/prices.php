<?php if(!empty($vendor) && is_object($vendor)) { ?>
	
<div class="vendorProfile" style="float:left;margin:20px 0 0 20px;width:790px;">
	<h1 style="font-size:15px;">Ваш прайс</h1>
	<div style="font-size:11px;margin-left:5px;margin-top:20px;">Дата последнего прайса: <?=date("d/m/Y", strtotime($vendor->price_date))?></div>
	<?php if(isset($upload_form)) echo $upload_form; ?>
	<?php if(isset($price_tmp_table)) echo $price_tmp_table; ?>
</div>

<?php } ?>