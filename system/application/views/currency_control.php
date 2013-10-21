<?php
 /*
 Currency block
 */


?>
<script language="JavaScript" type="text/javascript">
<!--
function currency_calc() {
  cur_cur=cur_cur+1;
  if (cur_cur>4) cur_cur=0;
  for (var i=0;i<=4;i++) {
      if (i==cur_cur) {
         $("#cur_"+i).attr("disabled","");
         $("#cur_"+i).attr("checked","checked");

      } else {
         $("#cur_"+i).attr("disabled","disabled");
         $("#cur_"+i).attr("checked","");
      }
  }
  getProposalInfo(cur_rec);
}
//-->
</script>
<div class="infoPropS">

        <div class="s1Head">
         <div class="left"></div>
         <div class="right"></div>

          <span class="sideLabel">Пересчет стоимости:</span>

        </div>
        <div id="prop_info">

           <table width="320" cellspacing="0" cellpadding="0" border="0" class="infoTable kurs">
            <tbody><tr>
              <td width="76" height="25"><input id="cur_0" type="radio" value="" name="" checked="checked" ></td>
              <td width="62"><input id="cur_1" type="radio" value="" name="" disabled="disabled"></td>
              <td width="62"><input id="cur_2" type="radio" value="" name="" disabled="disabled"></td>
              <td width="59"><input id="cur_3" type="radio" value="" name="" disabled="disabled"></td>
              <td width="61"><input id="cur_4" type="radio" value="" name="" disabled="disabled"></td>
            </tr>
            <tr>
              <td height="25">Оригинал</td>
              <td>UAH</td>
              <td>USD</td>
              <td>EUR</td>
              <td>RUB</td>

            </tr>
            <tr>
              <td colspan="5"><input type="button" class="shetButt" name="" value="" onclick="javascript:currency_calc();" title="Пересчитать"></td>

            </tr>
          </tbody>
        </table>
        </div>

        <span class="infoLabel"><span class="lTabBlue">*</span>Пересчет производится по усредненному курсу. Правильные цены показаны в режиме "Оригинал"</span>

</div>
<div class="s2FootS"></div>
<div class="clear"></div>