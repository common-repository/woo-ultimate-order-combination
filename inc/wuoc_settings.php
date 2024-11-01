<?php defined('ABSPATH') or die(__('No script kiddies please!', 'woo-uoc'));


if (!current_user_can('manage_woocommerce')) {
	wp_die(__('You do not have sufficient permissions to access this page.', 'woo-uoc'));
}

global $wuoc_url, $wuoc_data, $wuoc_pro, $wuoc_premium_copy, $wuoc_bulk_instantiated, $wuoc_activated, $wuoc_crons_options, $wuoc_orderslist_page_cron;

	$wuoc_cust = get_option('wuoc_cuztomization', array());


	$wuoc_shipping_status = get_option('wuoc_shipping_status', 'no_shipping');
	$wuoc_combined_order_status = get_option('wuoc_combined_order_status', '');
	$wuoc_os_order_statuses = wc_get_order_statuses();
	
?>




<div class="wrap wuoc_settings_div">





	<div class="icon32" id="icon-options-general"><br></div>
	<h2><i class="fas fa-paw" style="color:#9B5C8F"></i>&nbsp;<?php echo $wuoc_data['Name']; ?> <?php echo '(' . $wuoc_data['Version'] . ($wuoc_pro ? ') '.__('Pro', 'woo-uoc').'' : ')'); ?> - <?php _e("Settings", "woo-uoc"); ?> <?php if (!$wuoc_pro) { ?><a class="gopro" target="_blank" href="<?php echo $wuoc_premium_copy; ?>"><?php _e("Go Premium", "woo-uoc"); ?></a><?php } ?></h2>



	<h2 class="nav-tab-wrapper">
		<a class="nav-tab nav-tab-active"><i class="fas fa-cog" style="color:#AC8D12"></i>&nbsp;<?php _e("General Settings", "woo-uoc"); ?></a>
        <a class="nav-tab"><i class="fas fa-robot" style="color:#2C8BF0"></i>&nbsp;<?php _e("Automation", "woo-uoc"); ?></a>		
		<a class="nav-tab" data-tab="combined_orders"><i class="fas fa-check-double" style="color:#38C18E"></i>&nbsp;<?php _e("Combined Orders", "woo-uoc"); ?></a>
        <a class="nav-tab trash" data-tab="trash_orders"><i class="fas fa-trash-alt" style="color:#D93838"></i>&nbsp;<?php _e("Trashed Orders", "woo-uoc"); ?></a>
        <a class="nav-tab crons" data-tab="cron_jobs"><i class="fas fa-history" style="color:#0D84EE"></i>&nbsp;<?php _e("Cron Jobs", "woo-uoc"); ?></a>
        <a class="nav-tab documentation" data-tab="documentation"><i class="fas fa-book" style="color:#557B4A"></i>&nbsp;<?php _e("Documentation", "woo-uoc"); ?></a>
        <a class="nav-tab dev" data-tab="dev"><i class="fas fa-code" style="color:#930"></i>&nbsp;<?php _e("Developers", "woo-uoc"); ?></a>
        <a class="nav-tab logs" data-tab="logs"><i class="fas fa-route" style="color:#C39"></i>&nbsp;<?php _e("Logs", "woo-uoc"); ?></a>
        <a class="nav-tab" data-tab="help" data-type="free"><i class="far fa-question-circle" style="color:#FB7A47"></i>&nbsp;<?php _e("Help", 'woo-ultimate-order-combination'); ?></a>



    </h2>



	<?php if (!$wuoc_activated) : ?>
		<div class="wuoc_notes">
			<h2><?php _e("You need WooCommerce plugin to be installed and activated.", "woo-uoc"); ?> <?php _e("Please", "woo-uoc"); ?> <a href="plugin-install.php?s=woocommerce&tab=search&type=term" target="_blank"><?php _e("Install", "woo-uoc"); ?></a> <?php _e("and", "woo-uoc"); ?>/<?php _e("or", "woo-uoc"); ?> <a href="plugins.php?plugin_status=inactive" target="_blank"><?php _e("Activate", "woo-uoc"); ?></a> WooCommerce <?php _e("plugin to proceed", "woo-uoc"); ?>.</h2>
			<br />
			<br />
			<br />
			<br />
			<br />
			<br />
			<br />
			<br />
			<br />
			<br />
			<br />
			<br />
		</div>
	<?php exit;
	endif; ?>



	<form class="nav-tab-content wuoc_general_settings" method="post">
		<input type="hidden" name="wuoc_tn" value="<?php echo isset($_GET['t']) ? esc_attr($_GET['t']): ''; ?>" />
		<?php wp_nonce_field('wuoc_settings_action', 'wuoc_settings_field'); ?>

		<div class="wuoc_notes"></div>
		<div class="row">

			<div class="<?php echo isset($_POST['wuoc_analyze_orders'])?'col-md-12':'col-md-7'; ?> mt-3">
