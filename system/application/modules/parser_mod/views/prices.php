<style type="text/css">
.result {
	color:#006400;
	font-weight:bold;
	margin-left:17px;
}
</style>
<div>
	<?php if(isset($upload_form)) echo $upload_form; ?>
	<?php if(isset($price_table)) echo $price_table; ?>
</div>
<div class="result">
	<?php if(isset($result)) echo $result; ?>
</div>