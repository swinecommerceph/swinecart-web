'use strict';

var profile = {
    edit_farm_name: '',

    add: function(parent_form){
        config.preloader_progress.fadeIn();
        var farm_address = [];
        var data_values = {
            "_token" : parent_form.find('input[name=_token]').val()
        };

        farm_address.push({});
        $(parent_form).find('.add-farm').map(function () {
            var details = {
                'name': $(this).find('input[name="farmAddress[][name]"]').val(),
                'addressLine1': $(this).find('input[name="farmAddress[][addressLine1]"]').val(),
                'addressLine2': $(this).find('input[name="farmAddress[][addressLine2]"]').val(),
                'province': $(this).find('input[name="farmAddress[][province]"]').val(),
                'zipCode': $(this).find('input[name="farmAddress[][zipCode]"]').val(),
                'farmType': $(this).find('input[name="farmAddress[][farmType]"]').val(),
                'landline': $(this).find('input[name="farmAddress[][landline]"]').val(),
                'mobile': $(this).find('input[name="farmAddress[][mobile]"]').val()
            };
            farm_address.push(details);
        });

        data_values["farmAddress"] = farm_address;

        // Do AJAX
        $.ajax({
            url: parent_form.attr('action'),
            type: "POST",
            cache: false,
            data: data_values,
            success: function(data){
                var data = JSON.parse(data);
                Materialize.toast('Profile updated Success!', 1500, 'green lighten-1');
                window.setTimeout(function(){
                    config.preloader_progress.fadeOut();
                    location.reload(true);
                }, 1500);
            },
            error: function(message){
                console.log(message['responseText']);
                config.preloader_progress.fadeOut();
            }
        });
    },

    edit: function(parent_form, edit_button, cancel_button){
        config.preloader_progress.fadeIn();
        $.when(parent_form.find('input').prop('disabled',false)).done(function(){
            profile.edit_farm_name = edit_button.attr('data-tooltip');
            // Edit tooltip animation to Done
            edit_button.attr('data-tooltip','Done');
            edit_button.attr('data-position','top');
            edit_button.html('<i class="material-icons">done</i>');
            $(".tooltipped").tooltip({delay:50});
            edit_button.prop('disabled', false);
            cancel_button.toggle();
            config.preloader_progress.fadeOut();
        });
    },

    update: function(parent_form, edit_button, cancel_button){
        config.preloader_progress.fadeIn();
        var data_values;

        // Determine if form is of personal or farm information
        if(parent_form.attr('data-personal-id')){
            data_values = {
                "id": parent_form.attr('data-personal-id'),
                "address_addressLine1": parent_form.find('input[name=address_addressLine1]').val(),
                "address_addressLine2": parent_form.find('input[name=address_addressLine2]').val(),
                "address_province": parent_form.find('input[name=address_province]').val(),
                "address_zipCode": parent_form.find('input[name=address_zipCode]').val(),
                "landline": parent_form.find('input[name=landline]').val(),
                "mobile": parent_form.find('input[name=mobile]').val(),
                "_token": parent_form.find('input[name=_token]').val()
            };
        }
        else if (parent_form.attr('data-farm-id')) {
            var farm_address = [];
            var details = {
                "name": parent_form.find('input[name=name]').val(),
                "addressLine1": parent_form.find('input[name=addressLine1]').val(),
                "addressLine2": parent_form.find('input[name=addressLine2]').val(),
                "province": parent_form.find('input[name=province]').val(),
                "zipCode": parent_form.find('input[name=zipCode]').val(),
                "farmType": parent_form.find('input[name=farmType]').val(),
                "landline": parent_form.find('input[name=landline]').val(),
                "mobile": parent_form.find('input[name=mobile]').val(),
            };
            farm_address.push({});
            farm_address.push(details);

            data_values = {
                    "id": parent_form.attr('data-farm-id'),
                "_token": parent_form.find('input[name=_token]').val()
            };
            data_values["farmAddress"] = farm_address;
        }

        // Do AJAX
        $.ajax({
            url: parent_form.attr('action'),
            type: "PUT",
            cache: false,
            data: data_values,
            success: function(data){
                var data = JSON.parse(data);
                parent_form.find('input').prop('disabled',true);

                // Change the values of the input
                if(parent_form.attr('data-personal-id')){
                    parent_form.find('input[name=address_addressLine1]').val(data.address_addressLine1);
                    parent_form.find('input[name=address_addressLine2]').val(data.address_addressLine2);
                    parent_form.find('input[name=address_province]').val(data.address_province);
                    parent_form.find('input[name=address_zipCode]').val(data.address_zipCode);
                    parent_form.find('input[name=landline]').val(data.landline);
                    parent_form.find('input[name=mobile]').val(data.mobile);
                }
                else if(parent_form.attr('data-farm-id')){
                    parent_form.find('input[name=name]').val(data.name);
                    parent_form.find('.farm-title').html(data.name);
                    parent_form.find('input[name=addressLine1]').val(data.addressLine1);
                    parent_form.find('input[name=addressLine2]').val(data.addressLine2);
                    parent_form.find('input[name=province]').val(data.province);
                    parent_form.find('input[name=zipCode]').val(data.zipCode);
                    parent_form.find('input[name=farmType]').val(data.farmType);
                    parent_form.find('input[name=landline]').val(data.landline);
                    parent_form.find('input[name=mobile]').val(data.mobile);
                }

                // Done tooltip animation to Edit
                edit_button.attr('data-tooltip',profile.edit_farm_name);
                edit_button.attr('data-position','left');
                edit_button.html('<i class="material-icons">mode_edit</i>');
                $(".tooltipped").tooltip({delay:50});
                edit_button.prop('disabled', false);
                cancel_button.toggle();
                config.preloader_progress.fadeOut();
                Materialize.toast('Profile updated successfully!', 2000, 'green lighten-1');
            },
            error: function(message){
                console.log(message['responseText']);
                config.preloader_progress.fadeOut();
            }
        });
    },

    cancel: function(parent_form, edit_button, cancel_button){
        config.preloader_progress.fadeIn();
        cancel_button.tooltip('remove');
        $.when(parent_form.find('input').prop('disabled',true)).done(function(){
            // Done tooltip animation to Edit
            edit_button.attr('data-tooltip',profile.edit_farm_name);
            edit_button.attr('data-position','left');
            edit_button.html('<i class="material-icons">mode_edit</i>');
            $(".tooltipped").tooltip({delay:50});
            cancel_button.toggle();
            config.preloader_progress.fadeOut();
        });
    },

    remove: function(parent_form, row){
        config.preloader_progress.fadeIn();

        $.ajax({
            url: parent_form.attr('action'),
            type: "DELETE",
            cache: false,
            data: {
                "id": parent_form.attr('data-farm-id'),
                "_token": parent_form.find('input[name=_token]').val()
            },
            success: function(data){
                if(data == 'OK') {
                    row.remove();
                    config.preloader_progress.fadeOut();
                    Materialize.toast('Farm information removed',2000);
                }
                else{
                    config.preloader_progress.fadeOut();
                    Materialize.toast('Farm information removal unsuccessful', 2500, 'red');
                }
            },
            error: function(message){
                console.log(message['responseText']);
                config.preloader_progress.fadeOut();
            }
        });
    }
};
