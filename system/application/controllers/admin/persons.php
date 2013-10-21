<?php
/**
 * Class Vendors
 *
 * vendors class
 *
 * @author   Popov  - the big bug set!!!
 * @access   public
 * @package  Vendors.class.php
 * @created  Mon Sep 20 10:21:23 EEST 2010
 */
class Persons extends Controller
{
	private $vendor_id;
	private $user_id;
	
	private $price_page = 1;
	private $price_per_page = 100;
	private $price_num_links = 10;
	private $is_parsed = false;

	function Persons() {
		parent::Controller();

		$this->vendor_id = $this->uri->segment(4);
		if(!is_numeric($this->vendor_id)) $this->vendor_id = NULL;
	}

	function index() {
		
       if($this->db_session->userdata('group_id') == 2) {
        $vendors = $this->get_vendors();
		$values = array();
		$values['vendors'] = $vendors;

		$this->load->view('admin/vendors', $values);
			              	
        } else {
        	redirect(base_url()."admin");
        }
		
	}

	/**
	 * @todo The constuctions '$user_id = intval($this->db_session->userdata('user_id'));' and
	 * '$values['user'] = (Object)array('user_id' => $user_id);' and
	 * '$values['upload_form'] = $this->load->view("admin/price_upload_form", $values, true);'
	 * 
	 * can be remove when all vendors will upload prices
	 *
	 */
	function profile() {
		$vendor = $this->get_vendors($this->vendor_id, 'object');
		$functions = $this->vendor->get_vendor_functions($this->vendor_id);
		$persons = $this->get_vendor_person(null, $this->vendor_id);
		$accounts = $this->get_vendor_accounts(null, $this->vendor_id);
		$user_id = intval($this->db_session->userdata('user_id'));

		$values = array();
		$values['vendor'] = $vendor;
		$values['user'] = (Object)array('user_id' => $user_id);
		$values['upload_form'] = $this->load->view("admin/price_upload_form", $values, true);
		$values['persons'] = $persons;
		$values['persons_form'] = $this->load->view("admin/_vendor_person_tpl", $values, true);

		$config = $this->load->config('upload');
		$file_exist = false;
		if(!empty($vendor->file_ext)) {
			if(file_exists($config['upload_path'].$vendor->vendor_id.$vendor->file_ext)) $file_exist = true;
		}
		$values['functions'] = $functions;
		$values['accounts'] = $accounts;
		$values['file_exist'] = $file_exist;
		$this->load->view('admin/vendor_profile', $values);
	}

    	function newprofile() {
		$vendor = $this->get_vendors($this->vendor_id, 'object');
		$functions = $this->vendor->get_vendor_functions($this->vendor_id);
		$persons = $this->get_vendor_person(null, $this->vendor_id);
		$accounts = $this->get_vendor_accounts(null, $this->vendor_id);
		$user_id = intval($this->db_session->userdata('user_id'));

		$values = array();
		$values['vendor'] = $vendor;
		$values['user'] = (Object)array('user_id' => $user_id);
		$values['upload_form'] = $this->load->view("admin/price_upload_form", $values, true);
		$values['persons'] = $persons;
		$values['persons_form'] = $this->load->view("admin/_vendor_person_tpl", $values, true);

		$config = $this->load->config('upload');
		$file_exist = false;
		if(!empty($vendor->file_ext)) {
			if(file_exists($config['upload_path'].$vendor->vendor_id.$vendor->file_ext)) $file_exist = true;
		}
		$values['functions'] = $functions;
		$values['accounts'] = $accounts;
		$values['file_exist'] = $file_exist;
		$this->load->view('admin/vendor_newprofile', $values);
	}

	function upload_price() {
	     $this->user_id = $this->input->post('user_id');
		if(empty($this->user_id)) $this->user_id = $this->vendor_id;
		$this->vendor_id = $this->input->post('vendor_id');
		$price_id = modules::run('parser_mod/parser_ctr/upload_price', $this->vendor_id);
		if(!empty($price_id) && is_integer($price_id)) {			
			$this->set_vendor_function($this->vendor_id);

			// send admin email
			//$this->send_notification($this->vendor_id);
		}
		redirect('admin/vendors/profile/'.$this->vendor_id);
	}
	
	function send_notification($vendor_id){
		$vendor = $this->get_vendors($vendor_id, 'object');
		if( ! empty($vendor) && is_object($vendor) ) {
			
			$email = $vendor->vendor_email;
			$subject = "Уведомление";
			$message = $this->load->view('admin/send_notification_tpl', array('vendor' => $vendor), true);
			
			$this->load->helper('email');
			send_email($email, $subject, $message);
		}
	}

