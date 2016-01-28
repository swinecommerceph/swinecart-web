$(document).ready(function(){
    /*
     * Profile-related scripts
     */

    // Add another Farm Address
    $("#add-farmAddress").on('click',function(e){
        e.preventDefault();

        // Check if there is a #remove-formAddress button and remove it
        if($('#farmAddress-body').has('#remove-farmAddress')){
            $('#remove-farmAddress').remove().fadeOut('slow');
        }

        // Count how many current Farm Addresses are available
        var i = $("#farmAddress-body .add-farm").length+1;

        // Append inputs for another Farm Address in the Farm Information form
        $('<div class="row add-farm" style="display:none;">'+
        '<div class="col s10 offset-s1">'+
            '<div id="farm-'+i+'" class="card-panel z-depth-1">'+
                '<p> Farm '+i+' </p>'+
                '<div class="row">'+
                // Farm Address: Street Address
                    '<div class="input-field col s10 push-s1">'+
                        '<input name="farmAddress['+i+'][addressLine1]" type="text">'+
                        '<label for="farmAddress['+i+'][addressLine1]">Address Line 1* : Street, Road, Subdivision</label>'+
                    '</div>'+
                '</div>'+

                '<div class="row">'+
                // Farm Address: Address Line 2
                    '<div class="input-field col s10 push-s1">'+
                        '<input name="farmAddress['+i+'][addressLine2]" type="text">'+
                        '<label for="farmAddress['+i+'][addressLine2]">Address Line 2* : Barangay, Town, City</label>'+
                    '</div>'+
                '</div>'+

                '<div class="row">'+
                    // Farm Address: Province
                    '<div class="input-field col s5 push-s1">'+
                        '<input name="farmAddress['+i+'][province]" type="text">'+
                        '<label for="farmAddress['+i+'][province]">Province*</label>'+
                    '</div>'+

                    // Farm Address: Zip Code
                    '<div class="input-field col s5 push-s1">'+
                        '<input name="farmAddress['+i+'][zipCode]" type="text">'+
                        '<label for="farmAddress['+i+'][zipCode]">Postal/ZIP Code*</label>'+
                    '</div>'+
                '</div>'+


                '<div class="row">'+
                    // Farm Type
                    '<div class="input-field col s5 push-s1">'+
                        '<input name="farmAddress['+i+'][farmType]" type="text">'+
                        '<label for="farmAddress['+i+'][farmType]">Farm Type*</label>'+
                    '</div>'+
                '</div>'+


                '<div class="row">'+
                    // Farm Landline
                    '<div class="input-field col s5 push-s1">'+
                        '<input name="farmAddress['+i+'][landline]" type="text">'+
                        '<label for="farmAddress['+i+'][landline]">Landline</label>'+
                    '</div>'+

                    // Farm Mobile
                    '<div class="input-field col s5 push-s1">'+
                        '<input name="farmAddress['+i+'][mobile]" type="text">'+
                        '<label for="farmAddress['+i+'][mobile]">Mobile*</label>'+
                    '</div>'+
                '</div>'+

                '<div class="row ">'+
                    '<div class="col offset-s10 removeButton-field">'+
                        '<a href="#" id="remove-farmAddress" class="btn-floating btn-medium waves-effect waves-light deep-orange tooltipped" data-position="left" data-delay="50" data-tooltip="Remove this Farm">'+
                            '<i class="material-icons">remove</i>'+
                        '</a>'+
                    '</div>'+
                '</div>'+
            '</div>'+
        '</div>'+
        '</div>').appendTo('#farmAddress-body').fadeIn('slow');

        // Redirect to specified section
        location.href = '#farm-'+i;
        $("#remove-farmAddress").tooltip({delay:50});
        Materialize.toast('Farm Information added', 2000, 'blue');
    });

    // Remove inputs for another Farm Address in the Farm Information form
    $('body').on('click', '#remove-farmAddress' ,function(e){
        e.preventDefault();
        $('#remove-farmAddress').tooltip('remove');

        // Count how many current Farm Addresses are available
        var i = $("#farmAddress-body .add-farm").length;

        var $parent = $(this).parentsUntil('#farmAddress-body','.add-farm');
        var $prevRemoveButtonField = $parent.prev().find(".removeButton-field");
        $parent.remove().fadeOut('slow');

        if (i > 1){
            $('<a href="#" id="remove-farmAddress" class="btn-floating btn-medium waves-effect waves-light deep-orange tooltipped" data-position="left" data-delay="50" data-tooltip="Remove this Farm" style="display:none">'+
                '<i class="material-icons">remove</i>'+
            '</a>').appendTo($prevRemoveButtonField).fadeIn('slow');
        }

        // Redirect to specified section
        location.href = '#farm-'+(i-1);
        $("#remove-farmAddress").tooltip({delay:50});
        Materialize.toast('Farm Information removed', 2000, 'deep-orange');

    });





});
