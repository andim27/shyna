<?php
/*
* Блок регистрации
*/
?>
<script language="JavaScript" type="text/javascript">
<!--
$(document).ready(function() {
  if ($("#register_id").css("display")=="none") {
          $("#under_register_block").hide();
    } else {
          $("#under_register_block").show();
    }
  }
  )
var  ajax_actions_path="<?= base_url(); ?>main/ajax_action/";
function register_full()
{
if (precheck() == false) {return;};
$.post(ajax_actions_path,
        {
		 'action': "register",
				'username': $("#username").val(),
				'email': $("#email").val(),
                'pass1': $("#pass1").val(),
                'pass2': $("#pass2").val(),
                'fio' : $("#fio").val(),
                'city' : $("#city").val(),
                'tel' : $("#tel").val(),
                'company' : $("#company").val()
		 },
		function(data)
		{
			var message = '';
            data =  window["eval"](data)[0];
			if(data.status==1)
			{
                $("#error_msg").show().html('<span class="mainLabel">Вы успешно зарегистрированы!</span>');
                var t = setTimeout ( 'window.location = "<?= base_url(); ?>";',2000 );

		    }
			else //if(data.status<0)
			{
				$("#error_msg").show().html("Ошибка ввода: ");
				$("#username").css({'background-color':'white'});
				$("#email").css({'background-color':'white'});
				$("#pass1").css({'background-color':'white'});
				$("#pass1").css({'background-color':'white'});

				if( data.login_err!='' )
				{
                    //alert (data.login_err);
					$("#username").css({'background-color':'CC0000'});

					var e = document.getElementById('error_msg');

					$("#error_msg").html( e.innerHTML + '<br>'+ data.login_err)  ;
				}
				if( data.email_err!='' )
				{
                    //alert (data.email_err);
                    $("#email").css({'background-color':'#CC0000'});
                    var e = document.getElementById('error_msg');
					$("#error_msg").html( e.innerHTML + '<br>'+ data.email_err);
				}
                if( data.pass1_err!='' )
				{
                    //alert ('pass1' + data.pass1_err);
                    $("#pass1").css({'background-color':'#CC0000'});
                    var e = document.getElementById('error_msg');
					$("#error_msg").html( e.innerHTML + '<br>'+ data.pass1_err);
				}
                if( data.pass2_err!='' )
				{
                    //alert ('pass2' + data.pass2_err);
                    $("#pass2").css({'background-color':'#CC0000'});
                    var e = document.getElementById('error_msg');
					$("#error_msg").html( e.innerHTML + '<br>'+ data.pass2_err);
				}


			}
	   }
	);
}
function precheck() {
  if (($("#username").val() =="" ) || ($("#email").val() =="" ) || ($("#pass1").val() =="" ) ||  ($("#pass2").val() =="" ))
       {$("#error_msg").show().html('Ошибка ввода: есть пустое поле!');return false;}

   if ($("#pass1").val() != ($("#pass2").val() )) {
      {$("#error_msg").show().html('Ошибка ввода:подтвердите пароль!');return false;}
   }
   return true;
}
//-->
</script>

<?php if ((empty($user_id))&&($page=="main"))  :?>
   <div class="registr" id="register_id">
<?php else : ?>
   <div class="registr" id="register_id" style="display:none">
 <?php endif; ?>
<!--     <div class="registr" id="register_id">-->

        <div class="MHead">
         <div class="left"></div>
         <div class="right"></div>

          <span class="mainLabel">Регистрация</span>
        <!-- end.s1Head --></div>

              <div class="MfixBox">

              <form action="javascript: register_full();" name="user_form" id="user_form" method="post">
              <input type="hidden" value="register" name="action" id="action">
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
                    <td><span class="mainTxt">Пароль</span></td>
                    <td><input class="sregField" style="" id="pass1" name="pass1" type="password" onfocus="$('#error_msg').hide();" /></td>
                    <td><span class="mainTxt">-до 12 символов</span></td>
                  </tr>
                  <tr>
                    <td height="29"><span class="mBlueTxt">*</span></td>
                    <td><span class="mainTxt">Повторите Пароль</span></td>
                    <td><input class="sregField" style="" id="pass2" name="pass2" type="password" onfocus="$('#error_msg').hide();" /></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="29"><span class="mBlueTxt">*</span></td>
                    <td><span class="mainTxt">Mail</span></td>
                    <td><input class="bregField" style="" id="email" name="email" type="text" onfocus="$('#error_msg').hide();"/></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="29"></td>
                    <td><span class="mainTxt">ФИО</span></td>
                    <td><input class="sregField" style="" id="fio" name="fio" type="text" /></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="29"></td>
                    <td><span class="mainTxt">Телефон</span></td>
                    <td><input class="sregField" style="" id="tel" name="tel" type="text" /></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="29"></td>
                    <td><span class="mainTxt">Город</span></td>
                    <td><input class="sregField" style="" id="city" name="city" type="text" /></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="29"></td>
                    <td><span class="mainTxt">Компания</span></td>
                    <td><input class="sregField" style="" id="company" name="company" type="text" /></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="30"></td>
                    <td colspan="3"><span class="lTabBlack">Поля отмеченные ( <span class="lTabBlue">*</span> ) -обязательны к заполнению</span></td>

                  </tr>
                </table>
                 <input type="submit" value="" name="" class="registerButt" title="Регистрация">
                 </form>
               <!-- end . MfixBox --></div>

    <!-- end .resultSearch -->
    </div>

    <div id="under_register_block">
        <div class="MFoot"></div>
        <div class="clear"></div>
    </div>


 <div class="clear"></div>

  <!-- END Block Registracii  -->