<?php
/*
* Блок контент профайла
*/

?>
<style type="text/css">
/*<![CDATA[*/
div.tabs {
background: #4d8bbe;
padding: 1em;
}
/*
div.container {
margin: auto;
width: 90%;
margin-bottom: 10px;
}
*/
ul.tabNavigation {
list-style: none;
margin: 0;
padding: 0;
}

ul.tabNavigation li {
display: inline;
}

ul.tabNavigation li a {
padding: 3px 9px;
background-color: #FFFFFF;
color: #000;
text-decoration: none;
}

ul.tabNavigation li a.selected,
ul.tabNavigation li a.selected:hover {
background: #FFCC66;
color: #000;
}

ul.tabNavigation li a:hover {
background: #FFFF66;
color: #000;
}

ul.tabNavigation li a:focus {
outline: 0;
}

div.tabs div {
padding: 5px;
margin-top: 3px;
border: 1px solid #FFF;
background: #FFF;
}

div.tabs div h2 {
margin-top: 0;
}
</style>

<script language="JavaScript" type="text/javascript">
function save_price() {
  document.form_upload_price.submit();
}

function save_profile() {
   var options = {
        url:"<?= base_url(); ?>profile/save",
        resetForm:false,
        dataType: "html",
        success:showResponseData
  }
  $('#mydata').ajaxSubmit(options);
}
function showResponseData(data, statusText) {
     data =  window["eval"](data)[0];
     $("#mes_1").html(data.mes);
     var tm_id=setTimeout (function (){clearTimeout(tm_id);$("#mes_1").hide();},3000 );
}
function save_psw() {
  var options = {
        url:"<?= base_url(); ?>profile/save_psw",
        resetForm:true,
        dataType: "html",
        success:showResponsePsw
  }
  $('#form_psw').ajaxSubmit(options);
}
function showResponsePsw(data, statusText) {
     data =  window["eval"](data)[0];
     $("#mes_3").html(data.mes);
     var tm_id=setTimeout (function (){clearTimeout(tm_id);$("#mes_3").hide();},3000 );
}
function setNext(code,id){

  if (code == 13 ){
    el=document.getElementById(id);
    el.focus();
    return
  }
}
 //---------------------- tabs config ----------------------
$(function () {
    var tabContainers = $('div.tabs > div'); // получаем массив контейнеров
    tabContainers.hide().filter(':first').show(); // прячем все, кроме первого
    // далее обрабатывается клик по вкладке
    $('div.tabs ul.tabNavigation a').click(function () {
        tabContainers.hide(); // прячем все табы
        tabContainers.filter(this.hash).show(); // показываем содержимое текущего
        $('div.tabs ul.tabNavigation a').removeClass('selected'); // у всех убираем класс 'selected'
        $(this).addClass('selected'); // текушей вкладке добавляем класс 'selected'
        return false;
    }).filter(':first').click();
<?php if ($type==2):?>
$("#tb_2").click();
 <?php if ($sub_type==0):?>
    $('#mes_2').show().html("Файл отправлен!");
 <?php else :?>
    $('#mes_2').show().html("Ошибка отправки файла!");
 <?php endif; ?>
<?php endif; ?>
});

</script>
<?php if (!empty($user_login)) :?>
<div class="registr" id="register_id">
</div>

  <div class="MHead">
         <div class="left"></div>
         <div class="right"></div>

          <span class="mainLabel">Личный кабинет пользователя <?= $user_login; ?></span>
        <!-- end.s1Head -->
  </div>
     <div class="MfixBox">

     <div class="tabs">
<!-- Это сами вкладки -->
    <ul class="tabNavigation">
        <li><a class="" href="#first"  id="tb_1">Ваши Данные</a></li>
        <li><a class="" href="#second" id="tb_2">Ваш прайс</a></li>
        <li><a class="" href="#third"  id="tb_3">Смена пароля</a></li>
    </ul>
