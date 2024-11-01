jQuery(document).ready(function($){
	
	setTimeout(function(){
		if($('body.wp-admin #woocommerce-order-data #order_data .order_data_column_container').length>0){
			if(wuoc_obj.combined_info){
				$('<div class="wuoc-combined-info"><ul>'+wuoc_obj.combined_info+'</ul></div>').insertAfter($('body.wp-admin #woocommerce-order-data #order_data .order_data_column_container'));
			}
		}
	}, 1000);
	
	if(wuoc_obj.is_orders_list && $.inArray('orders_list', wuoc_obj.crons.button_display)>=0){
		var cron_btn = '<a href="'+wuoc_obj.crons.url+'" class="page-title-action" target="_blank" title="'+wuoc_obj.crons.button_title+'">'+wuoc_obj.crons.button_text+'</a>';
		$(cron_btn).insertAfter($('body.post-type-shop_order a.page-title-action').last());		
	}
	
	if(wuoc_obj.is_pro && wuoc_obj.is_orders_list && wuoc_obj.wuoc_filter_by_meta_key==1){
		$('<span class="wuoc-meta-filter"><input type="text" name="'+wuoc_obj.meta_filters.key_name+'" title="'+wuoc_obj.meta_filters.key_title+'" placeholder="'+wuoc_obj.meta_filters.key_placeholder+'" value="'+wuoc_obj.meta_filters.key_value+'" />=<input type="text" name="'+wuoc_obj.meta_filters.val_name+'" title="'+wuoc_obj.meta_filters.val_title+'" placeholder="'+wuoc_obj.meta_filters.val_placeholder+'" value="'+wuoc_obj.meta_filters.val_value+'" /></span>').insertBefore($('input[name="filter_action"]'));
	}
	

    $('.wuoc_retained_list_container > li').on('click', function(){
		
		 var dashicon = $(this).find('.dashicons');
		
		$('.wuoc_retained_list_container > li .dashicons').removeClass('dashicons-arrow-up').addClass('dashicons-arrow-down');
		dashicon.addClass('dashicons-arrow-up');
	
		$('.wuoc_retained_list_container > li ul').not($(this).find('ul')).hide(300);
	
		$(this).find('ul').show(300);

    });
	
	function wuoc_sort_table($table,order){
		var $rows = $('tbody > tr', $table);
		
		$rows.sort(function (a, b) {
			
			var terms_obj = wuoc_obj.products_terms_order;

			var product_id_1 = $(a).data('order_item_id');
			var keyA = terms_obj[product_id_1];
			
			var product_id_2 = $(b).data('order_item_id');
			var keyB = terms_obj[product_id_2];
			
			//console.log(keyA+' / '+keyB);
			if (order=='asc') {
				return (keyA > keyB) ? 1 : 0;
			} else {
				return (keyA > keyB) ? 0 : 1;
			}
		});
		$.each($rows, function (index, row) {
			$table.append(row);
		});
	}
	
	if(wuoc_obj.wuoc_sort_order_items_by_category==true){
		wuoc_sort_table($('table.woocommerce_order_items'),'asc');
	}

});