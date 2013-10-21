<?php

 // Letter for admin
 // when prices parsed

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<body>
Произведен разбор прайсов<br>
Дата:<?=  date("d.m.Y");  ?><br>
Колличество:<?= count($items); ?><br>
=================================<br>
<?php if (!empty($items) ) :?>
<?php for ($i=0;$i <= count($items)-1;$i++) :?>
<?= $i+1; ?>)Поставщик: <?= $items[$i]["vendor_name"]; ?><br>
Формат файла: <?= $items[$i]["file_ext"]; ?><br>
Строк в прайсе:<br>
 -- Всего: <?= $items[$i]["all_rec"]; ?><br>
 -- непонятных: <?= $items[$i]["bad_rec"]; ?><br>
--------------------------------<br>
<?php endfor; ?>
<?php endif; ?>
=================================<br>
</body>