	function parse_price() {
		$vendor = $this->get_vendors($this->vendor_id, 'object');
		if( ! empty($vendor) && is_object($vendor) ) {
			modules::run('parser_mod/parser_ctr/parse_price', $this->vendor_id, $vendor->file_ext);
			redirect('admin/vendors/train_price/'.$this->vendor_id);

		} else {
			redirect('admin/vendors');
		}
	}
	
	function train_price() {
		$vendor = $this->get_vendors($this->vendor_id, 'object');
		if( ! empty($vendor) && is_object($vendor) ) {

			$values = array();
			$price_table_diff = "";

			$this->load->model('vendor_mdl','vendor');
			$functions = $this->vendor->get_vendor_functions($this->vendor_id);
			$currency = $this->get_currency();
			
			if( ! empty($_POST) ) {			
				$post_data = array(					
					'brand_func' => $this->input->post('brand_func'),
					'width_func' => $this->input->post('width_func'),
					'profile_func' => $this->input->post('profile_func'),
					'diameter_func' => $this->input->post('diameter_func'),
					'model_func' => $this->input->post('model_func'),
					'load_index_func' => $this->input->post('load_index_func'),
					'speed_index_func' => $this->input->post('speed_index_func'),
					'amount_func' => $this->input->post('amount_func'),
					'price_func' => $this->input->post('price_func'),
					'price_type_func' => $this->input->post('price_type_func'),
					'comment_func' => $this->input->post('comment_func'),
				
					'brand_column' => "A" . $this->input->post('brands_col'),
					'width_column' => "A" . $this->input->post('width_col'),
					'profile_column' => "A" . $this->input->post('profile_col'),
					'diameter_column' => "A" . $this->input->post('diameter_col'),
					'model_column' => "A" . $this->input->post('model_col'),
					'load_index_column' => "A" . $this->input->post('load_index_col'),
					'speed_index_column' => "A" . $this->input->post('speed_index_col'),
					'amount_column' => "A" . $this->input->post('amount_col'),
					'price_column' => "A" . $this->input->post('price_col'),						
					'price_type' => $this->input->post('price_type'),						
					'comment_column' => "A" . $this->input->post('comment_col'),			
					
					'rows_ignore' => $this->input->post('rows_ignore'),
					'columns_count' => $functions->columns_count,
					'delete_tmp_table' => $this->input->post('delete_tmp_table')
				);
				if(!empty($post_data['price_type'])) {
					//$price_type = $this->get_currency($post_data['price_type']);
					if(!empty($price_type)) {
						if(is_array($price_type))$price_type = array_shift($price_type);
						$post_data['price_type'] = "'" . $price_type->currency_value . "'";
					}
				}
				$this->is_parsed = true;
				modules::run('parser_mod/parser_ctr/train_price', $post_data, $this->vendor_id);
				$values['statistics'] = modules::run('parser_mod/parser_ctr/getPriceStat', $this->vendor_id);
				
				$this->vendor->update_vendor_functions($this->vendor_id, $post_data);
				$functions = (Object)$post_data;				
			}
			$price_data = modules::run('parser_mod/parser_ctr/get_price_tmp', $this->vendor_id, $this->price_per_page, $this->price_page);				
			if(!empty($price_data)) {
				$row_count = $price_data['count'];
				unset($price_data['count']);
				
				$values['paginate_args'] = array(
					'total_rows'  => $row_count,
					'per_page'    => $this->price_per_page,
					'cur_page'    => $this->price_page,
					'num_links' => $this->price_num_links,
					'js_function' => 'get_price_rows',
					'base_url'    => base_url().'admin/vendors/train_price/' . $this->vendor_id . '/page/',
					'uri_segment' => 4
				);			
				$values['vendor'] = $vendor;
				$values['functions'] = $functions;
				$values['is_parsed'] = $this->is_parsed;
				$values['price_data'] = $price_data;
				$values['currency'] = $currency;
				
				$price_table = $this->load->view('admin/price_table', $values, true);
				if($this->is_parsed)
					$price_table_diff = $this->load->view('admin/price_table_diff', $values, true);
				$this->is_parsed = false;
					
				unset($values['price_data']);
				$values['price_table'] = $price_table;
				$values['price_table_diff'] = $price_table_diff;
				
				$this->load->view('admin/parse_price_form', $values);
				
			} else {
				redirect('admin/vendors/profile/' . $this->vendor_id);
			}
		} else {
			redirect('admin/vendors');
		}
	}