<?php if(function_exists('wuoc_general_settings_body')): ?>
<?php do_action('wuoc_general_settings_body') ?>
<?php else: ?>

<iframe style="width:100%; margin: 30px 0 30px 0;height:415px;" src="https://www.youtube.com/embed/DsxXj-DuBW4" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
<div class="alert alert-warning alert-dismissible fade show" role="alert">
  <strong><?php _e('Info!', 'woo-uoc'); ?></strong> <?php _e('This video tutorial is about Premium Version.', 'woo-uoc'); ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<iframe style="width:100%; margin: 20px 0 0 0;height:415px;" src="https://www.youtube.com/embed/HAMuzSm0Jd0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>


<?php endif; ?>

<input type="hidden" name="wuoc_settings[wuoc_additional][]" value="0" />
			</div>

			<div class="col-md-5 <?php echo isset($_POST['wuoc_analyze_orders'])?'d-none':''; ?>">
				<div class="wuoc_optional">


					<h3><?php _e("Optional", "woo-uoc"); ?></h3>

					<fieldset>
<p class="submit" style="margin:0; padding:0;"><input type="submit" value="<?php _e('Save Changes', 'woo-uoc'); ?>" class="button button-primary" id="submit" name="submit"></p>
						<ul>

<li class="wuoc_list_settings">
<strong><i class="fas fa-check-double"></i>&nbsp;<?php _e('Combine Settings', 'woo-uoc'); ?>:</strong>
<ul>
<li <?php echo (get_option('wuoc_maintain_uniqueness', 0) ? 'class="selected"' : ''); ?>>

<input class="wuoc_checkout_options" id="wuoc_maintain_uniqueness" name="wuoc_maintain_uniqueness" type="checkbox" value="1" <?php echo checked(get_option('wuoc_maintain_uniqueness', 0), true, false); ?> /><label for="wuoc_maintain_uniqueness"><?php _e("Keep Order Items Separate using Attributes and Values", "woo-uoc"); ?> <strong><?php _e("Off", "woo-uoc"); ?></strong>/<strong><?php _e("On", "woo-uoc"); ?></strong></label>

</li>

<li <?php echo ($wuoc_shipping_status ? 'class="selected"' : ''); ?>>

<label for="wuoc_shipping_status"><?php _e("Shipping", "woo-uoc"); ?></label>
<select class="wuoc_checkout_options" id="wuoc_shipping_status" name="wuoc_shipping_status">
<option value="" <?php echo selected($wuoc_shipping_status=='', true, false); ?>><?php _e('Default', 'woo-uoc'); ?></option>
<option value="no_shipping" <?php echo selected($wuoc_shipping_status=='no_shipping', true, false); ?>><?php _e('No Shipping', 'woo-uoc'); ?></option>
<option value="combine_shipping" <?php echo selected($wuoc_shipping_status=='combine_shipping', true, false); ?>><?php _e('Combine Shipping', 'woo-uoc'); ?></option>
<option value="separate_shipping" <?php echo selected($wuoc_shipping_status=='separate_shipping', true, false); ?>><?php _e('Separate Shipping', 'woo-uoc'); ?></option>
<option value="highest_shipping" <?php echo selected($wuoc_shipping_status=='highest_shipping', true, false); ?>><?php _e('Highest Shipping', 'woo-uoc'); ?></option>
</select>
<a title="<?php _e('Video Tutorial - How Shipping option works?', 'woo-uoc'); ?>" href="https://www.youtube.com/embed/7q9zG1DMLYY" target="_blank"><i class="fab fa-youtube"></i></a>

</li>

<li <?php echo ($wuoc_combined_order_status ? 'class="selected"' : ''); ?>>

<label for="wuoc_combined_order_status"><?php _e("Combined Order Status", "woo-uoc"); ?></label>
<select class="wuoc_checkout_options" id="wuoc_combined_order_status" name="wuoc_combined_order_status">
<option value="" <?php echo selected($wuoc_combined_order_status=='', true, false); ?>><?php _e('Default', 'woo-uoc'); ?></option>
<?php
	if(!empty($wuoc_os_order_statuses)){
		foreach($wuoc_os_order_statuses as $wuoc_os_order_status_key=>$wuoc_os_order_status_val){
?>			
<option value="<?php echo $wuoc_os_order_status_key; ?>" <?php echo selected($wuoc_os_order_status_key==$wuoc_combined_order_status, true, false); ?>><?php echo $wuoc_os_order_status_val; ?></option>
<?php
		}
		
	}
?>	
</select>

</li>

</ul>
</li>

