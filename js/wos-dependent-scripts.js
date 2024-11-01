jQuery(document).ready(function($){

    $('select#bulk-action-selector-top').on('change', function(){
        switch($(this).val()){
            default:
                $('#the-list .wc_actions.column-wc_actions p .wc_os_parent, input[name="wc_os_parent"][type="hidden"]').remove();
                break;
            case 'combine':
            case 'wuoc_combine':

                $('#the-list input[name="post[]"][type="checkbox"]:checked').addClass('wos_parent_mark').prop('checked', false);
                var current_obj = $('#the-list input[name="post[]"][type="checkbox"].wos_parent_mark');

                $.each(current_obj, function () {
                    var obj = $(this).parents().eq(1).find('.wc_actions.column-wc_actions p');
                    obj.find('.wc_os_parent').remove();
                });

                current_obj.removeClass('wos_parent_mark').click();
                break;
        }
    });
    $('#the-list input[name="post[]"][type="checkbox"]').on('click', function(){
        switch($('select#bulk-action-selector-top').val()){
            case 'combine':
            case 'wuoc_combine':
                var obj = $(this).parents().eq(1).find('.wc_actions.column-wc_actions p');
                if($(this).is(':checked')){
                    if(obj.find('a.wos_parent').length==0){
                        obj.append('<a title="Click here to mark this item as parent/main order during this action" class="button wc-action-button wc-action-button-wc_os_parent wc_os_parent"></a>');
                    }
                }else{
                    obj.find('.wc_os_parent').remove();
                }
                break;
        }
    });

    $('#the-list .wc_actions.column-wc_actions').on('click', 'p .wc_os_parent', function(event){
        event.preventDefault();
        $('p .wc_os_parent.selected').not(this).removeClass('selected');
        $(this).toggleClass('selected');

        var cvalue = $(this).parents().eq(2).find('input[name="post[]"][type="checkbox"]:checked').val();
        if($('input[name="wc_os_parent"][type="hidden"]').length==0){
            $('form#posts-filter').prepend('<input type="hidden" name="wc_os_parent" value="'+cvalue+'">');
        }else{
            $('input[name="wc_os_parent"][type="hidden"]').val(cvalue);
        }
    });
	

});