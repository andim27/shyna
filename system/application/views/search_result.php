<?php
/*
* Блок результатов поиска
*/


?>
<!--
* Shinok: Search engine
* Developer: Andrey Stepanovich Makarevich
* skype:andrey_makarevich
 -->
<!-- Block Result search  -->
<script language="JavaScript" type="text/javascript">
<!--
var mydata=[];
var cur_rec=0;
var cur_cur=0;
var cur_order=1;//price
function s_res() {
  out_str="";
  d=$("#diameter option:selected").val();
  p=$("#profil option:selected").val();
  w=$("#width option:selected").val();
  if (d != -1){
    $("#s_diameter").show();$("#s_diameter_val").html($("#diameter  option:selected").text());
  } else {
     $("#s_diameter").hide();$("#s_diameter_val").html("");
  }
  if (p != -1){
     $("#s_profil").show();$("#s_profil_val").html($("#profil option:selected").text());
  } else {
     $("#s_profil").hide();$("#s_profil_val").html("");
  }
  if (w != -1){
     $("#s_width").show();$("#s_width_val").html($("#width option:selected").text());
  } else {
     $("#s_width").hide();
  }
  //---more---------------
  s=season;//$('input[name="season"]:checked').val();
  c=car;//$('input[name="car"]:checked').val();
  b=brand;//$('input[name="brand"]:checked').val();
  if ((s != -1) && (s != undefined)){
     $("#s_season").show(); $("#s_season_val").html($("#season_name_"+s).html());
  } else {
     $("#s_season").hide();
  }
  if ((c != -1) && (c != undefined)){
     $("#s_car").show(); $("#s_car_val").html($("#car_name_"+c).html());
  } else {
     $("#s_car").hide();
  }
  if ((b != -1) && (b != undefined)){
     $("#s_brand").show(); $("#s_brand_val").html($("#brand_name_"+b).html());
  } else {
     $("#s_brand").hide();
  }
}
function pageShow(n){
if (can_show) {
    cur_page=n;
    allShow();
}
}
function pageShowSelect(n){
$.cookie('rec_cnt', $('#rec_count_sel').val());
$('#rec_count').val($('#rec_count_sel').val());
if (can_show) {
    cur_page=n;
    allShow();
}
}
function setRec_cnt() {
 rec_cnt=$.cookie('rec_cnt');
 if (rec_cnt == null) {rec_cnt=10;}
}
function setRecCountSel() {
setRec_cnt();
$("#rec_count_sel option[value='"+rec_cnt+"']").attr('selected', 'selected');
}
function allShow() {
can_show=false;
$('#rec_count').val($.cookie('rec_cnt'));
setRec_cnt();
if (page == "main"){
  window.location.href="<?=base_url();?>search/search_all";
} else {

s_res();
$('#mes_info').html("<font color='red'> идет поиск ...</font>");
$.post(
"<?=base_url();?>search/action_0",
 {
      "page":"<?= $page; ?>",
      "cur_page":cur_page,
      "rec_count":rec_cnt,
      "diameter":$("#diameter option:selected").val(),
      "profil":  $("#profil option:selected").val(),
      "width":   $("#width option:selected").val(),
      "season":  season,//$('input[name="season"]:checked').val(),
      "car":     car,
      "brand":   brand
   },
   function(data) {
	makeResponseData(data);
	showGridItems();
   }
)//--post
}//---else
}

function makeResponseData(data){
mydata =  window["eval"](data);
//alert("mydata[0].pages="+mydata[0].pages);
pg_links=mydata[0].pages;
cur_page=mydata[0].cur_page;
$('#mes_info').html("");
if ((pg_links != "") || (pg_links != undefined)) {
    $("#rec_count_sel").show();
    $("#page_links").html(pg_links);
}

if ((mydata.length <= 0) || (mydata.length == undefined ) ) {
  	 $('#mes_info').html(" данных нет");
     $("#rec_count_sel").hide();
	 jQuery("#s_t").jqGrid('clearGridData');
	return;
  }
  if (mydata.length <= 10) {
     $("#rec_count_sel").hide();
  }
}

