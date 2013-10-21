<style type="text/css" media="screen">
	@import '<?=base_url()?>js/uploader/swfuploader/css/default.css';
</style>
<div style="float:left;width:100%;">
	<form id="form1" action="<?=base_url()?>admin/vendors/upload_price" enctype="multipart/form-data" method="post">
		<div class="content">
				<table style="vertical-align:top;">
					<tr>
						<td>
							<div>
								<span>
									<input type="file" name="filename" id="filename" /><br/>
									<input type="hidden" name="filename" value="filename" />
								</span>
							</div>
						</td>
					</tr>
				</table>
				<input type="hidden" name="user_id" value="<?=$user->user_id?>" />
				<input type="hidden" name="vendor_id" value="<?=$vendor->vendor_id?>" />
				<input type="submit" value="Загрузить прайс" id="btnSubmit" style="margin:5px 0;" />
		</div>
	</form>
</div>