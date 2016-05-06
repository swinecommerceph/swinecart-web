'use strict';

var product = {

    before_select_value : '' ,
    current_primary_picture: 0,

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
                // console.log(data);
                Materialize.toast('Product added!', 2500, 'green lighten-1');
                var hidden_inputs =
                    '<input name="productId" type="hidden" value="'+data.product_id+'">'+
                    '<input name="name" type="hidden" value="'+data.name+'">'+
                    '<input name="type" type="hidden" value="'+data.type+'">'+
                    '<input name="breed" type="hidden" value="'+data.breed+'">';

                $('#media-dropzone').append(hidden_inputs);

                $('#add-media-modal h4').append(' to '+data.name);
                $('.add-product-button').attr('href','#add-media-modal');
                $('#overlay-preloader-circular').remove();
                parent_form.find('#submit-button').removeClass('disabled');
                $('#add-product-modal').closeModal();
                $('#add-media-modal').openModal();
            },
            error: function(message){
                console.log(message['responseText']);
                $('#overlay-preloader-circular').remove();
            }
        });
    },

    edit: function(){

    },

    remove: function(){

    },

    get_summary: function(product_id){
        $('.add-product-button').attr('href','#product-summary-modal');
        $('#add-media-modal').closeModal();
        $('#product-summary-modal').openModal();

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
                    image_list += '<div class="col s12 m6">'+
                            '<div class="card">'+
                                '<div class="card-image">'+
                                    '<img src="'+config.productImages_path+'/'+element.name+'">'+
                                    '<span class="card-title"></span>'+
                                '</div>'+
                                '<div class="card-action">'+
                                    '<a href="#!" class="set-primary-picture" data-product-id="'+data.id+'" data-img-id="'+element.id+'">Set as Primary Picture</a>'+
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
                $('#product-summary-collection').append(items);
                $('#other-details-summary .card-content').append(other_details_list);
                $('#images-summary .card-content .row').append(image_list);
                $('#videos-summary .card-content .row').append(video_list);
                $('#showcase-product-form').prepend('<input name="productId" type="hidden" value="'+data.id+'">');

                // Add listener to set-primary-picture anchor tags
                $('.set-primary-picture').click(function(e){
                    e.preventDefault();

                    // Check first if chosen image not the current primary picture
                    if(product.current_primary_picture != $(this).attr('data-img-id')){
                        product.set_primary_picture($(this), $(this).parents('form'), $(this).attr('data-product-id'), $(this).attr('data-img-id'));
                    }

                });

                $('#overlay-preloader-circular').remove();

            },
            error: function(message){
                console.log(message['responseText']);
            }
        });
    },

    set_primary_picture: function(anchor_tag, parent_form, product_id, img_id){
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
                // var data = JSON.parse(data);

                // Overwrite the old primary picture anchor's description
                $('.set-primary-picture[data-img-id="'+product.current_primary_picture+'"]').html('Set as Primary Picture');

                // New Primary Picture id
                product.current_primary_picture = img_id;
                anchor_tag.html('<i class="material-icons left teal-text">photo</i> Primary Picture');

            },
            error: function(message){
                console.log(message['responseText']);
            }
        });
    },

    showcase_product: function(parent_form){
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
                // var data = JSON.parse(data);
                window.setTimeout(function(){
                    location.reload(true);
                }, 1200);

            },
            error: function(message){
                console.log(message['responseText']);
            }
        });
    },

    manage_necessary_fields: function(type){

        // Fade in quantity field for semen
        if(type === 'semen'){
            $('#input-quantity-container').fadeIn(300);
            if(product.before_select_value === 'sow'){
                $('#other-details-container').html('');
                product.other_details_default.prependTo("#other-details-container").fadeIn(300);
            }
            product.before_select_value = 'semen';
        }

        // Provide default values in other_details category for sow
        else if(type === 'sow'){
            $('#other-details-container').html('');
            $('<div class="detail-container">'+
                    '<div class="input-field col s6">'+
                        '<input name="characteristic[]" type="text" value="Litter Size">'+
                        '<label for="characteristic[]" class="active">Characteristic</label>'+
                    '</div>'+
                    '<div class="input-field col s5">'+
                        '<input name="value[]" type="text" value="<value>">'+
                        '<label for="value[]" class="active">Value</label>'+
                    '</div>'+
                    '<div class="input-field col s1 remove-button-container">'+
        '                <a href="#" class="tooltipped remove-detail" data-position="top" data-delay="50" data-tooltip="Remove detail">'+
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
                        '<input name="value[]" type="text" value="<value>">'+
                        '<label for="value[]" class="active">Value</label>'+
                    '</div>'+
                    '<div class="input-field col s1 remove-button-container">'+
        '                <a href="#" class="tooltipped remove-detail" data-position="top" data-delay="50" data-tooltip="Remove detail">'+
                            '<i class="material-icons grey-text text-lighten-1">remove_circle</i>'+
                        '</a>'+
                    '</div>'+
                '</div>').hide().prependTo("#other-details-container").fadeIn(300);

            $('#input-quantity-container').fadeOut(300);
            $('.remove-detail').tooltip({delay:50});
            product.before_select_value = 'sow';
        }

        // Boar
        else{
            $('#input-quantity-container').fadeOut(300);
            if(product.before_select_value === 'sow'){
                $('#other-details-container').html('');
                $(product.other_details_default).prependTo("#other-details-container").fadeIn(300);
            }
            product.before_select_value = 'boar';
        }
    },

    add_other_detail : function(){
        $(product.other_details_default).hide().appendTo("#other-details-container").fadeIn(300);
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
