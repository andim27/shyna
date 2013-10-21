<?php
	/**
	 * Class Price
	 *
	 * price class
	 *
	 * @author   Popov
	 * @access   public
	 * @package  Price.class.php
	 * @created  Wed Sep 22 09:47:54 EEST 2010
	 */
	class Price extends Controller 
	{
		/**
		 * Constructor of Price
		 *
		 * @access  public
		 */
		function __constructor() {
			parent::Controller();
		}
		
		function set_price() {
			$this->load->model('price_mdl', 'price');
			return $this->price->set_price();
		}
	
		/**
		 * Destructor of Price 
		 *
		 * @access  public
		 */
		 function __dustructor() {
		 	
		 }
		
	}
?>