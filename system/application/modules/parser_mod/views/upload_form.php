<style type="text/css" media="screen">
	@import '<?=base_url()?>js/uploader/swfuploader/css/default.css';
</style>
<div>
	<form id="form1" action="<?=$upload_url?>" enctype="multipart/form-data" method="post">
		<div class="content">
			<fieldset>
				<legend>Загрузите ваш прайс</legend>
				<table style="vertical-align:top;">
					<tr>
						<td>
							<div>
								<span>
									<input type="file" name="<?=$input_filename?>" id="<?=$input_filename?>" /><br/>
									<input type="hidden" name="filename" value="<?=$input_filename?>" />
								</span>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div style="margin:5px 0;">
								<span>Число активных колонок:</span>
								<span><input type="text" name="general_cols" value="12" style="width:50px;" /></span>
							</div>
							<div style="margin:5px 0;">
								<span>Игнорировать до ряда:</span>
								<span><input type="text" name="row_start" value="1" style="width:50px;" /></span>
							</div>
						</td>
					</tr>
				</table>
				<input type="submit" value="Загрузить прайс" id="btnSubmit" style="margin:5px 0;" />
			</fieldset>
		</div>
	</form>
</div>