<li class="wuoc_list_settings">
<strong><?php _e('Cron Settings', 'woo-uoc'); ?>: <?php if(!$wuoc_pro): ?><a href="<?php echo $wuoc_premium_copy; ?>" target="_blank"><?php endif; ?><i title="<?php _e("Premium Features", "woo-uoc"); ?>" class="fas fa-history"></i><?php if(!$wuoc_pro): ?></a><?php endif; ?></strong>
<ul>
<li <?php echo (get_option('wuoc_thankyou_page_cron', 0) ? 'class="selected"' : ''); ?>>

<input class="wuoc_checkout_options" id="wuoc_thankyou_page_cron" name="wuoc_thankyou_page_cron" type="checkbox" value="1" <?php echo checked(get_option('wuoc_thankyou_page_cron', 0), true, false); ?> /><label for="wuoc_thankyou_page_cron"><?php _e("Thankyou Page / Order Received Page", "woo-uoc"); ?> <strong><?php _e("Off", "woo-uoc"); ?></strong>/<strong><?php _e("On", "woo-uoc"); ?></strong></label>

</li>

<li <?php echo ($wuoc_orderslist_page_cron ? 'class="selected"' : ''); ?>>

<input class="wuoc_checkout_options" id="wuoc_orderslist_page_cron" name="wuoc_orderslist_page_cron" type="checkbox" value="1" <?php echo checked($wuoc_orderslist_page_cron, true, false); ?> /><label for="wuoc_orderslist_page_cron"><?php _e("Orders List Page / Admin Panel", "woo-uoc"); ?> <strong><?php _e("Off", "woo-uoc"); ?></strong>/<strong><?php _e("On", "woo-uoc"); ?></strong></label>

</li>


</ul>

</li>

<li class="wuoc_list_settings">
<strong><?php _e('Orders List', 'woo-uoc'); ?>: <?php if(!$wuoc_pro): ?><a href="<?php echo $wuoc_premium_copy; ?>" target="_blank"><?php endif; ?><i title="<?php _e("Premium Features", "woo-uoc"); ?>" class="fas fa-medal"></i><?php if(!$wuoc_pro): ?></a><?php endif; ?></strong>
<ul>
<li <?php echo (get_option('wuoc_move_to_trash', 0) ? 'class="selected"' : ''); ?>>

<input class="wuoc_checkout_options" id="wuoc_move_to_trash" name="wuoc_move_to_trash" type="checkbox" value="1" <?php echo checked(get_option('wuoc_move_to_trash', 0), true, false); ?> /><label for="wuoc_move_to_trash"><?php _e("Move existing orders to trash on combine (bulk options)", "woo-uoc"); ?> <strong><?php _e("Off", "woo-uoc"); ?></strong>/<strong><?php _e("On", "woo-uoc"); ?></strong></label>

</li>

<li title="<?php _e("Premium Features", "woo-uoc"); ?>" <?php echo (get_option('wuoc_filter_by_meta_key', 0) ? 'class="selected"' : ''); ?>>

<input <?php disabled(!$wuoc_pro); ?> class="wuoc_checkout_options" id="wuoc_filter_by_meta_key" name="wuoc_filter_by_meta_key" type="checkbox" value="1" <?php echo checked(get_option('wuoc_filter_by_meta_key', 0), true, false); ?> /><label for="wuoc_filter_by_meta_key"><?php _e("Filter by Order meta keys", "woo-uoc"); ?> <strong><?php _e("Off", "woo-uoc"); ?></strong>/<strong><?php _e("On", "woo-uoc"); ?></strong></label>

</li>


<li title="<?php _e("Premium Features", "woo-uoc"); ?>" <?php echo (get_option('wuoc_custom_meta_key_column', 0) ? 'class="selected"' : ''); ?>>

<input <?php disabled(!$wuoc_pro); ?> class="wuoc_checkout_options" id="wuoc_custom_meta_key_column" name="wuoc_custom_meta_key_column" type="checkbox" value="1" <?php echo checked(get_option('wuoc_custom_meta_key_column', 0), true, false); ?> /><label for="wuoc_custom_meta_key_column"><?php _e("Custom Meta Key Column Sorting", "woo-uoc"); ?> <strong><?php _e("Off", "woo-uoc"); ?></strong>/<strong><?php _e("On", "woo-uoc"); ?></strong></label>
<span class="wuoc_transparent_field_wrapper"><input placeholder="<?php _e('Enter order meta key', 'woo-uoc'); ?>" type="text" name="wuoc_custom_meta_key_column_csv" id="wuoc_custom_meta_key_column_csv" value="<?php echo get_option('wuoc_custom_meta_key_column_csv', ''); ?>" /></span>
</li>



<li title="<?php _e("Enable/Disable View Order button on the edit order page", "woo-uoc"); ?>" <?php echo (get_option('wuoc_view_order_button', 0) ? 'class="selected"' : ''); ?>>

