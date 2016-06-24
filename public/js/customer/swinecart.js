'use strict';

var swinecart = {
    add: function(parent_form) {

        // Do AJAX
        $.ajax({
            url: config.swineCart_url + '/add',
            type: "POST",
            cache: false,
            data: {
                "_token": parent_form.find('input[name=_token]').val(),
                "productId": parent_form.attr('data-product-id'),
                "type": parent_form.attr('data-type'),
            },
            success: function(data) {
                // If product is not yet in Swine Cart
                if (data[0] === 'success') {
                    var span = $('#cart-icon span');

                    // Put quantity of Swine Cart to sessionStorage
                    sessionStorage.setItem('swine_cart_quantity', data[2]);

                    span.html(sessionStorage.getItem('swine_cart_quantity'));
                    span.addClass('badge');
                    $('#cart-icon .material-icons').addClass('left');
                    Materialize.toast(data[1] + ' added to Swine Cart', 1800, 'green lighten-1');
                }

                // If product is in Swine Cart but already requested
                else if (data[0] === 'requested') Materialize.toast(data[1] + ' already requested to Breeder', 1800, 'orange accent-2');

                // If product is already in Swine Cart and is not requested yet
                else Materialize.toast(data[1] + ' already in Swine Cart', 1800, 'orange accent-2');
            },
            error: function(message) {
                console.log(message['responseText']);
            }
        });

    },

    delete: function(parent_form, li_element) {
        // Do AJAX
        $.ajax({
            url: config.swineCart_url + '/delete',
            type: "DELETE",
            cache: false,
            data: {
                "_token": parent_form.find('input[name=_token]').val(),
                "itemId": parent_form.attr('data-item-id')
            },
            success: function(data) {
                // If deletion of item is successful
                if (data[0] === 'success') {
                    var span = $('#cart-icon span');

                    // Put quantity of Swine Cart to sessionStorage
                    sessionStorage.setItem('swine_cart_quantity', data[2]);

                    if (data[2] == 0) {
                        span.html("");
                        span.removeClass('badge');
                        $('#cart-icon .material-icons').removeClass('left');
                        $('#cart-dropdown #item-container').html(
                            '<li> <span class="center-align black-text"> No items in your Swine Cart </span> </li>'
                        );
                    } else span.html(sessionStorage.getItem('swine_cart_quantity'));

                    li_element.remove();
                    Materialize.toast(data[1] + ' removed from Swine Cart', 1800, 'green lighten-1');
                } else Materialize.toast(data[1] + ' is ' + data[0], 1500, 'orange accent-2');

            },
            error: function(message) {
                console.log(message['responseText']);
            }
        });
    },

    request: function(parent_form, status){
      // Do AJAX
      $.ajax({
        url: config.swineCart_url + '/request',
        type: "PUT",
        cache: false,
        data: {
          "_token": parent_form.find('input[name=_token]').val(),
          "itemId": parent_form.attr('data-item-id'),
          "productId": parent_form.attr('data-product-id')
        },
        success: function(data) {
          status.html('Requested');
          // $('.request-icon').remove();
          // $('.info').attr('class', 'info col s2 center-align')
        },
        error: function(message) {
          console.log(message['responseText']);
        },
      });
    },

    get_items: function() {
        // Do AJAX
        $.ajax({
            url: config.swineCart_url,
            type: "GET",
            cache: false,
            data: {},
            success: function(data) {
                var data = JSON.parse(data);

                // Check first if empty
                if (data.length == 0) {
                    $('#cart-dropdown #item-container').html(
                        '<li> <span class="center-align black-text"> No items in your Swine Cart </span> </li>'
                    );
                    config.preloader_circular.hide();
                } else {
                    var items = '';
                    data.forEach(function(element, index, array) {
                        // Parse if product breed is crossbreed
                        var product_breed = swinecart.capitalizeFirstLetter(element.product_breed);
                        if (product_breed.includes('+')) {
                            var part = product_breed.split('+');
                            product_breed = swinecart.capitalizeFirstLetter(part[0]) + ' x ' + swinecart.capitalizeFirstLetter(part[1]);
                        }

                        items += '<li class="collection-item avatar">' +
                            '<a href="' + config.viewProducts_url + '/' + element.product_id + '">' +
                            '<img src="' + element.img_path + '" alt="" class="circle">' +
                            '</a>' +
                            '<a href="' + config.viewProducts_url + '/' + element.product_id + '" class="anchor-title">' +
                            '<span class="title">' + element.product_name + '</span>' +
                            '</a>' +
                            '<p>' + swinecart.capitalizeFirstLetter(element.product_type) + ' - ' + product_breed + '<br>' +
                            element.breeder +
                            '</p>' +
                            '<form method="POST" action="' + config.host_url + config.swineCart_url + '/delete" accept-charset="UTF-8" data-item-id="' + element.item_id + '">' +
                            '<input name="_method" type="hidden" value="DELETE">' +
                            '<input name="_token" type="hidden" value="' + element.token + '">' +
                            '<a class="secondary-content delete-from-swinecart"><i class="material-icons">clear</i></a>' +
                            '</form>' +
                            '</li>';
                    });

                    // Put items to Swine Cart UI
                    $.when($('#cart-dropdown #item-container').html(items)).done(function() {
                        config.preloader_circular.hide();
                    });
                }

            },
            error: function(message) {
                console.log(message['responseText']);
            }
        });
    },

    rate: function(parent_form, comment) {
      $.ajax({
        url: config.swineCart_url + '/rate',
        type: "POST",
        data: {
          "_token": parent_form.find('input[name=_token]').val(),
          "breederId" : parent_form.attr('data-breeder-id'),
          "delivery" : parent_form.attr('data-delivery'),
          "transaction" : parent_form.attr('data-transaction'),
          "productQuality" : parent_form.attr('data-productQuality'),
          "afterSales" : parent_form.attr('data-afterSales'),
          "comment" : comment
        },
        success: function(data) {

        },
        error: function(message){
          console.log(message['responseText']);
        }
      });

    },

    get_quantity: function() {
        // Do AJAX
        $.ajax({
            url: config.swineCart_url + '/quantity',
            type: "GET",
            cache: false,
            data: {},
            success: function(data) {
                if (data != 0) {
                    var span = $('#cart-icon span');

                    // Put quantity of Swine Cart to sessionStorage
                    sessionStorage.setItem('swine_cart_quantity', data);

                    span.html(sessionStorage.getItem('swine_cart_quantity'));
                    span.addClass('badge');
                    $('#cart-icon .material-icons').addClass('left');
                }
            },
            error: function(message) {
                console.log(message['responseText']);
            }
        });
    },

    capitalizeFirstLetter: function(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
};
