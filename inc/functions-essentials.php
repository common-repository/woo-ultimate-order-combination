<?php
	if(!function_exists('wuoc_db_tables')){
		function wuoc_db_tables(){
			$tbl_arr = array();
			global $wpdb;
			$result = $wpdb->get_results("SHOW TABLES");
			if(!empty($result)){
				foreach($result as $table){
					$tbl_arr[] = current((array)$table);
				}
			}
			
			return $tbl_arr;
		}
	}
	if(!function_exists('wuoc_table_exists')){
		function wuoc_table_exists($tbl=''){
			
			global $wpdb;
			
			$db_tables = wuoc_db_tables();
			
			return in_array($wpdb->prefix.$tbl, $db_tables);
		}
	}
	if(!function_exists('wuoc_sanitize_data')){
		function wuoc_sanitize_data( $input ) {
			if(is_array($input)){		
				$new_input = array();	
				foreach ( $input as $key => $val ) {
					$new_input[ $key ] = (is_array($val)?wuoc_sanitize_data($val):sanitize_text_field( $val ));
				}			
			}else{
				$new_input = sanitize_text_field($input);			
				if(stripos($new_input, '@') && is_email($new_input)){
					$new_input = sanitize_email($new_input);
				}
				if(stripos($new_input, 'http') || wp_http_validate_url($new_input)){
					$new_input = esc_url_raw($new_input);
				}			
			}	
			return $new_input;
		}	
	}	
	if(!function_exists('wuoc_pre')){
	function wuoc_pre($data){
			if(isset($_GET['debug'])){
				wuoc_pree($data);
			}
		}	 
	} 	
	if(!function_exists('wuoc_pree')){
	function wuoc_pree($data){
				echo '<pre>';
				print_r($data);
				echo '</pre>';	
		
		}	 
	} 
	if(!function_exists('wuoc_admin_menu')){
		function wuoc_admin_menu()
		{
			global $wuoc_data;
			$title = str_replace('WooCommerce', 'WC', $wuoc_data['Name']);
			add_submenu_page('woocommerce', $title, __('Orders Combination', 'woo-uoc'), 'manage_options', 'wuoc-settings', 'wuoc_settings' );
	
		}
		add_action( 'admin_menu', 'wuoc_admin_menu' );
		
	}
	if(!function_exists('wuoc_plugin_linx')){
		function wuoc_plugin_linx($links) { 

			global $wuoc_premium_copy, $wuoc_pro;
			$settings_link = '<a href="admin.php?page=wuoc-settings">'.__('Settings', 'woo-uoc').'</a>';
			if($wuoc_pro){
				array_unshift($links, $settings_link); 
			}else{
				$wuoc_premium_link = '<a href="'.esc_url($wuoc_premium_copy).'" title="'.__('Go Premium', 'woo-uoc').'" target="_blank">'.__('Go Premium', 'woo-uoc').'</a>'; 
				array_unshift($links, $settings_link, $wuoc_premium_link); 
			}
			return $links; 
		}
	}
	add_filter( 'woocommerce_can_reduce_order_stock', 'wuoc_filter_woocommerce_can_reduce_order_stock', 10, 2 );	
	
	function wuoc_woocommerce_can_reduce_order_stock_inner($order_id=0){
		$wuoc_processed_order = true;
		
		$post_meta = get_post_meta($order_id);			
		if(!empty($post_meta)){			
			$post_meta = array_keys($post_meta);
			$wuoc_processed_order = (
			
										in_array('_wuoc_merged_orders', $post_meta) 
										|| 
										in_array('_wuoc_cloned_order', $post_meta) 
										|| 
										in_array('cloned_from', $post_meta) 
										|| 
										in_array('splitted_from', $post_meta) 
										|| 
										in_array('split_status', $post_meta)
									);
		}
		//wuoc_logger('debug', '#'.$order_id.' ~ '.$wuoc_processed_order);
		return $wuoc_processed_order;
	}
										
	if(!function_exists('wuoc_filter_woocommerce_can_reduce_order_stock')){
		function wuoc_filter_woocommerce_can_reduce_order_stock($return, $order){
			$order_id = $order->get_id();			
			
			$wuoc_processed_order = wuoc_woocommerce_can_reduce_order_stock_inner($order_id);
			
			if($wuoc_processed_order){
				$return = false;
			}
				
			
			return $return;
		}
	}
	if(!function_exists('wuoc_logger')){
		function wuoc_logger($type='debug', $data=array()){
			
			$types = array('debug');
			
			if(is_array($type) || is_object($type)){
				$data = (array)$type;
				$type = 'debug';
			}else{
				if(!array_key_exists($type, $types) && empty($data)){
					$data = $type;
					$type = 'debug';
				}
			}
			$wuoc_logger = get_option('wuoc_logger');
					
			$wuoc_logger = is_array($wuoc_logger)?$wuoc_logger:array();
			
			
			if(empty($data) || $data==$type){ return $wuoc_logger; }
			
			
			
			
			$debug_backtrace = debug_backtrace();
			$function = $debug_backtrace[1]['function'];
			$function .= (array_key_exists(2, $debug_backtrace)?' / '.$debug_backtrace[2]['function']:'');
			$function .= (array_key_exists(3, $debug_backtrace)?' / '.$debug_backtrace[3]['function']:'');
			$function .= (array_key_exists(4, $debug_backtrace)?' / '.$debug_backtrace[4]['function']:'');
			$function .= (array_key_exists(5, $debug_backtrace)?' / '.$debug_backtrace[5]['function']:'');
			
			switch($type){
				case 'debug':
					$data = (is_object($data)?(array)$data:$data);

					
					if(is_array($data) && !empty($data)){
						$wuoc_logger[] = $data;
						$wuoc_logger[] = '<small>('.$function.')</small> - '.date('d M, Y h:i:s A');
						
						update_option('wuoc_logger', $wuoc_logger);
					}else{				
						$wuoc_logger[] = $data.' <small>('.$function.')</small> - '.date('d M, Y h:i:s A');
						if(trim($data)){
							update_option('wuoc_logger', $wuoc_logger);
						
						}
						
					}
					
					
				break;
			}

			return $wuoc_logger;
		}
	}
	add_filter( 'woocommerce_prevent_adjust_line_item_product_stock', function($prevent=false){
		global $post;
		
		$is_order_page = (is_object($post) && !empty($post) && isset($post->post_type) && $post->post_type=='shop_order');
		if(!$is_order_page && is_array($_POST) && !empty($_POST) && array_key_exists('order_id', $_POST)){			
			$get_post = get_post($_POST['order_id']);
			if(is_object($get_post) && !empty($get_post)){
				$post = $get_post;				
			}
		}
		
		if(is_object($post) && !empty($post) && isset($post->post_type) && $post->post_type=='shop_order'){
			$wuoc_processed_order = wuoc_woocommerce_can_reduce_order_stock_inner($post->ID);			
			if($wuoc_processed_order){
				$prevent = true;
			}
		}
		
		
		
		return $prevent;
	});		