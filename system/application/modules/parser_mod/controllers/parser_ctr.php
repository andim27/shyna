<?php
	define('PARSER_MODULE_PATH', MODBASE.modules::path().'/');
	
	require_once(PARSER_MODULE_PATH."controllers/priceLoader.php");
	require_once(PARSER_MODULE_PATH."controllers/priceProcess.php");
	require_once(PARSER_MODULE_PATH."controllers/priceStat.php");	

	/**
	 * Class Parser_ctr
	 *
	 * 
	 *
	 * @author   Popov
	 * @access   public
	 * @package  Parser_ctr.class.php
	 * @created  Mon Sep 20 17:31:28 EEST 2010
	 */
	class Parser_ctr extends Controller 
	{
		public $filename = 'filename';
		
		/**
		 * Constructor of Parser_ctr
		 *
		 * @access  public
		 */
		function __constructor() {
			parent::Controller();
		}
		
		/**
		 * upload a price, set the price in database, load the price by ExcelParser library. return id of the price as the result
		 *
		 * @return	int	price_id
		 */
		public function upload_price($user_id) {			
			if(!empty($_FILES) && !empty($user_id)) {
				$loader = new priceLoader($user_id);
				
				$loader->upload_price($this->filename, $user_id);
				$upload_data = $loader->get_upload_data();
				
				if(!empty($upload_data) && isset($upload_data['file_ext'])) {
					return $loader->set_price_db($user_id, $upload_data['file_ext']);
				}
				return false;				
			}
		}
		
		function parse_price($vendor_id, $file_ext, $sheet_num) {
			$loader = new priceLoader($vendor_id);
			return $loader->loading($file_ext, $sheet_num);
		}
		
		function train_price($compare_data, $vendor_id) {
			if(empty($compare_data) || empty($vendor_id)) return FALSE;	
			
			$process = new priceProcess($compare_data, $vendor_id);			
			$process->parsing();
		}
		
		function train_apply($vendor_id) {
			if(empty($vendor_id)) return FALSE;
			
			$process = new priceProcess(null, $vendor_id);
			$process->parse_apply();
		}
		
		function get_price_tmp($vendor_id=null, $per_page=0, $page=1) {
			$this->load->model('parser_mdl', 'price');
	    	return $this->price->get_price_tmp($vendor_id, $per_page, $page);
		}
		
		function getPriceStat($vendor_id) {
			$stat = new priceStat($vendor_id);
			return $stat->getPriceStat();
		}
		
		function setPriceStat($vendor_id, $statistics) {
			$stat = new priceStat($vendor_id);
			$stat->setPriceStat($statistics);
		}
		//{Sending mail to suppliers
	    function parceVendorMail() {
			require_once(PARSER_MODULE_PATH."controllers/priceAccount.php");

			$mail = new priceAccount();
			$mail->parseAccounts();
			return $mail->getStatistics();
		}
		//}
		function delete_price($vendor_id) {
			if(empty($vendor_id)) return FALSE;
			
			$process = new priceProcess(null, $vendor_id);
			$process->delete_price();
		}
	
		/**
		 * Destructor of Parser_ctr 
		 *
		 * @access  public
		 */
		function __destructor() {}		
	}

// End of parser_ctr.php 
