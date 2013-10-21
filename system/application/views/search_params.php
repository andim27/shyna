<?php
/*
* Блок  поиска по параметрам шины
* - main,search
*/
?>
<script language="JavaScript" type="text/javascript">
<!--
var page="<?= $page; ?>";
var base_url= "<?= base_url() ?>";
var cur_page=1;
var can_show=true;
var rec_cnt=$.cookie('rec_count');
var season =$.cookie('season_val');
function search_all(){
if ((page =='main')||(page='search')) {
  //document.forms['s_params'].action=base_url+"search/15/26/35/";
  //document.forms['s_params'].action=base_url+"search";
  //alert(document.forms['s_params'].action);
  document.forms['s_params'].submit();
}else {
s_res();
var rec_cnt=$.cookie('rec_cnt');
if (rec_cnt == undefined) {rec_cnt=$("#rec_count option:selected").val();}
$.post(
   "<?= base_url() ?>search/action_0",
   {
      "page":"<?= $page; ?>",
      "cur_page":cur_page,
      "rec_count":rec_cnt,
      "diameter":$("#diameter option:selected").val(),
      "profil":  $("#profil option:selected").val(),
      "width":   $("#width option:selected").val(),
      "season":  season,//$('input[name="season"]:checked').val(),
      "car":     car,//$('input[name="car"]:checked').val(),
      "brand":   brand//$('input[name="brand"]:checked').val()

   },
   function responseSearch(data) {
     makeResponseData(data);
	 showGridItems();
   }
  )
}
}
//-->
</script>
  <!-- Block Search Params -->
      <div class="<?=(($page=="search"?'searchParamsS':'searchParams'));?>">

        <div class="s1Head">
         <div class="left"></div>
         <div class="right"></div>

          <span class="sideLabel">Поиск по параметрам:</span>

        <!-- end.s1Head --></div>

        <div class="shemaK"></div>

        <div class="sBox">


            <table width="180" border="0" cellpadding="0" cellspacing="0">
            <form id="s_params" action="<?= base_url() ?>search" method="post" name="search_params">
            <input type="hidden" name="page" value="<?= $page; ?>">
            <input type="hidden" name="rec_count" id="rec_count" value="" >
            <tr><!-- b:Ширина -->
                <td height="34"><span class="textBlue">P</span><span class="textBlack"> - Ширина:</span></td>
                <td>&nbsp;</td>
                <td>
                            <select  name="width" id="width">
                                 <?php if (empty($width)) :?>
                                 <option selected="selected" value=-1 >Все</option>
                                 <?php else :?>
                                  <option  value="-1" >Все</option>
                                 <?php endif; ?>
                                 <?php foreach ($width_items as $item) :?>
                                              <?php if ((!empty($width) and ($width==$item->value))) :?>
                                                <option  value="<?= $item->id; ?>" selected="selected"><?= $item->value; ?></option>
                                              <?php else :?>
                                                <option  value="<?= $item->id; ?>" ><?= $item->value; ?></option>
                                              <?php endif; ?>
                                 <?php endforeach; ?>
                             </select>
                </td>
            </tr><!-- e:Ширина -->
            <tr> <!-- b:Профиль -->
                <td height="34"><span class="textBlue">H</span><span class="textBlack"> - Профиль:</span></td>
                <td>&nbsp;</td>
                <td>
                             <select name="profil" id="profil">
                                  <?php if (empty($profil)) :?>
                                 <option selected="selected" value=-1 >Все</option>
                                 <?php else :?>
                                  <option  value="-1" >Все</option>
                                 <?php endif; ?>
                                 <?php foreach ($profil_items as $item) :?>
                                             <?php if ((!empty($profil) and ($profil==$item->value))) :?>
                                                <option  value="<?= $item->id; ?>" selected="selected"><?= $item->value; ?></option>
                                             <?php else :?>
                                                <option  value="<?= $item->id; ?>" ><?= $item->value; ?></option>
                                             <?php endif; ?>
                                  <?php endforeach; ?>
                             </select>
                </td>
            </tr><!-- e:Профиль -->
            <tr><!-- b:Диаметр -->
                <td width="98" height="34">
                  <span class="textBlue">D</span><span class="textBlack"> - Диаметр:</span></td>
                <td width="10" >&nbsp;</td>
                <td width="72">
                            <select name="diameter" id="diameter">
                                  <?php if (empty($diameter)) :?>
                                        <option selected="selected" value=-1 >Все</option>
                                 <?php else :?>
                                        <option  value="-1" >Все</option>
                                 <?php endif; ?>
                                 <?php foreach ($diameter_items as $item) :?>
                                             <?php if ((!empty($diameter) and ($diameter==$item->value))) :?>
                                                <option  value="<?= $item->id; ?>" selected="selected"><?= $item->value; ?></option>
                                             <?php else :?>
                                                <option  value="<?= $item->id; ?>" ><?= $item->value; ?></option>
                                             <?php endif; ?>
                                 <?php endforeach; ?>
                             </select>
                </td>
              </tr> <!-- e:Диаметр -->



              <tr>
                <td height="50" colspan="3" align="center">
                  <input class="sButtStyle" name="s_btn" id="s_btn" type="button"  value="" title="Найти" onclick="javascript:search_all();"/>
                </td>
              </tr>
             </form>
             <script language="JavaScript" type="text/javascript">
                  $('#rec_count').val($.cookie('rec_cnt'));
             </script>
            </table>


        <!-- end .sBox--></div>

      <!-- end.searchParams --></div>
      <div class="<?=(($page=="search"?'s1FootS':'s1Foot'));?>"></div>
      <div class="clear"></div>

  <!-- End Block Search Params  -->