<input class="wuoc_checkout_options" id="wuoc_view_order_button" name="wuoc_view_order_button" type="checkbox" value="1" <?php echo checked(get_option('wuoc_view_order_button', 0), true, false); ?> /><label for="wuoc_view_order_button"><?php _e("Enable/Disable View Order button", "woo-uoc"); ?> <strong><?php _e("Off", "woo-uoc"); ?></strong>/<strong><?php _e("On", "woo-uoc"); ?></strong></label>
</li>

</ul>

</li>

<li class="wuoc_list_settings">
<strong><?php _e('Orders Meta', 'woo-uoc'); ?>: <?php if(!$wuoc_pro): ?><a href="<?php echo $wuoc_premium_copy; ?>" target="_blank"><?php endif; ?><i title="<?php _e("Premium Features", "woo-uoc"); ?>" class="fas fa-medal"></i><?php if(!$wuoc_pro): ?></a><?php endif; ?></strong>
<ul>
<li <?php echo (get_option('wuoc_show_retained_meta', 0) ? 'class="selected"' : ''); ?>>

<input class="wuoc_checkout_options" id="wuoc_show_retained_meta" name="wuoc_show_retained_meta" type="checkbox" value="1" <?php echo checked(get_option('wuoc_show_retained_meta', 0), true, false); ?> /><label for="wuoc_show_retained_meta"><?php _e("Display Meta data from merged orders on edit page", "woo-uoc"); ?> <strong><?php _e("Off", "woo-uoc"); ?></strong>/<strong><?php _e("On", "woo-uoc"); ?></strong></label>

</li>

    <li <?php echo (get_option('wuoc_show_order_items_meta', 0) ? 'class="selected"' : ''); ?>>

        <input class="wuoc_checkout_options" id="wuoc_show_order_items_meta" name="wuoc_show_order_items_meta" type="checkbox" value="1" <?php echo checked(get_option('wuoc_show_order_items_meta', 0), true, false); ?> /><label for="wuoc_show_order_items_meta"><?php _e("Display Items History from merged orders on edit page", "woo-uoc"); ?> <strong><?php _e("Off", "woo-uoc"); ?></strong>/<strong><?php _e("On", "woo-uoc"); ?></strong></label>

    </li>
    
    <li <?php echo (get_option('wuoc_sort_order_items_by_category', 0) ? 'class="selected"' : ''); ?>>

        <input class="wuoc_checkout_options" id="wuoc_sort_order_items_by_category" name="wuoc_sort_order_items_by_category" type="checkbox" value="1" <?php echo checked(get_option('wuoc_sort_order_items_by_category', 0), true, false); ?> /><label for="wuoc_sort_order_items_by_category"><?php _e("Sort order items by product categories", "woo-uoc"); ?> <strong><?php _e("Off", "woo-uoc"); ?></strong>/<strong><?php _e("On", "woo-uoc"); ?></strong></label>

    </li>    
 </ul>
 
<strong style="margin:10px 0 10px 0"><?php _e('Extra Cloning', 'woo-uoc'); ?>: <?php if(!$wuoc_pro): ?><a href="<?php echo $wuoc_premium_copy; ?>" target="_blank"><?php endif; ?><i title="<?php _e("Premium Features", "woo-uoc"); ?>" class="fas fa-medal"></i><?php if(!$wuoc_pro): ?></a><?php endif; ?></strong>
<ul>    
    
    <li title="<?php _e("Premium Features", "woo-uoc"); ?>" <?php echo (get_option('wuoc_clone_order_notes', 0) ? 'class="selected"' : ''); ?>>

        <input class="wuoc_checkout_options" id="wuoc_clone_order_notes" name="wuoc_clone_order_notes" type="checkbox" value="1" <?php echo checked(get_option('wuoc_clone_order_notes', 0), true, false); ?> /><label for="wuoc_clone_order_notes"><?php _e("Clone order notes", "woo-uoc"); ?> <strong><?php _e("Off", "woo-uoc"); ?></strong>/<strong><?php _e("On", "woo-uoc"); ?></strong></label>

    </li>    
    <li title="<?php _e("Premium Features", "woo-uoc"); ?>" <?php echo (get_option('wuoc_clone_customer_notes', 0) ? 'class="selected"' : ''); ?>>

        <input class="wuoc_checkout_options" id="wuoc_clone_customer_notes" name="wuoc_clone_customer_notes" type="checkbox" value="1" <?php echo checked(get_option('wuoc_clone_customer_notes', 0), true, false); ?> /><label for="wuoc_clone_customer_notes"><?php _e("Clone customer notes", "woo-uoc"); ?> <strong><?php _e("Off", "woo-uoc"); ?></strong>/<strong><?php _e("On", "woo-uoc"); ?></strong></label>

    </li> 
    <li title="<?php _e("Premium Features", "woo-uoc"); ?>" <?php echo (get_option('wuoc_clone_shipping', 0) ? 'class="selected"' : ''); ?>>

        <input class="wuoc_checkout_options" id="wuoc_clone_shipping" name="wuoc_clone_shipping" type="checkbox" value="1" <?php echo checked(get_option('wuoc_clone_shipping', 0), true, false); ?> /><label for="wuoc_clone_shipping"><?php _e("Clone shipping classes", "woo-uoc"); ?> <strong><?php _e("Off", "woo-uoc"); ?></strong>/<strong><?php _e("On", "woo-uoc"); ?></strong></label>

    </li>        
