<?php
	/**
	 * Class Accounts
	 *
	 * accounts controller
	 *
	 * @author   Popov
	 * @access   public
	 * @package  Accounts.class.php
	 * @created  Mon Oct 18 20:51:24 EEST 2010
	 */
	class Accounts extends Controller
	{
		const ACCOUNT_EXISTS = 2;
		
		/**
		 * Constructor of Accounts
		 *
		 * @access  public
		 */
		function Accounts() {
			parent::Controller();
		}
		
		function ajax_actions() {
			$action = $this->input->post('action');

			$data = '';
			switch ($action) {
				case "add_account":
					$vendor_id = $this->input->post("vendor_id");
					$account_email = $this->input->post("account_email");

					$this->load->model('vendor_account_mdl', 'account');
					$account_exists = $this->get_vendor_accounts(null, null, $account_email);
					if(empty($account_exists)) {
						$account_data = array(
							'account_email' => $account_email,
							'vendor_id' => $vendor_id
						);
						$data = $this->set_vendor_account($account_data);

					} else {
						$data = 2;
					}
				break;
				case "edit_accounts":
					$vendor_id = $this->input->post("vendor_id");
					$accounts = unserialize($this->input->post("accounts"));
					$error_option = $error_type = false;
					if(!empty($accounts)) {
						foreach ($accounts as $account) {
							$account_exists = $this->get_vendor_accounts(null, null, $account['account_email']);
							if(is_array($account_exists)) $account_exists = array_shift($account_exists);
							if(empty($account_exists) || (is_object($account_exists) && $account_exists->account_id == $account['account_id'] && $account_exists->vendor_id == $vendor_id)) {
								$account_data = array('account_email' => $account['account_email']);
								$this->update_vendor_accounts($account['account_id'], $vendor_id, $account_data);		
							} else {								
								$error_option = $account_exists->account_email;
								$error_type = self::ACCOUNT_EXISTS;
							}
						}
					}
					$data = (Object)array('error_option' => $error_option, 'error_type' => $error_type);
					$data = json_encode($data);
				break;
			}
			$this->output->set_output($data);
		}
		
		private function get_vendor_accounts($account_id = null, $vendor_id = null, $account_email = null) {
			$this->load->model('vendor_account_mdl', 'account');
			return $this->account->get_vendor_accounts($account_id, $vendor_id, $account_email);
		}
		
		private function set_vendor_account($account_data) {
			$this->load->model('vendor_account_mdl', 'account');
			return $this->account->set_vendor_account($account_data);
		}
		
		private function update_vendor_accounts($account_id, $vendor_id, $account_data) {
			$this->load->model('vendor_account_mdl', 'account');
			return $this->account->update_vendor_account($account_id, $vendor_id, $account_data);
		}	
	}
?>