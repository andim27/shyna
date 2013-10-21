<?php
/*
* Блок входа
* - main
*/
?>
<script language="JavaScript" type="text/javascript">
<!--
function setregister()
{
$('#register_id').show();
$('#username').focus();
$("#under_register_block").show();
}

function enter_js()
{
 $('#login_err').show();
 $('#mes_enter_id').html("<strong>Выполняю вход...</strong>");
 action_enter="<?= base_url() ?>main/enter/";
 $.post(action_enter,
        {
		 'action': "enter",
		 'login_name':$("#login_name").val(),
		 'login_psw': $("#login_psw").val()
		 },
		function(data)
		{
		   data =  window["eval"](data)[0];
           $('#mes_enter_id').show().html("<strong>"+data.login_mes+" </strong>");
           if(data.status==1)
			{
			  $('#mes_enter_id').css({'color':'#006699'});

			} else {
			    $('#mes_enter_id').css({'color':'#FF0000'});
 			    $("#login_psw").html("")
				$("#login_name").html("").focus();
            }
            setTimeout ( 'window.location = "<?= base_url(); ?>";',2000 ); 
		}
 )
}
//-->
</script>
  <!-- Block Vhoda -->
    <?php if (empty($user_id)) :?>
     <div class="Vhod">

         <div class="s1Head">
         <div class="left"></div>
         <div class="right"></div>

          <span class="sideLabel">Вход</span>

        <!-- end.s1Head --></div>

              <div class="enterBox">
              <form name="enter" id="enter" >
               <input type="hidden" value="enter" name="action_enter" id="action_enter">
              <table width="210" border="0" cellpadding="0" cellspacing="0">
                  <tr id="login_err" style="display:none">
                      <td height="20" colspan="2"><span  id="mes_enter_id" class="mainTxt">Message:</span></td>
                  </tr>
                  <tr>
                    <td height="20" colspan="2"><span class="mainTxt" >Имя:</span></td>
                  </tr>
                  <tr>
                    <td height="20" colspan="2"><input class="vField" name="login_name" id="login_name" type="text" onchange="$('#mes_enter_id').hide();" /></td>
                  </tr>
                  <tr>
                    <td height="20" colspan="2"><span class="mainTxt">Пароль:</span></td>
                  </tr>
                  <tr>
                    <td height="20" colspan="2"><input class="vField" name="login_psw" id="login_psw" type="password" value="" onkeypress="if (event.keyCode == 13) enter_js();" /></td>
                  </tr>
                  <tr>
                    <td width="88" height="40">
                    <input class="enterButt" name="" type="button" value="" onclick="javascript:enter_js();" title="Войти"/></td>
                    <td width="122">
                                   <a class="vh" href="<?= base_url(); ?>register/">Регистрация</a><br />
                                   <a class="vh" href="javascript:fogotpsw();">Забыли пароль?</a>
                    </td>
                  </tr>
              </table>
              </form>
              <!-- end .enterBox --></div>




     <!-- end .Vhod --></div>
     <div class="s2Foot"></div>
     <div class="clear"></div>
   <?php endif; ?>
  <!-- END Block Vhoda -->
  <?php include('fogot_psw.php'); ?>