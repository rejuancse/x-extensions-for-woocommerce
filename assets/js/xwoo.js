/*========================================================================
 * WP Xwoo
 *======================================================================== */
jQuery(document).ready(function($){

	/**
	 * Show necessary Meta field and hide base on product type select
	 * WooCommerce compatibility
	 */
	$('.show_if_neo_xwoo_options').hide();
	$('#campaign-update-status-meta').hide();
	$('body').on('change','select#product-type',function() {
		if (this.value == "xwoo"){
			$('ul.product_data_tabs li').removeClass('active');
			$('.panel').hide();
			$('.show_if_neo_xwoo_options').show();
			$('.general_tab').addClass('active').show();
			$('#general_product_data').show();
			$('#campaign-update-status-meta').show();
		} else {
			$('.show_if_neo_xwoo_options').hide();
			$('#campaign-update-status-meta').hide();
		}
	});

	if ($('select#product-type').val() == "xwoo"){
		$('.show_if_neo_xwoo_options').show();
		$('#campaign-update-status-meta').show();
	}

    //Select2
    if(typeof $.fn.select2 !== 'undefined' ){
    	$('select.select2').select2();
    }

	//Date picker for input
	if(typeof $.fn.datepicker !== 'undefined' ){
		$('#_nf_duration_start, #_nf_duration_end').datepicker({
			dateFormat : 'dd-mm-yy'
		});
    }

	$('body').on('click','.xwoo-image-upload-btn',function(e) {
        e.preventDefault();
        var that = $(this);
        var image = wp.media({ 
            title: 'Upload Image',
            multiple: false
        }).open().on('select', function(e){
            var uploaded_image = image.state().get('selection').first();
            var uploaded_url = uploaded_image.toJSON().url;
            uploaded_image = uploaded_image.toJSON().id;
            $(that).parent().find( '.xwoo_rewards_image_field' ).val( uploaded_image );
            $(that).parent().find( '.xwoo-image-container' ).html( '<img width="100" src="'+uploaded_url+'" ><span class="xwoo-image-remove">x</span>' );
        });
    });

	$('body').on('click','.xwoo-image-remove',function(e) {
		var that = $(this);
	    $(that).parent().parent().find( '.xwoo_rewards_image_field' ).val( '' );
        $(that).parent().parent().find( '.xwoo-image-container' ).html( '' );
	});

	$('.xwoo-color-field').wpColorPicker();
});
