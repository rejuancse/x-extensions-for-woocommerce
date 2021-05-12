// Xwoo Scripts
jQuery(document).ready(function($){

    // Tab Menu Action (Product Single)
    $('.xwoo-tabs-menu a').on("click", (function (e) {
        e.preventDefault();
        $('.xwoo-tabs-menu li').removeClass('xwoo-current');
        $(this).parent().addClass('xwoo-current');
        var currentTab = $(this).attr('href');
        $('.xwoo-tab-content').hide();
        $(currentTab).fadeIn();
        return false;
    }));
    $($('.xwoo-current a').attr('href')).fadeIn();

    // Donate Field Add Max & Min Amount
    $('input[name="xwoo_donate_amount_field"]').on('blur change paste', function(){
        var input_price = $(this).val();
        var min_price = $(this).data('min-price');
        var max_price = $(this).data('max-price');
        if (input_price < min_price){
            if(min_price){
                $(this).val( min_price );
                $('.xwoo-tooltip-min').css({'visibility': 'visible'});
            }
        }else if (max_price < input_price){
            if(max_price){
                $(this).val( max_price );
                $('.xwoo-tooltip-max').css({'visibility': 'visible'});
            }
        }else{
            $('.xwoo-tooltip-min,.xwoo-tooltip-max').css({'visibility': 'hidden'});
        }
    });

    // Add Love Campaign
    $(document).on('click', '#love_this_campaign', function () {
        var campaign_id = $(this).data('campaign-id');
        $.ajax({
            type:"POST",
            url: xwoo_ajax_object.ajax_url,
            data: {'action': 'love_campaign_action', 'campaign_id': campaign_id},
            success:function(data){
                data = JSON.parse(data);
                if (data.success == 1){
                    $('#campaign_loved_html').html(data.return_html);
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                xwoo_modal({'success':0, 'message':'Error'})
            }
        });
    });

    // Remove Love Campaign
    $(document).on('click', '#remove_from_love_campaign', function () {
        var campaign_id = $(this).data('campaign-id');
        $.ajax({
            type:"POST",
            url: xwoo_ajax_object.ajax_url,
            data: {'action': 'remove_love_campaign_action', 'campaign_id': campaign_id},
            success:function(data){
                data = JSON.parse(data);
                $('#campaign_loved_html').html(data.return_html);
            },
            error: function(jqXHR, textStatus, errorThrown){
                xwoo_modal({'success':0, 'message':'Error'})
            }
        });
    });

    // Form Reward Image Upload
    $('body').on('click','.xwoo-image-upload-btn',function(e) {
        e.preventDefault();
        var that = $(this);

        var image = wp.media({
            title: 'Upload Image',
            multiple: false
        }).open()
            .on('select', function(e){
                var uploaded_image = image.state().get('selection').first();
                var uploaded_url = uploaded_image.toJSON().url;
                uploaded_image = uploaded_image.toJSON().id;
                $(that).parent().find( '.xwoo_rewards_image_field' ).val( uploaded_image );
                $(that).parent().find( '.xwoo_rewards_image_field_url' ).val( uploaded_url );
            });
    });
    $('body').on('click','.xwoo-image-remove',function(e) {
        var that = $(this);
        $(that).parent().find( 'xwoo_rewards_image_field_url' ).val( '' );
        $(that).parent().find( '.xwoo_rewards_image_field' ).val( '' );
    });
});
