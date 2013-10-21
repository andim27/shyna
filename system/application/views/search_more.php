<?php
/*
* Блок расширенного поиска
* - main,search
*/
?>
<script language="JavaScript" type="text/javascript">
<!--
var season=-1;
var car=-1;
var brand=-1;
function setSeason(n) {
$.cookie('season_val',n);
season=n;
}
function restoreSeason() {
  season=$.cookie('season_val');
  if (season == null){season=-1;}
  if (season != -1) {
      $('#season_radio_'+season).attr('checked',true);
  } else {
      $('#season_radio').attr('checked',true);
  }
}
function setCar(n) {
$.cookie('car_val',n);
car=n;
}
function restoreCar() {
  car=$.cookie('car_val');
  if (car == null){car=-1;}
  if (car != -1) {
      $('#car_radio_'+car).attr('checked',true);
  } else {
      $('#car_radio').attr('checked',true);
  }
}
function setBrand(n) {
$.cookie('brand_val',n);
brand=n;
if (n == -1){
  $('#brand_radio').attr('checked',true);
} else {
  $('#brand_radio').attr('checked',false);
}
}
function restoreBrand() {
  brand=$.cookie('brand_val');
  if (brand == null){brand=-1; }
  if (brand != -1) {
      $('#brand_radio_'+brand).attr('checked',true);
  } else {
      $('#brand_radio').attr('checked',true);
  }
}
//-->
</script>
 <!-- Block Rashirennie Parametri -->
      <div class="<?=(($page=="search"?'searchAllParamsS':'searchAllParams'));?>">

        <div class="s1Head">
         <div class="left"></div>
         <div class="right"></div>

          <span class="sideLabel">Расширенный поиск:</span>

        <!-- end.s1Head --></div>

        <div class="<?=(($page=="search"?'sAllboxS':'sAllbox'));?>">

        <!-- Sezon -->
          <table width="<?=(($page=="search"?'170':'180'));?>" border="0" cellpadding="0" cellspacing="0" class="sAlltable">
              <tr>
                <td colspan="2"><span class="textBlack">Сезон:</span></td>

              </tr>
              <tr>
                <td width="5">&nbsp;</td>
                <td width="<?=(($page=="search"?'165':'175'));?>">
                    <label><input type="radio" name="season" id="season_radio" onclick="setSeason(-1);" value=-1  <?php if (isset($season) and (trim($season) == "Все") or (empty($season))) {echo "checked='checked'";} ?>  />Все</label>
                    <label>
                      <input type="radio" name="season"  id="season_radio_0" onclick="setSeason(0);" value=0 <?php if ((isset($season)) and (trim($season) == "летние")) {echo "checked='checked'";}  ?> />
                      <img border="0" alt="" src="<?= base_url(); ?>images/sun.gif" width="9" height="10" /> <span id="season_name_0">летние</span></label>


                    <label>
                      <input type="radio" name="season"  id="season_radio_1" onclick="setSeason(1);" value=1 <?php if ((isset($season)) and (trim($season) == "зимние")) {echo "checked='checked'";}  ?> />
                      <img border="0" alt="" src="<?= base_url(); ?>images/snow.gif" width="9" height="10" /> <span id="season_name_1">зимние</span></label>

                    <label>
                      <input type="radio" name="season"  id="season_radio_2" onclick="setSeason(2);" value=2 <?php if ((isset($season)) and (trim($season) == "всесезонные")) {echo "checked='checked'";}  ?> />
                      <img alt="" border="0" src="<?= base_url(); ?>images/sun.gif" width="9" height="10" /> <span id="season_name_2">всесезонные</span> <img border="0" alt="" src="<?= base_url(); ?>images/snow.gif" width="9" height="10" /></label>

                </td>
              </tr>
              <tr>
                <td colspan="2" height="15" class="sAllborder"></td>

              </tr>

           </table>


        <!-- end Sezon -->


        <!-- Tip avto -->

            <table width="<?=(($page=="search"?'170':'180'));?>" border="0" cellpadding="0" cellspacing="0" class="sAlltable">
              <tr>
                <td colspan="2"><span class="textBlack">Тип автомобиля:</span></td>

              </tr>
              <tr>
                <td width="5">&nbsp;</td>

                <td width="<?=(($page=="search"?'165':'175'));?>">

                    <label>
                      <input type="radio" name="car" id="car_radio" onclick="setCar(-1);" value=-1 <?php if (isset($car) and (trim($car) == "Все") or (empty($car))) {echo "checked='checked'";} ?> />
                      Все
                    </label>

                    <label>
                      <input type="radio" name="car" id="car_radio_0" onclick="setCar(0);" value=0 <?php if ((isset($car)) and (trim($car) == "легковой")) {echo "checked='checked'";}  ?> />
                      <img alt="" border="0" src="<?= base_url(); ?>images/avto1.jpg" width="16" height="6" /> <span id="car_name_0">легковой</span>
                    </label>
					                    <label>
                      <input type="radio" name="car" id="car_radio_1" onclick="setCar(1);" value=1 <?php if ((isset($car)) and (trim($car) == "легкогрузовой")) {echo "checked='checked'";}  ?> />
                      <img alt="" border="0" src="<?= base_url(); ?>images/avto3.jpg" width="19" height="11" /> <span id="car_name_1">легкогрузовой</span>
                    </label>

                    <label>
                      <input type="radio" name="car" id="car_radio_2" onclick="setCar(2);" value=2  <?php if ((isset($car)) and (trim($car) == "грузовой")) {echo "checked='checked'";}  ?>  />
                      <img alt="" border="0" src="<?= base_url(); ?>images/avto4.jpg" width="23" height="13" /> <span id="car_name_2">грузовой</span>
                    </label>

                    <label>
                      <input type="radio" name="car" id="car_radio_3" onclick="setCar(3);" value=3 <?php if ((isset($car)) and (trim($car) == "внедорожник")) {echo "checked='checked'";}  ?> />
                     <img alt="" border="0" src="<?= base_url(); ?>images/avto2.jpg" width="16" height="11" /> <span id="car_name_3">внедорожник</span>
                    </label>

                </td>
              </tr>
              <tr>
                <td colspan="2" height="15" class="sAllborder"></td>

              </tr>

           </table>

        <!-- end Tip avto -->


        <!-- Brend -->

             <table width="<?=(($page=="search"?'170':'180'));?>" border="0" cellpadding="0" cellspacing="0" class="sAlltable">
              <tr>
                <td colspan="2"><span class="textBlack">Бренд:</span></td>

              </tr>
              <tr>
                <td width="5">&nbsp;</td>

                <td width="<?=(($page=="search"?'165':'175'));?>">

                    <label>
                      <input type="radio" name="brand" id="brand_radio" onclick="setBrand(-1);" value=-1  />
                      Все
                    </label>
                    <?php foreach ($brand_items as $item) :?>
                    <label>
                        <input type="radio" name="brand" id="brand_radio_<?= $item->id; ?>" onclick="setBrand(<?= $item->id; ?>);" value="<?= $item->id; ?>"  <?php if ((isset($brand)) and (trim($brand) == trim($item->value))) {echo "checked='checked'";}  ?> />
                      <span id="brand_name_<?= $item->id; ?>"> <?= $item->value; ?></span>
                    </label>
                    <?php endforeach; ?>
                </td>
              </tr>
              <tr>
                <td colspan="2" height="50"  align="center">
                <input class="sButtStyle" name="s2_brn" type="button" value="" onclick="javascript:search_all();" /></td>

              </tr>

           </table>
             </form>
        <!-- end Brend -->
            <script language="JavaScript" type="text/javascript">
            <!--
            restoreSeason();
            restoreCar();
            restoreBrand();
            //-->
            </script>

        </div>
      <!-- end.searchParams --></div>
      <div class="<?=(($page=="search"?'s1FootS':'s1Foot'));?>"></div>
      <div class="clear"></div>

  <!-- End Block Rashirennie Parametri  -->