/*========================================================================
 * WP Xwoo
 *======================================================================== */
jQuery(document).ready(function($){

	function countRemovesBtn(btn) {
		var rewards_count = $(btn).length;
		if (rewards_count > 1){
			$(btn).show();
		}else {
			$(btn).hide();
			if (btn == '.removeCampaignRewards') {
				$('.reward_group').show();
			}
			if (btn == '.removecampaignupdate') {
				$('#campaign_update_field').show();
			}
		}
		$(btn).first().hide();
	}

	//Add More Campaign Update Field
	$('#addreward').on('click', function (e) {
		e.preventDefault();
		$('#rewards_addon_fields').append( $('.reward_group').html() );
		countRemovesBtn('.removeCampaignRewards');
	});

	$('body').on('click', '.removeCampaignRewards', function (e) {
		e.preventDefault();
		$(this).closest('.campaign_rewards_field_copy').html('');
		countRemovesBtn('.removeCampaignRewards');
	});
	countRemovesBtn('.removeCampaignRewards');

	//Add More Campaign Update Field
	$('#addcampaignupdate').on('click', function (e) {
		e.preventDefault();
		var update = $('#campaign_update_field').html();
		$('#campaign_update_addon_field').append(update);
		countRemovesBtn('.removecampaignupdate');
	});

	$('body').on('click', '.removecampaignupdate', function (e) {
		e.preventDefault();
		$(this).closest('.campaign_update_field_copy').html('').hide();
		countRemovesBtn('.removecampaignupdate');
	});
	countRemovesBtn('.removecampaignupdate');


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