</ul>

</li>


<li class="wuoc_list_settings">
<strong><i class="fas fa-paint-brush"></i>&nbsp;<?php _e('Appearance', 'woo-uoc'); ?>:</strong>
<ul>
<li <?php echo (get_option('wuoc_bootstrap', 0) ? 'class="selected"' : ''); ?>>

<input class="wuoc_checkout_options" id="wuoc_bootstrap" name="wuoc_bootstrap" type="checkbox" value="1" <?php echo checked(get_option('wuoc_bootstrap', 0), true, false); ?> /><label for="wuoc_bootstrap"><?php _e("Bootstrap", "woo-uoc"); ?> <strong><?php _e("Off", "woo-uoc"); ?></strong>/<strong><?php _e("On", "woo-uoc"); ?></strong></label>

</li>


</ul>

</li>

							<?php if (function_exists('wuoc_email_notification')) : ?>
								<li class="wuoc_list_settings">
									<strong><i class="fas fa-envelope"></i>&nbsp;<?php _e('Send email notifications to customer', 'woo-uoc'); ?>:</strong>
									<ul>
										<li <?php echo (get_option('wuoc_order_combine_email', 0) ? 'class="selected"' : ''); ?>>

											<input class="wuoc_checkout_options" id="wuoc_order_combine_email" name="wuoc_order_combine_email" type="checkbox" value="1" <?php echo (get_option('wuoc_order_combine_email', 0) ? 'checked="checked"' : ''); ?> /><label for="wuoc_order_combine_email"><?php _e("Orders Combined", "woo-uoc"); ?> <strong><?php _e("Off", "woo-uoc"); ?></strong>/<strong><?php _e("On", "woo-uoc"); ?></strong></label>

										</li>

									</ul>

								</li>
								<li class="wuoc_list_settings">
									<strong><i class="fas fa-envelope"></i>&nbsp;<?php _e('Send email notifications to admin', 'woo-uoc'); ?>:</strong>
									<ul>
										<li <?php echo (get_option('wuoc_stock_short_email', 0) ? 'class="selected"' : ''); ?>>

											<input class="wuoc_checkout_options" id="wuoc_stock_short_email" name="wuoc_stock_short_email" type="checkbox" value="1" <?php echo (get_option('wuoc_stock_short_email', 0) ? 'checked="checked"' : ''); ?> /><label for="wuoc_stock_short_email"><?php _e("Product out of stock", "woo-uoc"); ?> <strong><?php _e("Off", "woo-uoc"); ?></strong>/<strong><?php _e("On", "woo-uoc"); ?></strong></label>

										</li>
										<li <?php echo (get_option('wuoc_product_backorder_email', 0) ? 'class="selected"' : ''); ?>>

											<input class="wuoc_checkout_options" id="wuoc_product_backorder_email" name="wuoc_product_backorder_email" type="checkbox" value="1" <?php echo (get_option('wuoc_product_backorder_email', 0) ? 'checked="checked"' : ''); ?> /><label for="wuoc_product_backorder_email"><?php _e("Product backorder", "woo-uoc"); ?> <strong><?php _e("Off", "woo-uoc"); ?></strong>/<strong><?php _e("On", "woo-uoc"); ?></strong></label>

										</li>                                        
										<li <?php echo (get_option('wuoc_new_order_email', 0) ? 'class="selected"' : ''); ?>>

											<input class="wuoc_checkout_options" id="wuoc_new_order_email" name="wuoc_new_order_email" type="checkbox" value="1" <?php echo (get_option('wuoc_new_order_email', 0) ? 'checked="checked"' : ''); ?> /><label for="wuoc_new_order_email"><?php _e("New Order", "woo-uoc"); ?> <strong><?php _e("On", "woo-uoc"); ?></strong>/<strong><?php _e("Off", "woo-uoc"); ?></strong></label>

										</li>                                        

									</ul>

								</li>

							<?php endif; ?>



						</ul>
						<p class="submit"><input type="submit" value="<?php _e('Save Changes', 'woo-uoc'); ?>" class="button button-primary" id="submit" name="submit"></p>
					</fieldset>


				</div>
			</div>
		</div>



	</form>

    <div class="nav-tab-content hide wuoc_auto_combined">

        <?php if(function_exists('wuoc_auto_combined_nav_tab_content')): ?>

                <?php wuoc_auto_combined_nav_tab_content(); ?>
                
		<?php else: ?>
