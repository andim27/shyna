<?php
/*
* Блок регистрации
*/
?>
<script language="JavaScript" type="text/javascript">
<!--

var  ajax_actions_path="<?= base_url(); ?>main/ajax_action/";
function feedback_full()
{
if (precheckFeed() == false) {return;};
$.post(ajax_actions_path,
        {
		 'action': "feedback",
				'username': $("#username").val(),
				'email': $("#email").val(),
                'tel' : $("#tel").val(),
                'message' : $("#message").val(),
                'captcha': $("#captcha").val()
		 },
		function(data)
		{
			var message = '';
            data =  window["eval"](data)[0];
			if(data.status==1)
			{
                $("#error_msg").show().html('<span class="mainLabel">Ваше сообщение отправлено!</span>');
                var t = setTimeout ( 'window.location = "<?= base_url(); ?>";',2500 );

		    }
			else //if(data.status<0)
			{
				$("#error_msg").show().html("Ошибка ввода: ");
				$("#email").css({'background-color':'white'});
				if( data.email_err!='' )
				{
                    $("#email").css({'background-color':'#CC0000'});
                    var e = document.getElementById('error_msg');
					$("#error_msg").html( e.innerHTML + '<br>'+ data.mes);
				}

			}
	   }
	);
}
function precheckFeed() {
  if (($("#username").val() =="" ) || ($("#email").val() =="" ) || ($("#tel").val() =="" ))
       {$("#error_msg").show().html('Ошибка ввода: есть пустое поле!');return false;}

   return true;
}
//-->
</script>


   <div class="registr" id="register_id">

<!--     <div class="registr" id="register_id">-->

        <div class="MHead">
         <div class="left"></div>
         <div class="right"></div>

          <span class="mainLabel">Обратная связь</span>
        <!-- end.s1Head --></div>

              <div class="MfixBox">

              <form action="javascript: feedback_full();" name="feedback_form" id="feedback_form" method="post">
              <input type="hidden" value="feedback" name="action" id="action">
              <em><div id="error_msg"></div></em>
               <table width="495" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="15" height="29"><span class="mBlueTxt">*</span></td>
                    <td width="182"><span class="mainTxt">Имя</span></td>
                    <td width="178"><input class="sregField" style="" id="username" name="username" type="text" onfocus="$('#error_msg').hide();"/></td>
                    <td width="120"><span class="mainTxt">-до 8 символов</span></td>
                  </tr>

                  <tr>
                    <td height="29"><span class="mBlueTxt">*</span></td>
                    <td><span class="mainTxt">Ваш e-mail</span></td>
                    <td><input class="bregField" style="" id="email" name="email" type="text" onfocus="$('#error_msg').hide();"/></td>			
                    <td>&nbsp;</td>
                  </tr>

                    <td height="29"><span class="mBlueTxt">*</span></td>
                    <td><span class="mainTxt">Телефон(+код города)</span></td>
                    <td><input class="sregField" style="" id="tel" name="tel" type="text" /></td>
                    <td>&nbsp;</td>
                  </tr>

                  <tr>
                    <td height="29"></td>
                    <td><span class="mainTxt">Сообщение</span></td>
                    <td colspan="2"><textarea class="slongMesField" style="" id="message" name="message" type="text" ></textarea></td>
                  </tr>
                  <tr>
                    <td height="29"></td>
                    <td><span class="mainTxt">Введите текст на картинке</span></td>
                    <td colspan="2"><?php echo $cap_img ;?><br><input type="text" id="captcha" name="captcha" size="17"  value="" /></td>
                  </tr>
                  <tr>
                    <td height="30"></td>
                    <td colspan="3"><span class="lTabBlack">Поля отмеченные ( <span class="lTabBlue">*</span> ) -обязательны к заполнению</span></td>

                  </tr>
                </table>
                 <input type="submit" value="" name="" class="sButtSend" title="Отправить">
                 </form>
               <!-- end . MfixBox --></div>

    <!-- end .resultSearch -->
    </div>

    <div id="under_register_block">
    <!--  <div class="MFoot"></div>       -->
        <div class="clear"></div>
    </div>


 <div class="clear"></div>

  <!-- END Block Registracii  -->