function showGridItems(){
  	jQuery("#s_t").jqGrid('clearGridData');
    for(var i=0;i<=mydata.length;i++) {
	   jQuery("#s_t").jqGrid('addRowData',i,mydata[i+1]);
    }
    $("#pager3_left").remove();
    can_show=true;
}
function p_order(n) {
  cur_order=n;
  getProposalInfo(cur_rec);
}
function getProposalInfo(id) {
   cur_rec=id;
   $("#vendor_info").html("");
   var row = jQuery("#s_t").jqGrid('getRowData',id);
   $.post(
    "<?=base_url();?>search/action_prop_info",
	      {
	        //"price_id":row.price_id,
            "cur_cur":cur_cur,
            "order":cur_order,
	        "width":row.width,
	        "profil":row.profil,
	        "diameter":row.diameter,
	        "brand":row.brand,
	        "model":row.model,
	        "ind_nagr":row.ind_nagr,
            "ind_vel":row.ind_vel,
            "model_id":row.model_id
           },
          function(data) {
			makeProposalData(data);
          }
   )
}
function makeProposalData(data){
  if (data !="") {
      $("#prop_info").html(data);
  }
  //mydata =  window["eval"](data);
  //jQuery("#p_info").jqGrid('clearGridData');
  //for(var i=0;i<=mydata.length;i++) {
  //  jQuery("#p_info").jqGrid('addRowData',i+1,mydata[i]);
  //}
}
function makeVendorData(data){
  $("#vendor_info").html(data);
}
function show_vendor_more(id) {
//row = jQuery("#p_info").jqGrid('getRowData',id);
$.post(
    "<?=base_url();?>search/action_vendor_info",
	      {"vendor_id":id//row.vendor_id
           },
          function(data) {
			makeVendorData(data);
          }
   )
}
function setMyLinks() {
var n;
all_rec=$("#rec_count option:selected").val();//20;
step_rec=10;
//--cur--
for (var i=step_rec;i<=all_rec;i=i+step_rec) {
   el=$("#pg_cur_id_"+i);
   if (el != null) {
       n=el.attr("href");
       n=n.substr(1);
       el.attr("href","javascript:pageShow("+n+");")
   }
}
//--next--
el=$("#pg_next_id");
if (el != null) {
    n=el.attr("href");
    n=n.substr(1);
    el.attr("href","javascript:pageShow("+n+");")
}
//--prev--
el=$("#pg_previous_id");
if (el != null) {
    n=el.attr("href");
    n=n.substr(1);
    el.attr("href","javascript:pageShow("+n+");")
}
}
//-->
</script>
    <div class="resultSearch">

        <div class="MHead">
         <div class="left"></div>
         <div class="right"></div>

          <span class="mainLabel">Результаты поиска:</span><span class="mainLabel" id="mes_info"></span>

        <!-- end.s1Head --></div>

         <div class="sBoxRes">

            <table width="430" border="0" cellspacing="0" cellpadding="0">
             <tr id="s_brand" style="display:none">
                <td colspan="2"><span class="mBlTxt">Бренд: </span><span id="s_brand_val" class="textBlack"></span></td>

                <td width="81">&nbsp;</td>
                <td width="150">&nbsp;</td>
              </tr>

              <tr >
               <td class="pagBox">
                    <span id="s_width" style="display:none" class="mBlTxt">Ширина: </span><span id="s_width_val"  class="textBlack"></span>
                    <span id="s_profil" style="display:none" class="mBlTxt">Профиль: </span><span id="s_profil_val" class="textBlack"></span>
                    <span id="s_diameter" style="display:none" class="mBlTxt">Диаметр: </span><span id="s_diameter_val" class="textBlack"></span>
               </td>
               <tr id="s_season" style="display:none">
                 <td>
                  <span class="mBlTxt">Сезон: </span><span  id="s_season_val"  class="textBlack"></span>
                 </td>
               </tr>


               <tr id="s_car" style="display:none">
                 <td>
                  <span class="mBlTxt">Авто  : </span><span id="s_car_val" class="textBlack"></span>
                 </td>
               </tr>

              </tr>

              <tr>
                <td colspan="4" valign="top">
                    <!-- START SEARCH TABLE -->
                        <table id="s_t" class="hereRes" width="430" border="0" cellspacing="0" cellpadding="0">
                        </table>
                        <div id="pager3"></div>
                        <script language="JavaScript" type="text/javascript" charset="utf-8">
                       jQuery("#s_t").jqGrid({
                       datatype: "local",
                       colNames:['P','H','D', 'Бренд','Модель','Нагр.','Скор.','model_id'],
                       colModel:[
                       {name:'width',index:'width', width:25, sortable:true,sorttype:"int"},
                       {name:'profil',index:'profil', width:20, sortable:true,sorttype:"string"},
                       {name:'diameter',index:'diameter', width:20,sortable:true,sorttype:"string"},
                       {name:'brand',index:'brand', width:85, align:"right",sortable:true,sorttype:"string"},
                       {name:'model',index:'model', width:100, align:"right",sorttype:"string"},
                       {name:'ind_nagr',index:'ind_nagr', width:30,align:"right",sorttype:"string"},
                       {name:'ind_vel',index:'ind_vel', width:30,align:"right",sorttype:"string"},
                       {name:'model_id',index:'model_id', width:20, sortable:false,hidden:true}
                    ],
                    onSelectRow: function(id){
                    getProposalInfo(id);
                    },
                   autowidth: true,
                   height:"auto",
                   sortname: 'width', viewrecords: true, sortorder: "desc",
                    loadonce: false, caption: "Найдены шины" });
                    rec_from=0;

                    allShow();
                    </script>
                    <!-- END SEARCH TABLE -->
                    </td>
              </tr>
              <tr>
                 <td class="pagBox">
                 <!--   <a class="pagLink" href="#">1</a>
                    <a class="pagLink" href="#">2</a>
                    <a class="pagLink" href="#">3</a> ... <a class="pagNav" href="#">>></a>
-->                &nbsp;
                 </td>
              </tr>
              <tr>
              <td class="pagBox" width="100%">
                  <div id="page_links" style="margin-right:10px;display:inline;"></div>
                  <select id="rec_count_sel" onchange="javascript:pageShowSelect(cur_page);">
                    <option selected="selected" value=10 >10</option>
                    <option value=20>20</option>
                    <option value=30>30</option>
                    <option value=50>50</option>
                  </select>

                  <script language="JavaScript" type="text/javascript">
                  <!--
                  setRecCountSel();
                  //-->
                  </script>
              </td>
              </tr>
            </table>

     <!-- end . sBoxRes --></div>

    <!-- end .resultSearch --></div>
    <div class="MFootS"></div>
    <div class="clear"></div>

  <!-- END Block Rezultat poiska  -->