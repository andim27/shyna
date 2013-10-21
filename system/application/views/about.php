<?php
/*
* Описание About
* - main
*/
?>
 <!-- Block O Nas  -->

     <div class="aboutBox">

        <div class="MHead">
         <div class="left"></div>
         <div class="right"></div>

          <span class="mainLabel">Добро пожаловать</span>

        <!-- end.s1Head --></div>

       <div class="mbox">

           <span class="mainTxt"><br />
           <b>ШинОК </b>- простой и быстрый способ найти шины.<br />
            Используя этот сайт Вы можете:<br>
           <ul style="list-style-image: url('<?= base_url(); ?>/images/snow.gif')">
             <li>Найти поставщика с нужными шинами;
             <li>Выбрать лучшее ценовое предложение;
             <li>Разместить свой прайс лист.
           </ul>
           <?php if (empty($user_id))  :?>
            <a href="javascript:setregister();">Зарегистрируйтесь</a> и узнайте больше!<br />
           <?php endif; ?> 
           </span>

               <div align="center">
                 <span class="mainTxt"> </span> <br /><br />
                  <img border="0" alt="" src="<?= base_url(); ?>images/diski.gif" width="419" height="102" />
               </div>


       <!-- end.mbox --></div>


     <!-- end.aboutBox--></div>
     <div class="MFoot"></div>
     <div class="clear"></div>

  <!-- END Block O Nas  -->