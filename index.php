<?php if ( ! defined( 'ABSPATH' ) ) exit; 
/*
	Plugin Name: Ultimate Order Combination
	Plugin URI: https://wordpress.org/plugins/woo-ultimate-order-combination
	Description: Using WooCommerce in combination with this plugin you can combine, merge, consolidate orders.
	Version: 1.8.7
	Author: Fahad Mahmood
	Author URI: http://androidbubble.com/blog/
	Text Domain: woo-uoc
	Domain Path: /languages/	
	License: GPL2
	
	
	This WordPress plugin is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or any later version. This WordPress plugin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details. You should have received a copy of the GNU General Public License along with this WordPress plugin. If not, see http://www.gnu.org/licenses/gpl-2.0.html.
*/


	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly
	}else{
		 clearstatcache();
	}
	
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	

	
	$wuoc_all_plugins = get_plugins();
	$wuoc_active_plugins = get_site_option( 'active_sitewide_plugins' );
	$wuoc_active_plugins = is_array($wuoc_active_plugins)?$wuoc_active_plugins:array();
	$wuoc_network_active_plugins = is_array($wuoc_active_plugins)?apply_filters( 'active_plugins', array_keys($wuoc_active_plugins) ):array();
	$wuoc_active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );

	$wuoc_woocommerce = ( 
			array_key_exists('woocommerce/woocommerce.php', $wuoc_all_plugins) 
		&& 
			(
					(is_multisite() && 	in_array('woocommerce/woocommerce.php', $wuoc_network_active_plugins))
				||
					in_array('woocommerce/woocommerce.php', $wuoc_active_plugins) 
			)
			
	);
	
	
	
	if ($wuoc_woocommerce) {
		
		define( 'WUOC_PLUGIN_DIR', dirname( __FILE__ ) );
		
		global $wuoc_url, $wuoc_data, $wuoc_pro, $wuoc_premium_copy, $wuoc_bulk_instantiated, $wuoc_activated, $is_wos_installed, $typenow, $wuoc_crons_options;
		global $is_automation_combine, $wuoc_auto_combined_settings, $wuoc_os_gf, $current_screen, $wuoc_is_order, $wuoc_controlled, $wuoc_custom_orders_table_enabled;
		global $wuoc_orderslist_page_cron;
		$wuoc_is_order = false;
		$wuoc_premium_copy = 'https://shop.androidbubbles.com/product/ultimate-order-combination';
		$wuoc_data = get_plugin_data(__FILE__);
		$wuoc_url = plugin_dir_url(__FILE__);		
		$wuoc_pro_file = WUOC_PLUGIN_DIR . '/pro/wuoc-pro.php';
		$wuoc_pro =  file_exists($wuoc_pro_file);
		$wuoc_controlled = false;
		$wuoc_orderslist_page_cron = get_option('wuoc_orderslist_page_cron', 0);		
		
		require_once(realpath(WUOC_PLUGIN_DIR . '/inc/functions-essentials.php'));
		
		$wuoc_crons_options = get_option('wuoc_crons_options');
		$wuoc_crons_options = (is_array($wuoc_crons_options)?$wuoc_crons_options:array());
		
		$wuoc_custom_orders_table_enabled = (get_option('woocommerce_custom_orders_table_enabled', true)=='yes');
		
		if(function_exists('wuoc_plugin_linx')){
			$plugin = plugin_basename(__FILE__); 
			add_filter("plugin_action_links_$plugin", 'wuoc_plugin_linx' );	
		}
		
		$typenow = ((isset($_GET['post_type']) && $_GET['post_type']=='shop_order')?'shop_order':'');
		
		$is_wuoc_shop_order = ($typenow=='shop_order');
		$is_wuoc_settings_page = (isset($_GET['page']) && $_GET['page']=='wuoc-settings');
		$is_edit_order_page = (isset($_GET['post']) && is_numeric($_GET['post']) && array_key_exists('SCRIPT_NAME', $_SERVER) && substr($_SERVER['SCRIPT_NAME'], -strlen('/wp-admin/post.php'), strlen('/wp-admin/post.php'))=='/wp-admin/post.php');
		
		//(array_key_exists('PHP_SELF', $_SERVER) && ($_SERVER['SCRIPT_NAME']=='/wp-admin/admin-ajax.php' || strpos($_SERVER['PHP_SELF'], 'admin-ajax.php')>=0))
		
		if($is_edit_order_page){
			$order_id = wuoc_sanitize_data($_GET['post']);
			$is_order_obj = get_post($order_id);
			if(is_object($is_order_obj) && property_exists($is_order_obj, 'post_type')){
				$wuoc_is_order = ($is_order_obj->post_type=='shop_order');
			}
		}
		
		
		
		if(
				(
						(is_admin() && (!$is_wuoc_shop_order && !$is_wuoc_settings_page && !isset($_GET['wuoc_crons']) && !$wuoc_is_order))
					||
						(!is_admin() && !isset($_GET['wuoc_crons']))					
				)
			
			&&
				!wp_doing_ajax()
			
		){
			//echo 'SHOULD NOT COMBINE';exit;
			return;
		}else{
			//echo 'SHOULD COMBINE';exit;
		}
		
		
		$wuoc_bulk_instantiated = false;
		$wuoc_activated = true;

        $wuoc_auto_combined_settings = get_option('wuoc_auto_combined_settings', array());
		//wuoc_pree($wuoc_auto_combined_settings);exit;
        $is_automation_combine = (array_key_exists('auto_combine', $wuoc_auto_combined_settings)?$wuoc_auto_combined_settings['auto_combine']:false);
		if(!$is_automation_combine && array_key_exists(1, $wuoc_auto_combined_settings)){
			foreach($wuoc_auto_combined_settings as $wuoc_auto_combined_setting){
				if(!$is_automation_combine){
					$is_automation_combine = (array_key_exists('auto_combine', $wuoc_auto_combined_setting)?$wuoc_auto_combined_setting['auto_combine']:false);
				}
			}
		}
		
				
		//https://shop.androidbubble.com/products/wordpress-plugin?variant=36439508189339';//
		
		$is_wos_installed = (array_key_exists('woo-order-splitter/index.php', $wuoc_all_plugins) && in_array('woo-order-splitter/index.php', $wuoc_active_plugins));
		$wuoc_os_gf = (array_key_exists('gravityforms/gravityforms.php', $wuoc_all_plugins) && in_array('gravityforms/gravityforms.php', $wuoc_active_plugins));
		
		
	
		
		
		require_once(realpath(WUOC_PLUGIN_DIR . '/inc/functions.php'));
		require_once(realpath(WUOC_PLUGIN_DIR . '/inc/functions-plus.php'));
		
		
		if($wuoc_pro)
		include_once(realpath($wuoc_pro_file));
		
		
		
		if(is_admin()){
			
			
			

			if(function_exists('wuoc_admin_scripts_wos_dependent') && !$is_wos_installed){

                add_action( 'admin_enqueue_scripts', 'wuoc_admin_scripts_wos_dependent', 99 );

            }


            if(function_exists('wuoc_admin_scripts')){
				add_action( 'admin_enqueue_scripts', 'wuoc_admin_scripts', 99 );	
			}
			
			add_action('admin_init', 'wuoc_settings_update');
			add_action('admin_head', 'wuoc_admin_head');
			
			
		}else{
			if(function_exists('wuoc_front_scripts'))
			add_action( 'wp_enqueue_scripts', 'wuoc_front_scripts', 99 );	
		}
		
		add_action('wp_head', 'wuoc_header_scripts');
		add_action('init', 'wuoc_init');

		

	
		add_action('woocommerce_analytics_update_order_stats', 'wuoc_update_order_lookup_tables');
		add_action('woocommerce_analytics_update_product', 'wuoc_update_product_lookup_tables', 10, 2);
		add_action('woocommerce_analytics_update_coupon', 'wuoc_update_coupon_lookup_tables', 10, 2);
		add_action('woocommerce_analytics_update_tax', 'wuoc_update_tax_lookup_tables', 10, 2);
		add_action('woocommerce_reports_get_order_report_query', 'wuoc_reports_get_order_report_query');
		add_action('woocommerce_email', 'wuoc_unhook_emails');
		
		if($wuoc_pro){
			
			if(is_admin()){
				
				
				add_action('admin_init', 'wuoc_pro_general_actions');	
				
				add_action('admin_footer', 'wuoc_add_combined_link');
				
				add_action( 'add_meta_boxes', 'wuoc_retained_meta_box', 10, 2 );
				add_action( 'add_meta_boxes', 'wuoc_order_items_meta_box', 10, 2 );
				
				
				
				add_action('wp_ajax_wuoc_get_combined_orders_html', 'wuoc_get_combined_orders_html');
				
				add_action('wp_ajax_wuoc_get_trash_orders_html', 'wuoc_get_trash_orders_html');
				
				add_action('wuoc_general_settings_body', 'wuoc_general_settings_body');
				add_action('wuoc_combined_order_body', 'wuoc_combined_order_body_function');
				
				if (isset($_POST['wuoc_combined_option'])) {
					add_action('wuoc_general_settings_body', 'wuoc_combined_option_update');
				}
				
				if (isset($_POST['wuoc_crons_options'])) {
					add_action('wuoc_crons_options_body', 'wuoc_crons_options_update');
				}
				
			}else{
				add_action('woocommerce_thankyou', 'wuoc_automation_combine_callback');			
			}
			
			add_action('init', 'wuoc_automation_pre_combine_callback');	
			
			
			
		}
		
	}