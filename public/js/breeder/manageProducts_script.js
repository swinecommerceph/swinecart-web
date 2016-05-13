$(document).ready(function(){

    // Hide certain elements
    $('#input-crossbreed-container, #input-quantity-container').hide();

    // Initialization of Modals
    $('.add-product-button').leanModal({
        ready: function(){
            var whole_tab_width = $('#add-product-modal .tabs').width();
            var swine_tab_width = $('#add-product-modal .tab').first().width();

            $('.indicator').css({"right": whole_tab_width - swine_tab_width, "left": "0px"});
        }
    });

    // Back to top button functionality
    $(window).scroll(function(){
        if ($(this).scrollTop() >= 250) $('#action-button').fadeIn(200);
        else{
            $('.fixed-action-btn').closeFAB();
            $('#action-button').fadeOut(200);
        }
    });

	// Dropdown
	$(".product-action-dropdown").dropdown({
        constrainwidth:false,
        gutter: 0,
        alignment: 'right'
    });

    // Publis
    $('.showcase-selected-button').click(function(e){
        e.preventDefault();
        product.showcase_selected($('#manage-selected-form'));
    });

    // Delete selected products
    $(".delete-selected-button").click(function(e){
        e.preventDefault();
        product.delete_selected($('#manage-selected-form'));
    });

    // Submit add product
    $("#create-product").submit(function(e){
        e.preventDefault();
        product.add($('#create-product'));
    });

    $('.edit-product-button').click(function(){
        $('#edit-product-modal').openModal({
            dismissible: false
        });
    });

    // Breed radio
    $("input#purebreed").on('click', function(){
        $('#input-crossbreed-container').hide();
        $('#input-purebreed-container').fadeIn(300);
    });

    $("input#crossbreed").on('click', function(){
        $('#input-purebreed-container').hide();
        $('#input-crossbreed-container').fadeIn(300);
    });

    // Manage necessary fields depending on product type
    $("#select-type").change(function(){
        product.manage_necessary_fields($(this).val())
    });

    // Add other details button
    $("#add-other-details").click(function(e){
        e.preventDefault();
        product.add_other_detail();
    });

    // Remove a detail from other details section
    $('body').on('click', '.remove-detail' ,function(e){
        e.preventDefault();
        product.remove_other_detail($(this));
    });

    // Move to Product Summary
    $('#next-button').click(function(e){
        e.preventDefault();
        product.get_summary($('#add-media-modal form').find('input[name="productId"]').val());
    });

    // Save as Draft the Product created
    $('#save-draft-button').click(function(e){
        e.preventDefault();
        $(this).html('Saving as Draft...');
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

    // Redirect to designated link upon checkbox value change
    $("#dropdown-container select").change(function(){
        filter.apply();
    });

    // Dropzone configuration
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
        dictDefaultMessage: "<h6> Drop images/videos here to upload </h6>"+
            "<i class='material-icons'>insert_photo</i> <i class='material-icons'>movie</i>"+
            "<br> <h6> Or just click anywhere in this container to choose file </h6>",
        previewTemplate: document.getElementById('custom-preview').innerHTML,
        init: function() {
            // Listen to events

            // Set default thumbnail for videos
            this.on("addedfile", function(file) {
                if (file.type.match(/video.*/)) this.emit("thumbnail", file, config.images_path+'/video-icon.png');
            });

            // On success of multiple uploads
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


});
