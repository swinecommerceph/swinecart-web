'use strict';

var products = {
    // function to get the product details and insert the DOM element to the modal to display it in the fron end
    fetchProductDetails: function(id){
        $.ajax({
            url: config.spectator_url+'/products/product-details',
            type: 'GET',
            cache: false,
            data: {
                'id': id
            },
            success: function(data){
                data.forEach(function(data){
                    $('#spectator-product-modal').html('\
                    <div class="modal-content">\
                        <h4 id="modal-header">'+data.name+'</h4>\
                        <div class="row">\
                            <div class="col s12 m12 l4">\
                                <img class="product_image" src="'+data.image_name+'" alt="Image broken" />\
                            </div>\
                            <div class="col s12 m12 l8">\
                                <div class="row">\
                                    <div class="col s12 m12 l12 xl12">\
                                        <div class="row">\
                                            <div class="col s6 m6 l6 xl6">\
                                                <div class="col s12 m12 l12 xl12 spectator-product-modal-title">\
                                                    Type\
                                                </div>\
                                                <div class="col s12 m12 l12 xl12 spectator-product-modal-data">\
                                                    '+data.type+'\
                                                </div>\
                                            </div>\
                                            <div class="col s6 m6 l6 xl6">\
                                                <div class="col s12 m12 l12 xl12 spectator-product-modal-title">\
                                                    Status\
                                                </div>\
                                                <div class="col s12 m12 l12 xl12 spectator-product-modal-data">\
                                                    '+data.status+'\
                                                </div>\
                                            </div>\
                                        </div>\
                                        <div class="row">\
                                            <div class="col s6 m6 l6 xl6">\
                                                <div class="col s12 m12 l12 xl12 spectator-product-modal-title">\
                                                    Price\
                                                </div>\
                                                <div class="col s12 m12 l12 xl12 spectator-product-modal-data">\
                                                    '+data.price+'\
                                                </div>\
                                            </div>\
                                            <div class="col s6 m6 l6 xl6">\
                                                <div class="col s12 m12 l12 xl12 spectator-product-modal-title">\
                                                    Quantity\
                                                </div>\
                                                <div class="col s12 m12 l12 xl12 spectator-product-modal-data">\
                                                    '+data.quantity+'\
                                                </div>\
                                            </div>\
                                        </div>\
                                        <div class="row">\
                                            <div class="col s4 m4 l4 xl4">\
                                                <div class="col s12 m12 l12 xl12 spectator-product-modal-title">\
                                                    ADG\
                                                </div>\
                                                <div class="col s12 m12 l12 xl12 spectator-product-modal-data">\
                                                    '+data.adg+'\
                                                </div>\
                                            </div>\
                                            <div class="col s4 m4 l4 xl4">\
                                                <div class="col s12 m12 l12 xl12 spectator-product-modal-title">\
                                                    FCR\
                                                </div>\
                                                <div class="col s12 m12 l12 xl12 spectator-product-modal-data">\
                                                    '+data.fcr+'\
                                                </div>\
                                            </div>\
                                            <div class="col s4 m4 l4 xl4">\
                                                <div class="col s12 m12 l12 xl12 spectator-product-modal-title">\
                                                    Backfat Thickness\
                                                </div>\
                                                <div class="col s12 m12 l12 xl12 spectator-product-modal-data">\
                                                    '+data.backfat_thickness+'\
                                                </div>\
                                            </div>\
                                        </div>\
                                    </div>\
                                </div>\
                            </div>\
                        </div>\
                        <div class="row">\
                            <div class="left col s12 m12 l12">\
                                <h5>Other Product Information</h5>\
                            </div>\
                            <div class="col s12">\
                            '+data.other_details+'\
                            </div>\
                        </div>\
                    </div>\
                    ')
                });
            },
            error: function(message){
                console.log(message['responseText']);
            }
        });
    }
};
