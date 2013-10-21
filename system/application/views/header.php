<?php
/*
* Header
* all
*/
?>
 <div class="header">

    <div class="headerTop">

        <div class="left">
          <div class="lpl">
           <a href="<?= base_url(); ?>"><img border="0" alt="SHINOK" title="SHINOK" src="<?= base_url(); ?>images/logo.jpg" width="192" height="60" /></a></div>
           <div id="aPos"><a href="<?= base_url(); ?>">www.shinok.biz</a></div> 

        <!-- end .left--></div>
       <?php if (!empty($user_login)) :?>
        <div class="right" id="welcom">

           <span class="labelBluT">Добро пожаловать</span>
           <br />

           <div class="userBox">
             <img alt="" style="padding-right:2px;" border="0" src="<?= base_url(); ?>images/people.jpg" width="9" height="19" />
             <span class="blackTxt"><?= $user_login; ?></span>

           </div>

        <!-- end .right-->
        </div>
        <?php endif; ?>
    <!-- end .headerTop --></div>

    <div class="headerMenu">

        <div id="left"></div>
        <div id="right"></div>

        <ul class="mainMenu">
        	<li><a class="mainMenu" href="<?= base_url(); ?>">Главная</a></li>
            <li><span class="mainMenu"></span></li>
            <li><a class="mainMenu" href="<?= base_url(); ?>contacts/">Контакты</a></li>
            <li><span class="mainMenu"></span></li>
            <li><a class="mainMenu" href="<?= base_url(); ?>profile/">Личный кабинет</a></li>
            <li><span class="mainMenu"></span></li>
            <?php if (!empty($user_id)):   ?>
            <li><a class="mainMenu" href="<?= base_url(); ?>logout/">Выход</a></li>
            <?php endif; ?>
        </ul>

    <!-- end .headerMenu --></div>

    <div class="headerBreadCr">

         <div class="left"></div>
         <div class="right"></div>
         <div class="home"></div>

            <a href="<?= base_url(); ?>">Главная</a>
            <?php if ($page == "search"): ?>
            <span>></span>
            <a href="javascript:search_all();">Поиск шин</a>
            <?php endif; ?>

    <!-- end .headerBreadCr--></div>

    <!-- end .header --></div>