<div class="alert alert-primary mt-4" role="alert">
  <?php _e('Automation is a premium feature in which you can define/set rules to combine upcoming orders with existing orders.', 'woo-uoc'); ?> <a class="btn btn-sm btn-warning" href="<?php echo $wuoc_premium_copy; ?>" target="_blank"><?php _e('Go Premium', 'woo-uoc'); ?></a>
  
</div>

<iframe style="width:100%; margin: 20px 0 0 0;height:415px;" src="https://www.youtube.com/embed/GzNC6SmprHc" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        <?php endif; ?>

    </div>

	
	<form class="nav-tab-content hide"  method="post">
		<?php if(function_exists('wuoc_combined_order_body_function')): ?>
		<?php do_action('wuoc_combined_order_body'); ?>
		<?php else: ?>
<div class="alert alert-success mt-4" role="alert">
  <?php _e('Combined Orders tab will show a list of combined orders history. You can track all combined orders and the relevant parent/original order details.', 'woo-uoc'); ?> <a class="btn btn-sm btn-warning" href="<?php echo $wuoc_premium_copy; ?>" target="_blank"><?php _e('Go Premium', 'woo-uoc'); ?></a>
</div>

<iframe style="width:100%; margin: 20px 0 0 0;height:415px;" src="https://www.youtube.com/embed/KOWb8-Ku5KY" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        <?php endif; ?>
        
	</form>
    


    
        <form class="nav-tab-content wuos_trash hide"  method="post">
			<?php if(function_exists('wuoc_trash_order_body_function')): ?>
            <?php wuoc_trash_order_body_function(); ?>
			<?php else: ?>
<div class="alert alert-danger mt-4" role="alert">
  <?php _e('Trashed Orders tab will show a list of trashed orders which can be restored as well. This feature will help you to undo trashed action.', 'woo-uoc'); ?> <a class="btn btn-sm btn-warning" href="<?php echo $wuoc_premium_copy; ?>" target="_blank"><?php _e('Go Premium', 'woo-uoc'); ?></a>
</div>

<iframe style="width:100%; margin: 20px 0 0 0;height:415px;" src="https://www.youtube.com/embed/GIyQ38NYnbk" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        <?php endif; ?>


        </form>
    
    
    <div class="nav-tab-content container-fluid hide" data-content="crons">
    <?php do_action('wuoc_crons_options_body') ?>
<?php
		$wuoc_crons_options = get_option('wuoc_crons_options');
		$wuoc_crons_options = (is_array($wuoc_crons_options)?$wuoc_crons_options:array());

	
		$orders_number_to_combine = (array_key_exists('number_to_combine', $wuoc_crons_options)?$wuoc_crons_options['number_to_combine']:1);
		$orders_number_to_combine = ((is_numeric($orders_number_to_combine) && $orders_number_to_combine>=1)?$orders_number_to_combine:1);
		
		$crons_button_display = (array_key_exists('button_display', $wuoc_crons_options)?$wuoc_crons_options['button_display']:array());
		
		$crons_clock_settings = (array_key_exists('clock', $wuoc_crons_options)?$wuoc_crons_options['clock']:array());
		//wuoc_pree($crons_clock_settings);
		//wuoc_pre($wuoc_crons_options);exit;
		
