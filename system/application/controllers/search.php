<?php
/*
* Shinok: Search engine
* Developer: Andrey Stepanovich Makarevich
* skype:andrey_makarevich
 */
class Search extends Controller {
var $data;
	function Search()
	{
		parent::Controller();
        //ini_set('memory_limit', '192M' );
        $this->load->model("shyna");
        $this->config->load('config_shyna');
        $this->data = array();
        $this->data["user_id"] =$this->db_session->userdata('user_id');
		$this->data["user_login"] =$this->db_session->userdata('user_login');
        $this->data["group_id"]=$this->db_session->userdata('group_id');
        $this->data["width_items"]   =$this->shyna->getWidths();
        $this->data["profil_items"]  =$this->shyna->getProfils();
        $this->data["diameter_items"]=$this->shyna->getDiameters();
        $this->data["brand_items"]   =$this->shyna->getBrands();
        $this->data['season_arr']    =$this->config->item("season_arr");
        $this->data['car_arr']       =$this->config->item("car_arr");
	}
    function index()
    //function index($p="",$h="",$d="")
	{
    //$p = $this->uri->segment(2);
	//$h = $this->uri->segment(3);
	//$d = $this->uri->segment(4);
    //$this->view($p,$h,$d,$s,$a,$b);
    //$this->view($p,$h,$d);
    $this->view();
	}
    function details()
	{
     //$p = $this->uri->segment(2);
	 //$h = $this->uri->segment(3);
	 //$d = $this->uri->segment(4);
     //pr ("details p=".$p." h=".$h." d=".$d);
     $this->view();
	}
    //function view($p="",$h="",$d="")
    function view()
	{
        // pr ("VIEW p=".$p." h=".$h." d=".$d);
        $tp_s = $this->input->post('tp_s');
        $season_arr = $this->config->item("season_arr");
        $car_arr    = $this->config->item("car_arr");
        $this->data["page"]="search";
        $this->data["tp_s"]=$tp_s;

            $this->data["width"]   = $this->input->post('width');
            $this->data["profil"]  = $this->input->post('profil');
            $this->data["diameter"]= $this->input->post('diameter');
            $this->data["width"]   =getValueById($this->data["width_items"],$this->data["width"]);
            $this->data["profil"]  =getValueById($this->data["profil_items"],$this->data["profil"]);
            $this->data["diameter"]=getValueById($this->data["diameter_items"],$this->data["diameter"]);

            $this->data["season"]  = $this->input->post('season');
            $this->data["car"]     = $this->input->post('car');
            $this->data["brand"]   = $this->input->post('brand');
            $this->data["brand"]   =getValueById($this->data["brand_items"],$this->data["brand"]);
            $this->data["season"]  =$season_arr[$this->data["season"]];
            $this->data["car"]     =$car_arr[$this->data["car"]];
            $this->data["rec_count"]=$this->input->post('rec_count');
            //$this->load->library('pagination');
            //$this->load->library('bi_pagination');
            $this->load->library('jq_pagination');
            //$config['base_url'] = base_url().'search/page/';
            //$config['base_url'] = 'javascript(';
            //$config['total_rows'] = $this->shyna->getPricesCnt();
            //$config['per_page'] = 10;
            $config['full_tag_open'] = '<div  id="pg_links" style="display:inline;width:100%;float:left">';
            $config['full_tag_close'] = '</div>';
            $config['cur_tag_open'] = '<b class=pagLink style="float:left;margin-right:5px">';
            $config['cur_tag_close'] = '</b>';
            //$config['num_tag_open'] = '<div class=pagLink style="float:left;margin-right:5px">';
            $this->jq_pagination->num_tag_open='<div class=pagLink style="float:left;margin-right:5px">';
            $this->jq_pagination->total_rows=$this->shyna->getPricesCnt();
            $this->jq_pagination->cur_page=0;
            $this->jq_pagination->initialize($config);
            $this->data["page_links"]=$this->jq_pagination->create_links();

        //pr("\n VIEW tp_s=".$tp_s." season=".$this->data["season"]." brand=".$this->data["brand"]." rec_count=".  $this->data["rec_count"] );
        $this->load->view('search_page',$this->data);
	}
    function search_all()
    {
         $this->data["page"]=$this->input->post('page');
         $this->data["rec_count"]=$this->input->post('rec_count');
         //pr("\n search_all rec_count=".$this->data["rec_count"]);
         $season_arr = $this->config->item("season_arr");
         $car_arr    = $this->config->item("car_arr");

         $this->data["width"]   = $this->input->post('width');
         $this->data["profil"]  = $this->input->post('profil');
         $this->data["diameter"]= $this->input->post('diameter');
         $this->data["season"]  = $this->input->post('season');
         $this->data["car"]     = $this->input->post('car');
         $this->data["brand"]   = $this->input->post('brand');
         //pr("\n search_all page>>".$this->data["page"]."<<  ".$this->data["width"]." profil=".$this->data["width"]." diameter=".$this->data["diameter"]." season=".$this->input->post('season'));
         if ($this->data["page"] =="main") {
             $this->data["width"]   =getValueById($this->data["width_items"],$this->data["width"]);
             $this->data["profil"]  =getValueById($this->data["profil_items"],$this->data["profil"]);
             $this->data["diameter"]=getValueById($this->data["diameter_items"],$this->data["diameter"]);
             $this->data["brand"]   =getValueById($this->data["brand_items"],$this->data["brand"]);
             $this->data["season"]  =$season_arr[$this->data["season"]];
             $this->data["car"]     =$car_arr[$this->data["car"]];
             $this->load->view('search_page',$this->data);
             return;
         }
         $this->data["cur_page"] = $this->input->post('cur_page');

         //$this->jq_pagination->cur_page=0;
         //$page_links_html=$this->jq_pagination->create_links();
         $items=$this->shyna->search_all($this->data,$this->data["cur_page"],$this->data["rec_count"]);
         $out_str="";
         if (!empty($items)){
            $out_str=$this->fillOut($items,$this->data["rec_count"]);
         }
         //pr("\n rec_from=".$this->data["rec_from"]);
         $out_str="[".$out_str."]";
         //pr("\n search_all out_str=".$out_str);
         echo $out_str;
    }
    function action_0()
    {
     $out_str="";
     $this->data["width"]   = $this->input->post('width');
     $this->data["profil"]  = $this->input->post('profil');
     $this->data["diameter"]= $this->input->post('diameter');
     $this->data["season"]  = $this->input->post('season');
     $this->data["car"]     = $this->input->post('car');
     $this->data["brand"]   = $this->input->post('brand');
     $this->data["cur_page"]= $this->input->post('cur_page');
     $this->data["rec_count"]=$this->input->post('rec_count');
     //pr("\n action_0 rec_count=".$this->data["rec_count"]);
     //pr("\n action_0 >><<  ".$this->data["width"]." profil=".$this->data["width"]." diameter=".$this->data["diameter"]." season=".$this->input->post('season')." car=".$this->input->post('car')." brand=".$this->input->post('brand')  );
     $items=$this->shyna->search_all($this->data);
     //pr("\naction_0 cur_page=".$this->data["cur_page"]." count items=".count($items));
     $per_page=$this->config->item('per_page');
     if (!empty( $this->data["rec_count"])) {$per_page=$this->data["rec_count"];}
     if (!empty($items)){
       $out_str=$this->fillOut($items,($per_page*($this->data["cur_page"]-1)+1),$per_page);
     }
     if ((count($items)<  $per_page)&&($this->data["cur_page"]==0)) {
         $page_links_html="{'pages':'','cur_page':".$this->data["cur_page"]."}";
     } else {
            $this->load->library('jq_Pagination');
            $this->jq_pagination->total_rows=count($items);//$this->shyna->getPricesCnt();
            $this->jq_pagination->cur_page= $this->data["cur_page"];
            $page_links_html="{'pages':'".$this->jq_pagination->create_links()."','cur_page':".$this->data["cur_page"]."}";
     }
    //$page_links_html="{'pages':'<a href=javascript:pageShow(1);>1</a>  <a href=javascript:pageShow(2);>2</a>'}";//$this->jq_pagination->create_links();
    if (empty($page_links_html)) {
       $page_links_html="{}";
    }
    if (! empty($out_str)) {
        $out_str="[".$page_links_html.",".$out_str."]";
    } else {
      $out_str="[".$page_links_html."]";
    }
    //pr("\n\n action_0 out_str=".$out_str);
    echo $out_str;
    }
    function action_1()
    {
     $out_str="";
     $items=$this->shyna->search_params($this->input->post('width'),$this->input->post('profil'),$this->input->post('diameter'));
     if (!empty($items)){
       $out_str=$this->fillOut($items);
     }
     echo "[".$out_str."]";
    }
    function action_2()
    {
     $out_str="";
     $items=$this->shyna->search_more($this->input->post('season'),$this->input->post('car'),$this->input->post('brand'));
     if (!empty($items)){
        $out_str=$this->fillOut($items);
     }
     echo "[".$out_str."]";
    }
    function fillOut($items,$start_cnt=1,$lim_len)
    {
      $out_str="";
      $cnt=1;
      $end_cnt=$start_cnt+$lim_len-1;
      //pr("\n fill out start_cnt=".$start_cnt." end_cnt=".$end_cnt);
      foreach ($items as $item){
        if (($cnt >=$start_cnt)&&($cnt <=$end_cnt) ) {
           if ($cnt==$start_cnt) {$zpt="";}else{$zpt=",";}
           $out_str.=$zpt.
           //"{'price_id':".$item->price_id.
           "{'width':'".$item->width_name.
           "','profil':'".$item->profile_name.
           "','diameter':'".$item->diameter_name.
           "','brand':'".$item->brand_name.
           "','model':'".$item->model_name.
           "','ind_nagr':'".$item->load_index_name.
           "','ind_vel':'".$item->speed_index_name.
           "','model_id':'".$item->model_id."'}";
        }
        $cnt++;
    }
        return $out_str;
    }
    function action_prop_info()
    {
      /*  $user_id=$this->input->post('user_id');
       if (! empty($user_id)) {
          $this->data["user_id"]=$this->input->post('user_id');//
       } else {
           $this->data["user_id"]=$this->db_session->userdata('user_id');
       }
      */
      if (empty( $this->data["user_id"]) ) {
        return "";
      }
      $this->data["width"]   = getIdByValue($this->data["width_items"],$this->input->post('width'));
      $this->data["profil"]  = getIdByValue($this->data["profil_items"],$this->input->post('profil'));
      $this->data["diameter"]= getIdByValue($this->data["diameter_items"],$this->input->post('diameter'));
      $this->data["brand"]   = getIdByValue($this->data["brand_items"],$this->input->post('brand'));
      //---get last params----
      $this->data["model_items"]=$this->shyna->getModels();
      //$this->data["model"]   =getIdByValue($this->data["model_items"], $this->input->post('model'));
      $this->data["model"]   =$this->input->post('model_id');

      $this->data["nagr_items"]=$this->shyna->getNagr();
      $this->data["vel_items"] =$this->shyna->getVel();

      $this->data["ind_nagr"] = getIdByValue($this->data["nagr_items"],$this->input->post('ind_nagr'));
      $this->data["ind_vel"]  = getIdByValue($this->data["vel_items"],$this->input->post('ind_vel'));

      $this->data["cur_cur"] = $this->input->post('cur_cur');
      $this->data["order"]   = $this->input->post('order');
      $p_info_items=$this->shyna->get_p_info($this->data);

      $this->data['p_info_items']=$p_info_items;
      $out_str=$this->load->view('p_info',$this->data,true);

      echo  $out_str;
    }
    function action_vendor_info()
    {
      $vendor_id=$this->input->post("vendor_id");
      $v_info_item=$this->shyna->get_vendor_info($vendor_id);
      $data['vendor_id']= $vendor_id;
      $data['v_info_item']=$v_info_item;
      $data["group_id"]=$this->data["group_id"];
      $data["user_data"]=modules::run('user_mod/user_ctr/get_user_data');
      $out_str=$this->load->view('vendor_address',$data,true);
      echo $out_str;
    }
}
/* end of file */