	function train_continue() {
		$this->is_parsed = true;
		$this->train_price();
	}
	
	function train_cancel() {
		$this->load->model('vendor_mdl', 'vendor');
		
		modules::run('parser_mod/parser_ctr/delete_price', $this->vendor_id);		
		$this->vendor->update_vendor_functions($this->vendor_id, array('delete_tmp_table' => '0'));
		
		redirect('admin/vendors/profile/' . $this->vendor_id);
	}
	
	function parceVendorMail() {
		$this->load->model('vendor_mdl', 'vendor');
		
		$statictics = modules::run('parser_mod/parser_ctr/parceVendorMail');
		if(!empty($statictics)) {
			foreach ($statictics as $stat) {
				$stat->price_date = date("Y-m-d H:i:s");
				$stat->price_status = "not parsed";

				$functions = (array)$this->vendor->get_vendor_functions($stat->vendor_id);

				modules::run('parser_mod/parser_ctr/setPriceStat', $stat->vendor_id, $stat);			// set statistics
				modules::run('parser_mod/parser_ctr/parse_price', $stat->vendor_id, $stat->file_ext);	// loading price
				modules::run('parser_mod/parser_ctr/train_price', $functions, $stat->vendor_id);		// parsing price
				modules::run('parser_mod/parser_ctr/train_apply', $stat->vendor_id);					// applying
			}
		}
	}
	
	function ajax_actions() {
		$action = $this->input->post('action');
		$data = '';
		switch ($action) {
            case "set_person":
                 $vendor_id = $this->input->post('vendor_id');
                 $person_id = $this->input->post('person_id');
                 $result = $error = false;
                 $this->load->model('vendor_person_mdl', 'person');
                 $person_data["name"]=$this->input->post('person_name');
                 $person_data["posada"]=$this->input->post('person_posada');
                 $person_data["phone"]=$this->input->post('person_phone');
                 $person_data["email"]=$this->input->post('person_email');
                 $result=$this->person->set_vendor_person($vendor_id, $person_data );
                 if ($result == false) {
                    $error=true;
                 }
                 $data = (Object)array('error' => $error, 'result' => $result);
                 $data = json_encode($data);
                 break;
            case "delete_person":
                  $vendor_id = $this->input->post('vendor_id');
                  $person_id = $this->input->post('person_id');
                  pr("\n ajax_actions delete_person ".$person_id." vendor_id =".$vendor_id);
                  $result = $error = false;
                  $this->load->model('vendor_person_mdl', 'person');
                  $error=$this->person->delete_person($vendor_id, $person_id );
                  $data = (Object)array('error' => $error, 'result' => $result);
                  pr("\n ajax_actions delete_person ".$person_id." data=".$data);
                  cache_clear();
                  $data = json_encode($data);
                  break;
			case "update_vendor":
				$vendor_id = $this->input->post('vendor_id');
				$vendor_name = $this->input->post('vendor_name');
				$vendor_name_short = $this->input->post('vendor_name_short');
				$vendor_city = $this->input->post('vendor_city');
				$vendor_city_short = $this->input->post('vendor_city_short');
				$vendor_phone = $this->input->post('vendor_phone');
				$vendor_fax = $this->input->post('vendor_fax');
				$vendor_email = $this->input->post('vendor_email');
				$vendor_www = $this->input->post('vendor_www');
				
				$this->load->model('vendor_mdl', 'vendor');
				
				$result = $error = false;
				$vendor_exists = $this->get_vendors(null, 'object', $vendor_name);
				if(empty($vendor_exists) || ($vendor_exists->vendor_id == $vendor_id)) {
					$vendor_data = array(
						'name' => $vendor_name,
						'short_name' => $vendor_name_short,
						'city'	=> $vendor_city,
						'short_city' => $vendor_city_short,
						'phone'	=>	$vendor_phone,
						'fax'	=>	$vendor_fax,
						'email'	=>	$vendor_email,
						'www'	=>	$vendor_www
					);
					$result = $this->update_vendor($vendor_id, $vendor_data);
					
				} else {
					$error = true;
				}
				$data = (Object)array('error' => $error, 'result' => $result);
				$data = json_encode($data);
				
			break;
			case "action_option":
				$option = $this->input->post('option');
				$vendor_id = $this->input->post('vendor_id');
				
				if(!is_numeric($vendor_id)) $vendor_id = NULL;

				if($option == 'apply') {
					modules::run('parser_mod/parser_ctr/train_apply', $vendor_id);
				}
			break;
			
			case "get_price_rows":
				$vendor_id = $this->input->post("vendor_id");
				$page = $this->input->post("page");
				$is_parsed = $this->input->post("is_parsed");
				
				$this->load->model('vendor_mdl','vendor');
				
				$tmp_data = "";
				$diff_data = "";
				$page_container = "";
				$values = array();
				
				if( !empty($vendor_id) && is_numeric($vendor_id) ) {
					$functions = $this->vendor->get_vendor_functions($vendor_id);
					$price_data = modules::run('parser_mod/parser_ctr/get_price_tmp', $vendor_id, $this->price_per_page, $page);
					$statistics = modules::run('parser_mod/parser_ctr/getPriceStat', $vendor_id);
					
					if(!empty($price_data)) {
						$row_count = $price_data['count'];
						unset( $price_data['count'] );
						
						$page_container = array(
							'total_rows' => $row_count,
							'per_page' => $this->price_per_page,
							'num_links' => $this->price_num_links,
							'cur_page' => $page,
							'js_function' => 'get_price_rows',
							'base_url'    => base_url().'admin/vendors/train_price/' . $this->vendor_id . '/page/',
							'uri_segment' => 4
						);
						$page_container = paginate_ajax($page_container);
												
						$values['functions'] = $functions;
						$values['price_data'] = $price_data;
						
						$tmp_data = $this->load->view('admin/_tmp_table_tpl', $values, true);						
						if($is_parsed == true) {
							$diff_data = $this->load->view('admin/_diff_table_tpl', $values, true);
						}
					}
				}
				$data = (Object)array('tmp_data' => $tmp_data, 'diff_data' => $diff_data, 'paginate' => $page_container, 'statistics' => $statistics);
				$data = json_encode($data);
			break;
			
			case "delete_price":
				$vendor_id = $this->input->post("vendor_id");
				modules::run('parser_mod/parser_ctr/delete_price', $vendor_id);
				$this->load->model('vendor_mdl', 'vendor');
				$this->vendor->update_vendor_functions($vendor_id, array('delete_tmp_table' => '0'));
			break;		
		}
		$this->output->set_output($data);
	}

