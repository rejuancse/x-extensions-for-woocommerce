/*-------------------------
 * XWOO Extensions
 *------------------------- */
jQuery(document).ready(function($){

	/**
	 * xwoo extension color picker
	 *--------------------------------- */ 
	$('.xwoo-color-field').wpColorPicker();

	/*
	 * xwoo extension enable/diable.
	 *---------------------------------- */ 
	$(document).on('change', '.xwoo_extensions_list_item', function(e) {
        var $that = $(this);
        var isEnable = $that.prop('checked') ? 1 : 0;
        var addonFieldName = $that.attr('name');
        $.ajax({
            url : ajaxurl,
            type : 'POST',
            data : { 
				isEnable:isEnable, 
				addonFieldName:addonFieldName, 
				action : 'xwoo_addon_enable_disable'
			},
            success: function (data) {
                if (data.success){
                    //Success
                }
            }
        });
    });
	
});
