'use strict';

var users = {
    fetch_user_info : function(id, role){
        $.ajax({
            url: config.spectator_url + '/users/details',
            type: "GET",
            cache: false,
            data: {
                'userId' : id,
                'userRole' : role
            },
            success: function(data) {
                $('#spectator-user-modal-content').empty();
                data.forEach(function(data){
                    if(role == 2){
                        $('#spectator-user-modal-content').html('\
                        <div class="row"> \
                            <div class="col s12 m6 l6"><div class="spectator-user-modal-title grey-text">Name</div><div class="spectator-user-modal-data">'+data.user_name+'</div></div> \
                            <div class="col s12 m6 l6"><div class="spectator-user-modal-title grey-text">User Type</div><div class="spectator-user-modal-data">'+data.role+'</div></div> \
                        </div> \
                        <div class="row"> \
                            <div class="col s12 m6 l6"><div class="spectator-user-modal-title grey-text">Email</div><div class="spectator-user-modal-data">'+data.email+'</div></div> \
                            <div class="col s12 m6 l6"><div class="spectator-user-modal-title grey-text">Status</div><div class="spectator-user-modal-data">'+data.status_instance+'</div></div> \
                        </div> \
                        <div class="row"> \
                            <div class="col s12 m12 l12"><div class="spectator-user-modal-title grey-text">Produce</div><div class="spectator-user-modal-data">'+data.produce+'</div></div> \
                        </div> \
                        <div class="row"> \
                            <div class="col s12 m12 l12"><div class="spectator-user-modal-title grey-text">Address</div><div class="spectator-user-modal-data">'+data.addressLine1+', '+ data.addressLine2+ '</div></div> \
                        </div> \
                        <div class="row"> \
                            <div class="col s12 m6 l6"><div class="spectator-user-modal-title grey-text">Province</div><div class="spectator-user-modal-data">'+data.province+'</div></div> \
                            <div class="col s12 m6 l6"><div class="spectator-user-modal-title grey-text">Zipcode</div><div class="spectator-user-modal-data">'+data.zipcode+'</div></div> \
                        </div> \
                        <div class="row"> \
                            <div class="col s12 m6 l6"><div class="spectator-user-modal-title grey-text">Mobile</div><div class="spectator-user-modal-data">'+data.office_mobile+'</div></div> \
                            <div class="col s12 m6 l6"><div class="spectator-user-modal-title grey-text">Landline</div><div class="spectator-user-modal-data">'+data.office_landline+'</div></div> \
                        </div> \
                        <div class="row"> \
                            <div class="col s12 m6 l6"><div class="spectator-user-modal-title grey-text">Contact Person</div><div class="spectator-user-modal-data">'+data.contact_person+'</div></div> \
                            <div class="col s12 m6 l6"><div class="spectator-user-modal-title grey-text">Contact Person Mobile</div><div class="spectator-user-modal-data">'+data.contact_person_mobile+'</div></div> \
                        </div> \
                        <div class="row"> \
                            <div class="col s12 m12 l12"><div class="spectator-user-modal-title grey-text">Website</div><div class="spectator-user-modal-data">'+data.website+'</div></div> \
                        </div> \
                        ');
                    }else{
                        $('#spectator-user-modal-content').html('\
                        <div class="row"> \
                            <div class="col s12 m6 l6"><div class="spectator-user-modal-title grey-text">Name</div><div class="spectator-user-modal-data">'+data.user_name+'</div></div> \
                            <div class="col s12 m6 l6"><div class="spectator-user-modal-title grey-text">User Type</div><div class="spectator-user-modal-data">'+data.role+'</div></div> \
                        </div> \
                        <div class="row"> \
                            <div class="col s12 m6 l6"><div class="spectator-user-modal-title grey-text">Email</div><div class="spectator-user-modal-data">'+data.email+'</div></div> \
                            <div class="col s12 m6 l6"><div class="spectator-user-modal-title grey-text">Status</div><div class="spectator-user-modal-data">'+data.status_instance+'</div></div> \
                        </div> \
                        <div class="row"> \
                            <div class="col s12 m12 l12"><div class="spectator-user-modal-title grey-text">Address</div><div class="spectator-user-modal-data">'+data.addressLine1+', '+ data.addressLine2+ '</div></div> \
                        </div> \
                        <div class="row"> \
                            <div class="col s12 m6 l6"><div class="spectator-user-modal-title grey-text">Province</div><div class="spectator-user-modal-data">'+data.province+'</div></div> \
                            <div class="col s12 m6 l6"><div class="spectator-user-modal-title grey-text">Zipcode</div><div class="spectator-user-modal-data">'+data.zipcode+'</div></div> \
                        </div> \
                        <div class="row"> \
                            <div class="col s12 m6 l6"><div class="spectator-user-modal-title grey-text">Mobile</div><div class="spectator-user-modal-data">'+data.mobile+'</div></div> \
                            <div class="col s12 m6 l6"><div class="spectator-user-modal-title grey-text">Landline</div><div class="spectator-user-modal-data">'+data.landline+'</div></div> \
                        </div> \
                        ');
                    }

                });
            },
            error: function(message) {
                console.log(message['responseText']);
            }
        });
    },
};
