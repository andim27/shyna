<?php
/*
* Блок адреса поставщика
* - seach
*/
?>
  <!-- Block Postavshikov  -->


  <div class="postavshik">

        <div class="MHead">
         <div class="left"></div>
         <div class="right"></div>

          <span class="mainLabel">Поставщик</span>

        <!-- end.s1Head --></div>
            <?php if (($group_id != 3)&&($group_id != 2)) :?>

            <div style="text-align: center">
              <span class="textBlack">Информация доступна для <font color="#FF0000">активных</font> пользователей.</span><br>
               <?php if (date("Y-m-d") > $user_data->active_end_date): ?>
               <span class="textBlack"><font color="#FF0000">Исчерпан срок активности.</font></span>
               <?php endif; ?>
              <span class="textBlack">Обратитесь для открытия доступа к <a href="<?= base_url(); ?>contacts/">администратору</a></span>
            </div>
            <?php else: ?>
              <div class="MfixBox">
               <?php if (!empty($v_info_item)) :?>
                <table width="420" border="0" cellspacing="0" cellpadding="0" >
                  <tr valign="top">
                    <td width="80" height="36"><span class="mainTxt">Название:</span> </td>
                    <td colspan="3"><span class="mBlTxt"><strong><?= $v_info_item[0]->name; ?></strong></span></td>
                   </tr>
                  <tr valign="top">
					<td width="57"><span class="mainTxt">Адрес:</span></td>
                  </tr>
				  <tr valign="center">
				      <td width="57"><div class="imp3"></div></td>
                      <td colspan="3">
                         <span class="mBlTxt"><?= $v_info_item[0]->city; ?><br /></span></td>  
                  </tr>
				   <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
				  <tr valign="top">
                    <td><span class="mainTxt">Телефон:</span></td>
				 </tr>
				 <tr valign="center">
					<td><div class="imp2"></div></td>
                    <td colspan="3"><span class="mBlTxt"><?= $v_info_item[0]->phone; ?><br /></span></td>                         
                 </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
				  <?php
					 if ((!empty($v_info_item[0]->vendor_person_name))||(!empty($v_info_item[0]->vendor_person_email))||(!empty($v_info_item[0]->vendor_person_phone))) :
					echo "<tr valign=\"top\">				  
							<td colspan=\"4\">
								<span class=\"mainTxt\">Контактные лица:</span>                        
							</td>
						</tr>
						<tr valign=\"top\">
							<td colspan=\"4\">
								<table border=\"0\" width=\"420\" >";				
								for($k=0;$k<=count($v_info_item)-1;$k++){
								echo "<tr><td width=\"73\"><div class=\"imp1\"></div></td><td><span class=\"mBlTxt\">";							 
								echo $v_info_item[$k]->vendor_person_name;							
								echo "</span></td><td><span class=\"mBlTxt\">";
								echo $v_info_item[$k]->vendor_person_phone;
								echo "</span></td></tr>";
								if (!empty($v_info_item[$k]->vendor_person_email)) :
									echo "<tr><td>&nbsp;</td><td colspan=\"2\"><span class=\"mBlTxt\">";
									echo $v_info_item[$k]->vendor_person_email;
									echo "</span></td></tr>";
								endif; 
								}

						echo "</table>
						</td>
					</tr>";
					endif; 
					?>  
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                </table>
              <?php else : ?>
              <span class="lTabBlue" >&nbsp;Данных по поставщику нет&nbsp;</span>
              <?php endif; ?>
       <!-- end . MfixBox --></div>
           <?php endif; ?>

    <!-- end .resultSearch --></div>
    <div class="MFootS"></div>
    <div class="clear"></div>


  <!-- END Block Postavshikov  -->