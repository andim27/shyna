<?php
/*
* Информация о предложении
* - search
*/
?>
 <!-- Block Infa o predlogenii -->

     <div class="infoPropS">

        <div class="s1Head">
         <div class="left"></div>
         <div class="right"></div>

          <span class="sideLabel">Информация о предложении:</span>

        </div>

                <div id="prop_info">
                <?php include "p_info.php";  ?>
                </div>
         <?php if (empty($user_id)) : ?>
            <span class="lTabBlue">&nbsp;&nbsp;*</span>
            <span class="infoLabel">Для получения дополнительной информации <br>&nbsp;&nbsp;<a href="<?= base_url(); ?>register/">войдите</a>  или <a href="<?= base_url(); ?>register/">зарегистрируйтесь!</a></span>



         <?php endif; ?>
         </div>
     <div class="s2FootS"></div>
     <div class="clear"></div>

  <!-- End Block Infa o predlogenii  -->
<!--
 <table id="p_info" class="infoTable"  width="323" border="0" cellpadding="0" cellspacing="0"></table>
  <script language="JavaScript" type="text/javascript">
                       jQuery("#p_info").jqGrid({

                       datatype: "local",
                       colNames:['vendor_id','Дата','Колво','Цена', 'Поставщик','Город','Прим.'],
                       colModel:[
                       {name:'vendor_id',index:'vendor_id', width:10, sortable:false,hidden:true},
                       {name:'price_date',index:'price_date', width:50, sortable:true,sorttype:"string"},
                       {name:'amount',index:'amount', width:50, sortable:true,sorttype:"int"},
                       {name:'price',index:'price', width:80,sortable:true,sorttype:"int"},
                       {name:'vendor_name',index:'vendor_name', width:70, align:"right",sortable:true,sorttype:"string"},
                       {name:'short_city',index:'short_city', width:50, align:"right",sorttype:"string"},
                       {name:'extra',index:'extra', width:20,align:"right"}

                    ],
                    onSelectRow: function(id){
                      show_vendor_more(id);
                    },
                    scroll:1,
                    width: 323,
                    resizable: false,
                    height:"auto",
                    rowNum:10, autowidth: false,
                    loadonce: false, caption: "Информация о предложении" });
                    //allShow();
  </script>
-->
