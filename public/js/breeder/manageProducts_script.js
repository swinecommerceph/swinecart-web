$(document).ready(function(){

    // Hide certain elements
    $('#input-crossbreed-container, #input-quantity-container').hide();

    // Initialization of Modals
    $('.add-product-button').leanModal({
        ready: function(){
            $('.indicator').css({"right": "442px", "left": "0px"});
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

    // Submit add product
    $("#create-product").submit(function(e){
        e.preventDefault();
        product.add($('#create-product'));
    });

    // Delete selected products
    $(".delete-selected-button").click(function(){
        console.log('Delete!');
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

    // Dropzone configuration
    Dropzone.options.mediaDropzone = {
        paramName: 'media',
        uploadMultiple: true,
        parallelUploads: 3,
        maxFiles: 12,
        maxFilesize: 50,
        addRemoveLinks: true,
        dictRemoveFile: "Remove",
        dictCancelUpload: "Cancel",
        acceptedFiles: "image/png, image/jpeg, image/jpg, video/avi, video/mp4, video/flv, video/mov",
        dictDefaultMessage: "<h6> Drop images/videos here to upload </h6>"+
            "<i class='material-icons'>insert_photo</i> <i class='material-icons'>movie</i>"+
            "<br> <h6> Or just click anywhere in this container to choose file </h6>",
        previewsContainer: "#custom-preview",
        init: function() {
            // Listen to events
            this.on('completemultiple', function(files){
                $('.dz-sucess-mark').html("<i class='material-icons green-text'>check_circle</i>");
                $('.dz-error-mark').html("<i class='material-icons orange-text text-lighten-1'>error</i>");
            });
            this.on('successmultiple', function(files, response){
                response = JSON.parse(response);
                response.forEach(function(element, index, array){
                    $('.dz-filename span[data-dz-name]').html(element.name);
                    // $('.dz-sucess-mark').html("<i class='material-icons green-text'>check_circle</i>");
                });
            });
            this.on('removedfile', function(file){
                var parent_form = $('#img-dropzone');
                console.log(file.type);
                // Do AJAX
                // $.ajax({
                //     url: config.productImages_url+'/delete',
                //     type: "DELETE",
                //     cache: false,
                //     data:{
                //         "_token" : parent_form.find('input[name=_token]').val(),
                //         "imageId":
                //     },
                //     success: function(data){
                //
                //     },
                //     error: function(message){
                //         console.log(message['responseText']);
                //     }
                // });
            });
        }
    };


});