	private function set_vendor_price($vendor_id, $price_id) {
		if( empty($vendor_id) || empty($price_id) ) return FALSE;

		$this->load->model('vendor_mdl','vendor');
		return $this->vendor->set_vendor_price($vendor_id, $price_id);
	}

	private function set_vendor_function($vendor_id, $function_data=null) {
		$this->load->model('vendor_mdl','vendor');
		return $this->vendor->set_vendor_function($vendor_id, $function_data);
	}
	
	private function set_vendor($user_id, $vendor_data = null) {
		$this->load->model('vendor_mdl','vendor');
		return $this->vendor->set_vendor($user_id, $vendor_data);
	}
	
	private function get_vendors($vendor_id=null, $returyn_type='array', $vendor_name = '') {
		$this->load->model('vendor_mdl','vendor');
		$vendors = $this->vendor->get_vendors($vendor_id, $vendor_name);

		if($returyn_type == 'object') {
			if($vendors && is_array($vendors)) $vendors = $vendors[0];
		}
		return $vendors;
	}
	
	private function update_vendor($vendor_id, $vendor_data) {
		$this->load->model('vendor_mdl','vendor');
		return $this->vendor->update_vendor($vendor_id, $vendor_data);
	}
	
	private function get_vendor_accounts($account_id = null, $vendor_id = null) {
		$this->load->model('vendor_account_mdl', 'account');
		return $this->account->get_vendor_accounts($account_id, $vendor_id);
	}
	
	private function get_vendor_person($person_id = null, $vendor_id = null, $person_name = null) {
		$this->load->model('vendor_person_mdl', 'person');
		return $this->person->get_vendor_person($person_id, $vendor_id, $person_name);
	}
	
	private function get_currency($currency_id=null, $currency_value=null) {
		$this->load->model('currency_mdl', 'currency');
		return $this->currency->get_currency($currency_id=null, $currency_value=null);
	}
	
	/**
	 * Destructor of Vendors 
	 *
	 * @access  public
	 */
	function __dustructor() {}
}
?>
