<?php
	
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly
	}
	
	
	
	class wuoc_bulk_order_splitter extends wuoc_order_splitter{
		
		public $original_order_id;
		public $items_added;
		
		function __construct() {
			
			global $wuoc_bulk_instantiated;
			
			
			
			if(!$wuoc_bulk_instantiated){
				
				add_action('admin_footer', array($this, 'custom_bulk_select'));			
				add_action('load-edit.php', array($this, 'custom_bulk_action'));
				
				$wuoc_bulk_instantiated = true;
				
			
			}
	
		}
		
		
		
		
		public function custom_bulk_select() {
	 
			global $post_type, $wuoc_settings;
			 
			if($post_type == 'shop_order') {
				
			?>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						
						$('<option>').val('wuoc_combine').text('<?php _e('Combine Selected Orders', 'woo-uoc')?>').appendTo("select[name='action']");
						
					});
				</script>
			<?php
			}
		}
	
		public function add_order_items($new_order){
			
			$new_order_id = ($new_order->get_id());
			if(!empty($this->include_items)){
				
				global $wuoc_settings;
				
				//$items_added = array();
				$this->items_added = is_array($this->items_added)?$this->items_added:array();

					
				foreach($this->include_items as $order_id => $product_items){
					
					
					$order = wc_get_order( $order_id ); 
	
					foreach($order->get_items() as $order_key => $values){


                        $product_id = $values['product_id'];
                        $variation_id = $values['variation_id'];

                        $product_id = $variation_id ? $variation_id : $product_id;

						if(in_array($product_id, $this->items_added) && !in_array($product_id, $this->unique_array)){
						//if(in_array($order_key, $this->items_added)){
							continue;
						}
						
						$this->items_added[] = $product_id;
						//$this->items_added[] = $order_key;


						
						if ($variation_id != 0) {
							$product = new WC_Product_Variation($product_id);
						
						} else {
							$product = new WC_Product($product_id);
						}
					
						$item                       = new WC_Order_Item_Product();
						$item->legacy_values        = $values;
						$item->legacy_cart_item_key = $order_key;
						
						
						$qty = $this->general_array[$product_id]['qty'];
						$order_total = $order_subtotal = $this->general_array[$product_id]['total'];
						
						$qty_line = $values['quantity'];
						
						$set_meta_data = array('id'=>$order_id, 'key'=>'order_key', 'value'=>$order_key);
						$item->set_meta_data(array($set_meta_data));
						
						$item->set_props( array(
							'item_key' => $order_key,
							'quantity'     => $qty,
							'variation'    => $variation_id,
							'subtotal'     => $order_subtotal,
							'total'        => $order_total,
							'subtotal_tax' => $values['line_subtotal_tax'],
							'total_tax'    => $values['line_tax'],
							'taxes'        => $values['line_tax_data'],
						) );
						
						if ( $product ) {
							$item->set_props( array(
								'name'         => $product->get_name(),
								'tax_class'    => $product->get_tax_class(),
								'product_id'   => (method_exists($product, 'get_type') && $product->get_type()=='variation') ? $product->get_parent_id() : $product->get_id(),
								'variation_id' => (method_exists($product, 'get_type') && $product->get_type()=='variation') ? $product->get_id() : 0,
								
							) );
						}
						
						//$item->set_backorder_meta();
						
						$item_id = $item->save();
						
						$items_traces = get_post_meta($new_order_id, '_items_traces', true);
						$items_traces = is_array($items_traces)?$items_traces:array();
						
						if(!in_array($order_key, $items_traces)){
							$items_traces[$item_id] = $order_key;
							update_post_meta($new_order_id, '_items_traces', $items_traces);
						}
						
						
						$new_order->add_item( $item );
						
						$new_order->save();	 
						
						
					}
					
					$remove_combined = in_array('remove_combined', $wuoc_settings['wuoc_additional']);
					
					if($remove_combined){
//						wp_trash_post($order_id);//24/01/2019

                        wp_update_post(array('ID' => $order_id, 'post_type' => 'wuoc_t_shop_order', 'post_status' => 'trash'));
					}
				}
				//exit;
			}
		}
		public function merge_order_items($new_order){
			
			// $new_order_id = ($new_order->get_id());
			$new_order = new WC_Order($new_order);
			if(!empty($this->unique_array)){
				
				global $wuoc_settings;
				
				//$items_added = array();
				$this->items_added = is_array($this->items_added)?$this->items_added:array();
				
				$values_qty_array = array();
					
				foreach($this->unique_array as $product_id => $product_attribs){
					
					

					

					if(get_option('wuoc_maintain_uniqueness', 0)){
						foreach($product_attribs as $attribs => $attribs_values){
							
							$attribs_array = explode('|', $attribs);
		
							if(!empty($attribs_values)){
								$values_qty_array = array();
								foreach ($attribs_values as $values => $product_info_array) {
		
									# code...
		
									$values_array = explode('|', $values);
									
									$values_qty = array('quantity'=>0,'subtotal'=>0,'total'=>0,'subtotal_tax'=>0,'total_tax'=>0,'attrib_value'=>array());
									
									foreach ($product_info_array as $product_info) {
		
										
		
		
										$values_qty['attrib_value'] = $values_array;
										$values_qty['name'] = $product_info['name'];
										$values_qty['tax_class'] = $product_info['tax_class'];
										$values_qty['quantity'] += (isset($product_info['quantity'])?(float)$product_info['quantity']:0);
										$values_qty['variation_id'] = $product_info['variation_id'];
										$values_qty['product_id'] = $product_info['product_id'];
										$values_qty['subtotal'] += (isset($product_info['subtotal'])?(float)$product_info['subtotal']:0);
										$values_qty['total'] += (isset($product_info['total'])?(float)$product_info['total']:0);
										$values_qty['subtotal_tax'] += (isset($product_info['line_subtotal_tax'])?(float)$product_info['line_subtotal_tax']:0);
										$values_qty['total_tax'] += (isset($product_info['line_tax'])?(float)$product_info['line_tax']:0);
										
										if(!array_key_exists('taxes', $values_qty)){
											$values_qty['taxes'] = array();
										}
										
										if(array_key_exists('taxes', $values_qty)){
											if(array_key_exists('total', $values_qty['taxes'])){ 
												$values_qty['taxes']['total'] += $product_info['line_tax_data']['total'];
											}else{
												$values_qty['taxes']['total'] = $product_info['line_tax_data']['total'];
											}
											if(array_key_exists('subtotal', $values_qty['taxes'])){ 
												$values_qty['taxes']['subtotal'] += $product_info['line_tax_data']['subtotal'];
											}else{
												$values_qty['taxes']['subtotal'] = $product_info['line_tax_data']['subtotal'];
											}
										}else{
											
										}
										
									}
		
									$values_qty_array[] = $values_qty;																
		
								}
		
								
		
								foreach($values_qty_array as $quantity_info){
									
									$item = new WC_Order_Item_Product();
									$item->set_props( array(
										'quantity'     => $quantity_info['quantity'],
										'variation'    => $quantity_info['variation_id'],
										'subtotal'     => $quantity_info['subtotal'],
										'total'        => $quantity_info['total'],
										'subtotal_tax' => $quantity_info['subtotal_tax'],
										'total_tax'    => $quantity_info['total_tax'],
										'taxes'        => $quantity_info['taxes'],
										'name'         => $quantity_info['name'],
										'tax_class'    => $quantity_info['tax_class'],
										'product_id'   => $quantity_info['product_id'],
										'variation_id' => $quantity_info['variation_id'],
									) );
									
									$item_id = $item->save();

									foreach($attribs_array as $i => $attrib_name){
										
										 if($attrib_name!='no_meta_key'){
											$attrib_name = str_replace('***', ' ', $attrib_name);
											$attrib_value = str_replace('***', ' ', $quantity_info['attrib_value'][$i]);
											wc_update_order_item_meta($item_id, $attrib_name,  $attrib_value);
										 }
									}
		
									
									$new_order->add_item( $item );						
									$new_order->save();	
								
								}
		
							}
							
							
							
						}
					}else{


						if(array_key_exists('product_info', $product_attribs)){
							$product_info = $product_attribs['product_info'];
							
							$item = new WC_Order_Item_Product();
							$item->set_props($product_info);
							unset($product_attribs['product_info']);
							
							$item_id = $item->save();
							
							if(!empty($product_attribs)){
								foreach($product_attribs as $attrib_name => $attrib_value){
									
									 if(!in_array($attrib_name, array('no_meta_key', 'attrib_vqty'))){
										$attrib_vqty = $product_attribs['attrib_vqty'];
										$attrib_k = $attrib_vqty[$attrib_name];
										
										
										$attrib_name = str_replace('***', ' ', $attrib_name);
										$attrib_value = array();
										foreach($attrib_k as $v=>$q){
											$attrib_value[] = str_replace('***', ' ', $v.' x'.$q);
										}
										$attrib_value = implode(', ', $attrib_value);
										
										wc_update_order_item_meta($item_id, $attrib_name,  $attrib_value);
									 }
								}
							}
							
							$new_order->add_item( $item );						
							$new_order->save();	
							
						}
						
						
					}
					

				}
				
				

				
			}
		}
		public function merge_product_info($existing=array(), $new=array()){

			
			
			
			
			$first = empty($existing);
			$merged = ($first?$new:$existing);
										
			foreach($merged as $k=>$v){
				switch($k){
					default:
						$merged[$k] = $new[$k];
					break;
					case 'line_tax_data':
						$line_tax_data = $merged[$k];
						$new_line_tax_data = $new[$k];
						
						$line_tax_data['total'][] = $new_line_tax_data['total'];
						$line_tax_data['subtotal'][] = $new_line_tax_data['subtotal'];
						
					break;
					case 'quantity':
					case 'total':
					case 'subtotal':
					case 'line_tax':
					case 'line_subtotal_tax':
						if($first){
							$merged[$k] = $new[$k];
						}else{
							
							$merged[$k] += (float)$new[$k];
						}
					break;
					
				}
			}
			
			
			return $merged;
		}
		public function custom_bulk_action($post_type='', $action='', $orderids=array(), $meta_arr=array(), $is_automation_process=false, $wuoc_auto_combined_settings=array(), $layer=0) {

			// Thanks to J Lo for the tutorial on bulk actions 
			// https://blog.starbyte.co.uk/woocommerce-new-bulk-action/
			
			//wuoc_logger('debug', 'custom_bulk_action / $post_type: '.$post_type.' line no. 369');
			//wuoc_logger('debug', 'custom_bulk_action / $action: '.$action.' line no. 370');
			
			
			$return = true;
			
			
			global $typenow;
			
			if(is_array($wuoc_auto_combined_settings) && empty($wuoc_auto_combined_settings)){
				global $is_automation_combine, $wuoc_auto_combined_settings;
			}else{
				$is_automation_combine = array_key_exists('auto_combine', $wuoc_auto_combined_settings) ? $wuoc_auto_combined_settings['auto_combine'] : array() ;
			}
			
			
			$post_type = ($post_type?$post_type:$typenow);
			

			
			$this->unique_array = (is_array($this->unique_array)?$this->unique_array:array());
			
			wuoc_pre($post_type);//exit;

			if($post_type == 'shop_order') {
			
				if(!$action){

                    $wp_list_table = _get_list_table('WP_Posts_List_Table');
                    $action = ($action?$action:$wp_list_table->current_action());

                } 
				
				$action = ($action?$action:$wp_list_table->current_action());
				
				
				
				
				$wuoc_is_keep_meta_keys = (isset($_POST['wuoc_keep_meta_keys']) && $_POST['wuoc_keep_meta_keys'] == 'yes');
				
				
				
                if($is_automation_combine && $is_automation_process){
					$wuoc_auto_combined_settings_ = array_key_exists(1, $wuoc_auto_combined_settings)?current($wuoc_auto_combined_settings):$wuoc_auto_combined_settings;
                    $wuoc_is_keep_meta_keys = array_key_exists('retained_meta', $wuoc_auto_combined_settings_);

                }
				

								
				$allowed_actions = array('wuoc_combine');
				
				if(!in_array($action, $allowed_actions)) return;
				
				if(empty($orderids) && isset($_REQUEST['post'])) {
					$orderids = array_map('intval', $_REQUEST['post']);
				}
				
				wuoc_pre($action);//exit;
				
				switch($action) {
					
				
					case 'wuoc_combine': //ALIVE CASE, ADDED FOR ORDERS CONSOLIDATION ONLY - 08 January 2019
					
						

						$parent_id_for_meta = 0;
						$wuoc_parents_arr = isset($_POST['wuoc_parents_arr'])?wuoc_sanitize_data($_POST['wuoc_parents_arr']):array();
						
						if(!empty($wuoc_parents_arr)){
							foreach($wuoc_parents_arr as $section_type => $section_data){
								if(!empty($section_data)){
									foreach($section_data as $section_unique => $section_item){
										if(in_array($section_item, $orderids)){
											
											$parent_id_for_meta = $section_item;
										}
									}
								}								
							}
						}

						
						$order_meta_kv = array();
						$order_meta_kv_all = array();						
						$this->include_items = array();
						

						

                        $wc_os_parent = ((isset($_GET['wc_os_parent']) && is_numeric($_GET['wc_os_parent']) && in_array($_GET['wc_os_parent'], $orderids))?wuoc_sanitize_data($_GET['wc_os_parent']):0);
                        $parent_id_for_meta = $parent_id_for_meta ? $parent_id_for_meta : $wc_os_parent;

                        if($parent_id_for_meta){//$wuoc_parent){
							//$parent_key = array_search($wuoc_parent, $orderids);
							$parent_key = array_search($parent_id_for_meta, $orderids);
							unset($orderids[$parent_key]);
							//array_unshift($orderids, $wuoc_parent);
							$orderids[] = $parent_id_for_meta;
						}


						//exit;
						
                        
						
						$orderids = array_unique($orderids);
						
						wuoc_pre($orderids);
						wuoc_pre(count($orderids).' * '.$is_automation_combine.' && '.$is_automation_process);
						
						//exit;
						
						if(count($orderids)<=1 && !isset($_GET['debug'])){ 
							if(!empty($orderids)){
								foreach( $orderids as $orderid ) {							
									if($is_automation_combine && $is_automation_process){
										update_post_meta($orderid, '_wuoc_sniffed',  time());
									}
								}
							}
							$return = false; 
						}
						
						
						$unique_array_check = array();
						
						wuoc_pre('$return: '.$return);//exit;
						
						if($return){
							
							foreach( $orderids as $orderid ) {
							
							if($is_automation_combine && $is_automation_process && !isset($_GET['debug'])){
								update_post_meta($orderid, '_wuoc_sniffed',  time());
							}
							
							
							$child_order = wc_get_order($orderid);
							
							if(is_object($child_order) && !empty($child_order)){
								foreach($child_order->get_items() as $item_id=>$item_data){
								
								$parent_product_id = $item_data->get_product_id();
								$variation_id = $item_data->get_variation_id();
								
								$product_id = $variation_id ? $variation_id : $parent_product_id;
								
								
								
								$product_type = get_post_type($product_id);

								if ($variation_id != 0) {
									$product = new WC_Product_Variation($product_id);
								
								} else {
									$product = new WC_Product($product_id);
								}
								
								
								
								$meta_key_value = array();
								
								foreach ($item_data->get_meta_data() as $metaData) {

									$attribute = $metaData->get_data();

									// attribute value
									$value = $attribute['value'];
									
									// attribute slug
									$key = $attribute['key'];

									if(substr($key, 0, 1) == '_'){
										continue;
									}

									$meta_key_value[$key] = $value;
									
						
									
								}

								ksort($meta_key_value);
								
								$unique_keys = array_keys($meta_key_value);
								$unique_values = array_values($meta_key_value);

								$product_info = array(
										'quantity' => $item_data->get_quantity(),
										'total' => $item_data->get_total(),
										'subtotal' => $item_data->get_subtotal(),
										'line_tax_data' => $item_data['line_tax_data'],
										'line_tax' => $item_data['line_tax'],
										'line_subtotal_tax' => $item_data['line_subtotal_tax'],										
										'name' => $product->get_name(),										
										'tax_class' => $product->get_tax_class(),										
										'product_id' => $parent_product_id,										
										'variation_id' => $variation_id,
										'product_type' => $product_type,
								);
								
								$item_qty_refunded = $child_order->get_qty_refunded_for_item( $item_id ); 
								$item_total_refunded = $child_order->get_total_refunded_for_item( $item_id );
								
								$product_info['quantity'] -= abs($item_qty_refunded);
								$product_info['total'] -= abs($item_total_refunded);
								$product_info['subtotal'] -= abs($item_total_refunded);
								
								
								

								if(!empty($unique_keys)){

									$unique_keys = array_map(function($single_key){
										if(!is_array($single_key)){
											return str_replace(' ', '***', trim($single_key));
										}else{
											return count($single_key);
										}

									}, $unique_keys);
									
									$unique_values = array_map(function($single_key){
										if(!is_array($single_key)){
											return str_replace(' ', '***', trim($single_key));
										}else{
											return count($single_key);
										}

									}, $unique_values);

									$unique_keys_str = implode('|', $unique_keys);
									$unique_values_str = implode('|', $unique_values);
									
									$this->unique_array[$product_id] = (isset($this->unique_array[$product_id])?$this->unique_array[$product_id]:array());

									if(get_option('wuoc_maintain_uniqueness', 0)){
										$this->unique_array[$product_id][$unique_keys_str][$unique_values_str][] = $product_info;
									}else{

										foreach($unique_keys as $i=>$ukey){
											
											$this->unique_array[$product_id][$ukey] = (isset($this->unique_array[$product_id][$ukey])?$this->unique_array[$product_id][$ukey]:array());
											$this->unique_array[$product_id][$ukey][$i] = (isset($this->unique_array[$product_id][$ukey][$i])?$this->unique_array[$product_id][$ukey][$i]:$unique_values[$i]);
											

											$this->unique_array[$product_id]['attrib_vqty'][$ukey][$unique_values[$i]] = (isset($this->unique_array[$product_id]['attrib_vqty'][$ukey][$unique_values[$i]])?$this->unique_array[$product_id]['attrib_vqty'][$ukey][$unique_values[$i]]+$product_info['quantity']:$product_info['quantity']);
											
										}
										
										$this->unique_array[$product_id]['product_info'] = (isset($this->unique_array[$product_id]['product_info'])?$this->unique_array[$product_id]['product_info']:array());
										
										$this->unique_array[$product_id]['product_info'] = $this->merge_product_info($this->unique_array[$product_id]['product_info'], $product_info);
										
									}

								}else{
									
									if(get_option('wuoc_maintain_uniqueness', 0)){
										$this->unique_array[$product_id]['no_meta_key']['no_meta_val'][] = $product_info;										
									}else{
										
										$this->unique_array[$product_id]['product_info'] = (isset($this->unique_array[$product_id]['product_info'])?$this->unique_array[$product_id]['product_info']:array());
										
										$this->unique_array[$product_id]['product_info'] = $this->merge_product_info($this->unique_array[$product_id]['product_info'], $product_info);
										
										//$this->unique_array[$product_id]['product_info'] = $product_info;
									}
									
								}

							}
							}
							
							}
						
						}
						
						$orderids = array_unique($orderids);
						
						//wuoc_pre($orderids);exit;
						
						if(count($orderids)<=1 && !isset($_GET['debug'])){ 
							if(!empty($orderids)){
								foreach( $orderids as $orderid ) {							
									if($is_automation_combine && $is_automation_process){
										
										update_post_meta($orderid, '_wuoc_sniffed',  time());
									}
								}
							}
							$return = false; 
						}
						
						wuoc_pre('$return: '.$return);//exit;
						
						$_cart_discount = 0;
						$_cart_discount_tax = 0;
						$user_id = 0;
						if($return){
							
							foreach( $orderids as $orderid ) {
							
							
							
							$order_meta_kv_this = get_post_meta($orderid);
							$order_meta_kv_this = is_array($order_meta_kv_this)?$order_meta_kv_this:array();
							
							if(!empty($order_meta_kv_this)){
								foreach($order_meta_kv_this as $order_meta_kv_ik=>$order_meta_kv_iv){
									
									$order_meta_kv_iv = current($order_meta_kv_iv);
									$order_meta_kv_iv = maybe_unserialize($order_meta_kv_iv);
	
									if($wuoc_is_keep_meta_keys){								
										$order_meta_kv_all[$order_meta_kv_ik][$orderid] = $order_meta_kv_iv;								
									}				

									switch($order_meta_kv_ik){
										case '_store_credit_used':												
											if(!array_key_exists($order_meta_kv_ik, $order_meta_kv)){
												$order_meta_kv[$order_meta_kv_ik] = $order_meta_kv_iv;
												
											}else{
												$order_meta_kv[$order_meta_kv_ik] = array_merge($order_meta_kv_iv, $order_meta_kv[$order_meta_kv_ik]);
												if(is_array($order_meta_kv[$order_meta_kv_ik])){
													foreach($order_meta_kv[$order_meta_kv_ik] as $scdu_k=>$scdu_v){
														if(array_key_exists($scdu_k, $order_meta_kv_iv)){
															$scdu_v += $order_meta_kv_iv[$scdu_k];
															$order_meta_kv[$order_meta_kv_ik] = array($scdu_k=>$scdu_v);
															$_cart_discount += $scdu_v;
														}
													}
												}
											}

										break;
										case '_store_credit_discounts':											
											if(!array_key_exists($order_meta_kv_ik, $order_meta_kv)){
												$order_meta_kv[$order_meta_kv_ik] = $order_meta_kv_iv;
												
											}else{
												$order_meta_kv[$order_meta_kv_ik] = array_merge($order_meta_kv_iv, $order_meta_kv[$order_meta_kv_ik]);
												if(is_array($order_meta_kv[$order_meta_kv_ik])){
													foreach($order_meta_kv[$order_meta_kv_ik] as $scdu_k=>$scdu_data){
														if(array_key_exists($scdu_k, $order_meta_kv_iv) && is_array($scdu_data)){
															
															$scdu_v = $order_meta_kv_iv[$scdu_k];
															

															foreach($scdu_data as $scdu_sk=>$scdu_sv){
																if(!is_array($scdu_sv)){
																	$scdu_v[$scdu_sk] += $scdu_sv;
																}else{
																	foreach($scdu_sv as $scdu_ctk=>$scdu_ctv){
																		$scdu_ctv += $scdu_v[$scdu_sk][$scdu_ctk];
																		$scdu_v[$scdu_sk] = array($scdu_ctk=>$scdu_ctv);
																		$_cart_discount_tax += $scdu_ctv;
																	}
																	
																}
															}
															
															$order_meta_kv[$order_meta_kv_ik] = array($scdu_k=>$scdu_v);
														}
													}
												}
											}

										break;
										default:
											$order_meta_kv[$order_meta_kv_ik] = $order_meta_kv_iv;
										break;
									}

	//								if(!array_key_exists($order_meta_kv_ik, $order_meta_kv) && $order_meta_kv_iv!=''){
										
	//								}
								}
							}

							
							
							
							
							
							if(!isset($this->include_items[$orderid]))
							$this->include_items[$orderid] = array();
							
							$order_check = get_post($orderid);
							
							if(is_object($order_check) && $order_check->post_type=='shop_order'){
		
								$original_order = wc_get_order($orderid);
								$_user_id = $original_order->get_user_id();
								$user_id = ($_user_id?$_user_id:$user_id);
								
								foreach($original_order->get_items() as $item_id=>$item_data){
									
									$product_id = $item_data->get_product_id();
									$variation_id = $item_data->get_variation_id();
									$product_id = $variation_id ? $variation_id : $product_id;
	
	
									$quantity = $item_data->get_quantity();
									$total = $item_data->get_total();
									
									if(isset($this->general_array[$product_id]) && !in_array($product_id, $this->unique_array)){
										$this->general_array[$product_id]['qty'] += (int)$quantity;
										$this->general_array[$product_id]['total'] += (float)$total;
									}else{
										$this->general_array[$product_id]['qty'] = $quantity;
										$this->general_array[$product_id]['total'] = $total;
									}
									
									$this->include_items[$orderid][$product_id] = $quantity;
									
								}
								
	
	
								//update_post_meta($order_id, 'merge_status', true);
								if(!isset($_GET['debug'])){
									update_post_meta($orderid, 'wuoc_combined', time());
									update_post_meta($orderid, '_wuoc_layer', $layer);
								}
							}
						}
						
						}
						
						if($_cart_discount){
							$order_meta_kv['_cart_discount'] = $_cart_discount;
							
							
						}
						if($_cart_discount_tax){
							$order_meta_kv['_cart_discount_tax'] = $_cart_discount_tax;
						}
						
						//exit;
						//wuoc_pre($original_order->get_id());
						//wuoc_pre($original_order);
								
							
						
						$wuoc_combined_order_status = get_option('wuoc_combined_order_status', '');
						
						
						
						
						
					
						$order_data =  array(
							'post_date'     => gmdate( 'Y-m-d H:i:s', current_time( 'timestamp' ) ),
							'post_date_gmt' => gmdate( 'Y-m-d H:i:s', current_time( 'timestamp' ) ),
							'post_type'     => 'shop_order',
							'post_status'   => 'publish',
							'ping_status'   => 'closed',
							'post_password' => uniqid( 'order_' ),
						);
						
						if($user_id){
							$order_data['post_author'] = $user_id;
						}
						
						
						if($wuoc_combined_order_status){// && function_exists('wuoc_update_order_status')){
							//wuoc_update_order_status($new_order_id, $wuoc_combined_order_status);
							$order_data['post_status'] = $wuoc_combined_order_status;
						}else{
							$order_data['post_status'] = wuoc_add_prefix($original_order->get_status(), 'wc-');
						}
						
						$combined_status = array_key_exists('status_combined', $wuoc_auto_combined_settings) ? $wuoc_auto_combined_settings['status_combined'] : array() ;
						//wuoc_logger('debug', $combined_status);
						if(is_array($combined_status) && !empty($combined_status)){
							$order_data['post_status'] = current($combined_status);
						}
						wuoc_pre($order_data);
						wuoc_pre('$return: '.$return);//exit;

						if($return){
							
							//wuoc_pre('$order_data');
							//wuoc_pre($order_data);exit;
							//$order = wc_create_order();
							$new_order_id = wp_insert_post( apply_filters( 'woocommerce_new_order_data', $order_data), true );
							//wuoc_pre($order->get_id());
							//wuoc_pre('$new_order_id: '.$new_order_id);
							//exit;
							
							wuoc_pre('ORDER CREATED AS: ');
							wuoc_pre($new_order_id);
							//wuoc_logger('debug', 'EXCLUSIVE #'.$new_order_id.' - '.$wuoc_combined_order_status);
	
							if ( is_wp_error( $new_order_id ) ) {				
								add_action( 'admin_notices', array($this, 'merge__error'));
							} else {
								
								update_post_meta($new_order_id, '_wuoc_layer', $layer);
	
								if(!empty($meta_arr)){
									foreach($meta_arr as $meta_key=>$meta_val){									
										update_post_meta($new_order_id, $meta_key, $meta_val);
										//wuoc_logger('debug', $new_order_id.' - '.$meta_key.' 798');
									}							
								}
	
								$this->clone_in_progress = true;							
								
								if(!empty($orderids)){
									$this->merge_order_items($new_order_id);
									
									foreach($orderids as $orderid){									
										$this->cloned_order_data($new_order_id, $orderid, false);
									}
									
									
								}
								
								
	
	
								
	
	
								if(!empty($order_meta_kv)){
									
									
									foreach($order_meta_kv as $oKey=>$oValue){
										
										
										if($wuoc_is_keep_meta_keys){
											
											$all_order_meta = $order_meta_kv_all[$oKey];
											$oKey_ = ltrim($oKey, '_');
											$all_order_meta_key = '__'.str_replace(array('___', '__'), '', $oKey_);
											$this->wuoc_update_post_meta($new_order_id, $all_order_meta_key, $all_order_meta);
											//wuoc_logger('debug', $new_order_id.' - '.$all_order_meta_key.' 830');
											
										}
										$oValue = apply_filters('wuoc_update_post_meta_value', $new_order_id, $oKey, $oValue, $wuoc_is_keep_meta_keys);									
										$this->wuoc_update_post_meta($new_order_id, $oKey, $oValue);
										//wuoc_logger('debug', $new_order_id.' - '.$oKey.' 833');
									}
								}
								update_post_meta($new_order_id, '_wuoc_cloned_order', true);
								update_post_meta($new_order_id, '_wuoc_report_included', 'no');
								update_post_meta($new_order_id, '_wuoc_merged_orders', $orderids);
								update_post_meta($new_order_id, 'split_status', true);
								update_post_meta($new_order_id, '_wuoc_is_keep_meta_keys', $wuoc_is_keep_meta_keys);		
													
								
								$debug_backtrace = debug_backtrace();
								$function = $debug_backtrace[1]['function'];
								$function .= (array_key_exists(2, $debug_backtrace)?' / '.$debug_backtrace[2]['function']:'');
								$function .= (array_key_exists(3, $debug_backtrace)?' / '.$debug_backtrace[3]['function']:'');
								$function .= (array_key_exists(4, $debug_backtrace)?' / '.$debug_backtrace[4]['function']:'');
								$function .= (array_key_exists(5, $debug_backtrace)?' / '.$debug_backtrace[5]['function']:'');
								wuoc_logger('debug', '$new_order_id: #'.$new_order_id.' / '.$function);
								
								if($new_order_id){
									wuoc_email_notification(array('new'=>($new_order_id), 'original'=>$orderids), $action);
								}
								
								
								$order = wc_get_order($new_order_id);
								
								$order->calculate_totals();
								
								$subtotal = $order->get_subtotal();
								$subtotal_updated = $subtotal;
								
								if($_cart_discount){
									//update_post_meta($new_order_id, '_cart_discount', $_cart_discount);
									
									//$coupons = $order->get_items( 'coupon' );
									
									
									
									$subtotal_updated -= $_cart_discount;
									
								}
								
								if($_cart_discount_tax){
									//update_post_meta($new_order_id, '_cart_discount_tax', $_cart_discount_tax);
									
									$subtotal_updated -= $_cart_discount_tax;						
								}
								
								
								$new_order_shipping_items = $order->get_items('shipping');
								
								
								
								foreach ( $new_order_shipping_items as $item_id=>$new_order_shipping_item ) {
					
									$method_id = $new_order_shipping_item['method_id'];
									
									if($method_id){
										$cost = wc_format_decimal( $new_order_shipping_item['cost'] );					
										$cost = (float)($cost?$cost:0);
										
										//$new_order_shipping_methods[$method_id] = array('cost'=>$cost,'item_id'=>$item_id);
										if($cost<=0){
	
											$order->remove_item($item_id);
											$order->save();	
										}
									}else{
										$order->remove_item($item_id);
										$order->save();	
									}
									
								}
								
								//$order = wc_get_order($new_order_id);
								//$new_order_shipping_items = $order->get_items('shipping');
	
								
								//exit;
								
								
								if($subtotal_updated!=$subtotal){
									$order = wc_get_order($new_order_id);
									$order->set_total( $subtotal_updated );	
									$order->save();	
								}
								
								
								//exit;
								
	
								if(function_exists('wuoc_update_order_item_meta')){
	
									wuoc_update_order_item_meta($new_order_id, $orderids);
	
								}
								
								if(get_option('wuoc_move_to_trash', 0) && !$is_automation_process){
									foreach( $orderids as $orderid ) {
										wp_update_post(array('ID' => $orderid, 'post_type' => 'wuoc_t_shop_order', 'post_status' => 'trash'));
										update_post_meta($orderid, 'cloned_from', $new_order_id);
									}
								}
								
								
								
	
								if($new_order_id){
									
									if(function_exists('wuoc_clone_order_notes')){ wuoc_clone_order_notes($new_order_id, $orderids); }
									if(function_exists('wuoc_clone_customer_notes')){ wuoc_clone_customer_notes($new_order_id, $orderids); }
									if(function_exists('wuoc_clone_shipping')){ wuoc_clone_shipping($new_order_id, $orderids); }
									
									do_action('wuoc_combined_order_created_hook', 'wuoc_combined_order_created_hook_callback', $new_order_id, $orderids);
								}
								$return = $new_order_id;
	
	
							}
							
				
						
						}

	
					break;								
					
					default: 
					
					break;
				}
				
			}
			
			return $return;
		
		}

		
		   
	}

	new wuoc_bulk_order_splitter;

	
	