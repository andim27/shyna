<?php
/*
* Block proposal information
*/
?>
<?php    //pr("\n VIEW _prop_info user_id=".$user_id);   ?>
<?php if (!empty($user_id)) : ?>
<table class="infoTable"  width="340" border="0" cellpadding="0" cellspacing="0">
                  <tr class="nTr">
                    <th width="30">Дата</th>
                    <th width="20">&nbsp;К-во&nbsp;</th>
                    <th width="60"  style="cursor:pointer" onclick="javascript:p_order(1);">Цена<span class="lTabBlue">*</span></th>
                    <th width="110" style="cursor:pointer" onclick="javascript:p_order(2);">Поставщик</th>
                    <th width="80"  style="cursor:pointer" onclick="javascript:p_order(3);">Город</th>
                    <th width="38">Примечание</th>
                  </tr>
                  <?php if (!empty($p_info_items)) :?>
                  <?php foreach ($p_info_items as $item) :?>
                      <tr class="hTr">
                        <td><?= $item->price_date; ?></td>
                        <td align="center" width="25px"><?= eregi("есть|Есть|более|Более|^>|^<|$>|$<|0",$item->amount)?"?":$item->amount; ?></td>
                        <td align="center" width="65px"><?= getPriceByCuorse($item->price,$item->course,$item->currency_id); ?> <?= $item->currency_symbol; ?></td>

                         <?php if (($group_id != 3)&&($group_id != 2)) :?>
                             <td width="110px" align="center" style="overflow: hidden;" title="Доступно для активных пользователей"><a href="javascript:show_vendor_more(<?= $item->vendor_id; ?>);">***</a></td>
                         <?php else: ?>
                             <td width="110px" align="center" style="overflow: hidden;" title="<?= $item->vendor_short_name;; ?>"><a href="javascript:show_vendor_more(<?= $item->vendor_id; ?>);"><?=$item->vendor_short_name; ?></a></td>
                         <?php endif; ?>
                         <?php if (($group_id != 3)&&($group_id != 2)) :?>
                             <td width="80px"  title="Доступно для активных пользователей"><span style="margin-left:20px"><a href="javascript:show_vendor_more(<?= $item->vendor_id; ?>);">***</a></span></td>
                         <?php else: ?>
                             <td width="80px"  title="<?= $item->city; ?>"><?= $item->short_city; ?></td>
                         <?php endif; ?>

                        <td width="38px"  title="<?= $item->extra; ?>"><?= mb_substr($item->extra,0,20); ?></td>
                      </tr>
                  <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="5" align="center" height="30px;">
                      <span class="lTabBlue">Данных нет</span>
                      </td>
                    </tr>
                  <?php endif; ?>
</table>
<?php else : ?>
 <table class="infoTable"  width="340" border="0" cellpadding="0" cellspacing="0">
            <tr class="nTr">
                    <th width="30">Дата</th>
                    <th width="20">&nbsp;К-во&nbsp;</th>
                    <th width="60">Цена<span class="lTabBlue">*</span></th>
                <!--    <th width="110">Поставщик</th>     -->
                <!--    <th width="70"  align="left">Город</th>   -->
                    <th width="45"  align="left">Примечание</th>
             </tr>
             <?php if (!empty($p_info_items)) :?>
                  <?php foreach ($p_info_items as $item) :?>
                      <tr class="hTr">
                        <td><?= $item->price_date; ?></td>
                        <td align="center"><?= eregi("есть|Есть|более|Более|^>|^<|$>|$<|0",$item->amount)?"?":$item->amount; ?></td>
                        <td align="center"><?= $item->price*(empty($item->course)?1:$item->course); ?> <?= $item->currency_symbol; ?></td>
                        <td width="45px"  title="<?= $item->extra; ?>"><?= mb_substr($item->extra,0,20); ?></td>
                      </tr>
                  <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="5" align="center" height="30px;">
                      <span class="lTabBlue">Данных нет</span>
                      </td>
                    </tr>
             <?php endif; ?>
 </table>
<?php endif; ?>