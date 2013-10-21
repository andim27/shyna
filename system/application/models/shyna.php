<?php

  class Shyna extends Model {
  function getPricesCnt()
  {
    $query="SELECT count(*) as cnt FROM prices";
    $query = $this->db->query($query);
    return $query->row()->cnt;
  }
  function getModels()
  {
    $query="SELECT id as id,name as value FROM model ORDER BY value";
    $query = $this->db->query($query);
    return $query->result();
  }
  function getBrands()
  {
    $query="SELECT * FROM brand ORDER BY value";
    $query = $this->db->query($query);
    return $query->result();
  }
  function getNagr()
  {
    $query="SELECT id, ind as value FROM load_index ORDER BY value";
    $query = $this->db->query($query);
    return $query->result();
  }
  function getVel()
  {
    $query="SELECT id,ind as value FROM speed_index ORDER BY value";
    $query = $this->db->query($query);
    return $query->result();
  }
  function getWidths()
  {
    $query="SELECT * FROM width ORDER BY value";
    $query = $this->db->query($query);
    return $query->result();
  }
  function getProfils()
  {
    $query="SELECT * FROM profile ORDER BY value";
    $query = $this->db->query($query);
    return $query->result();
  }
  function getDiameters()
  {
    $query="SELECT * FROM diameter ORDER BY value";
    $query = $this->db->query($query);
    return $query->result();
  }
  function search_params($w,$p,$d)
  {
    $w_cond="";
    $p_cond="";
    $d_cond="";
    $order_cond="";
    if (!empty($w)){
       $w_cond=" and (width.value='".$w."')";
       $order_cond=" ORDER BY width_name";
    }
    if (!empty($p)){
       $p_cond=" and (profile.value='".$p."')";
       if (!empty($order_cond))
           {$order_cond.=",profile_name";}
           else {$order_cond=" ORDER BY profile_name";}
    }
    if (!empty($d)){
       $d_cond=" and (diameter.value='".$d."')";
       if (!empty($order_cond))
           {$order_cond.=",diameter_name";}
           else {$order_cond=" ORDER BY diameter_name";}
    }
    if (empty($order_cond)){$order_cond=" ORDER BY diameter_name,width_name,profile_name";}
    $where_cond=" WHERE (1) ";
    $query="SELECT prices.id as price_id,
            width.value as width_name,
            profile.value as profile_name,
            diameter.value as diameter_name,
            brand.value as brand_name,
            model.name as model_name,
            load_index.value as load_index_name,
            speed_index.ind as speed_index_name,
            prices.extra
            FROM prices
             LEFT JOIN width    ON prices.width_id    = width.id
             LEFT JOIN profile  ON prices.profile_id  = profile.id
             LEFT JOIN diameter ON prices.diameter_id = diameter.id
             LEFT JOIN brand    ON prices.brand_id    = brand.id
             LEFT JOIN model    ON prices.model_id    = model.id
             LEFT JOIN load_index  ON prices.load_id  = load_index.id
             LEFT JOIN speed_index ON prices.speed_id = speed_index.id".$where_cond.$w_cond.$p_cond.$d_cond.$order_cond;
    $query = $this->db->query($query);
    return $query->result();
  }
  function search_more($s,$c,$b)
  {
    //pr("\n search_mores= ".$s." c=".$c." b=".$b);
    $s_cond="";
    $c_cond="";
    $b_cond="";
    $season_arr = $this->config->item("season_arr");
    $car_arr    = $this->config->item("car_arr");
    if (!empty($s)){
       $s=getKeyByValue($season_arr,$s);
       if ($s != -1){
           $w_cond=" and (model.season='".$s."')";
       }
    }
    if (!empty($c)){
       $c=getKeyByValue($car_arr,$c);
       if ($c != -1){
           $c_cond=" and (model.car='".$c."')";
       }
    }
    if (!empty($b)){
       $b_cond=" and (brand.value='".$b."')";
    }
    //pr("\n After key s=".$s." c=".$c." b=".$b);
    $where_cond=" WHERE (1) ";
    $query="SELECT prices.id as price_id,
            width.value as width_name,
            profile.value as profile_name,
            diameter.value as diameter_name,
            brand.value as brand_name,
            model.name as model_name,
            load_index.value as load_index_name,
            speed_index.ind as speed_index_name,
            prices.extra
            FROM prices
             LEFT JOIN width    ON prices.width_id    = width.id
             LEFT JOIN profile  ON prices.profile_id  = profile.id
             LEFT JOIN diameter ON prices.diameter_id = diameter.id
             LEFT JOIN brand    ON prices.brand_id    = brand.id
             LEFT JOIN model    ON prices.model_id    = model.id
             LEFT JOIN load_index  ON prices.load_id  = load_index.id
             LEFT JOIN speed_index ON prices.speed_id = speed_index.id".$where_cond.$s_cond.$c_cond.$b_cond;
    //pr("\n search_more=".$query);
    $query = $this->db->query($query);
    return $query->result();
  }
  //==========================ALL=========================================
  function search_all($data,$cur_page=0,$lim_len=10) {
   $w_cond="";
   $p_cond="";
   $d_cond="";
   $s_cond="";
   $c_cond="";
   $b_cond="";
   $lim_cond="";
   $w=$data['width'];
   $p=$data['profil'];
   $d=$data['diameter'];
   if ($cur_page == 0) {
       $lim_from=0;
   } else {
       $lim_from=$cur_page*$lim_len;
   }
   //---limit cond---------------------------------------
   //$lim_cond=" LIMIT ".$lim_from.", ".$lim_len;
   //----------params------------------------------------
   if ($w != -1){
       $w=getValueById($data["width_items"],$w);
       $w_cond=" and (width.value='".$w."')";
       $order_cond=" ORDER BY width_name";
    }
    if ($p != -1){
       $p=getValueById($data["profil_items"],$p);
       $p_cond=" and (profile.value='".$p."')";
       if (!empty($order_cond))
           {$order_cond.=",profile_name";}
           else {$order_cond=" ORDER BY profile_name";}
    }
    if ($d != -1){
       $d=getValueById($data["diameter_items"],$d);
       $d_cond=" and (diameter.value='".$d."')";
       if (!empty($order_cond))
           {$order_cond.=",diameter_name";}
           else {$order_cond=" ORDER BY diameter_name";}
    }
    //-------------more params---------------------------
    $s=$data['season'];
    $c=$data['car'];
    $b=$data['brand'];
    if ($s != -1){
       if ($s != -1){
           $s_cond=" and (model.season='".$s."')";
       }
    }
    if ($c != -1){
       if ($c != -1){
           $c_cond=" and (model.car='".$c."')";
       }
    }
    if ($b != -1){
       $b=getValueById($data["brand_items"],$b);
       $b_cond=" and (brand.value='".$b."')";    //--- model.brand_id=b
       //$b_cond=" and (model.brand_id='".$b."')";
    }
   //-------------order----------------------------------
   //if (empty($order_cond)){$order_cond=" ORDER BY diameter_name,width_name,profile_name,brand_name,model_name,load_index_name,speed_index_name";}
   //---for all---
   $order_cond=" ORDER BY width_name,profile_name,diameter_name,brand_name,model_name,load_index_name,speed_index_name ASC";
   $where_cond=" WHERE (1) ";
   $query="SELECT   DISTINCT
     width.value    as width_name,
     profile.value  as profile_name,
     diameter.value as diameter_name,
     brand.value    as brand_name,
     model.name     as model_name,
     model.id       as model_id,
     load_index.ind as load_index_name,
     speed_index.ind  as speed_index_name

     FROM prices
     LEFT JOIN width    ON prices.width_id    = width.id
     LEFT JOIN profile  ON prices.profile_id  = profile.id
     LEFT JOIN diameter ON prices.diameter_id = diameter.id
     LEFT JOIN brand    ON prices.brand_id    = brand.id
     LEFT JOIN model    ON prices.model_id    = model.id
     LEFT JOIN load_index  ON prices.load_id  = load_index.id
     LEFT JOIN speed_index ON prices.speed_id = speed_index.id".$where_cond.$w_cond.$p_cond.$d_cond.$s_cond.$c_cond.$b_cond.$order_cond.$lim_cond;
     //pr("\n\n search_all (query)=".$query);
     $query = $this->db->query($query);
    return $query->result();
  }
  function get_p_info($data) {

    $w=$data['width'];
    $p=$data['profil'];
    $d=$data['diameter'];
    $b=$data['brand'];
    $m=$data['model'];
    $n=$data['ind_nagr'];
    $v=$data['ind_vel'];
    $cur_cur=$data["cur_cur"];
    $order=$data["order"];
    if (empty($cur_cur)) {
       $cur_cur="prices.currency_id ";
    }
    $where_cond=" WHERE (1) ";
    $w_cond="";
    $p_cond="";
    $d_cond="";

    $b_cond="";
    $m_cond="";

    $n_cond="";
    $v_cond="";

    if (!empty($w)) {
      $w_cond=" and prices.width_id=".$w;
    } else {$w_cond="";}
    if (!empty($p)) {
      $p_cond=" and prices.profile_id=".$p;
    } else {$p_cond="";}
    if (!empty($d)) {
      $d_cond=" and prices.diameter_id=".$d;
    } else {$d_cond="";}

    if (!empty($b)) {
      $b_cond=" and brand.id=".$b;
    } else {$d_cond="";}
    if (!empty($m)) {
      $m_cond=" and model.id=".$m;
    } else {$m_cond="";}

    if (!empty($n)) {
      $n_cond=" and load_index.id=".$n;
    } else {$n_cond="";}
    if (!empty($v)) {
      $v_cond=" and speed_index.id=".$v;
    } else {$v_cond="";}
    $order_cond=" ORDER BY price";
    if (!empty($order)){
        if ($order==1){
            $order_cond=" ORDER BY price";
        }
        if ($order==2){
            $order_cond=" ORDER BY vendor_short_name";
        }
        if ($order==3){
            $order_cond=" ORDER BY short_city";
        }
    }

    $query="SELECT DISTINCT
     DATE_FORMAT(pricelists.price_date,'%d.%m') as price_date,
     prices.amount,
     CAST(prices.price*c2.course/c1.course AS UNSIGNED) as price,
     vendor.name as vendor_name,
	 vendor.short_name as vendor_short_name,
     vendor.city,
     vendor.short_city,
     vendor.id as vendor_id,
     prices.extra,
     prices.currency_id,
     c1.course,
     c2.course,
     c1.currency_symbol
     FROM pricelists
      LEFT JOIN vendor ON pricelists.vendor_id=vendor.id
      LEFT JOIN prices ON prices.list_id=pricelists.list_id
      LEFT JOIN brand    ON prices.brand_id=brand.id
      LEFT JOIN model    ON prices.model_id=model.id
      LEFT JOIN load_index   ON prices.load_id=load_index.id
      LEFT JOIN speed_index  ON prices.speed_id=speed_index.id
      LEFT JOIN currency c1 ON c1.currency_id=".$cur_cur."
      LEFT JOIN currency c2 ON c2.currency_id=prices.currency_id
       ".$where_cond.$w_cond.$p_cond.$d_cond.$b_cond.$m_cond.$n_cond.$v_cond.$order_cond;
      //pr("\n get_p_info(query)=".$query);

    $query = $this->db->query($query);
    return $query->result();
  }
  function get_vendor_info($vendor_id) {
    if (empty($vendor_id)) {return false;}
    $query="SELECT vendor.name,vendor.city,vendor.phone,vendor.email,vendor_person.name as vendor_person_name, vendor_person.phone as vendor_person_phone, vendor_person.email as vendor_person_email  FROM vendor
    LEFT JOIN vendor_person ON vendor.id=vendor_person.vendor_id
    WHERE vendor.id=".$vendor_id;
    $query = $this->db->query($query);
    return $query->result();
  }
  }

?>
