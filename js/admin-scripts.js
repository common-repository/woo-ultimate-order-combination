// JavaScript Document
jQuery(document).ready(function ($) {
	
	
	
	
	if(typeof wuoc_obj.crons.clock.active!='undefined' && wuoc_obj.crons.clock.active){
		
		$('.wuoc-timepicker').timepicker({
			timeFormat: 'hh:mm p',
			interval: 30,
			minTime: new Date(0, 0, 0, 0, 0, 0),
			maxTime: new Date(0, 0, 0, 23, 59, 0),
			defaultTime: wuoc_obj.crons.clock.time,
			startTime: new Date(0, 0, 0, 10, 0, 0),
			dynamic: false,
			dropdown: true,
			scrollbar: true
		});
		
		$('.wuoc-timepicker').show();
	
	}
	

	var order_str_count = 0;

	function get_checked_order_qty(section_id) {


		var checked_orders = $('#group-' + section_id).find('input[type="checkbox"]:checked').length;
		//$('#heading-' + section_id).find('.total_checked_orders').html(checked_orders);

		if (checked_orders > 0) {

			$('.wuoc-uncombined-btn').removeAttr('disabled');
			$('.wuoc_field_set_trash_container .wuoc-trash-btn').removeAttr('disabled');

		} else {

			$('.wuoc-uncombined-btn').attr('disabled', 'disabled');
			$('.wuoc_field_set_trash_container .wuoc-trash-btn').attr('disabled', 'disabled');

		}

	};

	function wuoc_general_checked_order_qty() {
		var checked_orders = $('input[name^="wuoc_orders_list"]:checked').length;
		if (checked_orders < 2) {
			$('input[name="wuoc_process_combine"]').attr({
				'disabled': 'disabled',
				'title': 'Select at least 2 orders to proceed.'
			});
			// var remove_str = $('.wuoc_remove_original_order_str').html();
			
			// if(remove_str != undefined){
			// 	remove_str = remove_str.replace('Orders', 'Order');
			// 	$('.wuoc_remove_original_order_str').html(remove_str);
			// 	order_str_count = 0;
			// }			


		} else {

			$('input[name="wuoc_process_combine"]').removeAttr('disabled').removeAttr('title');

			// var remove_str = $('.wuoc_remove_original_order_str').html();

			// if(order_str_count == 0){
			// 	remove_str = remove_str.replace('Order', 'Orders');
			// 	order_str_count = 1;
			// }

			

			//$('.wuoc_remove_original_order_str').html(remove_str);
		}
	}

	$('.analyze-again').on('click', function(){
		$(this).remove();
		var obj = $('input[name="wuoc_process_combine"]');
		obj.removeAttr('disabled').removeAttr('title').attr({'value': obj.data('value'), 'name': obj.data('name')});		
	});

	$('.wuoc_settings_div a.nav-tab').click(function () {

		$(this).siblings().removeClass('nav-tab-active');
		$(this).addClass('nav-tab-active');
		$('.nav-tab-content, form:not(.wrap.wuoc_settings_div .nav-tab-content)').hide();
		$('.nav-tab-content').eq($(this).index()).show();
		$('form#wuoc_auto_combined_form').show();
		$('.nav-tab-content').eq($(this).index()).find('form').show();
		window.history.replaceState('', '', wuoc_obj.this_url + '&t=' + $(this).index());
		$('form input[name="wuoc_tn"]').val($(this).index());
		wuoc_obj.wuoc_tab = $(this).index();
		// wos_trigger_selected_ie();

	});
	
	$('#number_to_combine').on('change', function(){
		$('.number_to_combine').html($(this).val());
	});

	$('select#bulk-action-selector-top').on('change', function () {
		switch ($(this).val()) {
			default:
				$('#the-list .wc_actions.column-wc_actions p .wuoc_parent, input[name="wuoc_parent"][type="hidden"]').remove();
				break;
			case 'wuoc_combine':
				$('#the-list input[name="post[]"][type="checkbox"]:checked').addClass('wuoc_parent_mark').prop('checked', false);
				$('#the-list input[name="post[]"][type="checkbox"].wuoc_parent_mark').removeClass('wuoc_parent_mark').click();
				break;
		}
	});
	$('#the-list input[name="post[]"][type="checkbox"]').on('click', function () {
		switch ($('select#bulk-action-selector-top').val()) {
			case 'wuoc_combine':
				var obj = $(this).parents().eq(1).find('.wc_actions.column-wc_actions p');
				if ($(this).is(':checked')) {
					if (obj.find('a.wuoc_parent').length == 0) {
						obj.append('<a title="Click here to mark this item as parent/main order during this action" class="button wc-action-button wc-action-button-wuoc_parent wuoc_parent"></a>');
					}
				} else {
					obj.find('.wuoc_parent').remove();
				}
				break;
		}
	});

	$('#the-list .wc_actions.column-wc_actions').on('click', 'p .wuoc_parent', function (event) {
		event.preventDefault();
		$('p .wuoc_parent.selected').not(this).removeClass('selected');
		$(this).toggleClass('selected');

		var cvalue = $(this).parents().eq(2).find('input[name="post[]"][type="checkbox"]:checked').val();
		if ($('input[name="wuoc_parent"][type="hidden"]').length == 0) {
			$('form#posts-filter').prepend('<input type="hidden" name="wuoc_parent" value="' + cvalue + '">');
		} else {
			$('input[name="wuoc_parent"][type="hidden"]').val(cvalue);
		}
	});

	$('.wuoc_option_item').on('mousedown', function () {
		$(this).css({
			cursor: "grabbing"
		});
	});

	$('.wuoc_option_item').on('mouseup', function () {
		$(this).css({
			cursor: "grab"
		});
	});

	$('#wuoc_sortable').sortable();

	$('body').on('click', '.wuoc-none', function () {
		var section_id = $(this).parent().data('id');
		$('#group-' + section_id + ' input[type="checkbox"]').prop('checked', false);
		get_checked_order_qty(section_id);
		wuoc_general_checked_order_qty();

	});

	$('body').on('click', '.wuoc-all', function () {
		var section_id = $(this).parent().data('id');
		$('#group-' + section_id + ' input[type="checkbox"]').prop('checked', true);
		get_checked_order_qty(section_id);
		wuoc_general_checked_order_qty();

	});

	$('.wuoc-section-none').on('click', function () {
		var section_id = $(this).parent().data('id');
		// $('.wuoc_analyze_box input[type="checkbox"]').prop('checked',false);
		//$('small[data-id^="' + section_id + '"]').find('.wuoc-none').click();
		//$(this).closest('.inner-group').find('input[type="checkbox"]').prop('checked',false);
		//get_checked_order_qty(section_id);
		//wuoc_general_checked_order_qty();
		$(this).closest('.inner-group').find('.wuoc-none').click();
	});

	$('.wuoc-section-all').on('click', function () {
		var section_id = $(this).parent().data('id');
		// $('.wuoc_analyze_box input[type="checkbox"]').prop('checked',true);
		//$('small[data-id^="' + section_id + '"]').find('.wuoc-all').click();
		//(this).closest('.inner-group').find('input[type="checkbox"]').prop('checked',true);
		//get_checked_order_qty(section_id);
		//wuoc_general_checked_order_qty();
		$(this).closest('.inner-group').find('.wuoc-all').click();
		
	});

	$('#wuoc_order_status').on('change', function () {
		if (wuoc_obj.analyze_orders == 'true') {
			document.location.reload();
		}
	});

	/* New Code Start 27/12/2019 */

	$('.wuoc_settings_div a.nav-tab').click(function () {

		if ($(this).data('tab') == 'combined_orders') {
			$('.wuoc_combined_order_wrapper').find('.wuoc_loader').removeClass('d-none');

			var data = {
				action: "wuoc_get_combined_orders_html",
				wuoc_get_combined_ajax: "wuoc_get_combined_orders_ajax",
			}

			$.post(ajaxurl, data, function (data) {

				$('.wuoc_combined_order_wrapper').find('.wuoc_loader').addClass('d-none');
				$('.wuoc_analyze_box_wrapper').remove();
				$('.wuoc_combined_order_wrapper').append(data);
				$('.wuoc_field_set_container').removeClass('d-none');

			});

		}

		if ($(this).data('tab') == 'trash_orders') {
			$('.wuoc_trash_order_wrapper').find('.wuoc_loader').removeClass('d-none');

			var data = {

				action: "wuoc_get_trash_orders_html",
				wuoc_get_combined_ajax: "wuoc_get_trash_orders_ajax",
			}

			$.post(ajaxurl, data, function (data) {

				$('.wuoc_trash_order_wrapper').find('.wuoc_loader').addClass('d-none');
				$('.wuoc_analyze_trash_box_wrapper').remove();
				$('.wuoc_trash_order_wrapper').append(data);
				$('.wuoc_field_set_trash_container').removeClass('d-none');

			});

		}


	});


	$('body').on('change', 'input[name^="wuoc_trash_orders_list"], input[name^="wuoc_combined_orders_list"], input[name^="wuoc_orders_list"]', function () {


		var list_group = $(this).parents().eq(1).attr("id");
		var section_id = list_group.replace('group-', '');
		var checked_orders = get_checked_order_qty(section_id);
		// $(get_checked_order_qty);

	});

	$('body').on('change', 'input[name^="wuoc_orders_list"]', function () {

		wuoc_general_checked_order_qty();
	});

	if (wuoc_obj.wuoc_tab == 0) {
		wuoc_general_checked_order_qty();
	};

	if (wuoc_obj.wuoc_tab == 1) {
		$('.wuoc_settings_div a.nav-tab:contains("Combined Orders")').click();
	};

	

	$('.wuoc_search_box').on('keydown', function () {
		
		setTimeout(function(){

			var text = $.trim($('.wuoc_search_box').val());

			if (text != '') {


				var criteria_head = $("div.wuoc_criteria_head");
				var list_group_heading = $('.wuoc_analyze_box div[id^="heading-"]');
				var list_group = $('.wuoc_analyze_box ul.list-group');
				var list_group_item = $('.wuoc_analyze_box ul li.list-group-item');
	
									
	
				for (var i = 0; i < list_group_item.length; i++) {
					var para = $(list_group_item[i]).html();
					if (typeof para != 'undefined') {
						var item_index = para.toLowerCase().indexOf(text.toLowerCase());
						var target_item = $(list_group_item[i]);
	
	
						if (item_index != -1) {
	
							target_item.show();
	
						} else {
	
							target_item.hide();
	
						}
					}
				}
	
				
	
				$.each(criteria_head, function(){
	
					var analyze_box_ul_id = $(this).find("small[data-id]").data('id');
					var analyze_box_ul = $('.wuoc_analyze_box ul[id^="group-'+analyze_box_ul_id+'"]');
					var analyze_box_heading_visible = $('.wuoc_analyze_box div[id^="heading-'+analyze_box_ul_id+'"]:visible').length;
					
	
					$.each(analyze_box_ul, function(){
	
						var visible_li_len = $(this).find("li.list-group-item:visible").length;
		
		
						var group_id = $(this).attr("id");
						var heading_id = group_id.replace("group", "#heading");
						
		
						if(visible_li_len == 0){
							$(heading_id).hide();
						}else{
							$(heading_id).show();
						}
	
						analyze_box_heading_visible = $('.wuoc_analyze_box div[id^="heading-'+analyze_box_ul_id+'"]:visible').length;
	
	
						
					});
	
					if(analyze_box_heading_visible != 0){
						
						$(this).show();
						
						
					}else{
	
						$(this).hide();			
						
						
					}			
	
					
				});
	
				if($("div.wuoc_criteria_head:visible").length == 0){
					
					$('#wuoc_no_match').remove();
					$('.wuoc_analyze_box').before(`<div class="alert alert-danger alert-dismissible" id="wuoc_no_match">
					<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
						`+wuoc_obj.no_results+`
					</div>`);
	
				}else{
					$('#wuoc_no_match').remove();
	
				}
				
	
			}
		},100);

		

		

	});


	$('.wuoc_trash_link').on('click', function(e){
		e.preventDefault();

		$('.nav-tab.trash').click();
	})


	$('body').on('click', '.wuoc-del_perm-btn:not(.hide)', function(e){

		e.preventDefault();
		var confirmation = confirm(wuoc_obj.delete_confirmation);

		if(confirmation){

			$(this).parents('p.submit:first').find('.wuoc-del_perm-btn.hide').click();
		}

	});


	$('.wuoc_settings_div .accordion_wrapper button.accordion').on('click', function(e){

		e.preventDefault();
		var next_panel = $(this).next('.panel:first');
		var this_parent = $(this).parents('.accordion_wrapper:first');
		this_parent.find('.panel').not(next_panel).slideUp();
		$(this).next('.panel:first').slideDown();

		$('.wuoc_settings_div .accordion_wrapper button.accordion .minus').hide();
		$('.wuoc_settings_div .accordion_wrapper button.accordion .plus').show();

		$(this).find('.plus').hide();
		$(this).find('.minus').show();


	});

		
	$.each($('.wuoc_auto_combined_statuses'), function(){
		var select_id = $(this).attr('id');
		
		new SlimSelect({
	
			select: '#'+select_id,
			placeholder: 'Select statuses',
			closeOnSelect: false,
		});
	});


	setTimeout(function(){

		$('.wuoc_settings_saved_notice').fadeOut();


	}, 5000)

	$('#wuoc_auto_combined_form .rules_row input[type="checkbox"]').on('change', function(){
		
		var obj = $(this);
		var required_rules = $('#wuoc_auto_combined_form .rules_row input[type="checkbox"].rule_required:checked');
		var required_rule_notice = $('#wuoc_auto_combined_form .required_rule_notice');
		
		if(obj.parents().eq(1).find('ul').length>0){
			if(obj.is(':checked')){
				obj.parents().eq(1).find('ul').show();
			}else{
				obj.parents().eq(1).find('ul').hide();
			}
		}

		if(required_rules.length == 0){

			required_rule_notice.show();

		}else{

			required_rule_notice.hide();

		}


	});

	$('#wuoc_auto_combined_form .rules_row input[type="checkbox"]').change();


	$('form.wuoc_general_settings input[type="checkbox"][name^="wuoc_combined_option"]').on('change', function(){	
			
		switch($(this).val()){
			case '_billing_address_index':
				$('input[type="checkbox"][id="_gs_shipping_address_index"]').prop('checked', false);
			break;
			case '_shipping_address_index':
				$('input[type="checkbox"][id="_gs_billing_address_index"]').prop('checked', false);
			break;
			case '_billing_phone':
				$('input[type="checkbox"][id="_gs_shipping_phone"]').prop('checked', false);
			break;
			case '_shipping_phone':
				$('input[type="checkbox"][id="_gs_billing_phone"]').prop('checked', false);
			break;
			case '_billing_email':
				$('input[type="checkbox"][id="_gs_shipping_email"]').prop('checked', false);
			break;
			case '_shipping_email':
				$('input[type="checkbox"][id="_gs_billing_email"]').prop('checked', false);
			break;						
		}
	});
	$('form.wuoc_auto_combined_form input[type="checkbox"][name^="wuoc_auto_combined"]').on('change', function(){		
		
		switch($(this).val()){
			case '_billing_address_index':
				$('input[type="checkbox"][id="_cs_shipping_address_index"]').prop('checked', false);
			break;
			case '_shipping_address_index':
				$('input[type="checkbox"][id="_cs_billing_address_index"]').prop('checked', false);
			break;
			case '_billing_phone':
				$('input[type="checkbox"][id="_cs_shipping_phone"]').prop('checked', false);
			break;
			case '_shipping_phone':
				$('input[type="checkbox"][id="_cs_billing_phone"]').prop('checked', false);
			break;
			case '_billing_email':
				$('input[type="checkbox"][id="_cs_shipping_email"]').prop('checked', false);
			break;
			case '_shipping_email':
				$('input[type="checkbox"][id="_cs_billing_email"]').prop('checked', false);
			break;						
		}
	});
	$('a.wuoc_logger_clear_log').on('click', function (e) {

		e.preventDefault();

		$('.wuoc_logger ul.wuoc_debug_log').html('');

		var data = {

			action: 'wuoc_logger_clear_log',
			wuoc_logger_clear_log: 'true',
			wuoc_logger_clear_log_field: wuoc_obj.nonce,
		}

		// console.log(data);
		$.post(ajaxurl, data, function (response, code) {

			// console.log(response);
			if (code == 'success') {


				//
			}

		});


	});
	
	$('#wuoc_auto_delete_original_orders').on('click', 'input[type="radio"]', function(){
		var val = $(this).val();
		$(this).parents().eq(1).find('li.selected').removeClass('selected');
		$(this).parent().addClass('selected');
		switch(val){
			case 'none':
				
			break;
			default:
				
			break;
		}
	});
	
	$('body').on('click', '.wuoc-combined-pagination button', function(){
		var set = $(this).data('set');
		var data = {

			action: 'wuoc_load_combined_orders',
			wuoc_load_combined_orders: 'true',
			nonce_field: wuoc_obj.nonce,
			set: set,
		}
		
		$('.wuoc_combined_order_wrapper').find('.wuoc_loader').removeClass('d-none');
		
		//$.blockUI({message:''});
		$.post(ajaxurl, data, function (response, code) {

			// console.log(response);
			if (code == 'success') {
				
				
				if($.trim(response)!=''){
					$('.wuoc_combined_order_wrapper .wuoc_analyze_box_wrapper').remove();
					$('.wuoc_combined_order_wrapper').append(response);
				}
				
			}
			//$.unblockUI();
			$('.wuoc_combined_order_wrapper').find('.wuoc_loader').addClass('d-none');

		});
		
		
	});
	$('#wuoc_auto_combine_layers').on('change', function(){
		var data = {
			action: 'wuoc_update_rules_layers',
			wuoc_rules_layers: $(this).val(),
			nonce_field: wuoc_obj.nonce,
		}
		$.blockUI({message:''});
		$.post(ajaxurl, data, function (response, code) {
			if (code == 'success') {
				if($.trim(response)!=''){
				}
			}
			document.location.reload();
			//$.unblockUI();
		});
	});
	
	$('#clear-wuoc-meta').on('click', function(){
		
		var ids = $('input[name="clear-wuoc-meta-for-order-ids"]').val();
		
		if(ids){
			var data = {
				action: 'wuoc_clear_meta_data',
				order_ids: ids,
				nonce_field: wuoc_obj.nonce,
			}
			$.blockUI({message:''});
			$.post(ajaxurl, data, function (response, code) {
				if (code == 'success') {
					if($.trim(response)!=''){
					}
				}
				//document.location.reload();
				$.unblockUI();
			});
		}
	});
	
});