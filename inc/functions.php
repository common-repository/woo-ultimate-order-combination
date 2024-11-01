<?php if ( ! defined( 'ABSPATH' ) ) exit;

use \Automattic\WooCommerce\Admin\API\Reports\Cache as ReportsCache;



	include_once(realpath(WUOC_PLUGIN_DIR.'/inc/wuoc-emails.php'));
	// if(WUOC_PLUGIN_DIR.'/inc/functions-inner.php')
	// include_once(WUOC_PLUGIN_DIR.'/inc/functions-inner.php');

	if(!function_exists('wuoc_add_prefix')){
		function wuoc_add_prefix($str, $prefix=''){
			return $prefix.str_replace($prefix, '', $str);
		}
	}
	if(!function_exists('wuoc_remove_str')){
		function wuoc_remove_str($str, $prefix){
			return str_replace($prefix, '', $str);
		}
	}	
		
	if(!function_exists('wuoc_add_combined_link')){
		function wuoc_add_combined_link()
		{
			global $typenow;
		
			if ($typenow=='shop_order') {
				$total_combined = get_option('wuoc_combined_orders_count', 0);
			?>
				<script type="text/javascript" language="javascript">
					jQuery(document).ready(function($) {
						$('.subsubsub').append('| <a href="<?php echo admin_url('admin.php?page=wuoc-settings&t=2'); ?>" target="_blank"><?php _e('Combined', 'woo-uoc'); ?> (<?php echo $total_combined; ?>)</a>');
					});
				</script>
		
		<?php
			}
		}
	}
	
	function wuoc_settings_update(){
		
		
		
		if(!empty($_POST)){
		
			global $wuoc_currency, $wuoc_settings, $wuoc_orderslist_page_cron;
		
			if(!empty($_POST) && isset($_POST['wuoc_settings'])){
				 
				
				$wuoc_currency = get_woocommerce_currency_symbol();
	
				wuoc_settings_refresh();
						
				
				if ( 
					! isset( $_POST['wuoc_settings_field'] ) 
					|| ! wp_verify_nonce( $_POST['wuoc_settings_field'], 'wuoc_settings_action' ) 
				) {
				
				   _e('Sorry, your nonce did not verify.', 'woo-uoc');
				   exit;
				
				} else {
				
						$wuoc_additional = (isset($_POST['wuoc_settings']['wuoc_additional']));			   
						
						$wuoc_settings_updated = wuoc_sanitize_data($_POST['wuoc_settings'] );
						
						$wuoc_settings_updated['wuoc_additional'] = (isset($wuoc_settings_updated['wuoc_additional']) && is_array($wuoc_settings_updated['wuoc_additional']))?$wuoc_settings_updated['wuoc_additional']:(isset($wuoc_settings['wuoc_additional'])?$wuoc_settings['wuoc_additional']:'');
						
						// $wuoc_products_existing = $wuoc_settings['wuoc_products'];

						update_option('wuoc_settings', wuoc_sanitize_data($wuoc_settings_updated));

						$wuoc_order_combine_email = (isset($_POST['wuoc_order_combine_email'])?wuoc_sanitize_data($_POST['wuoc_order_combine_email']):($wuoc_additional?0:get_option('wuoc_order_combine_email', 0)));
						update_option( 'wuoc_order_combine_email', $wuoc_order_combine_email );


						$wuoc_show_retained_meta = (isset($_POST['wuoc_show_retained_meta'])?wuoc_sanitize_data($_POST['wuoc_show_retained_meta']):($wuoc_additional?0:get_option('wuoc_show_retained_meta', 0)));
						update_option( 'wuoc_show_retained_meta', $wuoc_show_retained_meta );
						
						
						
						$wuoc_maintain_uniqueness = (isset($_POST['wuoc_maintain_uniqueness'])?wuoc_sanitize_data($_POST['wuoc_maintain_uniqueness']):($wuoc_additional?0:get_option('wuoc_maintain_uniqueness', 0)));
						update_option( 'wuoc_maintain_uniqueness', $wuoc_maintain_uniqueness );
						
						$wuoc_shipping_status = (isset($_POST['wuoc_shipping_status'])?wuoc_sanitize_data($_POST['wuoc_shipping_status']):($wuoc_additional?0:get_option('wuoc_shipping_status', '')));
						update_option( 'wuoc_shipping_status', $wuoc_shipping_status );
						
						$wuoc_combined_order_status = (isset($_POST['wuoc_combined_order_status'])?wuoc_sanitize_data($_POST['wuoc_combined_order_status']):($wuoc_additional?0:get_option('wuoc_combined_order_status', '')));
						update_option( 'wuoc_combined_order_status', $wuoc_combined_order_status );						
						
						$wuoc_thankyou_page_cron = (isset($_POST['wuoc_thankyou_page_cron'])?wuoc_sanitize_data($_POST['wuoc_thankyou_page_cron']):($wuoc_additional?0:get_option('wuoc_thankyou_page_cron', 0)));
						update_option( 'wuoc_thankyou_page_cron', $wuoc_thankyou_page_cron );
						
						$wuoc_orderslist_page_cron = (isset($_POST['wuoc_orderslist_page_cron'])?wuoc_sanitize_data($_POST['wuoc_orderslist_page_cron']):($wuoc_additional?0:$wuoc_orderslist_page_cron));
						update_option( 'wuoc_orderslist_page_cron', $wuoc_orderslist_page_cron );
						
						
						$wuoc_move_to_trash = (isset($_POST['wuoc_move_to_trash'])?wuoc_sanitize_data($_POST['wuoc_move_to_trash']):($wuoc_additional?0:get_option('wuoc_move_to_trash', 0)));
						update_option( 'wuoc_move_to_trash', $wuoc_move_to_trash );
						
						$wuoc_filter_by_meta_key = (isset($_POST['wuoc_filter_by_meta_key'])?wuoc_sanitize_data($_POST['wuoc_filter_by_meta_key']):($wuoc_additional?0:get_option('wuoc_filter_by_meta_key', 0)));
						update_option( 'wuoc_filter_by_meta_key', $wuoc_filter_by_meta_key );
						
						$wuoc_custom_meta_key_column = (isset($_POST['wuoc_custom_meta_key_column'])?wuoc_sanitize_data($_POST['wuoc_custom_meta_key_column']):($wuoc_additional?0:get_option('wuoc_custom_meta_key_column', 0)));
						update_option( 'wuoc_custom_meta_key_column', $wuoc_custom_meta_key_column );
						
						$wuoc_view_order_button = (isset($_POST['wuoc_view_order_button'])?wuoc_sanitize_data($_POST['wuoc_view_order_button']):($wuoc_additional?0:get_option('wuoc_view_order_button', 0)));
						update_option( 'wuoc_view_order_button', $wuoc_view_order_button );
						

						$wuoc_custom_meta_key_column_csv = (isset($_POST['wuoc_custom_meta_key_column_csv'])?wuoc_sanitize_data($_POST['wuoc_custom_meta_key_column_csv']):($wuoc_additional?0:get_option('wuoc_custom_meta_key_column_csv', 0)));
						update_option( 'wuoc_custom_meta_key_column_csv', $wuoc_custom_meta_key_column_csv );
						
						
						
						$wuoc_show_order_items_meta = (isset($_POST['wuoc_show_order_items_meta'])?wuoc_sanitize_data($_POST['wuoc_show_order_items_meta']):($wuoc_additional?0:get_option('wuoc_show_order_items_meta', 0)));
						update_option( 'wuoc_show_order_items_meta', $wuoc_show_order_items_meta );
						
						$wuoc_sort_order_items_by_category = (isset($_POST['wuoc_sort_order_items_by_category'])?wuoc_sanitize_data($_POST['wuoc_sort_order_items_by_category']):($wuoc_additional?0:get_option('wuoc_sort_order_items_by_category', 0)));
						update_option( 'wuoc_sort_order_items_by_category', $wuoc_sort_order_items_by_category );						
						
						$wuoc_clone_order_notes = (isset($_POST['wuoc_clone_order_notes'])?wuoc_sanitize_data($_POST['wuoc_clone_order_notes']):($wuoc_additional?0:get_option('wuoc_clone_order_notes', 0)));
						update_option( 'wuoc_clone_order_notes', $wuoc_clone_order_notes );
						
						$wuoc_clone_customer_notes = (isset($_POST['wuoc_clone_customer_notes'])?wuoc_sanitize_data($_POST['wuoc_clone_customer_notes']):($wuoc_additional?0:get_option('wuoc_clone_customer_notes', 0)));
						update_option( 'wuoc_clone_customer_notes', $wuoc_clone_customer_notes );
						
						$wuoc_clone_shipping = (isset($_POST['wuoc_clone_shipping'])?wuoc_sanitize_data($_POST['wuoc_clone_shipping']):($wuoc_additional?0:get_option('wuoc_clone_shipping', 0)));
						update_option( 'wuoc_clone_shipping', $wuoc_clone_shipping );
						

						$wuoc_stock_short_email = (isset($_POST['wuoc_stock_short_email'])?wuoc_sanitize_data($_POST['wuoc_stock_short_email']):($wuoc_additional?0:get_option('wuoc_stock_short_email', 0)));
						update_option( 'wuoc_stock_short_email', $wuoc_stock_short_email );

						$wuoc_product_backorder_email = (isset($_POST['wuoc_product_backorder_email'])?wuoc_sanitize_data($_POST['wuoc_product_backorder_email']):($wuoc_additional?0:get_option('wuoc_product_backorder_email', 0)));
						update_option( 'wuoc_product_backorder_email', $wuoc_product_backorder_email );						

						$wuoc_new_order_email = (isset($_POST['wuoc_new_order_email'])?wuoc_sanitize_data($_POST['wuoc_new_order_email']):($wuoc_additional?0:get_option('wuoc_new_order_email', 0)));
						update_option( 'wuoc_new_order_email', $wuoc_new_order_email );
						
						
						$wuoc_bootstrap = (isset($_POST['wuoc_bootstrap'])?wuoc_sanitize_data($_POST['wuoc_bootstrap']):($wuoc_additional?0:get_option('wuoc_bootstrap', 0)));
						update_option( 'wuoc_bootstrap', $wuoc_bootstrap );
						
						
						
						wuoc_settings_refresh();
				   
				}
				
				
			}
	
			
	
		}
	}
	
	
	class wuoc_order_splitter {
		
		/** @var original order ID. */
		public $original_order_id;
		public $auto_split;
		public $exclude_items;
		public $include_items;
		public $include_items_qty;
		public $general_array;
		public $unique_array;
		public $clone_in_progress;
	
		/**
		 * Fire clone_order function on clone request.
		 */
		
		function __construct() {
			
			$this->exclude_items = array();
			$this->include_items = array();
			$this->include_items_qty = array();
			$this->general_array = array();
			$this->unique_array = array();
			$this->clone_in_progress = false;
			
			
		}
		
		
		

		
		public function cloned_order_data($order_id, $originalorderid = array(), $clone_order=true, $reduce_stock=false, $clone_shipping=true){
			
			
			global $yith_pre_order, $wuoc_debug;
			$order = new WC_Order($order_id);

            add_filter('woocommerce_update_product_stock_query', function($sql, $product_id_with_stock, $new_stock, $operation){

                global $wpdb;
                $sql = $wpdb->prepare(
                    "UPDATE {$wpdb->postmeta} SET meta_value = meta_value %+f WHERE post_id = %d AND meta_key='_stock'",
                    0.0, // This will either subtract or add depending on operation.
                    $product_id_with_stock
                );

                return $sql;

            }, 10, 4);
		
			$originalorderids = (is_array($originalorderid)?$originalorderid:array($originalorderid));

			
			
			foreach( $originalorderids as $originalorderid ) {
			
				$this->original_order_id = 0;
				
				if ($originalorderid != null) {
					$this->original_order_id = $originalorderid;
				} elseif(array_key_exists('order_id', $_GET)) {
					$this->original_order_id = wuoc_sanitize_data($_GET['order_id']);
				}
				
				if(!$this->original_order_id){ continue; }
				
				if(!$wuoc_debug){
					
					// $this->wuoc_update_post_meta($order_id, 'cloned_from', $this->original_order_id);
					// if(!$this->clone_in_progress)				
					// $this->wuoc_update_post_meta($order_id, 'splitted_from', $this->original_order_id);
				}
				
				$original_order = wc_get_order($this->original_order_id);
				
				$original_order->add_order_note(__('Child Order').' #'.$order_id.'');

				
				
				$order_status = $original_order->get_status();
				
				// Check if Sequential Numbering is installed
				
				if ( class_exists( 'WC_Seq_Order_Number_Pro' ) ) {
					
					// Set sequential order number 
					
					$setnumber = new WC_Seq_Order_Number_Pro;
					$setnumber->set_sequential_order_number($order_id);
					
				}
				
				if(!$wuoc_debug){
				
					$this->clone_order_header($order_id);
					$this->clone_order_billing($order_id);
					
					if($clone_shipping)
					$this->clone_order_shipping($order_id);
					
					
					$this->clone_order_shipping_items($order_id, $original_order);
					$this->clone_order_fees($order, $original_order);
					
					$this->clone_order_coupons($order, $original_order);
				
				}
				
				add_filter( 'woocommerce_can_reduce_order_stock', 'wuoc_filter_woocommerce_can_reduce_order_stock', 10, 2 );
				
				if($clone_order){

					$this->clone_order_items($order, $original_order);
					$this->meta_keys_clone_from_to($order_id, $this->original_order_id);//exit;

					
				}elseif(method_exists($this, 'add_order_items')){

					// $this->merge_order_items($order);
					
				}
				
				if(!$wuoc_debug){
					$this->wuoc_update_post_meta( $order_id, '_payment_method', get_post_meta($this->original_order_id, '_payment_method', true) );
					$this->wuoc_update_post_meta( $order_id, '_payment_method_title', get_post_meta($this->original_order_id, '_payment_method_title', true) );
				}
				
				if(!$wuoc_debug){
					//$order->update_status($order_status); //('on-hold');
					
				}
				
				$order->add_order_note(__('Parent Order').' #'.$this->original_order_id.'');
				

			}

		}	
		
		public function meta_keys_clone_from_to($order_id_to=0, $order_id_from=0){
			if($order_id_from && $order_id_to){
				$order_id_to_meta = get_post_meta($order_id_to);
				$order_id_to_keys = array_keys($order_id_to_meta);
				
				$order_id_from_meta = get_post_meta($order_id_from);
				//$order_id_from_meta['wpml_language'] = array('de');
				$order_id_from_keys = array_keys($order_id_from_meta);
				
				
				
				
				
				
				$arr_diff = array_diff($order_id_from_keys, $order_id_to_keys);
				
				
				if(!empty($arr_diff)){
					foreach($arr_diff as $diff_key){
						
						if(array_key_exists($diff_key, $order_id_from_meta)){
							$diff_value = current($order_id_from_meta[$diff_key]);
							if(!in_array($diff_key, array('wuoc_order_splitter_cron', 'wuoc_update_status'))){
								update_post_meta($order_id_to, $diff_key, $diff_value);
								//wuoc_logger('debug', $order_id_to.' - '.$diff_key.' 310');
							}
						}
					}
				}
				
				$original_order = wc_get_order($order_id_from);
				$new_order = wc_get_order($order_id_to);
				
				$old_order_items = array();
				
				foreach($original_order->get_items() as $item_id=>$item_data){
					//$item_meta = wc_get_order_item_meta($item_id);
					$item_meta = $this->wuoc_get_order_item_meta($item_id);
					$item_meta['item_id'] = $item_id;

					
					$pid = $item_data->get_product_id();
					$vid = $item_data->get_variation_id();	
					
					$old_order_items[$pid][$vid] = $item_meta;
										
					
				}

				if(false){
					$new_order_items = array();
					foreach($new_order->get_items() as $item_id=>$item_data){
						$pid = $item_data->get_product_id();
						$vid = $item_data->get_variation_id();					
						
						if(!empty($old_order_items) && array_key_exists($pid, $old_order_items)){
							if(array_key_exists($vid, $old_order_items[$pid])){
								$item_meta = $old_order_items[$pid][$vid];
								foreach($item_meta as $key=>$value){			
									$value = (is_array($value)?current($value):$value);
									$existing_value = wc_get_order_item_meta($item_id, $key, true);		

									if((is_array($existing_value) && empty($existing_value)) || (!is_array($existing_value) && $existing_value=='')){
										wc_update_order_item_meta($item_id, $key, $value);
										
									}
								}
							}
						}
					}	
				}
				
				if(true){
					
	
					//exit;
					$new_order_items = array();
					$items_traces = get_post_meta($order_id_to, '_items_traces', true);
					$items_traces = maybe_unserialize($items_traces);
					
					foreach($new_order->get_items() as $item_id=>$item_data){
						
						if(array_key_exists($item_id, $items_traces)){
						
						
						
						
							$pid = $item_data->get_product_id();
							$vid = $item_data->get_variation_id();					
							
							if(!empty($old_order_items) && array_key_exists($pid, $old_order_items)){
								if(array_key_exists($vid, $old_order_items[$pid])){
									
									
									
									$item_meta = $old_order_items[$pid][$vid];
									
									$old_item_id = (array_key_exists('item_id', $item_meta)?$item_meta['item_id']:0);
									
									if(!empty($item_meta) && $old_item_id && $items_traces[$item_id]==$old_item_id){
										foreach($item_meta as $key=>$value){			
											$value = (is_array($value)?current($value):$value);			
											$existing_value = wc_get_order_item_meta($item_id, $key, true);		
											
											$consider_update = (((is_array($existing_value) && empty($existing_value)) || (!is_array($existing_value) && $existing_value=='')) && ((is_array($value) && !empty($value)) || (!is_array($value) && $value!='')));
											
											
											
											
											
											if($consider_update){
												
												wc_update_order_item_meta($item_id, $key, $value);										
												
											}
										}
									}
								}
							}
							
						}
					}
					
				}
				
			}			
		}
		
		public function wuoc_update_post_meta($post_id, $key, $val){
			//if(is_array(get_post_custom_keys($post_id)) && !in_array($key, get_post_custom_keys($post_id))){
				

				//if(!in_array($key, array('_store_credit_used','_store_credit_discounts'))){
					//$val = apply_filters('wuoc_update_post_meta_value', $post_id, $key, $val);
					update_post_meta($post_id, $key, $val);
				//}
			//}
			//wuoc_logger('debug', $post_id.' - '.$key.' 421');
		}
		


		function wuoc_get_order_item_meta($item_id){
			$obj = array();
			if($item_id){
				global $wpdb;
				$meta_query = 'SELECT * FROM `'.$wpdb->prefix.'woocommerce_order_itemmeta` WHERE order_item_id='.$item_id;
				
				$results = $wpdb->get_results($meta_query);
				
				if(!empty($results)){
					foreach($results as $result){
						$obj[$result->meta_key] = array($result->meta_value);
					}
				}
				
			}
			return $obj;
		}
		
	
		
		public function clone_order_header($order_id, $_order_total=false){
			
			
			
			if($_order_total){
				
			}else{
				
				$_order_total = get_post_meta($this->original_order_id, '_order_total', true);
				
			}
			
			
	
			update_post_meta( $order_id, '_order_shipping', get_post_meta($this->original_order_id, '_order_shipping', true) );
			update_post_meta( $order_id, '_order_discount', get_post_meta($this->original_order_id, '_order_discount', true) );
			update_post_meta( $order_id, '_cart_discount', get_post_meta($this->original_order_id, '_cart_discount', true) );
			update_post_meta( $order_id, '_order_tax', get_post_meta($this->original_order_id, '_order_tax', true) );
			update_post_meta( $order_id, '_order_shipping_tax', get_post_meta($this->original_order_id, '_order_shipping_tax', true) );
			update_post_meta( $order_id, '_order_total',  wuoc_sanitize_data($_order_total));
	
			update_post_meta( $order_id, '_order_key', 'wc_' . apply_filters('woocommerce_generate_order_key', uniqid('order_') ) );
			update_post_meta( $order_id, '_customer_user', get_post_meta($this->original_order_id, '_customer_user', true) );
			update_post_meta( $order_id, '_order_currency', get_post_meta($this->original_order_id, '_order_currency', true) );
			update_post_meta( $order_id, '_prices_include_tax', get_post_meta($this->original_order_id, '_prices_include_tax', true) );
			update_post_meta( $order_id, '_customer_ip_address', get_post_meta($this->original_order_id, '_customer_ip_address', true) );
			update_post_meta( $order_id, '_customer_user_agent', get_post_meta($this->original_order_id, '_customer_user_agent', true) );
			
	
			$_tribe_tickets_meta = get_post_meta($this->original_order_id, '_tribe_tickets_meta', true);
			if($_tribe_tickets_meta)
			update_post_meta( $order_id, '_tribe_tickets_meta', get_post_meta($this->original_order_id, '_tribe_tickets_meta', true) );
			
		}
		
		/**
		 * Duplicate Order Billing meta
		 */
		
		public function clone_order_billing($order_id){
	
			update_post_meta( $order_id, '_billing_city', get_post_meta($this->original_order_id, '_billing_city', true));
			update_post_meta( $order_id, '_billing_state', get_post_meta($this->original_order_id, '_billing_state', true));
			update_post_meta( $order_id, '_billing_postcode', get_post_meta($this->original_order_id, '_billing_postcode', true));
			update_post_meta( $order_id, '_billing_email', get_post_meta($this->original_order_id, '_billing_email', true));
			update_post_meta( $order_id, '_billing_phone', get_post_meta($this->original_order_id, '_billing_phone', true));
			update_post_meta( $order_id, '_billing_address_1', get_post_meta($this->original_order_id, '_billing_address_1', true));
			update_post_meta( $order_id, '_billing_address_2', get_post_meta($this->original_order_id, '_billing_address_2', true));
			update_post_meta( $order_id, '_billing_country', get_post_meta($this->original_order_id, '_billing_country', true));
			update_post_meta( $order_id, '_billing_first_name', get_post_meta($this->original_order_id, '_billing_first_name', true));
			update_post_meta( $order_id, '_billing_last_name', get_post_meta($this->original_order_id, '_billing_last_name', true));
			update_post_meta( $order_id, '_billing_company', get_post_meta($this->original_order_id, '_billing_company', true));
			
			do_action('clone_extra_billing_fields_hook', $order_id, $this->original_order_id);
			
		}
		
		/**
		 * Duplicate Order Shipping meta
		 */
		
		public function clone_order_shipping($order_id){
	
			update_post_meta( $order_id, '_shipping_country', get_post_meta($this->original_order_id, '_shipping_country', true));
			update_post_meta( $order_id, '_shipping_first_name', get_post_meta($this->original_order_id, '_shipping_first_name', true));
			update_post_meta( $order_id, '_shipping_last_name', get_post_meta($this->original_order_id, '_shipping_last_name', true));
			update_post_meta( $order_id, '_shipping_company', get_post_meta($this->original_order_id, '_shipping_company', true));
			update_post_meta( $order_id, '_shipping_address_1', get_post_meta($this->original_order_id, '_shipping_address_1', true));
			update_post_meta( $order_id, '_shipping_address_2', get_post_meta($this->original_order_id, '_shipping_address_2', true));
			update_post_meta( $order_id, '_shipping_city', get_post_meta($this->original_order_id, '_shipping_city', true));
			update_post_meta( $order_id, '_shipping_state', get_post_meta($this->original_order_id, '_shipping_state', true));
			update_post_meta( $order_id, '_shipping_postcode', get_post_meta($this->original_order_id, '_shipping_postcode', true));
			
			do_action('clone_extra_shipping_fields_hook', $order_id, $this->original_order_id);
		
		}
		
		
		/**
		 * Duplicate Order Fees
		 */
		
		public function clone_order_fees($order, $original_order){
	
			$fee_items = $original_order->get_fees();
	 
			if (empty($fee_items)) {
				
			} else {
				
				foreach($fee_items as $fee_key => $fee_value){
					
					$fee_item  = new WC_Order_Item_Fee();
	
					$fee_item->set_props( array(
						'name'        => $fee_item->get_name(),
						'tax_class'   => $fee_value['tax_class'],
						'tax_status'  => $fee_value['tax_status'],
						'total'       => $fee_value['total'],
						'total_tax'   => $fee_value['total_tax'],
						'taxes'       => $fee_value['taxes'],
					) );
					
					$order->add_item( $fee_item );	 
					
				}
				
			}
	   
		}
		
		/**
		 * Duplicate Order Coupon
		 */
		
		public function clone_order_coupons($order, $original_order){
	
			//$coupon_items = $original_order->get_used_coupons();
			
			$coupon_items = (method_exists($original_order, 'get_coupon_codes'))?$original_order->get_coupon_codes():'';
	
			if (empty($coupon_items)) {
				
			} else {
				
				foreach($original_order->get_items( 'coupon' ) as $coupon_key => $coupon_values){
					
					$coupon_item  = new WC_Order_Item_Coupon();
	
					$coupon_item->set_props( array(
						'name'  	   => $coupon_values['name'],
						'code'  	   => $coupon_values['code'],
						'discount'     => $coupon_values['discount'],
						'discount_tax' => $coupon_values['discount_tax'],
					) );
	
					$order->add_item( $coupon_item );	 
					
				}
				
			}
	   
		}
		

		
		/**
		 * Clone Items - v 1.3
		 */
		
		public function clone_order_items($order, $original_order){
			
			global $wuoc_pro, $yith_pre_order, $wuoc_debug;
			
			$order_id = $order->get_id();
			$order_status = $original_order->get_status();
			
					
			foreach($original_order->get_items() as $order_key => $values){
			
				if(!empty($this->exclude_items) && in_array($values['product_id'], $this->exclude_items)){ //07 January 2019 - So we can clone, slice, partially clone and/or partially split an order
					continue;
				}
				
				if(!empty($this->include_items) && !in_array($values['product_id'], $this->include_items)){ //07 January 2019 - So we can clone, slice, partially clone and/or partially split an order
					continue;
				}				
				
				
					
		
				//$order->update_status(($this->processing?'completed':$order_status));
				if($wuoc_pro){
					//START >> 05 January 2019 - THIS SECTION IS ADDED TO CONTROL DIFFERENT ORDER STATUSES WITH PRODUCT BASED META KEYS AND VALUES				
					$wuoc_rules = get_option('wuoc_rules', array());
					$wuoc_rules = is_array($wuoc_rules)?$wuoc_rules:array();				
					$meta_kv = get_post_meta($values['product_id']);
					$meta_kv = (is_array($meta_kv)?$meta_kv:array());

					
					$cross_match = array_intersect_key($wuoc_rules, $meta_kv);
					
					
					
					if(!empty($cross_match)){
						$wuoc_order_statuses = wc_get_order_statuses();
						
						$wuoc_order_statuses_keys = array_keys($wuoc_order_statuses);
						
						foreach($cross_match as $mk=>$rd){
							if(!empty($rd)){
								foreach($rd as $rk=>$rv){
									if(in_array($rv, $wuoc_order_statuses_keys)){
										$order_status = $rv;
									}
								}
							}
						}
						
						
											
						if($yith_pre_order){
							
							if(array_key_exists('_ywpo_preorder', $meta_kv)){						
								$order_status = 'wc-on-hold';
								update_post_meta( $order_id, '_order_has_preorder', $meta_kv['_ywpo_preorder'][0]);
							}

						}
					}
	
					//END << 05 January 2019 - THIS SECTION IS ADDED TO CONTROL DIFFERENT ORDER STATUSES WITH PRODUCT BASED META KEYS AND VALUES
					
				}
				
				if ($values['variation_id'] != 0) {
					$product = new WC_Product_Variation($values['variation_id']);
				
				} else {
					$product = new WC_Product($values['product_id']);	
				}
				
				$product_id = (method_exists($product, 'get_type') && $product->get_type()=='variation') ? $product->get_parent_id() : $product->get_id();
				
				$unit_price = $product->get_price();
				
				$item                       = new WC_Order_Item_Product();
				$item->legacy_values        = $values;
				$item->legacy_cart_item_key = $order_key;
				
				
				
				$product_qty = ((is_array($this->include_items_qty) && array_key_exists($product_id, $this->include_items_qty))?$this->include_items_qty[$product_id]:$values['quantity']);
				
				
				
				$item_qty_refunded = $original_order->get_qty_refunded_for_item( $order_key ); 
				$item_total_refunded = $original_order->get_total_refunded_for_item( $order_key );
				
				//$values['quantity'] -= abs($item_qty_refunded);
				//$values['total'] -= abs($item_total_refunded);
				//$values['subtotal'] -= abs($item_total_refunded);
				
				$line_price = ($values['quantity']>=1?($values['line_total']/$values['quantity']):$values['line_total']);
				
				
				
				$set_props = array(
					//'quantity'     => ($product_qty>$values['quantity']?$product_qty:$values['quantity']),
					'order_key' => $order_key,
					'quantity'     => ($product_qty<=$values['quantity']?$product_qty:$values['quantity']),//24/10/2019 because new provided qty should be within ordered qty.
					'variation'    => $values['variation'],					
					'subtotal_tax' => $values['line_subtotal_tax'],
					'total_tax'    => $values['line_tax'],
					'taxes'        => $values['line_tax_data'],
				);

								
				if($line_price!=$unit_price){
					$total = $line_price*$set_props['quantity'];
				}else{
					$total = false;
				}
				
				
				
				$set_props['subtotal'] = ($total?$total:$unit_price*$set_props['quantity']);
				$set_props['total'] = ($total?$total:$unit_price*$set_props['quantity']);
				

				$item->set_props($set_props);
				
				if ( $product ) {
					$item->set_props( array(
						'name'         => $product->get_name(),
						'tax_class'    => $product->get_tax_class(),
						'product_id'   => $product_id,
						'variation_id' => (method_exists($product, 'get_type') && $product->get_type()=='variation') ? $product->get_id() : 0,
						
					) );
				}
				

				
				//if(array_key_exists($product_id, $this->include_items_qty))
				$item->set_backorder_meta();
				
				if($product_qty){
					$order->add_item( $item );
					$order->save();
					
					//wc_delete_order_item_meta(, 'split');
				}else{

				}
			 
			}
			
			
		}
		
		
	
		
		function clone__success() {
		
			$class = 'notice notice-success is-dismissible';
			$message = __( 'Order Cloned.', 'woo-uoc' );
		
			printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
	
		}
		
	
		function clone__error() {
			$class = 'notice notice-error';
			$message = __( 'Duplication failed, an error has occurred.', 'woo-uoc' );
		
			printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
		}
		
		
		
		
		public function clone_order_shipping_items($order_id, $original_order, $qty=false){
		 	
			$wuoc_shipping_status_option = get_option('wuoc_shipping_status', '');
			
			$order = new WC_Order($order_id);
			$new_order_shipping_items = $order->get_items('shipping');
			
			$original_order_shipping_items = $original_order->get_items('shipping');
			
			$new_order_shipping_methods = array();
			
			foreach ( $new_order_shipping_items as $item_id=>$new_order_shipping_item ) {

				$method_id = $new_order_shipping_item['method_id'];
				if($method_id){
					$cost = wc_format_decimal( $new_order_shipping_item['cost'] );					
					$cost = (float)($cost?$cost:0);
					$new_order_shipping_methods[$method_id] = array('cost'=>$cost,'item_id'=>$item_id);
				}
				
			}
			
			
			
			$cost_total = 0;
			
			
			foreach ( $original_order_shipping_items as $original_order_shipping_item ) {				
			
				$cost = wc_format_decimal( $original_order_shipping_item['cost'] );
				
				if($cost && $qty){ //22/05/2019
					$qty_total = wuoc_order_total_qty($original_order);
					$per_item_cost = ($cost/$qty_total);
					$cost = ($qty*$per_item_cost);
				}
				
				$cost_total += (float)$cost;

				
				$method_id = $original_order_shipping_item['method_id'];
				
				if(!$method_id){
					continue;
				}
				
				
				$item_id = get_post_meta($order_id, '_wuoc_'.$method_id, true);
				
				
				
				
				
				if($wuoc_shipping_status_option && $wuoc_shipping_status_option!='no_shipping'){
					
					switch($wuoc_shipping_status_option){
						case 'combine_shipping':
						
							if(!$item_id){								
								$item_id = wc_add_order_item( $order_id, array(
									'order_item_name'       => $original_order_shipping_item['name'],
									'order_item_type'       => 'shipping'
								) );
						
								update_post_meta($order_id, '_wuoc_'.$method_id, $item_id);
							
							}
							
							$existing_cost = get_post_meta($order_id, '_wuoc_'.$method_id.'_shipping', true);
							$existing_cost = ($existing_cost>0?$existing_cost:0);
							
							$cost = (is_numeric($cost)?$cost:(float)$cost);
							
							$cost += (float)$existing_cost;
							
							update_post_meta($order_id, '_wuoc_'.$method_id.'_shipping', $cost);
							
							if($cost>0){
								wc_update_order_item_meta( $item_id, 'method_id', $method_id );
								wc_update_order_item_meta( $item_id, 'cost',  $cost );		
							}
						
						break;
						case 'separate_shipping':	
							

							
							if($cost>0){
								$item_id = wc_add_order_item( $order_id, array(
									'order_item_name'       => $original_order_shipping_item['name'],
									'order_item_type'       => 'shipping'
								) );
						
							
								wc_add_order_item_meta( $item_id, 'method_id', $method_id );
								wc_add_order_item_meta( $item_id, 'cost',  $cost );						
							}
						
						break;
						case 'highest_shipping':
						
							if(!$item_id){								
								$item_id = wc_add_order_item( $order_id, array(
									'order_item_name'       => $original_order_shipping_item['name'],
									'order_item_type'       => 'shipping'
								) );
						
								update_post_meta($order_id, '_wuoc_'.$method_id, $item_id);							
							}
							
							if($cost_total>0){
								
								
								$existing_cost = get_post_meta($order_id, '_wuoc_'.$method_id.'_shipping', true);
								$existing_cost = ($existing_cost>0?$existing_cost:0);
								
								$cost = ($cost_total>$existing_cost?$cost_total:$existing_cost);
							
								update_post_meta($order_id, '_wuoc_'.$method_id.'_shipping', $cost);
								
								wc_update_order_item_meta( $item_id, 'method_id', $method_id );
								wc_update_order_item_meta( $item_id, 'cost',  $cost );		
							}
							
						
						break;					
					}					
				}
				
			}
			
			
			
	
			
		}
		   
	}
	
	new wuoc_order_splitter;




	function wuoc_settings(){ 



		if ( !current_user_can( 'manage_woocommerce' ) )  {



			wp_die( __( 'You do not have sufficient permissions to access this page.', 'woo-uoc' ) );



		}



		global $wpdb; 

		
		$css_arr = array();
				
		include_once(realpath(WUOC_PLUGIN_DIR.'/inc/wuoc_settings.php'));	

	
	}
	
	
	
		
	function wuoc_settings_refresh(){
		global $wuoc_settings;
		$wuoc_settings = get_option('wuoc_settings', array());		
		$wuoc_settings['wuoc_additional'] = (isset($wuoc_settings['wuoc_additional']) && is_array($wuoc_settings['wuoc_additional']))?$wuoc_settings['wuoc_additional']:array();		
	}
	
	function wuoc_array_unique_recursive($array)
	{
		$array = array_unique($array, SORT_REGULAR);
	
		foreach ($array as $key => $elem) {
			if (is_array($elem)) {
				$array[$key] = wuoc_array_unique_recursive($elem);
			}
		}
	
		return $array;
	}	

	
	
	
	
	
	function wuoc_init(){
		
		global $wuoc_currency, $wuoc_activated;
		
		
		
		if(!$wuoc_activated)
		return;
		
		
		$wuoc_currency = get_woocommerce_currency_symbol();
		wuoc_settings_refresh();
		
		
		
		
	}
	
		
	
	

	function wuoc_front_scripts() {	
		/*wp_enqueue_script(
			'wuoc_scripts',
			plugins_url('js/front-scripts.js', dirname(__FILE__)),
			array('jquery'),
			time()
		);	*/
	}

    function wuoc_admin_scripts_wos_dependent() {


	    global $typenow;

        if($typenow == 'shop_order'){

            wp_enqueue_script(
                'wuoc_wos_dependent_scripts',
                plugins_url('js/wos-dependent-scripts.js', dirname(__FILE__)),
                array('jquery'),
                time()
            );

            wp_register_style('wuoc_wos_dependent-styles', plugins_url('css/wos-dependent-styles.css?t='.time(), dirname(__FILE__)));
            wp_enqueue_style( 'wuoc_wos_dependent-styles' );


        }



    }
		
	function wuoc_admin_scripts() {

        global $typenow, $wuoc_crons_options, $pagenow, $wuoc_pro, $post;
		
		$current_screen = get_current_screen();

		
		wp_register_style('wuos-bootstrap', plugins_url('css/bootstrap.min.css?t='.time(), dirname(__FILE__)));
		/*wp_register_script('wuoc_bootstrap_script', 
			plugins_url('js/bootstrap.min.js', dirname(__FILE__)),
			array ('jquery', 'jquery-ui'),                  //depends on these, however, they are registered by core already, so no need to enqueue them.
			time()
		);*/
		
		$is_orders_list = ($current_screen->post_type=='shop_order');
		$is_edit_order_page = ($pagenow=='post.php' && is_object($post));
		
				
		$crons_button_display = (array_key_exists('button_display', $wuoc_crons_options)?$wuoc_crons_options['button_display']:array());
		$crons_clock_settings = (array_key_exists('clock', $wuoc_crons_options)?$wuoc_crons_options['clock']:array());
		//wuoc_pree($crons_clock_settings);
		$translation_array = array(
										'is_pro' => $wuoc_pro,
										
										'combined_info' => '',
										
										'wuoc_filter_by_meta_key' => get_option('wuoc_filter_by_meta_key', 0),
										
										'wuoc_sort_order_items_by_category' => get_option('wuoc_sort_order_items_by_category', 0),
										
										'is_orders_list' => $is_orders_list,
										
										'crons' => array(
															'button_display'=>$crons_button_display, 
															'url'=>(in_array('controls', $crons_button_display)?admin_url():home_url()).'?wuoc_crons', 
															'button_text'=>__('Combine Orders Cron Job', 'woo-uoc'), 
															'button_title'=>__('Click here to execute combine orders cron job', 'woo-uoc'),
															
													),
													
										
										
										'meta_filters' => array(
										
															'key_title' => __('Order meta keys - custom fields section', 'woo-uoc'),
															'key_placeholder' => __('Enter order meta key', 'woo-uoc'),
															'key_name' => '_meta_key',
															'key_value' => (isset($_GET['_meta_key'])?wuoc_sanitize_data($_GET['_meta_key']):''),

															'val_title' => __('Order meta keys value - custom fields section', 'woo-uoc'),
															'val_placeholder' => __('Enter order meta value', 'woo-uoc'),
															'val_name' => '_meta_value',
															'val_value' => (isset($_GET['_meta_value'])?wuoc_sanitize_data($_GET['_meta_value']):''),
															
														),	
										'products_terms_order' => array(),		
								);
								
		if($is_edit_order_page){
			$order_id = $post->ID;
			$po_number = get_post_meta($order_id, 'po_number', true);
			$po_number = is_array($po_number)?$po_number:($po_number?array($po_number):array());
			
			if(!empty($po_number)){
				$translation_array['combined_info'] .= '<li><b>'.__('Ordered by / PO#', 'woo-uoc').':</b> '.implode(', ', $po_number).'</li>';
			}
			
			$_wuoc_merged_orders = get_post_meta($order_id, '_wuoc_merged_orders', true);
			$_wuoc_merged_orders = is_array($_wuoc_merged_orders)?$_wuoc_merged_orders:array();
			
			if(!empty($_wuoc_merged_orders)){				
				$translation_array['combined_info'] .= '<li><b>'.__('This invoice is a combination of', 'woo-uoc').':</b> '.implode(', ', $_wuoc_merged_orders).'</li>';
			}			
		}
		
        if($typenow == 'shop_order'){
			
			global $post;
			
			$order_id = (is_object($post)?$post->ID:0);
			$products_terms_order = array();
			if(is_numeric($order_id) && $order_id>0){
				$order_obj = wc_get_order($order_id);
				
				if(is_object($order_obj) && isset($order_obj->get_items)){
					foreach($order_obj->get_items() as $item_key=>$item_val){
						$product_id = $item_val->get_product_id();
						$terms = get_the_terms ( $product_id, 'product_cat' );
						if(!empty($terms)){
							$product_cats = array();
							foreach($terms as $term){
								$product_cats[] = $term->name;
							}
							$products_terms_order[$item_key] = implode(', ', $product_cats);
						}
					}
					if(!empty($products_terms_order)){
						$translation_array['products_terms_order'] = $products_terms_order;
					}
					}
			}
			
			//wuoc_pree($products_terms_order);exit;

            wp_enqueue_script(
                'wuoc_order_shop_scripts',
                plugins_url('js/shop-order-script.js', dirname(__FILE__)),
                array('jquery'),
                time()
            );
			
			
			wp_localize_script( 'wuoc_order_shop_scripts', 'wuoc_obj', $translation_array );
			

            wp_register_style('wuoc_order_shop-styles', plugins_url('css/shop-order-style.css?t='.time(), dirname(__FILE__)));
            wp_enqueue_style( 'wuoc_order_shop-styles' );
			
			if(get_option('wuoc_bootstrap', 0) && false){
				wp_enqueue_style( 'wuos-bootstrap' );
				
				//wp_enqueue_script('wuoc_bootstrap_script');
				
				wp_enqueue_script(
					'wuoc_bootstrap_script',
					plugins_url('js/bootstrap.min.js', dirname(__FILE__)),
					array('jquery'),
					time()
				);	
				
				
			}

        }
		
		

	    if(isset($_GET['page']) && $_GET['page']=='wuoc-settings'){

            
            
			
            wp_enqueue_script('wuos_slim', plugins_url('js/slimselect.js', dirname(__FILE__)), array('jquery'));
            wp_enqueue_style('wuos-slim', plugins_url('css/slimselect.css', dirname(__FILE__)));
			wp_enqueue_style('wuos-timepicker', plugins_url('css/jquery.timepicker.min.css', dirname(__FILE__)));


            wp_enqueue_style( 'wuos-bootstrap' );
			
			wp_enqueue_script('wuos-fontawesome-js', plugin_dir_url(dirname(__FILE__)) . 'js/fontawesome.min.js', array('jquery'));

            //wp_enqueue_script('wuoc_bootstrap_script');

            wp_enqueue_script(
                'wuoc_jquery_ui',
                plugins_url('js/jquery-ui-blockui.min.js', dirname(__FILE__)),
                array('jquery'),
                time()
            );

            wp_enqueue_script(
                'wuoc_scripts',
                plugins_url('js/admin-scripts.js', dirname(__FILE__)),
                array('jquery', 'jquery-effects-core', 'jquery-ui-core'),
                time()
            );
			wp_enqueue_script(
                'wuoc_bootstrap_script',
                plugins_url('js/bootstrap.min.js', dirname(__FILE__)),
                array('jquery'),
                time()
            );
			
			wp_enqueue_script(
                'wuoc_timepicker',
                plugins_url('js/jquery.timepicker.min.js', dirname(__FILE__)),
                array('jquery'),
                time()
            );



            $translation_array = array(
				
                'this_url' => admin_url( 'admin.php?page=wuoc-settings' ),
                'delete_confirmation' => __('Are you sure, you want to delete the merged orders permanently which belong to the selected orders?', 'woo-uoc'),
                'wuoc_tab' => (isset($_GET['t'])?wuoc_sanitize_data($_GET['t']):'0'),
                'analyze_orders' => isset($_POST['wuoc_analyze_orders'])?'true':'false',
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'wuoc_nonce' ),
				'no_results'   => __('No results found.', 'woo-uoc'),
				'crons' => array(
									'clock'=>$crons_clock_settings,
						),

            );

			

            wp_localize_script( 'wuoc_scripts', 'wuoc_obj', $translation_array );


        }
		
		if(
				(isset($_GET['page']) && $_GET['page']=='wuoc-settings')
			||
				($typenow == 'shop_order')
		){
			wp_enqueue_style('wuos-admin', plugins_url('css/admin-style.css?t='.time(), dirname(__FILE__)));
			wp_enqueue_style('wuos-fontawesome', plugins_url('css/fontawesome.min.css', dirname(__FILE__)));
		}
		
		/*	

		if(isset($_GET['action']) && $_GET['action']=='edit'){
			$current_screen = get_current_screen();
			if($current_screen['post_type']=='shop_order'){
				wp_enqueue_style( 'wuos-bootstrap' );
			}
		}
		
		*/
		
		
	}		
	
	
	function wuoc_header_scripts(){
		//global $post;
?>
	<style type="text/css">

	</style>
<?php		
	}
	
			
	
	function wuoc_order_cloning(){
		wuoc_settings_refresh();
		global $wuoc_settings;
		
		$cloning = (in_array('cloning', $wuoc_settings['wuoc_additional']));		
		
		return $cloning;
	}
	
	function wuoc_order_qty_split(){
		wuoc_settings_refresh();
		global $wuoc_settings;
		
		$qty_split = (in_array('qty_split', $wuoc_settings['wuoc_additional']));
		
		return $qty_split;		
	}	
	
	function wuoc_links($actions, $post){
		

	
	
	
		return $actions;
				
	}
	
	add_filter( 'post_row_actions', 'wuoc_links', 10, 2 );
	

	
	//add_action( 'woocommerce_order_action_wc_custom_order_action', 'sv_wc_process_order_meta_box_action' );
	function wuoc_order_total_qty($order){
		$qty = 0;		
		foreach($order->get_items() as $item_id=>$item_data){
		
			$qty += $item_data->get_quantity();
			
		}
		
		return $qty;
	}
	
	
	
	function wuoc_admin_head(){
		
		global $wuoc_url, $post, $pagenow;
		
		$order_received = '';
		
		$wuoc_view_order_button = get_option('wuoc_view_order_button', 0);
		
		if($wuoc_view_order_button && $pagenow=='post.php' && is_object($post) && $post->post_type=='shop_order'){

			$wc_order = wc_get_order($post->ID);
		
			$order_received = wc_get_checkout_url().'order-received/'.$post->ID.'/?key='.$wc_order->get_order_key();
			
		}
		
		
		
?>

	<style type="text/css">
	
		
		li.current a[href="admin.php?page=wuoc-settings"], 
		li.current a[href="admin.php?page=wuoc-settings"]:hover {
			background-color: #fff !important;
			color: #32373c !important;
			background-image:url("<?php echo $wuoc_url; ?>img/woo.png") !important;
			background-size: 18px !important;
			background-repeat: no-repeat !important;
			background-position: 4px 10px !important;
			text-indent: 14px !important;
			font-size: 12px !important;
		}
				
		li.current a[href="admin.php?page=wuoc-settings"]:hover {
			background-color: #EFEFEF !important;
			color: #1B1B1B !important;
		}
		
		@media only screen and (max-device-width: 480px) {
			
			
		}			
		
		/* ipad */
		@media only screen 
		and (min-device-width : 768px) 
		and (max-device-width : 1024px)  {
		}
		
		@media all and (-ms-high-contrast: none), (-ms-high-contrast: active) {

		}
		@supports (-ms-accelerator:true) {
		  /* IE Edge 12+ CSS styles go here */ 
		}				
	</style>
    <script type="text/javascript" language="javascript">
		jQuery(document).ready(function($){
			<?php if(isset($_GET['mt']) && isset($_GET['post'])): ?>
			if($('.woocommerce-order-data__heading').length>0){
				$('.woocommerce-order-data__heading').html('Order #<?php echo esc_attr($_GET['mt']); ?>');
			}
			<?php endif; ?>
			
			<?php if($order_received): ?>
			setTimeout(function(){
				$('<a href="<?php echo $order_received; ?>" class="page-title-action" target="_blank"><?php echo _e('View order', 'woocommerce'); ?></a>').insertAfter($('a.page-title-action').last());
			}, 1000);
			<?php endif; ?>
		});
	</script>
<?php		
		
	}
	
	







	function wuoc_update_lookup_tables($order_id, $table_name_without_prefix){

        global $wpdb;

        $wuoc_cloned_order =  get_post_meta($order_id, '_wuoc_cloned_order', true);
        $force_sync =  get_post_meta($order_id, '_wuoc_force_sync', true);


        if($wuoc_cloned_order && !$force_sync){

            $prodcut_lookup_table = $wpdb->prefix.$table_name_without_prefix;
            $query_product = "DELETE FROM $prodcut_lookup_table WHERE order_id = $order_id";
            $wpdb->query($query_product);


        }

    }

	function wuoc_update_order_lookup_tables ($order_id){

	    global $wpdb;

        $wuoc_cloned_order =  get_post_meta($order_id, '_wuoc_cloned_order', true);
        $force_sync =  get_post_meta($order_id, '_wuoc_force_sync', true);

        if($wuoc_cloned_order && !$force_sync){



            $order_stats_table = $wpdb->prefix.'wc_order_stats';
            $query_order = "DELETE FROM $order_stats_table WHERE order_id = $order_id";
            $wpdb->query($query_order);

        }

        ReportsCache::invalidate();


    }

    function wuoc_update_product_lookup_tables ($order_item_id, $order_id){

        wuoc_update_lookup_tables($order_id, 'wc_order_product_lookup');

        ReportsCache::invalidate();

    }

    function wuoc_update_coupon_lookup_tables ($coupon_id, $order_id){

        wuoc_update_lookup_tables($order_id, 'wc_order_coupon_lookup');

        ReportsCache::invalidate();

    }

    function wuoc_update_tax_lookup_tables ($tax_item_rate_id, $order_id){

        wuoc_update_lookup_tables($order_id, 'wc_order_tax_lookup');

        ReportsCache::invalidate();

    }
	
	
	
	function wuoc_reports_get_order_report_query($query){
		global $wpdb;
		
		$query['join'] .= " LEFT JOIN $wpdb->postmeta as wuoc_postmeta ON (posts.ID = wuoc_postmeta.post_id AND wuoc_postmeta.meta_key = '_wuoc_report_included' ) ";
		$query['where'] .= " AND ((wuoc_postmeta.meta_key IN ('_wuoc_report_included') AND wuoc_postmeta.meta_value = 'yes') OR wuoc_postmeta.post_id IS NULL) ";
		return $query;
	
	}

	

	add_action('wp_ajax_wuoc_logger_clear_log', 'wuoc_logger_clear_log');
	
	if(!function_exists('wuoc_logger_clear_log')){
		function wuoc_logger_clear_log(){
	
			if(!empty($_POST) && isset($_POST['wuoc_logger_clear_log'])){
	
				if (
					! isset( $_POST['wuoc_logger_clear_log_field'] )
					|| ! wp_verify_nonce( $_POST['wuoc_logger_clear_log_field'], 'wuoc_nonce' )
				) {
	
					_e('Sorry, your nonce did not verify.', 'woo-uoc');
					exit;
	
				} else {
					
					update_option('wuoc_logger', array());
	
				}
			}
	
			wp_die();
		}
	}
	
	
	
	add_action('wp_ajax_wuoc_clear_meta_data', 'wuoc_clear_meta_data');
	
	if(!function_exists('wuoc_clear_meta_data')){
		function wuoc_clear_meta_data(){
			
			
			if(!empty($_POST) && isset($_POST['order_ids'])){
	
				if (
					! isset( $_POST['nonce_field'] )
					|| ! wp_verify_nonce( $_POST['nonce_field'], 'wuoc_nonce' )
				) {
	
					_e('Sorry, your nonce did not verify.', 'woo-uoc');
					exit;
	
				} else {
					
					global $wpdb;
					
					$order_ids = wuoc_sanitize_data($_POST['order_ids']);
					$order_ids = explode(',', $order_ids);
					$order_ids = array_filter($order_ids);
					$order_ids = array_map(function($v){ return (is_numeric($v)?$v:''); }, $order_ids);
					$order_ids = array_filter($order_ids);
					
					$clear_query = "DELETE FROM $wpdb->postmeta WHERE post_id IN (".implode(',', $order_ids).") AND meta_key LIKE '%wuoc%'";
					$wpdb->query($clear_query);
					//wuoc_pree($order_ids);
					
					exit;
				}
			}
			
		}
	}
	
	add_action('wp_ajax_wuoc_update_rules_layers', 'wuoc_update_rules_layers');
	
	if(!function_exists('wuoc_update_rules_layers')){
		function wuoc_update_rules_layers(){
			
			
			if(!empty($_POST) && isset($_POST['wuoc_rules_layers'])){
	
				if (
					! isset( $_POST['nonce_field'] )
					|| ! wp_verify_nonce( $_POST['nonce_field'], 'wuoc_nonce' )
				) {
	
					_e('Sorry, your nonce did not verify.', 'woo-uoc');
					exit;
	
				} else {
					$wuoc_auto_combined_settings = get_option('wuoc_auto_combined_settings', array());
					
					$wuoc_rules_layers = $_POST['wuoc_rules_layers'];
					if(is_numeric($wuoc_rules_layers) && $wuoc_rules_layers>0){
						for($layer=1; $layer<=$wuoc_rules_layers; $layer++){
							if(!array_key_exists($layer, $wuoc_auto_combined_settings)){
								$wuoc_auto_combined_settings[$layer] = array(
								
									'auto_combine' => 0,
									'statuses' => array(),
									'status_combined' => '',
									'remove_original' => 'none',
									'original_order_status' => '',
									'rules' => array(),
									'consider_paid_within' => '',
									
									
								);
							}
						}
					}
					update_option('wuoc_auto_combined_settings', $wuoc_auto_combined_settings);
					//wuoc_pree($wuoc_auto_combined_settings);exit;
					update_option('wuoc_rule_layers', wuoc_sanitize_data($_POST['wuoc_rules_layers']));
				}
			}
		}
	}
	
	add_action('wp_ajax_wuoc_load_combined_orders', 'wuoc_load_combined_orders');
	
	if(!function_exists('wuoc_load_combined_orders')){
		function wuoc_load_combined_orders(){
			
			
			if(!empty($_POST) && isset($_POST['wuoc_load_combined_orders'])){
	
				if (
					! isset( $_POST['nonce_field'] )
					|| ! wp_verify_nonce( $_POST['nonce_field'], 'wuoc_nonce' )
				) {
	
					_e('Sorry, your nonce did not verify.', 'woo-uoc');
					exit;
	
				} else {
					
					if(function_exists('wuoc_get_combined_orders_html')){
						
						wuoc_get_combined_orders_html();
						
						
					}
	
				}
			}

			wp_die();
		}
	}
	
	add_action( 'transition_post_status', 'wuoc_status_transition', 99, 3 );
	function wuoc_status_transition( $new, $old, $post ) {

		global $wpdb, $wuoc_auto_combined_settings;
		
		$order_id = (is_object($post)?$post->ID:$post);
		
		$_wuoc_original_order_status = '';
		
		//wuoc_logger('debug', 'is_numeric: '.is_numeric($order_id).': '.$post->ID);
		
		if(is_numeric($order_id)){
			
			$_wuoc_original_order_status = get_post_meta($order_id, '_wuoc_original_order_status', true);
			$_wuoc_layer = get_post_meta($order_id, '_wuoc_layer', true);
			$_wuoc_layer = false;//($_wuoc_layer?$_wuoc_layer:1);
			
			//wuoc_logger('debug', '$order_id: '.$order_id.': '.$_wuoc_layer);
			
			if(!$_wuoc_original_order_status && $_wuoc_layer && array_key_exists($_wuoc_layer, $wuoc_auto_combined_settings)){
				
				$_wuoc_original_order_status = $wuoc_auto_combined_settings[$_wuoc_layer]['original_order_status'];
				
				//wuoc_logger('debug', '$_wuoc_original_order_status: '.$_wuoc_original_order_status);
				
			}
			
		}



		$status = ($_wuoc_original_order_status?$_wuoc_original_order_status:$new);
		//wuoc_pree($status);wuoc_pree($post->post_type);wuoc_pree($_wuoc_original_order_status.'!='.$new);exit;
		//wuoc_logger('debug', $post->ID.': '.$new.' - '.$old.' - '.$_wuoc_original_order_status);
		
		if ( $post->post_type == 'shop_order' &&  $_wuoc_original_order_status!='' && $_wuoc_original_order_status!=$new) {
			// do stuff
			$order_id = $post->ID;
			$status = wuoc_add_prefix($status, 'wc-');
			$update_query = "UPDATE $wpdb->posts set post_status = '$status' WHERE ID = $order_id";
			$wpdb->query($update_query);
				
			//wuoc_logger('debug', $post->ID.': '.$new.' - '.$old.' - '.$_wuoc_original_order_status);
			delete_post_meta($order_id, '_wuoc_original_order_status'); //06/12/2022
			
		} else {
			return;
		}
	}
	
	add_filter('wuoc_update_post_meta_value', 'wuoc_update_post_meta_value_callback', 9, 4);
	
	function wuoc_update_post_meta_value_callback($order_id=0, $meta_key='', $meta_val='', $force=false){
		
		global $is_automation_combine;
		
		if($is_automation_combine && $force){
			
			if($order_id>0 && $meta_key!=''){
				
				if(substr($meta_key, 0, 1)!='_'){
					$meta_vals = get_post_meta($order_id, '__'.$meta_key, true);
					$meta_vals = (is_array($meta_vals)?$meta_vals:array());					
					$meta_vals[] = (is_array($meta_val)?$meta_val:array($order_id=>$meta_val));
					
					if(!empty($meta_vals)){
						foreach($meta_vals as $meta_val_key=>$meta_val_arr){
							if(is_array($meta_val_arr)){
								unset($meta_vals[$meta_val_key]);
								$meta_vals = array_merge($meta_vals, $meta_val_arr);
							}
						}
					}
					
					$meta_vals = array_unique($meta_vals);
					$meta_key_ = ltrim($meta_key, '_');
					$meta_key_ = str_replace(array('___', '__'), '', $meta_key_);
					//$meta_key_ = ltrim($meta_key_, '_');
					//wuoc_logger('debug', '#'.$order_id.'  -  '.$meta_key.' - '.$meta_key_);
					update_post_meta($order_id, '__'.$meta_key_, $meta_vals);
				}
				
			}
			
		}
		
		return $meta_val;
	}