// Crowdfunding Scripts

jQuery(document).ready(function($){
    
    var count = 0;
    var numItems = $('.xwoo-block').length;
    if(numItems!=0){ count = numItems; }
    $.fn.createNewForm = function( count ){
        return this.each(function(){
            $(this).find('input,textarea,select').each(function(){
                var $that = $(this);
                $that.attr('name', this.name.replace(/\d/, count ));
                $that.val('');
            });
        });
    };
    $('.add-new').on('click', function(e){
        var $form       = $('.xwoo-block').last(),
            $cloned     = $form.clone();
        $cloned.createNewForm(count);
        $('#xwoo-clone .add-new').before( $($cloned) );
        count = count+1;
    });
    $(document).on('click','.remove-button',function(events){
        if($('.xwoo-block').length > 1){
            $(this).parent('.xwoo-block').remove();
        }
    });
    $('#xwoo_form_start_date, #xwoo_form_end_date').datepicker({
        dateFormat : 'dd-mm-yy'
    });

    $('.xwoo_donate_button').on('click', function(e){
        const data = $('.xwoo_donate_amount_field').val();
        if(!data){
            e.preventDefault();
            $('.xwoo-tooltip-empty').css({'visibility': 'visible'});
        } else {
            $('.xwoo-tooltip-empty').css({'visibility': 'hidden'});
        }
    });

    // Pie Chart
    $('.crowdfound-pie-chart').easyPieChart({
        barColor: '#1adc68',
        trackColor: '#f5f5f5',
        scaleColor: false,
        lineWidth: 5,
    });

    $('.datepickers_1').datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $('.datepickers_2').datepicker({
        dateFormat: 'yy-mm-dd'
    });

    // Insert Campaign Post Data
    $('#xwoofrontenddata').submit(submit_frontend);
    function submit_frontend(){
        tinyMCE.triggerSave();
        var front_end_data = $(this).serialize();
        $.ajax({
            type:"POST",
            url: xwoo_ajax_object.ajax_url,
            data: front_end_data,
            success:function(data){
                var parseData = JSON.parse(data);

                if ( ! parseData.success){
                    //Reset reCaptcha if failed
                    if( (typeof grecaptcha !== 'undefined') && ($('.g-recaptcha').length !== 0) ) {
                        grecaptcha.reset();
                    }
                }
                if (xwoo_modal(data)){  }
            },
            error: function(jqXHR, textStatus, errorThrown){
                xwoo_modal({'success':0, 'message':'Error sending data'})
            }
        });
        return false;
    }

    $( document ).on('click', '.xwoo-print-button', function (e) {
        window.print();
    });

    // Common Modal Function
    function xwoo_modal( data, print = false ){
        var data = JSON.parse(data);
        var html = '<div class="xwoo-modal-wrapper"> ' +
            ' <div class="xwoo-modal-content"> ' +
            '<div class="xwoo-modal-wrapper-head">' +
            '<span id="xwoo_modal_title">Message</span><a href="javascript:;" class="xwoo-modal-close">&times;</a>';
            if( print ){
                html += '</div><span class="xwoo-print-button button xwoo-edit-btn">print</span>';
            }
            html += '<div class="xwoo-modal-content-inner"><div id="xwoo_modal_message"></div></div></div></div>';
        if ($('.xwoo-modal-wrapper').length == 0){
            $('body').append(html);
            if (data.redirect){
                if ( $('#xwoo_redirect_url').length == 0 ){
                    $('body').append('<input type="hidden" id="xwoo_redirect_url" value="'+data.redirect+'" />');
                }
            }
        }
        if (data.success == 1){
            if(data.message){
                $('.xwoo-modal-wrapper #xwoo_modal_message').html( data.message );
            }
            if(data.title){
                $('.xwoo-modal-wrapper #xwoo_modal_title').html( data.title );
            }
            $('.xwoo-modal-wrapper').css({'display': 'block'});
            if ( $('#xwoofrontenddata').length > 0 ){
                $("#xwoofrontenddata")[0].reset();
            }
            return true;
        }else {
            $('.xwoo-modal-wrapper #xwoo_modal_message').html(data.message);
            $('.xwoo-modal-wrapper').css({'display': 'block'});
            return false;
        }
    }
    window.xwoo_modal = xwoo_modal; //make global function

    // Image Upload Function
    function xwoo_upload_image( button_class ) {
        var _custom_media = true,
            _orig_send_attachment = wp.media.editor.send.attachment;
        $('body').on('click',button_class, function(e) {
            var button_id ='#'+$(this).attr('id');
            var button = $(button_id);
            _custom_media = true;
            wp.media.editor.send.attachment = function(props, attachment){
                if ( _custom_media  ) {
                    var attachment_url = attachment.url;
                    $('.xwoo-form-image-url').val(attachment_url);
                    $('.xwoo-form-image-id').val(attachment.id);
                } else {
                    return _orig_send_attachment.apply( button_id, [props, attachment] );
                }
            };
            wp.media.editor.open(button);
            return false;
        });
    }
    xwoo_upload_image('.xwoo-image-upload');
    $(document).on('click','.add_media', function(){
        _custom_media = false;
    });

    // Login Toggle Add (Frontend Submit Form)
    $('.xwooShowLogin').on('click', function (e) {
        e.preventDefault();
        $('.xwoo_login_form_div').slideToggle();
    });

    // Repeatable Rewards
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

    //Add Rewards
    $('#addreward').on('click', function (e) {
        e.preventDefault();
        var rewards_fields = $('.reward_group').html();
        $('#rewards_addon_fields').append(rewards_fields).hide().show('slow');
        $('#rewards_addon_fields .campaign_rewards_field_copy:last-child').find('input,textarea,select').each(function(){
            if ( ($(this).attr('name') != 'remove_rewards')&&($(this).attr('type') != 'button') ){
                $(this).val('');
            }
        });
        countRemovesBtn('.removeCampaignRewards');
    });

    // Remove Campaign Reward
    $('body').on('click', '.removeCampaignRewards', function (e) {
        e.preventDefault();
        const topPosition = $(this).closest('#reward_options').offset().top;
        $(this).closest('.campaign_rewards_field_copy').html('');
        $("html, body").animate({ scrollTop: topPosition - 100 }, 100);
        countRemovesBtn('.removeCampaignRewards');
    });
    countRemovesBtn('.removeCampaignRewards');

    //Add More Campaign Update Field
    $('#addcampaignupdate').on('click', function (e) {
        e.preventDefault();
        var update_fields = $('#campaign_update_field').html();
        $('#campaign_update_addon_field').append(update_fields);
        countRemovesBtn('.removecampaignupdate');
    });
    
    // Remove Campaign Update
    $('body').on('click', '.removecampaignupdate', function (e) {
        e.preventDefault();
        $(this).closest('.campaign_update_field_copy').html('').hide();
        countRemovesBtn('.removecampaignupdate');
    });
    countRemovesBtn('.removecampaignupdate');

    // Dashboard Edit Form
    $('#xwoo_active_edit_form').on('click', function(e){
        e.preventDefault();
        $('#xwoo_update_display_wrapper').hide();
        $('#xwoo_update_form_wrapper').fadeIn('slow');
    });

    // Edit Enable
    $('#xwoo-edit').on('click', function (e) {
        e.preventDefault();
        $('#xwoo-edit').hide();
        $('.xwoo-content input,.xwoo-content textarea,.xwoo-content select').not('.xwoo-content input[name="username"]').removeAttr("disabled").css( "border", "1px solid #dfe1e5" );
        $('.xwoo-save-btn').delay(100).fadeIn('slow');
        $('.xwoo-cancel-btn').delay(100).fadeIn('slow');
        $('button.xwoo-image-upload').show();
    });

    // Dashboard Data Save
    function xwoo_dashboard_data_save(){
        var return_data;
        var postdata = $('#xwoo-dashboard-form').serializeArray();
        $.ajax({
                async: false,
                url : xwoo_ajax_object.ajax_url,
                type: "POST",
                data : postdata,
                success:function(data, textStatus, jqXHR) {
                    xwoo_modal(data);
                    return_data = data;
                },
                error: function(jqXHR, textStatus, errorThrown){
                    xwoo_modal({'success':0, 'message':'Error sending data'})
                }
            });
        $('.xwoo-content input,.xwoo-content textarea,.xwoo-content select').attr("disabled","disabled").css( "border", "none" );
        $('.xwoo-cancel-btn').hide();
        $('#xwoo-edit').delay(100).fadeIn('slow');
        return return_data;
    }

    // Dashboard Cancel Button
    $('.xwoo-cancel-btn').on('click', function(e){
        e.preventDefault();
        $('.xwoo-content input,.xwoo-content textarea,.xwoo-content select').attr("disabled","disabled").css( "border", "none" );
        $('.xwoo-cancel-btn').hide();
        $('#xwoo-dashboard-save').hide();
        $('#xwoo-profile-save').hide();
        $('#xwoo-contact-save').hide();
        $('button.xwoo-image-upload').hide();
        $('#xwoo-edit').delay(100).fadeIn('slow');
    });

    // Dashboard Froentend ( Dashboard )
    $('#xwoo-dashboard-save').on('click', function (e) {
        e.preventDefault(); //STOP default action
        var postdata = $('#xwoo-dashboard-form').serializeArray();
        xwoo_dashboard_data_save();
    });

    // Dashboard Froentend ( Profile )
    $('#xwoo-profile-save').on('click', function (e) {
        e.preventDefault(); //STOP default action
        xwoo_dashboard_data_save();
    });

    // Dashboard Froentend ( Contact )
    $('#xwoo-contact-save').on('click', function (e) {
        e.preventDefault(); //STOP default action
        xwoo_dashboard_data_save();
    });

    // Dashboard Froentend ( Password )
    $('#xwoo-password-save').on('click', function (e) {
        e.preventDefault(); //STOP default action
        xwoo_dashboard_data_save();
    });

    // Dashboard Froentend ( Update )
    $('#xwoo-update-save').on('click', function (e) {
        e.preventDefault(); //STOP default action
        var return_respone = xwoo_dashboard_data_save();
        xwoo_modal(return_respone);
    });

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

    // Modal Bio in Product details
    $('.xwoo-fund-modal-btn').on('click', function (e) {
        e.preventDefault();
        var author = $(this).data('author');
        $.ajax({
            type:"POST",
            url: xwoo_ajax_object.ajax_url,
            data: { 'action': 'xwoo_bio_action', 'author': author },
            success:function(data){
                xwoo_modal( data );
            },
            error: function(jqXHR, textStatus, errorThrown){ xwoo_modal({'success':0, 'message':'Error'}) }
        });
    });

    // Modal Close Option
    $(document).on('click', '.xwoo-modal-close', function(){
        $('.xwoo-modal-wrapper').css({'display': 'none'});
        if ( $('#xwoo_redirect_url').length > 0 ) {
            location.href = $('#xwoo_redirect_url').val();
        }
    });

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

    $(document).on('click', '#user-registration-btn', function (e) {
        e.preventDefault();
        var registration_form_data = $(this).closest('form').serialize();
        $.ajax({
            type:"POST",
            url: xwoo_ajax_object.ajax_url,
            data: registration_form_data,
            success:function(data){
                xwoo_modal(data);
                data = JSON.parse(data);
                if (data.success){
                    location.href = data.redirect;
                }else {
                    if(typeof grecaptcha !== 'undefined'){
                        grecaptcha.reset();
                    }
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                xwoo_modal({'success':0, 'message':'Error'});
            }
        });
    });
    
    var image = $('input[name=xwoo-form-image-url]').val();
    if( image!='' ){
        $('#xwoo-image-show').html('<img width="150" src="'+image+'" />');
    }
    $(document).on('click','.media-button-insert',function(e){
        var image = $('input[name=xwoo-form-image-url]').val();
        if( $('.profile-form-img').length > 0 ){
            $('.profile-form-img').attr( 'src',image );
        }else{
            if(image!=''){
                $('#xwoo-image-show').html('<img width="150" src="'+image+'" />');
            }
        }
    });

    // Hide Billing and Shipping Information
    if( $('body.woocommerce-checkout').length >= 1 ){
        if( $('#billing_email').length < 1 ){
            $('#customer_details').css({'display': 'none'});
        }
    }

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

    // Reward On Click
    $('body').on('click','.price-value-change',function(e) {
        e.preventDefault();
        var reward = $(this).data('reward-amount');
        $("html, body").animate({ scrollTop: 0 }, 600,
            function() {
                setTimeout(function(){
                    $(".xwoo_donate_amount_field").addClass("xwoosplash");
                }, 100 );
                setTimeout(function(){
                    $(".xwoo_donate_amount_field").val( reward );
                    $( ".xwoo_donate_amount_field" ).removeClass( "xwoosplash" );
                }, 1000 );
            });
    });
    $(document).on('click','table.reward_table_dashboard tr',function(e) {
        $(this).find('.reward_description').slideToggle();
    });

    // Order View (Dashboard Page)
    $(document).on('click', '.xwoo-order-view', function (e) {
        e.preventDefault();
        var orderid = $(this).data('orderid');
        $.ajax({
            type:"POST",
            url: xwoo_ajax_object.ajax_url,
            data: { 'action': 'xwoo_order_action', 'orderid': orderid },
            success:function(data){
                xwoo_modal( data, true );
            },
            error: function(jqXHR, textStatus, errorThrown){
                xwoo_modal({'success':0, 'message':'Error'})
            }
        });
    });

    // Embed Popup (Single Page)
    $(document).on('click', '.xwoo-icon-embed', function (e) {
        e.preventDefault();
        var postid = $(this).data('postid');
        $.ajax({
            type:"POST",
            url: xwoo_ajax_object.ajax_url,
            data: { 'action': 'xwoo_embed_action', 'postid': postid },
            success:function(data){
                xwoo_modal(data);
            },
            error: function(jqXHR, textStatus, errorThrown){ xwoo_modal({'success':0, 'message':'Error'}) }
        });
    });

    // $(document).on('click', '.xwoo-export-data', function (e) {
    //     e.preventDefault();
    //     //var postid = $(this).data('postid');
    //     console.log('Done 2');
    //     $.ajax({
    //         type:"POST",
    //         url: xwoo_ajax_object.ajax_url,
    //         data: { 'action': 'download_data', 'postid': 222 },
    //         success:function(data){
    //             // xwoo_modal(data);
    //             console.log('Done 1');
    //         },
    //         error: function(jqXHR, textStatus, errorThrown){ 
    //             console.log('1->', jqXHR);
    //             console.log('2->', textStatus);
    //             console.log('3->', errorThrown);
    //         }
    //     });
    // });

    /**
     * Place the predefined price in the donation input value
     *
     * @since 10.22
     */
    $(document).on('click', 'ul.xwoo_predefined_pledge_amount li a', function(){
        var price = $(this).attr('data-predefined-price');
        $('.xwoo_donate_amount_field').val(price);
    });

    $('select[name="xwoo-form-type"]').on('change', function(){
        if( $(this).val() == 'never_end' ){
            $('#xwoo_form_start_date').parents('.xwoo-single').hide();
            $('#xwoo_form_end_date').parents('.xwoo-single').hide();
        }else{
            $('#xwoo_form_start_date').parents('.xwoo-single').show();
            $('#xwoo_form_end_date').parents('.xwoo-single').show();
        }
    });
    
});
