'use strict';

$( document ).ready(function() {
    products.view();
    $('.card').click(function(){
        var values = $(this).attr('data-values').split('|');

        $('#modal-header').text(values[0]);
        $('#data-type').text(values[1]);
        $('#data-adg').text(values[2]);
        $('#data-fcr').text(values[3]);
        $('#data-backfat').text(values[4]);
        $('#data-status').text(values[5]);
        $('#data-quantity').text(values[6]);
        $('#data-price').text(values[7]);
        $('#modal-image').attr('src', values[8]);
        $('#data-information').text(values[9]);


    });
});


// Component for displaying the product information
Vue.component('product-modal', {

    props: [
        'info'
    ],
    // template for component for the product information
    template: '<div id="product-modal" class="modal modal-fixed-footer">'+
                '<div class="modal-content">'+
                    '<h4 id="modal-header"></h4>'+
                    '<div class="divider"></div>'+
                    '<div class="row">'+
                        '<div class="col s12 center">'+
                            '<img id="modal-image" class="product_image" src="" alt="Image broken" onerror="this.src='+'/images/logo.png'+'" />'+
                        '</div>'+
                        '<div class="col s12">'+
                            '<table>'+
                                '<thead>'+
                                    '<tr>'+
                                        '<th data-field="type">Type</th>'+
                                        '<th data-field="adg">ADG</th>'+
                                        '<th data-field="fcr">FCR</th>'+
                                        '<th data-field="backfat_thickness">Backfat Thickness</th>'+
                                        '<th data-field="status">Status</th>'+
                                        '<th data-field="quantity">Quantity</th>'+
                                        '<th data-field="price">Price</th>'+

                                    '</tr>'+
                                '</thead>'+

                                '<tbody>'+
                                    '<tr>'+
                                        '<td id="data-type"></td>'+
                                        '<td id="data-adg"></td>'+
                                        '<td id="data-fcr"></td>'+
                                        '<td id="data-backfat"></td>'+
                                        '<td id="data-status"></td>'+
                                        '<td id="data-quantity"></td>'+
                                        '<td id="data-price"></td>'+
                                    '</tr>'+
                                '</tbody>'+
                            '</table>'+

                        '</div>'+
                    '</div>'+
                    '<div class="row">'+
                        '<div class="left col s12">'+
                            '<h5>Other Product Information</h5>'+
                        '</div>'+
                        '<div id="data-information" class="col s12">'+

                        '</div>'+
                    '</div>'+
                '</div>'+
                '<div class="modal-footer">'+
                    '<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Close</a>'+
                '</div>'+
            '</div>',
    methods: {

    }

});

var vm = new Vue({
    el: '#app-products',
    data: {
        toggled: false,
        info: info
    },

    methods: {
        displayProductModal: function(){

        }
    }
});
