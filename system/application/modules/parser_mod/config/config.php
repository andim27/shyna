<?php 
	$config['templates'] = array(
		'tyre' => 
			array(
				array(
					'name' => 'Ширина/Высота/Радиус',
					'tpl_name' => 'whr',
					'sql' => 
						array(
							'brandname' => "SELECT INSTR('foobarbar', 'bar')",
							'width' => "SUBSTRING_INDEX(INSTR(%column_name%, '/'), '/', 1)", //"SUBSTRING_INDEX(A2, '/', 1)",
							'height' => "SUBSTRING_INDEX(SUBSTRING(%column_name%,INSTR(%column_name%,'/')+1),'/', 1)",
							'radius' => "SUBSTRING_INDEX(
								(SUBSTRING(
								SUBSTRING(%column_name%, 
									INSTR(%column_name%,'/')+1
								), 
								INSTR(SUBSTRING(%column_name%, INSTR(%column_name%,'/')+1),'/')+1)),
							' ',
							1)",
							'model' => "REPLACE(REPLACE(A4, A3, ''), (concat((REVERSE(SUBSTRING(REVERSE(SUBSTRING_INDEX(A4, ' ', -1)), 2))), (SUBSTRING(SUBSTRING_INDEX(A4, ' ', -1), -1)))), '')",
							'speed_index' => "SUBSTRING(SUBSTRING_INDEX(tmp.A2, ' ', -1), -1)",
							'load_index' => "REVERSE(SUBSTRING(REVERSE(SUBSTRING_INDEX(tmp.A2, ' ', -1)), 2))"
						)
				)
			),
			array(
				'name' => 'Ширина/Высота R Радиус',
				'tpl_name' => 'wh_R_r',
				'sql' => ''
			),
			array(
				'name' => 'Ширина/ВысотаRРадиус',
				'tpl_name' => 'whRr',
				'sql' => ''
			)
	);
	$config['ext_allowed'] = array('.xls', '.xlsx', '.cvs');
?>