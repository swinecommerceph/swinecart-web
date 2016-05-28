$(document).ready(function(){

    var all_checked = false;
    // Hide certain elements
    $('.input-crossbreed-container, .input-quantity-container').hide();

    /* ----------- Manage Products page general functionalities ----------- */
    // Back to top button functionality
    $(window).scroll(function(){
        if ($(this).scrollTop() >= 250) $('#action-button').fadeIn(200);
        else{
            $('.fixed-action-btn').closeFAB();
            $('#action-button').fadeOut(200);
        }
    });

    // Select All Products
    $('.select-all-button').click(function(e){
        e.preventDefault();
        console.log(all_checked);
        if(!all_checked){
            $('#view-products-container input[type=checkbox]').prop('checked', true);
            $('.select-all-button i').html('event_busy');
            $('.select-all-button').attr('data-tooltip', 'Unselect all Products');
            all_checked = true;
        }
        else{
            $('#view-products-container input[type=checkbox]').prop('checked', false);
            $('.select-all-button i').html('event_available');
            $('.select-all-button').attr('data-tooltip', 'Select all Products');
            all_checked = false;
        }
    });

    // Showcase Selected Button
    $('.showcase-selected-button').click(function(e){
        e.preventDefault();
        var checked_products = [];

        $('#view-products-container input[type=checkbox]:checked').each(function(){
            checked_products.push($(this).attr('data-product-id'));
        });
        product.update_selected($('#manage-selected-form'), checked_products, 'showcase');
    });

    // Unshowcase Selected Button
    $('.unshowcase-selected-button').click(function(e){
        e.preventDefault();
        var checked_products = [];

        $('#view-products-container input[type=checkbox]:checked').each(function(){
            checked_products.push($(this).attr('data-product-id'));
        });
        product.update_selected($('#manage-selected-form'), checked_products, 'unshowcase');
    });

    // Delete selected products
    $(".delete-selected-button").click(function(e){
        e.preventDefault();
        var checked_products = [];

        $('#view-products-container input[type=checkbox]:checked').each(function(){
            checked_products.push($(this).attr('data-product-id'));
        });
        product.delete_selected($('#manage-selected-form'), checked_products);
    });

    // Showcase chosen product
    $('.showcase-product-button').click(function(e){
        e.preventDefault();
        $('.tooltipped').tooltip('remove');
        product.update_selected($('#manage-selected-form'), [$(this).attr('data-product-id')], 'showcase');
    });

    // Unshowcase chosen product
    $('.unshowcase-product-button').click(function(e){
        e.preventDefault();
        $('.tooltipped').tooltip('remove');
        product.update_selected($('#manage-selected-form'), [$(this).attr('data-product-id')], 'unshowcase');
    });

    // Add a product
    $('.add-product-button').click(function(){
        $('#add-product-modal').openModal({
            ready: function(){
                var whole_tab_width = $('#add-product-modal .tabs').width();
                var swine_tab_width = $('#add-product-modal .tab').first().width();

                $('.indicator').css({"right": whole_tab_width - swine_tab_width, "left": "0px"});
            }
        })
        product.modal_history.push('#add-product-modal');
    });

    // Edit chosen product
    $('.edit-product-button').click(function(){
        product.get_product($(this).attr('data-product-id'));
    });

    // Delete chosen product
    $('.delete-product-button').click(function(e){
        e.preventDefault();
        product.delete_selected($('#manage-selected-form'), [$(this).attr('data-product-id')]);
    });

    // Redirect to designated link upon checkbox value change
    $("#dropdown-container select").change(function(){
        filter.apply();
    });

    // Back button on modals
    $('.back-button').click(function(e){
        e.preventDefault();
        console.log(product.modal_history);
        $(product.modal_history.pop()).closeModal();

        // If goig back to add-product-modal it must be directed to edit-product-modal
        if(product.modal_history_tos() === '#add-product-modal') {
            product.get_product($('#add-media-modal form').find('input[name="productId"]').val());

            // Set-up first modal action buttons
            if(product.modal_history_tos().includes('add')){
                $('.from-add-process').show();
                $('.from-edit-process').hide();
            }
            else {
                $('.from-add-process').hide();
                $('.from-edit-process').show();
            }
        }
        else $(product.modal_history_tos()).openModal();
        console.log(product.modal_history);
    });

    /* ----------- Add Product Modal functionalities ----------- */
    // Submit add product
    $("#create-product").submit(function(e){
        e.preventDefault();
        product.add($('#create-product'));
    });

    /* ----------- Add Media Modal functionalities ----------- */
    // Move to Product Summary Modal
    $('#next-button').click(function(e){
        e.preventDefault();
        product.get_summary($('#add-media-modal form').find('input[name="productId"]').val());
        $(this).removeClass('disabled');
    });

    // media-dropzone initialization and configuration
    Dropzone.options.mediaDropzone = {
        paramName: 'media',
        uploadMultiple: true,
        parallelUploads: 3,
        maxFiles: 12,
        maxFilesize: 50,
        // addRemoveLinks: true,
        // dictRemoveFile: "Remove",
        // dictCancelUpload: "Cancel",
        acceptedFiles: "image/png, image/jpeg, image/jpg, video/avi, video/mp4, video/flv, video/mov",
        dictDefaultMessage: "<h5 style='font-weight: 300;'> Drop images/videos here to upload </h5>"+
            "<i class='material-icons'>insert_photo</i> <i class='material-icons'>movie</i>"+
            "<br> <h5 style='font-weight: 300;'> Or just click anywhere in this container to choose file </h5>",
        previewTemplate: document.getElementById('custom-preview').innerHTML,
        init: function() {
            // Listen to events
            // Set default thumbnail for videos
            this.on("addedfile", function(file) {
                if (file.type.match(/video.*/)) this.emit("thumbnail", file, config.images_path+'/video-icon.png');
            });

            // Inject attributes on element upon success of multiple uploads
            this.on('successmultiple', function(files, response){
                response = JSON.parse(response);
                var item = 0;
                response.forEach(function(element){
                    preview_element = files[item].previewElement;
                    preview_element.setAttribute('data-media-id', element.id);
                    $('.dz-filename span[data-dz-name]').html(element.name);
                    item++;
                });

                $(".tooltipped").tooltip({delay:50});
            });

            // Remove file from file system and database records
            this.on('removedfile', function(file){
                var mime_type = file.type.split('/');
                var media_type = mime_type[0];
                // Do AJAX
                $.ajax({
                    url: config.productMedia_url+'/delete',
                    type: "DELETE",
                    cache: false,
                    data:{
                        "_token" : $('#media-dropzone').find('input[name=_token]').val(),
                        "mediaId" : file.previewElement.getAttribute('data-media-id'),
                        "mediaType" : media_type
                    },
                    success: function(data){

                    },
                    error: function(message){
                        console.log(message['responseText']);
                    }
                });
            });
        }
    };

    /* ----------- Product Summary Product Modal functionalities ----------- */
    // Save as Draft the Product created
    $('#save-draft-button').click(function(e){
        e.preventDefault();
        $(this).html('Saving as Draft ...');
        window.setTimeout(function(){
            location.reload(true);
        }, 1200);

    });

    // Showcase Product created
    $('#showcase-button').click(function(e){
        e.preventDefault();
        $(this).html('Showcasing ...');
        product.showcase_product($(this).parents('form'));
    });

    // Change html of set-display-photo anchor tag if it is a display photo
    $('body').on('click', '.set-display-photo' ,function(e){
        e.preventDefault();

        // Check first if chosen image not the current primary picture
        if(product.current_display_photo != $(this).attr('data-img-id')){
            product.set_display_photo($(this), $(this).parents('form'), $(this).attr('data-product-id'), $(this).attr('data-img-id'));
            console.log($(this).parents('form')+ ' ' + product.current_display_photo);
        }
    });

    $('#save-button').click(function(e){
        e.preventDefault();
        $(this).html('Saving ...');
        window.setTimeout(function(){
            location.reload(true);
        }, 1200);
    });

    /* ----------- Edit Product Modal functionalities ----------- */
    // Update details of a product
    $('.update-button').click(function(e){
        e.preventDefault();
        $(this).addClass('disabled');
        product.edit($('#edit-product'));
        product.get_summary($('#edit-product').find('input[name="productId"]').val());
        $(this).removeClass('disabled');
    });

    // Open Edit Media Modal
    $('#edit-media-button').click(function(e){
        e.preventDefault();
        $('#edit-product-modal').closeModal();
        $('#edit-media-modal').openModal({dismissible: false});
        product.modal_history.push('#edit-media-modal')
    });

    // Open Add Media Modal
    $('#add-media-button').click(function(e){
        e.preventDefault();
        $('#edit-product-modal').closeModal();
        $('#add-media-modal').openModal({
            dismissible: false,
            ready: function(){
                product.modal_history.push('#add-media-modal');
            }
        });
    });

    /* ----------- Edit Media Modal ----------- */
    // edit-media-dropzone initialization and configuration
    Dropzone.options.editMediaDropzone = {
        paramName: 'media',
        uploadMultiple: true,
        parallelUploads: 3,
        maxFiles: 12,
        maxFilesize: 50,
        // addRemoveLinks: true,
        // dictRemoveFile: "Remove",
        // dictCancelUpload: "Cancel",
        acceptedFiles: "image/png, image/jpeg, image/jpg, video/avi, video/mp4, video/flv, video/mov",
        dictDefaultMessage: "<h5 style='font-weight: 300;'> Drop images/videos here to upload </h5>"+
            "<i class='material-icons'>insert_photo</i> <i class='material-icons'>movie</i>"+
            "<br> <h5 style='font-weight: 300;'> Or just click anywhere in this container to choose file </h5>",
        previewTemplate: document.getElementById('custom-preview').innerHTML,
        init: function() {
            // Listen to events

            // Set default thumbnail for videos
            this.on("addedfile", function(file) {
                if (file.type.match(/video.*/)) this.emit("thumbnail", file, config.images_path+'/video-icon.png');
            });

            // Inject attributes on element upon success of multiple uploads
            this.on('successmultiple', function(files, response){
                response = JSON.parse(response);
                var item = 0;
                response.forEach(function(element){
                    preview_element = files[item].previewElement;
                    preview_element.setAttribute('data-media-id', element.id);
                    $('.dz-filename span[data-dz-name]').html(element.name);
                    item++;
                });

                $(".tooltipped").tooltip({delay:50});
            });

            // Remove file from file system and database records
            this.on('removedfile', function(file){
                var mime_type = file.type.split('/');
                var media_type = mime_type[0];
                // Do AJAX
                $.ajax({
                    url: config.productMedia_url+'/delete',
                    type: "DELETE",
                    cache: false,
                    data:{
                        "_token" : $('#media-dropzone').find('input[name=_token]').val(),
                        "mediaId" : file.previewElement.getAttribute('data-media-id'),
                        "mediaType" : media_type
                    },
                    success: function(data){

                    },
                    error: function(message){
                        console.log(message['responseText']);
                    }
                });
            });
        }
    };

    // Delete image / Delete video button
    $('body').on('click', '.delete-image, .delete-video' ,function(e){
        e.preventDefault();

        var card_container = $(this).parents('.card').first().parent();
        var data_values = {
            "_token" : $('#media-dropzone').find('input[name=_token]').val(),
            "mediaId" : $(this).attr('data-media-id')
        };

        // Check if the chosen media is an image and is the current display photo
        if($(this).hasClass('delete-image') && $(this).attr('data-media-id') == product.current_display_photo){
            Materialize.toast('Cannot delete display photo!', 1500 , 'orange accent-2');
        }
        else{
            // Initialize mediaType value
            if($(this).hasClass('delete-image')) data_values["mediaType"] = 'image';
            else data_values["mediaType"] = 'video';

            // Do AJAX
            $.ajax({
                url: config.productMedia_url+'/delete',
                type: "DELETE",
                cache: false,
                data: data_values,
                success: function(data){
                    card_container.remove();
                },
                error: function(message){
                    console.log(message['responseText']);
                }
            });
        }

    });


    /* ----------- Form functionalities ----------- */
    // Breed radio
    $("input.purebreed").on('click', function(){
        $(this).parents('form').find('.input-crossbreed-container').hide();
        $(this).parents('form').find('.input-purebreed-container').fadeIn(300);
    });
    $("input.crossbreed").on('click', function(){
        $(this).parents('form').find('.input-purebreed-container').hide();
        $(this).parents('form').find('.input-crossbreed-container').fadeIn(300);
    });

    // Manage necessary fields depending on product type
    $("#select-type").on('change', function(){
        product.manage_necessary_fields($(this).parents('form'), $(this).val());
    });
    $("#edit-select-type").on('change', function(){
        console.log($(this).parents('form'));
        product.manage_necessary_fields($(this).parents('form'), $(this).val());
    });

    // Add other details button
    $(".add-other-details").click(function(e){
        e.preventDefault();
        product.add_other_detail($(this).parents('form'));
    });

    // Remove a detail from other details section
    $('body').on('click', '.remove-detail' ,function(e){
        e.preventDefault();
        product.remove_other_detail($(this));
    });


});
