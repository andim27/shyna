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
function fogotpsw()
{
  $("#fogot").show();
}
function fogot_js()
{
 $('#mes_fogot_place').show();
 $('#mes_fogot_id').show().html("<strong>Выполняю восстановление...</strong>");
 action_enter="<?= base_url() ?>main/ajax_action/";
 $.post(action_enter,
        {
		 'action': "fogot",
		 'email':$("#e_mail").val()
		 },
		function(data)
		{
		   data =  window["eval"](data)[0];
           $('#mes_fogot_place').show();
           $('#mes_fogot_id').show().html("<strong>"+data.mes+" !</strong>");
           if(data.status==1)
			{
			  $('#mes_fogot_id').css({'color':'#006699'});
              setTimeout ( 'window.location = "<?= base_url(); ?>";',3000 );
			} else {
			    $('#mes_fogot_id').css({'color':'#FF0000'});
            }
             $('#mes_fogot_id').show().html("<strong>"+data.mes+" !</strong>");
		}
 )
}
//-->
</script>
  <!-- Block Vhoda -->
    <?php if (empty($user_id)) :?>
    <div  id="fogot" style="display:none">
     <div class="Vhod">

         <div class="s1Head">
         <div class="left"></div>
         <div class="right"></div>

          <span class="sideLabel">Восстановление пароля</span>

        <!-- end.s1Head --></div>

              <div class="enterBox">
              <form name="fogot" id="fogot" onkeypress="if (event.keyCode == 13) fogot_js();">
              <input type="hidden" value="enter" name="action_enter" id="action_enter">
              <table width="210" border="0" cellpadding="0" cellspacing="0">
                  <tr id="mes_fogot_place" style="display:none">
                      <td height="20" colspan="2"><span  id="mes_fogot_id" class="mainTxt" ></span></td>
                  </tr>
                  <tr>
                    <td height="20" colspan="2"><span class="mainTxt" >Ваш e-mail:</span></td>
                  </tr>
                  <tr>
                    <td height="20" colspan="2"><input class="vField" name="e_mail" id="e_mail" type="text" onchange="$('#mes_fogot_id').hide();" /></td>
                  </tr>
                  <tr>
                    <td width="88" height="40">
                    <input class="restoreButt" name="" type="button" value="" onclick="javascript:fogot_js();" title="Восстановить"/></td>
                  </tr>
              </table>
              </form>
              <!-- end .enterBox --></div>
     <!-- end .Vhod --></div>
       <div class="s2Foot"></div>
       <div class="clear"></div>
     </div>
   <?php endif; ?>
  <!-- END Block Vhoda -->