<?php
	/**
	 * Class priceStat
	 *
	 * price statistics
	 *
	 * @author   Popov
	 * @access   public
	 * @package  priceStat.class.php
	 * @created  Mon Oct 11 13:29:51 EEST 2010
	 */
	class priceStat extends Parser
	{
		function priceStat($vendor_id){
			parent::Parser($vendor_id);
		}
		
		function getPriceStat(){
			$isset = $this->_check();
			if($isset) {			
				$query = "
				select
					count(tmp.row_id) as count_all,
					count(tmp1.row_id) as count_null,
                   (count(tmp.row_id)-count(tmp1.row_id)) as count_not_null
				from
					{$this->tablename_tmp} tmp 
				left join {$this->tablename_tmp} tmp1 
				on tmp.row_id = tmp1.row_id
				and (tmp1.brand_id =''
					or tmp1.width_id is null
					or tmp1.profile_id is null
					or tmp1.diameter_id is null
					or tmp1.model_id is null
					or tmp1.speed_index_id is null
					or tmp1.load_index_id is null
				)";
				$query = $this->ci->db->query($query);
				if( ! $query ) throw new Exception( $this->db->_error_message() );
				$stat = $query->row();
				//if(!empty($stat)) {
				//	$stat->count_not_null = $stat->count_all - $stat->count_null;
				//}
				$this->setPriceStat($stat);
				return $stat;
			}
			return false;
		}
		
		function setPriceStat($statistics) {
			$query = "SELECT * FROM {$this->table_pricelists} where vendor_id='{$this->vendor_id}'";
			$query = $this->ci->db->query($query);
			if( ! $query ) throw new Exception( $this->db->_error_message() );
			$stat = $query->row();
			if(!$stat) {
				$query = "INSERT INTO {$this->table_pricelists} 
					(vendor_id, price_date, price_status, file_ext, count_null, count_not_null, type_getting) 
				VALUES	
					('{$this->vendor_id}', 
					'{$statistics->price_date}',
					'{$statistics->price_status}',
					'{$statistics->file_ext}',
					'{$statistics->count_null}',
					'{$statistics->count_not_null}',
					'{$statistics->type_getting}')";
				
				if( ! $this->ci->db->query($query) )
				throw new Exception( $this->ci->db->_error_message() );	
							
				return true;
								
			} else {
				$pricelists_fields = array(
									'price_date',
									'price_status',
									'file_ext',
									'count_null',
									'count_not_null',
									'type_getting');
									
				$pricelists_data = array();
				foreach($statistics as $key=>$value) {
					if (in_array($key, $pricelists_fields)) $pricelists_data[$key] = $value;
				}
				if (!empty($pricelists_data)) {					
					$this->ci->db->where('vendor_id', $this->vendor_id);
					return $this->ci->db->update($this->table_pricelists, $pricelists_data);
				}
			}
		}		
	}
?>