?>		
        <div class="row mt-3">
	       
            <form class="wuoc_cron_settings"  method="post">

	            <fieldset>
                <input type="hidden" name="wuoc_tn" value="<?php echo isset($_GET['t']) ? esc_attr($_GET['t']): ''; ?>" />
                <?php wp_nonce_field('wuoc_settings_action', 'wuoc_settings_field'); ?>
                
                
                <div class="alert alert-primary mt-4" role="alert">
				  <?php _e('Cron jobs help you to keep the orders combined automatically with a defined limit of orders per round. Default value is 1 for the database query for each cron job call.', 'woo-uoc'); ?> <?php if(!$wuoc_pro): ?><a class="btn btn-sm btn-warning" href="<?php echo $wuoc_premium_copy; ?>" target="_blank"><?php _e('Go Premium', 'woo-uoc'); ?></a><?php endif; ?>
                  
                </div>
                
                <ul class="position-relative cron-settings-wrapper">
                    <li>
                        <b style="font-size:36px; color:#0D84EE;"><span style="color:red;">curl</span> "<?php echo home_url(); ?>/?wuoc_crons"</b> <a title="<?php _e('Click here to see how the cron job settings work?', 'woo-uoc'); ?>" target="_blank" href="<?php echo $wuoc_url; ?>/img/cron-job-settings.png"><img style="float:right; height:300px;" src="<?php echo $wuoc_url; ?>/img/cron-job-settings.png" title="<?php _e('How cron job settings work?', 'woo-uoc'); ?>" alt="<?php _e('Cron job settings explained.', 'woo-uoc'); ?>" /></a>
                    </li>
                    <li>
                        <label><?php _e('Orders per round', 'woo-uoc'); ?>: </label>
                        <input name="wuoc_crons_options[number_to_combine]" id="number_to_combine" type="number" value="<?php echo $orders_number_to_combine; ?>" min="1" /> <small>(e.g. <span class="number_to_combine"><?php echo $orders_number_to_combine; ?></span> (new) + 1 (last combined) = 1 <?php _e('new combined order', 'woo-uoc'); ?>)</small>
                    
                    </li>
                    
                    <li>
                    	
                    	<label><?php _e('Cron Time', 'woo-uoc'); ?>: </label>
                    	<input type="text" class="wuoc-timepicker" name="wuoc_crons_options[clock][time]" /> <input name="wuoc_crons_options[clock][active]" id="clock_active" type="checkbox" value="1" <?php checked(array_key_exists('active', $crons_clock_settings)); ?> /> <small><?php _e('Enable', 'woo-uoc'); ?>/<?php _e('Disable', 'woo-uoc'); ?></small></li>
                    </li>
                    
					<li style="padding-top:10px;">
                        <label style="font-weight:bold; float:left;"><?php _e('Cron Button Display', 'woo-uoc'); ?>: </label>
                        
                        <ul style="padding-left:180px;">
                        	<li><input name="wuoc_crons_options[button_display][]" id="orders_list" type="checkbox" value="orders_list" <?php checked(in_array('orders_list', $crons_button_display)); ?> /> <small><?php _e('Orders List Page', 'woo-uoc'); ?></small></li>
                        </ul>
                    
                    </li>
                    
                    <li style="padding-top:10px;">
                        <label style="font-weight:bold; float:left;"><?php _e('Cron Controls', 'woo-uoc'); ?>: </label>
                        
                        <ul style="padding-left:180px;">
                        	<li><input name="wuoc_crons_options[button_display][]" id="controls" type="checkbox" value="controls" <?php checked(in_array('controls', $crons_button_display)); ?> /> <small><?php _e('Enable', 'woo-uoc'); ?>/<?php _e('Disable', 'woo-uoc'); ?></small></li>
                        </ul>
                    
                    </li>
                                        
                </ul>     

				<p class="submit" style="margin:20px 0 0 0; padding:0;"><input type="submit" value="<?php _e('Save Changes', 'woo-uoc'); ?>" class="button button-primary" id="submit" name="submit"></p>
                </fieldset>
            </form>                       
        
        </div>

    </div>
    
    <div class="nav-tab-content container-fluid hide" data-content="documentation">

        <div class="row mt-3">
            
            <ul class="position-relative">
            	<li title="<?php _e('This plugin is compatible with Gravity Forms. Click on PDF and PPSX file to see it in action.', 'woo-uoc'); ?>">
                	
                    <div style="text-align:center;" class="mb-4">
                    <img src="<?php echo $wuoc_url; ?>/img/gravity-forms.svg" style="margin:20px 0 30px;" /><br />
                    <a style="margin:0 20px 0 0;" href="https://plugins.svn.wordpress.org/woo-ultimate-order-combination/assets/Order-Combination-Gravity-Forms.pdf" target="_blank"><i style="color:#F20F00; font-size:50px;" class="fas fa-file-pdf"></i></a>
                    <a style="margin:0 20px 0 0;" class="ml-4" href="https://plugins.svn.wordpress.org/woo-ultimate-order-combination/assets/Order-Combination-Gravity-Forms.ppsx" target="_blank"><i style="color:#CA4223; font-size:50px;" class="fas fa-file-powerpoint"></i></a>
                    <a class="ml-4" href="https://www.youtube.com/embed/NlM72V458L4" target="_blank"><i style="color:#f00; font-size:50px;" class="fab fa-youtube-square"></i></a>

                    </div>
            	</li>
                <li>
                <strong>Filter Hooks:</strong><br /><br />


                	<ul>
                    	<li>add_filter('wuoc_update_post_meta_value', 'wuoc_update_post_meta_value_callback', 9, 4);</li>
                        <li>add_filter('wuoc_email_notification_filter', 'wuoc_email_notification_filter_callback', 9, 2);</li>
                    </ul>    
                        <br />

                </li>
                
                <li>
                <strong>Action Hooks:</strong><br /><br />


                	<ul>
                    	<li>add_action('clone_extra_billing_fields_hook', 'clone_extra_billing_fields_hook_callback', 9, 2);</li>
                        <li>add_action('clone_extra_shipping_fields_hook', 'clone_extra_shipping_fields_hook_callback', 9, 2);</li>
                        <li>add_action('wuoc_combined_order_created_hook', 'wuoc_combined_order_created_hook_callback', 9, 2);</li>
                    </ul>    
                        
                </li>
			</ul>   
		</div>
	</div>        
    

    <div class="nav-tab-content container-fluid hide wuoc_logger mt-4" data-content="dev">
    <i class="fas fa-code"></i><br /><br />
    <input name="clear-wuoc-meta-for-order-ids" class="w-50" type="text" placeholder="<?php _e('Order IDs to clean wuoc from meta', 'woo-uoc'); ?>" /><br /><small>(<?php _e('Do not use this option if you are not sure about it.', 'woo-uoc'); ?>)</small><br /><br />

    <input type="button" class="btn btn-danger clearfix float-none" id="clear-wuoc-meta" value="<?php _e('Clear WUOC from Meta', 'woo-uoc'); ?>" />
    
    
    <ul style="float:right; width:300px; height:400px; overflow-y: auto;">
    	<li><?php echo implode('</li><li>', wuoc_db_tables()); ?></li>
    </ul>
    
    </div>
    
    <div class="nav-tab-content container-fluid hide wuoc_logger mt-4" data-content="logs">
			<?php
            
            
            $wuoc_logger = wuoc_logger('debug');
            
            if(!empty($wuoc_logger)){
                krsort($wuoc_logger);
                ?>
                
           
                <div style="float: right"><a class="btn btn-sm btn-danger wuoc_logger_clear_log"><?php _e('Clear Debug Log', 'woo-uoc'); ?> <i class="fas fa-trash"></i></a> </div>
           
                        
                <ul class="wuoc_debug_log">
                    <?php

                    foreach($wuoc_logger as $log){
                        ?>
                        <li>
                        <?php 
                        if(is_array($log) || is_object($log)){
                            wuoc_pree($log);
                        }else{
                            echo $log;
                        }
                        ?>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
                <?php
            }else{
    
    ?>
    <div class="alert alert-info" role="alert">
      <?php echo __('Nothing logged yet.', 'woo-uoc'); ?>
    </div>
    <?php			
            }
            ?>
    </div>	

    <div class="nav-tab-content container-fluid hide" data-content="help">

        <div class="row mt-3">
        
        	<ul class="position-relative">
            	<li><a class="btn btn-sm btn-info" href="https://wordpress.org/support/plugin/woo-ultimate-order-combination/" target="_blank"><?php _e('Open a Ticket on Support Forums', 'woo-ultimate-order-combination'); ?> &nbsp;<i class="fas fa-tag"></i></a></li>
                <li><a class="btn btn-sm btn-warning" href="http://demo.androidbubble.com/contact/" target="_blank"><?php _e('Contact Developer', 'woo-ultimate-order-combination'); ?> &nbsp;<i class="fas fa-headset"></i></a></li>
                <li><a class="btn btn-sm btn-secondary" href="<?php echo $wuoc_premium_copy; ?>/?help" target="_blank"><?php _e('Need Urgent Help?', 'woo-ultimate-order-combination'); ?> &nbsp;<i class="fas fa-phone"></i></i></a></li>
                <li><iframe width="560" height="315" src="https://www.youtube.com/embed/HAMuzSm0Jd0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></li>
			</ul>                
        </div>

    </div>
    
    
</div>

<script type="text/javascript" language="javascript">
	jQuery(document).ready(function($) {

<!--		--><?php //if (isset($_POST['wuoc_tn'])) : ?>
//
//			$('.nav-tab-wrapper .nav-tab:nth-child(<?php //echo wuoc_sanitize_data($_POST['wuoc_tn']) + 1; ?>//)').click();
//
//		<?php //endif; ?>

        <?php if (isset($_GET['t'])) : ?>

        $('.nav-tab-wrapper .nav-tab:nth-child(<?php echo esc_attr($_GET['t']) + 1; ?>)').click();

        <?php endif; ?>


	});
</script>

<style type="text/css">
	<?php echo (is_array($css_arr)?implode('', $css_arr):''); ?>#wpfooter {
		display: none;
	}

	<?php if (!$wuoc_pro) : ?>#adminmenu li.current a.current {
		font-size: 12px !important;
		font-weight: bold !important;
		padding: 6px 0px 6px 12px !important;
	}

	#adminmenu li.current a.current,
	#adminmenu li.current a.current span:hover {
		color: #9B5C8F;
	}

	#adminmenu li.current a.current:hover,
	#adminmenu li.current a.current span {
		color: #fff;
	}

	<?php endif; ?>.woocommerce-message,
	.update-nag {
		display: none;
	}
	.notice.notice-error,
	.error.notice{
		display:none;
	}
</style>

<?php