<!-- Это контейнеры содержимого -->
    <div id="first">
        <h4>Ваши настройки на сайте </h4>
        <p>Здесь можно изменить свои данные.Для изменения заполните поля и нажмите кнопку сохранить</p>
        <span id="mes_1" class="goodMes"></span>
        <form name="mydata" id="mydata" method="post">
        <table width="495" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="29"><span class="mBlueTxt">*</span></td>
                    <td><span class="mainTxt">Статус пользователя:</span></td>
					<?php if($user_data->group_id==2): ?>
							<td><span class=mainLabel>Администратор</span></td>
					<?php else: ?>		
                    <?php if (empty($user_data->active_end_date)) :?>
                    <td><span class=mainLabel>активен до: <span style="color:#FF9900;font-size:14px">без ограничений</span></span></td>
                    <?php else: ?>
                      <?php if (date("Y-m-d") > $user_data->active_end_date): ?>
                            <td><span class=mainLabel>доступ закончен</span></td>
						
					  <?php else: ?>
                            <td><span class=mainLabel>активен до: <span style="color:#FF9900;font-size:14px"><?= date("d-m-Y",strtotime($user_data->active_end_date)); ?></span></span></td>
							<?php endif; ?>							
                      <?php endif; ?>

                    <?php endif; ?>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td width="15" height="29"><span class="mBlueTxt">*</span></td>
                    <td width="162"><span class="mainTxt">Имя</span></td>
                    <td width="220"><input class="slongField" style="" id="username" name="username" type="text" disabled="disabled" onfocus="$('#error_msg').hide();" value="<?= $user_login; ?>" /></td>
                    <td width="100"><span class="mainTxt"></span></td>
                  </tr>
                   <tr>
                  <td height="29"></td>
                    <td><span class="mainTxt">ФИО</span></td>
                    <td><input class="slongField" style="" id="fio" name="fio" type="text" value="<?= $user_data->fio; ?>"  onkeypress="setNext(event.keyCode,'tel');" /></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="29"></td>
                    <td><span class="mainTxt">Телефон</span></td>
                    <td><input class="slongField" style="" id="tel" name="tel" type="tel" value="<?= $user_data->tel; ?>" onkeypress="setNext(event.keyCode,'city');" /></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="29"></td>
                    <td><span class="mainTxt">Город</span></td>
                    <td><input class="slongField" style="" id="city" name="city" type="text" value="<?= $user_data->city; ?>" onkeypress="javascript:setNext(event.keyCode,'company');" /></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="29"></td>
                    <td><span class="mainTxt">Компания</span></td>
                    <td><input class="slongField" style="" id="company" name="company" type="text" value="<?= $user_data->company; ?>" onkeypress="javascript:setNext(event.keyCode,'email');" /></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="29"><span class="mBlueTxt">*</span></td>
                    <td><span class="mainTxt">Mail</span></td>
                    <td><input class="slongField" style="" id="email" name="email" type="email" onfocus="$('#error_msg').hide();" value="<?= $user_data->email; ?>" onkeypress="javascript:setNext(event.keyCode,'s_btn');" /></td>
                    <td>&nbsp;</td>
                  </tr>

                  <tr>
                   <td height="29"></td>
                   <td></td>
                   <td> <input type="button" onclick="javascript:save_profile();" alt="Сохранить" name="s_btn" id="s_btn" class="sButtSave"></td>
                   <td></td>
                  </tr>

                </table>
                </form>
    </div>
    <div id="second">
        <h4>Ваш прайс в системе</h4>
        <span id="mes_2" class="goodMes"></span>
        <form id="form_upload_price" name="form_upload_price" action="<?=base_url()?>profile/upload_price" enctype="multipart/form-data" method="post">
          <input type="hidden" value="<?= $user_id; ?>" name="user_id" >
          <table width="495" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="29"></td>
                    <td><span class="mainTxt">Дата последней загрузки</span></td>
                    <td><input class="sregField" style="" id="date_load" name="date_load" type="text" value="<?=$pf_date;?>"/></td>
                    <td>&nbsp;&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="29"></td>
                    <td><span class="mainTxt">Файл прайса</span></td>
                    <td><input class="sregField" style="" id="userfile" name="userfile" type="file" /></td>
                    <td>&nbsp;&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="29"></td>
                    <td><span class="mainTxt">Комментарий</span></td>
                    <td><textarea class="commentField" name="comment" rows="4" cols="7"></textarea></td>
                    <td>&nbsp;&nbsp;</td>
                  </tr>
                  <tr>
                   <td height="29"></td>
                   <td></td>
                   <td> <input type="button" onclick="javascript:save_price();" alt="Загрузить" name="s_btn" class="sButtLoad"></td>
                   <td>&nbsp;&nbsp;</td>
                  </tr>
           </table>
        </form>
    </div>
    <div id="third">
        <h4>Данные для связи</h4>
        <p>Здесь можно поменять Ваш пароль входа в систему.</p>
            <span id="mes_3" class="goodMes"></span>
            <form name="form_psw" id="form_psw" method="post" action="<?= base_url() ?>/profile/save_psw">
            <table width="495" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="29"><span class="mBlueTxt">*</span></td>
                    <td><span class="mainTxt">Новый Пароль</span></td>
                    <td><input class="sregField" style="" id="pass1" name="pass1" type="password" onfocus="$('#error_msg').hide();" /></td>
                    <td><span class="mainTxt">-до 12 символов</span></td>
                  </tr>
                  <tr>
                    <td height="29"><span class="mBlueTxt">*</span></td>
                    <td><span class="mainTxt">Повторите Пароль</span></td>
                    <td><input class="sregField" style="" id="pass2" name="pass2" type="password" onfocus="$('#error_msg').hide();"  onkeypress="if (event.keyCode == 13) save_psw();"/></td>
                    <td>&nbsp;</td>
                  </tr>

                  <tr>
                   <td height="29"></td>
                   <td></td>
                   <td> <input type="button" onclick="javascript:save_psw();" alt="Изменить пароль" title="Изменить пароль" name="s_btn" class="sButtChange"></td>
                   <td></td>
                  </tr>
            </table>
            </form>
    </div>
</div>
</div>
<div class="clear"></div>
<?php else: ?>
 <span class="mainLabel">Для работы с сайтом надо <a href="javascript:setregister();" style="color:#FFCC00;">зарегистрироваться</a>!</span>
 <div class="clear"></div>
<?php endif; ?>

<!--<div class="MFoot"></div>
<div class="clear"></div>-->