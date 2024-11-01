<?php
	add_filter('wp_mail','wuoc_redirect_mails', 99, 1);
	function wuoc_redirect_mails($args){
		
		$wuoc_stock_short_email = get_option('wuoc_stock_short_email', 0);
		$wuoc_product_backorder_email = get_option('wuoc_product_backorder_email', 0);
		$wuoc_new_order_email = get_option('wuoc_new_order_email', 0);
		

		$backorder = strpos($args['subject'], 'Product backorder');
		$out_of_stock = strpos($args['subject'], 'Product out of stock');
		$new_order = strpos($args['subject'], 'New order #');

		if(!$wuoc_stock_short_email && $out_of_stock !== false){
			$args['to'] = '';
		}
		if(!$wuoc_product_backorder_email && $backorder !== false){
			$args['to'] = '';
		}
		if($wuoc_new_order_email && $new_order !== false){
			$args['to'] = '';
		}		
		
			
		
	
		
		return $args;
	}
	
	function wuoc_email_notification($pref=array(), $action='wuoc_combine'){
		
		$ret = false;
		$myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
		$wuoc_cart_notices = get_option( 'wuoc_cart_notices', true);
		if ( $myaccount_page_id ) {
			$myaccount_page_url = get_permalink( $myaccount_page_id );
		}		
		$to = '';
		$subject = '';
		$display_name = '';
		$body = 'USER_NAME,<br><br>BODY_1BODY_2BODY_3<br><br>'.get_bloginfo('name').'<br>'.get_bloginfo('description').'<br>'.get_bloginfo('wpurl');
		switch($action){
			case 'wuoc_combine':
				$subject = __('Following orders are combined into order#', 'woo-uoc').' '.$pref['new'];
				$body_1 = __('Following orders are combined into one order#', 'woo-uoc').' <a href="'.$myaccount_page_url.'view-order/'.$pref['new'].'">'.$pref['new'].'</a>';
				$body_1 .= '<br><br><ul>';
				$order_id = 0;

				if(!empty($pref['original'])){
					foreach($pref['original'] as $order_id){
						//$original_order = new WC_Order($orderid);
						//echo $order_id.'<br>';
						$post_author_id = get_post_field( 'post_author', $order_id );
						$body_1 .= '<li>Order# <a href="'.$myaccount_page_url.'view-order/'.$order_id.'">'.$order_id.'</a></li>';
					}
				}	

				$body_1 .= '</ul><br><br>';
				
				$body_2 = __('Order items will remain intact, same product (items) will be merged and quantity will be incremented.', 'woo-uoc').'<br><br>';
				
				$body_3 = '<a href="'.$myaccount_page_url.'orders'.'">'.__('Click here', 'woo-uoc').'</a> '.__('to check your orders status in your account.', 'woo-uoc').'';
					

				$post_author = get_userdata( $post_author_id );
				
				
				
				
				
				
				if(get_option('wuoc_order_combine_email', 0)){
					
					if(!empty($post_author) && isset($post_author->user_email)){
						$to = $post_author->user_email;
						$display_name = strtoupper($post_author->display_name);
					}else{
						$any_order = wc_get_order($order_id);
						if(!empty($any_order)){
							$to = $any_order->get_billing_email();
							$display_name = ($display_name?$display_name:$any_order->get_formatted_billing_full_name());
						}
					}
					
					
				}
				
				$body = str_replace(array('USER_NAME', 'BODY_1', 'BODY_2', 'BODY_3'), array($display_name, $body_1, $body_2, $body_3), $body);
				
			break;
			
		}
		

		$co_efrom_name = ((isset($wuoc_cart_notices['co_efrom_name']) && $wuoc_cart_notices['co_efrom_name']!='')?$wuoc_cart_notices['co_efrom_name']:get_bloginfo('name'));
		$co_efrom_email = ((isset($wuoc_cart_notices['co_efrom_email']) && $wuoc_cart_notices['co_efrom_email']!='')?$wuoc_cart_notices['co_efrom_email']:get_bloginfo('admin_email'));
		$co_ereplyto_email = ((isset($wuoc_cart_notices['co_ereplyto_email']) && $wuoc_cart_notices['co_ereplyto_email']!='')?$wuoc_cart_notices['co_ereplyto_email']:get_bloginfo('admin_email'));
		
		$headers = array(
						'Content-Type: text/html; charset=UTF-8',
						'From: '.$co_efrom_name.' <'.$co_efrom_email.'>',
						'Reply-To: '.get_bloginfo('name').' <'.$co_ereplyto_email.'>'
					);

		
		$arr = apply_filters('wuoc_email_notification_filter', $pref, $action);
		
		if(is_array($arr)){
			
			if(isset($arr['to']) && $arr['to']!='' && $arr['to']!=$to){ $to = $arr['to']; }
			if(isset($arr['subject']) && $arr['subject']!='' && $arr['subject']!=$subject){ $subject = $arr['subject']; }
			if(isset($arr['body']) && $arr['body']!='' && $arr['body']!=$body){ $body = $arr['body']; }
			if(isset($arr['headers']) && $arr['headers']!='' && $arr['headers']!=$headers){ $headers = $arr['headers']; }
			
		}
		
		if(email_exists($to) || is_email($to)){
			$ret = wp_mail( $to, $subject, $body, $headers );
		}
		
		
		return $ret;
		
	}
	/**
	 * Unhook and remove WooCommerce default emails.
	 */
	 
	
	
	function wuoc_unhook_emails( $email_class ) {
	
			/**
			 * Hooks for sending emails during store events
			 **/
			 
			$disable_backorder_mail_notification = get_option( 'wuoc_backorder_mail_notification', 0 );

			if($disable_backorder_mail_notification) {
				// unhooks sending email backorders during store events
				remove_action( 'woocommerce_low_stock_notification', array( $email_class, 'low_stock' ) );
				remove_action( 'woocommerce_no_stock_notification', array( $email_class, 'no_stock' ) );				
				remove_action( 'woocommerce_product_on_backorder_notification', array( $email_class, 'backorder' ) );
			}
		
	}	