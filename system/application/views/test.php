<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title></title>
    <meta http-equiv="content-type"content="text/html;charset=utf-8"/>
</head>
<body>
	<div><?php if(isset($upload_form)) echo $upload_form; ?></div>
	<div style="color:#006400;font-weight:bold;margin-left:17px;"><?php if(isset($result)) echo $result; ?></div>
	<div style="display:none;">
		<div style="float:left;">
			<table border=1 cellpadding="0" cellspacing="0">
				<!--<tr>
					<td><b>Отпускная цена</b></td>
				</tr>-->
				<?php
					/*$str = '';
					for($row = 15; $row <= $highestRow; ++$row) {
						$value = $aSheet->getCellByColumnAndRow(1, $row)->getValue();
						$str .= '<tr><td>';
						$str .= empty($value) ? '&nbsp;' : $value;
						$str .= '</td></tr>';
					}
					echo $str;
				?>
			</table>
		</div>
		<div style="float:left;">
			<table border=1 cellpadding="0" cellspacing="0">
				<tr>
					<td><b>ОПТ</b></td>
				</tr>
				<?php
					$str = '';
					for($row = 15; $row <= $highestRow; ++$row) {
						$value = $aSheet->getCellByColumnAndRow(2, $row)->getValue();
						$str .= '<tr><td>';
						$str .= empty($value) ? '&nbsp;' : $value;
						$str .= '</td></tr>';
					}
					echo $str;
				?>
			</table>
		</div>
		<div style="float:left;">
			<table border=1 cellpadding="0" cellspacing="0">
				<tr>
					<td><b>Остаток</b></td>
				</tr>
				<?php
					$str = '';
					for($row = 15; $row <= $highestRow; ++$row) {
						$value = $aSheet->getCellByColumnAndRow(3, $row)->getValue();
						$str .= '<tr><td>';
						$str .= empty($value) ? '&nbsp;' : $value;
						$str .= '</td></tr>';
					}
					echo $str;
				?>
			</table>
		</div>
		<div style="float:left;">
			<table border=1 cellpadding="0" cellspacing="0">
				<tr>
					<td><b>Остаток</b></td>
				</tr>
				<?php
					$str = '';
					for($row = 12; $row <= $highestRow; ++$row) {
						$value = $aSheet->getCellByColumnAndRow(4, $row)->getValue();
						$str .= '<tr><td>';
						$str .= empty($value) ? '&nbsp;' : $value;
						$str .= '</td></tr>';
					}
					echo $str;*/
				?>
			</table>
		</div>
	</div>
</body>
</html>