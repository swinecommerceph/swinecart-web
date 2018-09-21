$(document).ready(function(){

    // Variable for checking if all products
    // are selected or not
    var all_checked = false;

    // Hide certain elements
    $('.input-crossbreed-container').hide();

    // initialization of Materialize's Date Picker
    $('.datepicker').pickadate({
        max: true,
        selectMonths: true,
        selectYears: 4,
        format: 'mmmm d, yyyy'
    });

    /* ----------- Manage Products page general functionalities ----------- */
    // Back to top button functionality
    $(window).scroll(function(){
        if ($(this).scrollTop() >= 250) $('#action-button').fadeIn(200);
        else{
            $('.fixed-action-btn').closeFAB();
            $('#action-button').fadeOut(200);
        }
    });

    // Giving a border on product card/s when checkbox is clicked
    $('.single-checkbox').change(function(e) {
        e.preventDefault();

        // Iterates all the product cards
        $('#view-products-container input[type=checkbox]').each(function() {
            
            // Locates the checked card/s and retrieves the id/s for jQuery
            var string = "#product-";
            var product_id = $(this).attr('data-product-id');
            var div_id = string + product_id;

            // Apply the border on the element with class of 'card hoverable'
            var card_element = div_id + ">div";

            // Apply the border/s if checked, else remove the blue border
            if ($(this).is(':checked')) {
                $(card_element).css({
                    "border": "solid 4px #42a5f5"
                });
            }   
            else {
                $(card_element).css({
                    "border": "solid 4px transparent"
                });
            }
        });
    });

    // Select All Products
    $('.select-all-button').click(function(e){
        e.preventDefault();

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

        $('.tooltipped').tooltip();
    });

    // Display Selected Button
    $('.display-selected-button').click(function(e){
        e.preventDefault();
        var checked_products = [];

        $('#view-products-container input[type=checkbox]:checked').each(function(){
            checked_products.push($(this).attr('data-product-id'));
        });
        product.update_selected($('#manage-selected-form'), '', checked_products, 'display');
    });

    // Hide Selected Button
    $('.hide-selected-button').click(function(e){
        e.preventDefault();
        var checked_products = [];

        $('#view-products-container input[type=checkbox]:checked').each(function(){
            checked_products.push($(this).attr('data-product-id'));
        });
        product.update_selected($('#manage-selected-form'), '', checked_products, 'hide');
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

    // Display chosen product
    $('body').on('click', '.display-product-button' ,function(e){
        e.preventDefault();
        $(this).tooltip('remove');
        product.update_selected($('#manage-selected-form'), $(this), [$(this).attr('data-product-id')], 'display');
    });

    // Hide chosen product
    $('body').on('click', '.hide-product-button' ,function(e){
        e.preventDefault();
        $(this).tooltip('remove');
        product.update_selected($('#manage-selected-form'), $(this), [$(this).attr('data-product-id')], 'hide');
    });

    // Add a product
    $('.add-product-button').click(function(){
        $('#add-product-modal').modal({
            ready: function(){
                // Programmatically select th 'swine-information' tab
                $('#add-product-modal ul.tabs').tabs('select_tab', 'swine-information');
            }
        });
        $('#add-product-modal').modal('open');
        product.modal_history.push('#add-product-modal');
    });

    // Edit chosen product
    $('.edit-product-button').click(function(){
        $('#edit-product-modal').modal({
            ready: function(){
                // Programmatically select the 'edit-swine-information' tab
                $('#edit-product-modal ul.tabs').tabs('select_tab', 'edit-swine-information');
            }
        });
        $('#edit-product-modal').modal('open');
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

        $(product.modal_history.pop()).modal('close');

        // If going back to add-product-modal it must be directed to edit-product-modal
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
        else $(product.modal_history_tos()).modal('open');
    });

    /* ----------- Add Product Modal functionalities ----------- */
    $("#add-product-modal #other-details-tab").click(function(e){
        $('#submit-button').show();
    });

    /* ----------- Add Media Modal functionalities ----------- */
    // Move to Product Summary Modal
    $('#next-button').click(function(e){
        e.preventDefault();
        product.get_summary($('#add-media-modal form').find('input[name="productId"]').val());
    });

    // media-dropzone initialization and configuration
    Dropzone.options.mediaDropzone = {
        paramName: 'media',
        uploadMultiple: true,
        parallelUploads: 1,
        maxFiles: 12,
        maxFilesize: 50,
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
                    var preview_element = files[item].previewElement;
                    preview_element.setAttribute('data-media-id', element.id);
                    preview_element.getElementsByClassName('dz-filename')[0].getElementsByTagName('span')[0].innerHTML = element.name;
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

        // Disable save-draft-button and display-button
        $('#display-button').addClass('disabled');
        $(this).addClass('disabled');
        $(this).html('Saving as Draft ...');

        window.setTimeout(function(){
            location.reload(true);
        }, 1200);

    });

    // Display Product created
    $('#display-button').click(function(e){
        e.preventDefault();

        // Disable display-button and save-draft-button
        $('#save-draft-button').addClass('disabled');
        $(this).addClass('disabled');
        $(this).html('Displaying ...');

        product.display_product($(this).parents('form'));
    });

    // Change html of set-display-photo anchor tag if it is a display photo
    $('body').on('click', '.set-display-photo' ,function(e){
        e.preventDefault();

        // Check first if chosen image not the current primary picture
        if(product.current_display_photo != $(this).attr('data-img-id')){
            product.set_display_photo($(this), $(this).parents('form'), $(this).attr('data-product-id'), $(this).attr('data-img-id'));
        }
    });

    $('#save-button').click(function(e){
        e.preventDefault();

        // Disable save-button
        $(this).addClass('disabled');
        $(this).html('Saving ...');

        window.setTimeout(function(){
            location.reload(true);
        }, 1200);
    });

    /* ----------- Edit Product Modal functionalities ----------- */
    // Open Edit Media Modal
    $('#edit-media-button').click(function(e){
        e.preventDefault();
        $('#edit-product-modal').modal('close');
        $('#edit-media-modal').modal({dismissible: false});
        $('#edit-media-modal').modal('open');
        product.modal_history.push('#edit-media-modal')
    });

    // Open Add Media Modal
    $('#add-media-button').click(function(e){
        e.preventDefault();
        $('#edit-product-modal').modal('close');
        $('#add-media-modal').modal({
            dismissible: false,
            ready: function(){
                product.modal_history.push('#add-media-modal');
            }
        });
        $('#add-media-modal').modal('open');
    });

    /* ----------- Edit Media Modal ----------- */
    // edit-media-dropzone initialization and configuration
    Dropzone.options.editMediaDropzone = {
        paramName: 'media',
        uploadMultiple: true,
        parallelUploads: 1,
        maxFiles: 12,
        maxFilesize: 50,
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
                    var preview_element = files[item].previewElement;
                    preview_element.setAttribute('data-media-id', element.id);
                    preview_element.getElementsByClassName('dz-filename')[0].getElementsByTagName('span')[0].innerHTML = element.name;
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

        // Disable delete-image/delete-video button
        $(this).addClass('disabled');
        $(this).html('Deleting ...');

        var card_container = $(this).parents('.card').first().parent();
        var data_values = {
            "_token" : $('#media-dropzone').find('input[name=_token]').val(),
            "mediaId" : $(this).attr('data-media-id')
        };

        // Check if the chosen media is an image and is the current display photo
        if($(this).hasClass('delete-image') && $(this).attr('data-media-id') == product.current_display_photo){
            Materialize.toast('Cannot delete display photo!', 1500 , 'orange accent-2');

            // Enable delete-image/delete-video button
            $(this).removeClass('disabled');
            $(this).html('Delete');
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
