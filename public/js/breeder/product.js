'use strict';

var product = {

    before_select_value : '' ,
    current_display_photo : 0,
    modal_history : [],
    other_details_default :
        '<div class="detail-container">'+
            '<div class="input-field col s6">'+
                '<input name="characteristic[]" type="text">'+
                '<label for="characteristic[]">Characteristic</label>'+
            '</div>'+
            '<div class="input-field col s5">'+
                '<input name="value[]" type="text">'+
                '<label for="value[]">Value</label>'+
            '</div>'+
            '<div class="input-field col s1 remove-button-container">'+
'                <a href="#" class="tooltipped remove-detail" data-position="top" data-delay="50" data-tooltip="Remove detail">'+
                    '<i class="material-icons grey-text text-lighten-1">remove_circle</i>'+
                '</a>'+
            '</div>'+
        '</div>'
    ,

    modal_history_tos : function(){
        return product.modal_history[product.modal_history.length-1];
    },

    add : function(parent_form){
        // Attach overlay preloader
        $('<div id="overlay-preloader-circular" class="valign-wrapper" style="padding:7rem;">'+
            '<div class="center-align preloader-overlay">'+
                '<div class="preloader-wrapper big active">'+
                    '<div class="spinner-layer spinner-blue-only">'+
                        '<div class="circle-clipper left">'+
                          '<div class="circle"></div>'+
                        '</div><div class="gap-patch">'+
                          '<div class="circle"></div>'+
                        '</div><div class="circle-clipper right">'+
                          '<div class="circle"></div>'+
                        '</div>'+
                    '</div>'+
                '</div>'+
            '</div>'+
        '</div>')
        .css({
            position: "absolute", width: "100%", height: "100%", top: $('#add-product-modal .modal-content').scrollTop(), left: 0, background: "rgba(255,255,255,0.8)", display:"block"
        })
        .appendTo($("#add-product-modal .modal-content").css("position", "relative"));

        // Let the overlay preloader change its top position every scroll
        $('#add-product-modal .modal-content').scroll(function(){
            $('#overlay-preloader-circular').css({top:$(this).scrollTop()});
        });

        var data_values = {
            "name": parent_form.find('input[name=name]').val(),
            "type": parent_form.find('#select-type').val(),
            "farm_from_id": parent_form.find('#select-farm').val(),
            "age": parent_form.find('input[name=age]').val(),
            "price": parent_form.find('input[name=price]').val(),
            "adg": parent_form.find('input[name=adg]').val(),
            "fcr": parent_form.find('input[name=fcr]').val(),
            "backfat_thickness": parent_form.find('input[name=backfat_thickness]').val(),
            "_token" : parent_form.find('input[name=_token]').val(),
        };

        // Only get quantity field from semen product type
        if($('#select-type').val() === 'semen') data_values["quantity"] = parent_form.find('input[name=quantity]').val();
        else data_values["quantity"] = 1;

        // Transform breed syntax if crossbreed
        if($("#create-product input:checked").val() === 'crossbreed'){
            var fbreed = parent_form.find('input[name=fbreed]').val();
            var mbreed = parent_form.find('input[name=mbreed]').val();

            data_values["breed"] = fbreed.toLowerCase().trim()+'+'+mbreed.toLowerCase().trim();
        }
        else data_values["breed"] = parent_form.find('input[name=breed]').val().toLowerCase().trim();

        // Transform syntax of Other details category values
        var other_details = '';
        $(parent_form).find('.detail-container').map(function () {
            var characteristic = $(this).find('input[name="characteristic[]"]').val();
            var value = $(this).find('input[name="value[]"]').val();
            if(characteristic && value) other_details += characteristic+' = '+value+',';
        });

        data_values["other_details"] = other_details;

        // Do AJAX
        $.ajax({
            url: parent_form.attr('action'),
            type: "POST",
            cache: false,
            data: data_values,
            success: function(data){
                var data = JSON.parse(data);
                var hidden_inputs =
                    '<input name="productId" type="hidden" value="'+data.product_id+'">'+
                    '<input name="name" type="hidden" value="'+data.name+'">'+
                    '<input name="type" type="hidden" value="'+data.type+'">'+
                    '<input name="breed" type="hidden" value="'+data.breed+'">';

                Materialize.toast('Product added!', 2500, 'green lighten-1');

                $('#media-dropzone').append(hidden_inputs);
                $('#add-media-modal h4').append(' to '+data.name);
                $('.add-product-button').attr('href','#add-media-modal');
                $('#overlay-preloader-circular').remove();
                $('#add-product-modal').closeModal();
                parent_form.find('#submit-button').removeClass('disabled');

                // Open Add Media Modal
                $('#add-media-modal').openModal({
                    dismissible: false,
                    ready: function(){
                        // Resize media-dropzone's height
                        var content_height = $('#add-media-modal .modal-content').height();
                        var header_height = $('#add-media-modal h4').height();
                        $('#media-dropzone').css({'height': content_height - header_height});

                        $( window ).resize(function() {
                            var content_height = $('#add-media-modal .modal-content').height();
                            var header_height = $('#add-media-modal h4').height();
                            $('#media-dropzone').css({'height': content_height - header_height});
                        });
                    }
                });
                product.modal_history.push('#add-media-modal');
            },
            error: function(message){
                console.log(message['responseText']);
                $('#overlay-preloader-circular').remove();
            }
        });
    },

    edit: function(parent_form){
        var data_values = {
            "id": parent_form.find('input[name=productId]').val(),
            "name": parent_form.find('input[name=name]').val(),
            "type": parent_form.find('#edit-select-type').val(),
            "farm_from_id": parent_form.find('#edit-select-farm').val(),
            "age": parent_form.find('input[name=age]').val(),
            "price": parent_form.find('input[name=price]').val(),
            "adg": parent_form.find('input[name=adg]').val(),
            "fcr": parent_form.find('input[name=fcr]').val(),
            "backfat_thickness": parent_form.find('input[name=backfat_thickness]').val(),
            "_token" : parent_form.find('input[name=_token]').val(),
        };

        // Only get quantity field from semen product type
        if($('#edit-select-type').val() === 'semen') data_values["quantity"] = parent_form.find('input[name=quantity]').val();
        else data_values["quantity"] = 1;


        // Transform breed syntax if crossbreed
        if($("#edit-product input:checked").val() === 'crossbreed'){
            var fbreed = parent_form.find('input[name=fbreed]').val();
            var mbreed = parent_form.find('input[name=mbreed]').val();

            data_values["breed"] = fbreed.toLowerCase().trim()+'+'+mbreed.toLowerCase().trim();
        }
        else data_values["breed"] = parent_form.find('input[name=breed]').val().toLowerCase().trim();

        // Transform syntax of Other details category values
        var other_details = '';
        $(parent_form).find('.detail-container').map(function () {
            var characteristic = $(this).find('input[name="characteristic[]"]').val();
            var value = $(this).find('input[name="value[]"]').val();
            if(characteristic && value) other_details += characteristic+' = '+value+',';
        });

        data_values["other_details"] = other_details;

        // Do AJAX
        $.ajax({
            url: parent_form.attr('action'),
            type: "PUT",
            cache: false,
            data: data_values,
            success: function(data){
                Materialize.toast('Product updated!', 1500, 'green lighten-1');
                $('#edit-product-modal').closeModal();
            },
            error: function(message){
                console.log(message['responseText']);
            }
        });
    },

    delete_selected: function(parent_form, products){
        // Check if there are checked products
        if(products.length > 0){
            // Acknowledge first confirmation to remove
            $('#confirmation-modal').openModal();
            $('#confirm-remove').click(function(e){
                e.preventDefault();

                config.preloader_progress.fadeIn();
                // Do AJAX
                $.ajax({
                    url: config.manageSelected_url,
                    type: "DELETE",
                    cache: false,
                    data: {
                        "_token": parent_form.find('input[name=_token]').val(),
                        "product_ids": products
                    },
                    success: function(data){
                        products.forEach(function(element){
                            $('#product-'+element).remove();
                        });
                        config.preloader_progress.fadeOut();
                        Materialize.toast('Selected Products deleted!', 2000, 'green lighten-1');

                    },
                    error: function(message){
                        console.log(message['responseText']);
                    }
                });
            });
        }
        else Materialize.toast('No products chosen!', 1500 , 'orange accent-2');
    },

    get_product: function(product_id){
        // Do AJAX
        $.ajax({
            url: config.productSummary_url,
            type: "GET",
            cache: false,
            data:{
                "product_id" : product_id
            },
            success: function(data){
                var data = JSON.parse(data);
                var parent_form = $('#edit-product');
                var images = data.imageCollection;
                var videos = data.videoCollection;
                var image_list = '';
                var video_list = '';
                var hidden_inputs =
                    '<input name="productId" type="hidden" value="'+data.id+'">'+
                    '<input name="name" type="hidden" value="'+data.name+'">'+
                    '<input name="type" type="hidden" value="'+data.type+'">'+
                    '<input name="breed" type="hidden" value="'+data.breed+'">';

                $(parent_form).append('<input name="productId" type="hidden" value="'+data.id+'">');
                $('#edit-media-dropzone').append(hidden_inputs);
                $('#edit-media-modal h4').html('Edit Media of '+data.name);

                // General input initialization
                parent_form.find('input[name=name]').val(data.name);
                parent_form.find('label[for=name]').addClass('active')
                parent_form.find('input[name=price]').val(data.price);
                parent_form.find('label[for=price]').addClass('active')
                parent_form.find('input[name=quantity]').val(data.quantity);
                parent_form.find('label[for=quantity]').addClass('active')
                parent_form.find('input[name=age]').val(data.age);
                parent_form.find('label[for=age]').addClass('active')
                parent_form.find('input[name=adg]').val(data.adg);
                parent_form.find('label[for=adg]').addClass('active')
                parent_form.find('input[name=fcr]').val(data.fcr);
                parent_form.find('label[for=fcr]').addClass('active')
                parent_form.find('input[name=backfat_thickness]').val(data.backfat_thickness);
                parent_form.find('label[for=backfat_thickness]').addClass('active');

                // For select initializations
                $('#edit-select-type').val(data.type.toLowerCase());
                $('#edit-select-farm').val(data.farm_from_id);
                $('select').material_select();

                // For the breed initialization
                if(data.breed.includes('x')){
                    var crossbreed = data.breed.split('x');
                    parent_form.find('input[name=fbreed]').val(crossbreed[0].toString().trim());
                    parent_form.find('label[for=fbreed]').addClass('active');
                    parent_form.find('input[name=mbreed]').val(crossbreed[1].toString().trim());
                    parent_form.find('label[for=mbreed]').addClass('active');
                    parent_form.find('.input-purebreed-container').hide();
                    parent_form.find('.input-crossbreed-container').fadeIn(300);
                }
                else {
                    parent_form.find('input[name=breed]').val(data.breed);
                    parent_form.find('label[for=breed]').addClass('active');
                }

                // Other Details
                if(data.other_details){
                    var other_details_info = data.other_details.split(',');
                    var details = '';
                    other_details_info.forEach(function(element){
                        var information = element.split('=');
                        if(information != ''){
                            details += '<div class="detail-container">'+
                                    '<div class="input-field col s6">'+
                                        '<input name="characteristic[]" type="text" value="'+ information[0].toString().trim() +'">'+
                                        '<label for="characteristic[]" class="active">Characteristic</label>'+
                                    '</div>'+
                                    '<div class="input-field col s5">'+
                                        '<input name="value[]" type="text" value="'+ information[1].toString().trim() +'">'+
                                        '<label for="value[]" class="active">Value</label>'+
                                    '</div>'+
                                    '<div class="input-field col s1 remove-button-container">'+
                                        '<a href="#" class="tooltipped remove-detail" data-position="top" data-delay="50" data-tooltip="Remove detail">'+
                                            '<i class="material-icons grey-text text-lighten-1">remove_circle</i>'+
                                        '</a>'+
                                    '</div>'+
                                '</div>';
                        }
                    });

                    parent_form.find('.other-details-container').html('');
                    $(details).prependTo(parent_form.find(".other-details-container")).fadeIn(300);

                    // Open Edit Product Modal after product information has been fetched
                    $('#edit-product-modal').openModal({
                        ready: function(){
                            var whole_tab_width = $('#edit-product-modal .tabs').width();
                            var swine_tab_width = $('#edit-product-modal .tab').first().width();

                            $('.indicator').css({"right": whole_tab_width - swine_tab_width, "left": "0px"});
                        }
                    });

                    // Set-up value of current_modal_id
                    product.modal_history.push('#edit-product-modal');

                    // Set-up Images in Edit Media Modal
                    images.forEach(function(element){
                        var anchor_tag_html = 'Set as Display Photo';

                        // Change html value of set-display-photo anchor tag if image is the display photo
                        if(element.id == data.primary_img_id){
                            product.current_display_photo = element.id;
                            anchor_tag_html = '<i class="material-icons left teal-text">photo</i> Display Photo';
                        }

                        image_list += '<div class="col s12 m6">'+
                                '<div class="card">'+
                                    '<div class="card-image">'+
                                        '<img src="'+config.productImages_path+'/'+element.name+'">'+
                                        '<span class="card-title"></span>'+
                                    '</div>'+
                                    '<div class="card-action">'+
                                        '<a href="#!" class="set-display-photo" data-product-id="'+data.id+'" data-img-id="'+element.id+'">'+ anchor_tag_html +'</a>'+
                                        '<a href="#!" class="delete-image right" data-media-id="'+element.id+'">Delete</a>'+
                                    '</div>'+
                                '</div>'+
                            '</div>';
                    });

                    // Set-up Videos in Edit Media Modal
                    videos.forEach(function(element){
                        video_list += '<div class="col s12 m6">'+
                                '<div class="card">'+
                                    '<div class="card-image">'+
                                        '<video class="responsive-video" controls>'+
                                            '<source src="'+config.productVideos_path+'/'+element.name+'" type="video/mp4">'+
                                        '</video>'+
                                    '</div>'+
                                    '<div class="card-action">'+
                                        '<a></a>'+
                                        '<a href="#!" class="delete-video right" data-media-id="'+element.id+'">Delete</a>'+
                                    '</div>'+
                                '</div>'+
                            '</div>';
                    });

                    $('#edit-images-summary .card-content .row').html(image_list);
                    $('#edit-videos-summary .card-content .row').html(video_list);
                }

            },
            error: function(message){
                console.log(message['responseText']);
            }
        });
    },

    get_summary: function(product_id){
        $(product.modal_history_tos()).closeModal();

        // Set-up first modal action buttons
        if(product.modal_history_tos().includes('add')){
            $('.from-add-process').show();
            $('.from-edit-process').hide();
        }
        else {
            $('.from-add-process').hide();
            $('.from-edit-process').show();
        }

        $('#product-summary-modal').openModal({ dismissible: false });
        product.modal_history.push('#product-summary-modal');

        // Attach overlay preloader
        $('<div id="overlay-preloader-circular" class="valign-wrapper" style="padding:7rem;">'+
            '<div class="center-align preloader-overlay">'+
                '<div class="preloader-wrapper big active">'+
                    '<div class="spinner-layer spinner-blue-only">'+
                        '<div class="circle-clipper left">'+
                          '<div class="circle"></div>'+
                        '</div><div class="gap-patch">'+
                          '<div class="circle"></div>'+
                        '</div><div class="circle-clipper right">'+
                          '<div class="circle"></div>'+
                        '</div>'+
                    '</div>'+
                '</div>'+
            '</div>'+
        '</div>')
        .css({
            position: "absolute", width: "100%", height: "100%", top: $('#add-product-modal .modal-content').scrollTop(), left: 0, background: "rgba(255,255,255,0.8)", display:"block"
        })
        .appendTo($("#product-summary-modal .modal-content").css("position", "relative"));

        // Let the overlay preloader change its top position every scroll
        $('#product-summary-modal .modal-content').scroll(function(){
            $('#overlay-preloader-circular').css({top:$(this).scrollTop()});
        });

        // Do AJAX
        $.ajax({
            url: config.productSummary_url,
            type: "GET",
            cache: false,
            data:{
                "product_id" : product_id
            },
            success: function(data){
                var data = JSON.parse(data);
                var other_details = data.other_details.split(',');
                var images = data.imageCollection;
                var videos = data.videoCollection;

                // General Info
                var items = '<li class="collection-item">'+data.type+' - '+data.breed+'</li>'+
    				'<li class="collection-item">'+data.age+' days old</li>'+
    				'<li class="collection-item">Average Daily Gain: '+data.adg+'</li>'+
    				'<li class="collection-item">Feed Conversion Ratio: '+data.fcr+'</li>' +
                    '<li class="collection-item">Backfat Thickness: '+data.backfat_thickness+'</li>';

                var other_details_list = '<p>';
                var image_list = '';
                var video_list = '';

                // Other Details
                other_details.forEach(function(element){ other_details_list += element.trim() + '<br>'; });
                other_details_list += '</p>';

                // Images
                images.forEach(function(element){
                    var anchor_tag_html = 'Set as Display Photo';

                    // Change html value of set-display-photo anchor tag if image is the display photo
                    if(element.id == data.primary_img_id){
                        product.current_display_photo = element.id;
                        anchor_tag_html = '<i class="material-icons left teal-text">photo</i> Display Photo';
                    }

                    image_list += '<div class="col s12 m6">'+
                            '<div class="card">'+
                                '<div class="card-image">'+
                                    '<img src="'+config.productImages_path+'/'+element.name+'">'+
                                    '<span class="card-title"></span>'+
                                '</div>'+
                                '<div class="card-action">'+
                                    '<a href="#!" class="set-display-photo" data-product-id="'+data.id+'" data-img-id="'+element.id+'">'+ anchor_tag_html +'</a>'+
                                '</div>'+
                            '</div>'+
                        '</div>';
                });

                // Videos
                videos.forEach(function(element){
                    video_list += '<div class="col s12 m6">'+
                            '<video class="responsive-video" controls>'+
                                '<source src="'+config.productVideos_path+'/'+element.name+'" type="video/mp4">'+
                            '</video>'+
                        '</div>';
                });

                $('#product-summary-collection h5').html(data.name);
                $('#product-summary-collection h6').html(data.farm_province);
                $('#product-summary-collection div').html(items);
                $('#other-details-summary .card-content div').html(other_details_list);
                $('#images-summary .card-content .row').html(image_list);
                $('#videos-summary .card-content .row').html(video_list);
                $('#display-product-form').prepend('<input name="productId" type="hidden" value="'+data.id+'">');
                $('#overlay-preloader-circular').remove();

            },
            error: function(message){
                console.log(message['responseText']);
            }
        });
    },

    set_display_photo: function(anchor_tag, parent_form, product_id, img_id){

        // Do AJAX
        $.ajax({
            url: parent_form.attr('action'),
            type: "POST",
            cache: false,
            data: {
                "_token": parent_form.find('input[name=_token]').val(),
                "product_id": product_id,
                "img_id": img_id
            },
            success: function(data){
                // Overwrite the old display photo's anchor description
                parent_form.find('.set-display-photo[data-img-id="'+product.current_display_photo+'"]').html('Set as Display Photo');

                // New Display Photo id
                product.current_display_photo = img_id;
                anchor_tag.html('<i class="material-icons left teal-text">photo</i> Display Photo');
            },
            error: function(message){
                console.log(message['responseText']);
            }
        });
    },

    display_product: function(parent_form){
        // Do AJAX
        $.ajax({
            url: parent_form.attr('action'),
            type: "POST",
            cache: false,
            data: {
                "_token": parent_form.find('input[name=_token]').val(),
                "product_id": parent_form.find('input[name=productId]').val()
            },
            success: function(data){
                window.setTimeout(function(){
                    location.reload(true);
                }, 1200);
            },
            error: function(message){
                console.log(message['responseText']);
            }
        });
    },

    update_selected: function(parent_form, update_button, products, status){
        // Check if there are checked products
        if(products.length > 0){
            config.preloader_progress.fadeIn();
            // Do AJAX
            $.ajax({
                url: parent_form.attr('action'),
                type: "POST",
                cache: false,
                data: {
                    "_token": parent_form.find('input[name=_token]').val(),
                    "product_ids": products,
                    "updateTo_status": status
                },
                success: function(data){
                    var filter_status = $('#status-select option:selected').val();

                    // Do not remove product card if the filter enables
                    // all product statuses (hidden & displayed)
                    if(filter_status == "all-status"){
                        var product_name = update_button.attr('data-product-name');

                        if(status == 'display'){
                            update_button.removeClass('display-product-button');
                            update_button.addClass('hide-product-button');
                            update_button.attr('data-tooltip','Hide '+product_name);
                            update_button.tooltip({delay:50});
                            update_button.find('.material-icons').html('archive');
                            update_button.parents('.card').find('.card-image img').removeClass('hidden');
                        }
                        else{
                            update_button.removeClass('hide-product-button');
                            update_button.addClass('display-product-button');
                            update_button.attr('data-tooltip','Display '+product_name);
                            update_button.tooltip({delay:50});
                            update_button.find('.material-icons').html('unarchive');
                            update_button.parents('.card').find('.card-image img').addClass('hidden');
                        }
                    }
                    else{
                        products.forEach(function(element){
                            $('#product-'+element).remove();
                        });
                    }
                    config.preloader_progress.fadeOut();
                    Materialize.toast('Selected Products updated!', 2000, 'green lighten-1');
                },
                error: function(message){
                    console.log(message['responseText']);
                }
            });
        }
        else Materialize.toast('No products chosen!', 1500 , 'orange accent-2');

    },

    manage_necessary_fields: function(parent_form, type){
        // Fade in quantity field for semen
        if(type === 'semen'){
            parent_form.find('.input-quantity-container').fadeIn(300);
            if(product.before_select_value === 'sow'){
                parent_form.find('.other-details-container').html('');
                $(product.other_details_default).prependTo(parent_form.find(".other-details-container")).fadeIn(300);
            }
            product.before_select_value = 'semen';
        }

        // Provide default values in other_details category for sow
        else if(type === 'sow'){
            parent_form.find('.other-details-container').html('');
            $('<div class="detail-container">'+
                    '<div class="input-field col s6">'+
                        '<input name="characteristic[]" type="text" value="Litter Size">'+
                        '<label for="characteristic[]" class="active">Characteristic</label>'+
                    '</div>'+
                    '<div class="input-field col s5">'+
                        '<input name="value[]" type="text" value="">'+
                        '<label for="value[]" class="active">Value</label>'+
                    '</div>'+
                    '<div class="input-field col s1 remove-button-container">'+
                        '<a href="#" class="tooltipped remove-detail" data-position="top" data-delay="50" data-tooltip="Remove detail">'+
                            '<i class="material-icons grey-text text-lighten-1">remove_circle</i>'+
                        '</a>'+
                    '</div>'+
                '</div>'+
            '<div class="detail-container">'+
                    '<div class="input-field col s6">'+
                        '<input name="characteristic[]" type="text" value="Number of teats">'+
                        '<label for="characteristic[]" class="active">Characteristic</label>'+
                    '</div>'+
                    '<div class="input-field col s5">'+
                        '<input name="value[]" type="text" value="">'+
                        '<label for="value[]" class="active">Value</label>'+
                    '</div>'+
                    '<div class="input-field col s1 remove-button-container">'+
        '                <a href="#" class="tooltipped remove-detail" data-position="top" data-delay="50" data-tooltip="Remove detail">'+
                            '<i class="material-icons grey-text text-lighten-1">remove_circle</i>'+
                        '</a>'+
                    '</div>'+
                '</div>').hide().prependTo(parent_form.find(".other-details-container")).fadeIn(300);

            parent_form.find('.input-quantity-container').fadeOut(300);
            parent_form.find('.remove-detail').tooltip({delay:50});
            product.before_select_value = 'sow';
        }

        // Boar
        else{
            parent_form.find('.input-quantity-container').fadeOut(300);
            if(product.before_select_value === 'sow'){
                parent_form.find('.other-details-container').html('');
                $(product.other_details_default).prependTo(parent_form.find(".other-details-container")).fadeIn(300);
            }
            product.before_select_value = 'boar';
        }
    },

    add_other_detail : function(parent_form){
        $(product.other_details_default).hide().appendTo(parent_form.find(".other-details-container")).fadeIn(300);
        $('.remove-detail').tooltip({delay:50});
    },

    remove_other_detail : function(remove_icon){
        var parent_container = remove_icon.parents('.detail-container');
        remove_icon.tooltip('remove');
        $.when(parent_container.fadeOut(300)).done(function(){
            parent_container.remove();
        });

    }

};
