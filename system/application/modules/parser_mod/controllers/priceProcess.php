<?php
	require_once(MODBASE.modules::path().'/libraries/PHPExcel/PHPExcel.php');
	require_once(MODBASE.modules::path()."/controllers/Parser.php");
	/**
	 * Class priceProcess
	 *
	 * processing price file
	 *
	 * @author   Popov
	 * @access   public
	 * @package  priceProcess.class.php
	 * @created  Tue Sep 21 11:58:00 EEST 2010
	 */
	class priceProcess extends Parser
	{
		public $brand_column;
		public $width_column;
		public $profile_column;
		public $diameter_column;
		public $model_column;
		public $load_index_column;
		public $speed_index_column;
		public $amount_column;
		public $price_column;
		public $price_type_column;
		public $comment_column;
		
		public $brand_func;		
		public $width_func;		
		public $profile_func;
		public $diameter_func;
		public $model_func;
		public $load_index_func;
		public $speed_index_func;
		public $amount_func;
		public $price_func;
		public $price_type_func;
		public $comment_func;
		
		public $rows_ignore;
		
		/**
		 * Constructor of priceProcess
		 *
		 * @access  public
		 */
		function priceProcess($params, $vendor_id) {
			parent::Parser($vendor_id);
			if( ! empty($params) ) {
				if ( is_array($params) ) {
					foreach ($params as $key => $val) {
						$this->$key = $val;
					}
				}
			}
		}
		
		/**
		 * recieve data from the POST
		 * to form a list of parametrs
		 * using parametrs, do the searchin temporary table; update fields in the table, accroding to parametrs
		 *
		 */
		function parsing() {		

			$this->parsing_brand();		 	 
            $this->parsing_model();
			$this->parsing_load();
            $this->parsing_speed();

			
			$this->parsing_value($this->profile_column, $this->profile_func, 'profile_id');
			$this->parsing_value($this->diameter_column, $this->diameter_func, 'diameter_id');			
			$this->parsing_value($this->width_column, $this->width_func, 'width_id');
			$this->parsing_value_type($this->price_type_column, $this->price_type_func, 'price_type');
			
			$this->apply_value($this->amount_column, $this->amount_func, 'amount');
			$this->apply_value($this->price_column, $this->price_func, 'price');
			$this->parsing_comment();

		}

		private function parsing_brand() {
			try {
				$this->prepare_temp($this->brand_column, $this->brand_func, 'brand_id');

				$query = "UPDATE {$this->tablename_tmp} s,
                            (select bs.id, t.row_id, bs.name, t.A1
                            from brand_syn bs, {$this->tablename_tmp} t, brand b,
                                (select t.row_id,max(length(bs.name)) as maxlength
                                from brand_syn bs, {$this->tablename_tmp} t, brand b
                                where b.id=bs.brand_id
                                and lower(replace(t._temp, ' ','')) like concat('%',lower(bs.name),'%')
                                group by t.row_id
                                ) bl
                            where b.id=bs.brand_id
                            and lower(replace(t._temp, ' ','')) like concat('%',lower(bs.name),'%')
                            and t.row_id=bl.row_id and length(bs.name)=bl.maxlength
                            ) res
                          SET s.brand_id = res.id
                          WHERE s.row_id= res.row_id
    					 ";
				  if( ! $this->ci->db->query($query) )
							 throw new Exception( $this->ci->db->_error_message() );
			    } catch (Exception $e) {
				    $this->setError($e, __FUNCTION__);
			    }
		}

		private function prepare_temp($column_name, $function, $attr_column) {
			if(empty($column_name) || ($column_name == 'A0') || empty($attr_column)) return false;
            $query = "UPDATE {$this->tablename_tmp} SET _temp = {$column_name}";
			if( ! $this->ci->db->query($query) )
				    throw new Exception( $this->ci->db->_error_message() );
            if(!empty($function)) {
                $function_list = explode('{',$function);
                foreach ($function_list as $key => $value) {
				    $value = str_replace('%column_name%', '_temp', $value);
				    $query = "UPDATE {$this->tablename_tmp} SET _temp =({$value})";
				    if( ! $this->ci->db->query($query) )
				    throw new Exception( $this->ci->db->_error_message() );
                }
			}
		}

		private function parsing_update() {
			$query = "update {$this->tablename_tmp} set
				model_id=(
				select ".str_repeat("REPLACE(", 23)."
					model_id,
					' ', ''),
					'/', ''),
					'?', ''),
					'.', ''),
					',', ''),
					'>', ''),
					'<', ''),
					'=', ''),
					'_', ''),
					'-', ''),
					')', ''),
					'(', ''),
					'*', ''),
					'&', ''),
					'^', ''),
					'%', ''),
					'$', ''),
					'#', ''),
					'@', ''),
					'!', ''),
					'~', ''),
					'`', ''),
					'|', '')
				)";
			if ( ! $this->ci->db->query($query)) return FALSE;

			$query = "UPDATE {$this->tablename_tmp} t, (
									SELECT t.row_id as t_id, w.id as w_id, p.id as p_id, d.id as d_id,
											m.model_id, li.id as li_id, si.id as si_id
									FROM ".$this->tablename_tmp." t
									LEFT JOIN width w ON w.value=trim(t.width_id)
									LEFT JOIN profile p ON p.value=trim(t.profile_id)
									LEFT JOIN diameter d ON d.value=trim(t.diameter_id)
									LEFT JOIN model_syn m ON m.name=t.model_id
									LEFT JOIN load_index li ON li.ind=trim(t.load_index_id)
									LEFT JOIN speed_index si ON si.ind=trim(t.speed_index_id)) props
			SET
				t.width_id = props.w_id,
				t.profile_id = props.p_id,
				t.diameter_id = props.d_id,
				t.model_id = props.model_id,
				t.load_index_id = props.li_id,
				t.speed_index_id = props.si_id
			WHERE t.row_id = props.t_id";
			if( ! $this->ci->db->query($query) )
			throw new Exception( $this->ci->db->_error_message() );
		}

		function parse_apply() {
			if(empty($this->vendor_id)) return FALSE;

			try {
			    $query_prep="select count(sh.id) from sheets sh, pricelists pl where sh.list_id=pl.list_id and pl.vendor_id='".$this->vendor_id;
			    $query_prep = $this->ci->db->query($query);
			    if( ! $query_prep ) throw new Exception( $this->db->_error_message() );

			    for ($i=0;$i<$query_prep;$i++) {
			    	// verify if user continues train
				//$query = "select delete_tmp_table from {$this->table_vendor_functions} where vendor_id='{$this->vendor_id}'";
				$query = "select sh.active from sheets sh, pricelists pl where sh.list_id=pl.list_id and pl.vendor_id=".$this->vendor_id ." and sh.sheet_id=$i";
				$query = $this->ci->db->query($query);
				if( ! $query ) throw new Exception( $this->db->_error_message() );
				$user_continues = $query->row()->active;
				if( ! $user_continues) {
					//$query = "DELETE FROM prices where list_id in (select list_id from pricelists where vendor_id='".$this->vendor_id."')";
				    $query = "DELETE FROM prices where list_id in (select list_id from pricelists where vendor_id='".$this->vendor_id."') and sheet_id in (select sheet_id from sheets where list_id=(select list_id from pricelists where vendor_id=".$this->vendor_id."))";
					$query = $this->ci->db->query($query);
					if( ! $query ) throw new Exception( $this->db->_error_message() );
				}

				
				$query = "insert into prices (brand_id, list_id, width_id, profile_id, diameter_id, model_id, load_id, speed_id, extra, amount, price, price_type, currency_id)
				(
					select distinct
						bs.brand_id,
						p.list_id,
						t.width_id,
						t.profile_id,
						t.diameter_id,
						ms.model_id,
						t.load_index_id,
						t.speed_index_id,
						t.comment,
						t.amount,
						if(t.price <> '', round(t.price,0), '?'),
						t.price_type,
						t.price_type
					FROM
						".$this->tablename_tmp." t,
						vendor v,
						pricelists p,
						sheets sh,
                        model_syn ms,
                        brand_syn bs,		
                        brand br
					where
						v.id=p.vendor_id
						and v.id='".$this->vendor_id."'
					and t.brand_id is not null
					and t.width_id is not null
					and t.profile_id is not null
					and t.diameter_id is not null
					and t.model_id is not null
					and t.speed_index_id is not null
					and t.load_index_id is not null
					and t.amount is not null
					and t.price is not null
                    and t.brand_id = bs.id
                    and t.model_id = ms.id
                    ";
//				}
				$query .= "	);";
				$query = $this->ci->db->query($query);
				if( ! $query ) throw new Exception( $this->db->_error_message() );
				else {
						$query = "DELETE FROM ".$this->tablename_tmp."
						WHERE brand_id is not null
						and width_id is not null
						and profile_id is not null
						and diameter_id is not null
						and model_id is not null
						and speed_index_id is not null
						and load_index_id is not null
						and amount is not null
						and price is not null";

					$query = $this->ci->db->query($query);
					if( ! $query ) throw new Exception( $this->db->_error_message() );
		
					// change status price
					$query = "UPDATE ".$this->table_sheets." sh LEFT JOIN ".$this->table_pricelists." pl on pl.list_id=sh.list_id SET parsed = '1' WHERE pl.vendor_id = '{$this->vendor_id}' and sh.sheet_id=$i";
					$query = $this->ci->db->query($query);
					if( ! $query ) throw new Exception( $this->db->_error_message() );
				}
					if( ! $user_continues) {
						$this->_drop();
					}

					return true;
				}
			} catch (Exception $e) {
				$this->setError($e, __FUNCTION__);
			}
			return false;
		}

		function delete_price() {
			$this->_drop();
		}

		private function parsing_value($column_name, $function, $attr_column) {
            $this->prepare_temp($column_name, $function, $attr_column);
			$table_name = substr_replace($attr_column, '',-3,3); //delete '_id' from $attr_column
            $query = "UPDATE {$this->tablename_tmp} t
                      LEFT JOIN {$table_name} n
                      ON round(replace(trim(t._temp),',','.'),2) = round(replace(n.value,',','.'),2)
                      SET t.{$attr_column} = n.id
                     ";
			if( ! $this->ci->db->query($query) )
				    throw new Exception( $this->ci->db->_error_message() );
		}
		private function parsing_value_type($column_name, $function, $attr_column) {
            $this->prepare_temp($column_name, $function, $attr_column);
            $query = "UPDATE {$this->tablename_tmp} t, currency n 
                      SET t.{$attr_column} = n.currency_id
					  WHERE trim(t._temp) = n.currency_value OR trim(t._temp) = n.currency_id
                     ";
			if( ! $this->ci->db->query($query) )
				    throw new Exception( $this->ci->db->_error_message() );
		}

		private function apply_value($column_name, $function, $attr_column) {
            $this->prepare_temp($column_name, $function, $attr_column);

            $query = "UPDATE {$this->tablename_tmp} t
                     SET t.{$attr_column} = trim(t._temp)
                     ";
			if( ! $this->ci->db->query($query) )
				    throw new Exception( $this->ci->db->_error_message() );
		}



        private function parsing_model() {
			try {
					$this->prepare_temp($this->model_column, $this->model_func, 'model_id');
                    $this->clean_model();
					$query = "
                        UPDATE {$this->tablename_tmp} s,
                               (SELECT ms.id, t.row_id
                               FROM model_syn ms, {$this->tablename_tmp} t, model m,  brand_syn bs,
                                    (SELECT t.row_id,max(length(ms.name)) as maxlength
                                    FROM model_syn ms, {$this->tablename_tmp} t, model m, brand_syn bs
                                    WHERE m.brand_id=bs.brand_id AND bs.id=t.brand_id AND m.id=ms.model_id
                                    AND lower(replace(t._temp, ' ','')) like concat('%',lower(ms.name),'%')
                                    group by t.row_id
                                ) ml
                                WHERE  m.brand_id=bs.brand_id AND bs.id=t.brand_id AND m.id=ms.model_id
                                AND lower(replace(t._temp, ' ','')) like concat('%',lower(ms.name),'%')
                                AND t.row_id=ml.row_id AND length(ms.name)=ml.maxlength
                                ) res
                        SET s.model_id = res.id
                        WHERE s.row_id= res.row_id
						";
					if( ! $this->ci->db->query($query) )
					    throw new Exception( $this->ci->db->_error_message() );

		    } catch (Exception $e) {
			    $this->setError($e, __FUNCTION__);
		    }
	    }

        private function parsing_load() {
			try {
					$this->prepare_temp($this->load_index_column, $this->load_index_func, 'load_index_id');
					$query = "
                        UPDATE load_index li, {$this->tablename_tmp} t,
                            (SELECT t.row_id, t.load_index_id, if(left(t._temp,LENGTH(SUBSTRING_INDEX(t._temp,' ',1))) rlike '^[5-9][[:digit:]]{1}',left(t._temp,2), if(left(t._temp,LENGTH(SUBSTRING_INDEX(t._temp,' ',1))) rlike '^[12][[:digit:]]{2}',left(t._temp,3), '')) as lvalue
                            FROM {$this->tablename_tmp} t) res
                        SET t.load_index_id = li.id
                        WHERE res.lvalue=li.ind
                        AND t.row_id=res.row_id
						";
					if( ! $this->ci->db->query($query) )
					    throw new Exception( $this->ci->db->_error_message() );

		    } catch (Exception $e) {
			    $this->setError($e, __FUNCTION__);
		    }
	    }

        private function parsing_speed() {
			try {
					$this->prepare_temp($this->speed_index_column, $this->speed_index_func, 'speed_index_id');
					$query = "
                        UPDATE {$this->tablename_tmp} t,
                            (SELECT if(si.id is null, 32,si.id) as id, res.row_id
                            FROM {$this->tablename_tmp} t,
	                            (SELECT t.row_id, trim(replace(replace(replace(t._temp,li.ind,''),li.ind-2,''),li.ind-4,'')) as svalue
                                FROM load_index li, {$this->tablename_tmp} t
                                WHERE t.load_index_id = li.id) res
                            LEFT JOIN speed_index si
                            ON trim(left(res.svalue,LENGTH(SUBSTRING_INDEX(res.svalue,' ',1))))=si.ind
                            where res.row_id=t.row_id ) res1
                        SET t.speed_index_id= res1.id
                        WHERE res1.row_id=t.row_id
						";
					if( ! $this->ci->db->query($query) )
					    throw new Exception( $this->ci->db->_error_message() );

		    } catch (Exception $e) {
			    $this->setError($e, __FUNCTION__);
		    }
	    }

        private function parsing_comment() {
			try {
				$this->prepare_temp($this->comment_column, $this->comment_func, 'comment_id');

				$query = "
                    UPDATE {$this->tablename_tmp} t,
                       (SELECT t.row_id,
                        trim( both '.' from trim( LEADING 'R' from trim( both '/' from trim( both ',' from trim(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(
	                    upper(t._temp),
	                    ' ',''),
                        '/',''),
	                    li.ind,''), li.ind-2,''), li.ind-4,''),
	                    w.value,''),
	                    p.value,''),
	                    d.value,''),
	                    upper(ms.name),''),
	                    upper(bs.name),''),
	                    si.ind,'')
	                    ))))) as svalue, t.a1
                        FROM {$this->tablename_tmp} t
                        LEFT JOIN load_index li ON t.load_index_id = li.id
                        LEFT JOIN speed_index si ON t.speed_index_id=si.id
                        LEFT JOIN model_syn ms ON t.model_id = ms.id
                        LEFT JOIN brand_syn bs ON t.brand_id = bs.id
                        LEFT JOIN width w ON t.width_id= w.id
                        LEFT JOIN diameter d ON t.diameter_id=d.id
                        LEFT JOIN profile p ON t.profile_id=p.id
                        WHERE
                            bs.id IS NOT NULL
                        AND ms.id IS NOT NULL
                        AND w.id IS NOT NULL
                        AND p.id IS NOT NULL
                        AND d.id IS NOT NULL
                       ) res
                       SET t.comment = lower(res.svalue)
                       WHERE t.row_id = res.row_id
					";
					if( ! $this->ci->db->query($query) )
					    throw new Exception( $this->ci->db->_error_message() );

		    } catch (Exception $e) {
			    $this->setError($e, __FUNCTION__);
		    }
	    }

   		private function clean_model() {
			$query = "update {$this->tablename_tmp} set
				_temp=(
				select ".str_repeat("REPLACE(", 22)."
					_temp,
                    '/', ''),
                    '\\\\', ''),
                    '?', ''),
                    '.', ''),
                    '>', ''),
                    '<', ''),
                    '=', ''),
                    '_', ''),
                    '-', ''),
                    ')', ''),
                    '(', ''),
                    '*', ''),
                    '&', ''),
                    '^', ''),
                    '%', ''),
                    '$', ''),
                    '#', ''),
                    '@', ''),
                    '!', ''),
                    '~', ''),
                    '`', ''),
                    '|', '')
				)";
			if ( ! $this->ci->db->query($query)) return FALSE;
        }

    }
?>
