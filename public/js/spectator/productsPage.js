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

// Component for advanced search
Vue.component('advanced-search', {
    // template for component for the advanced search
    template: '<div id="search-filter-modal" class="modal modal-fixed-footer">'+
                    '<form action="home/product/advancedsearch" method="GET" class="spectator_product_advanced_search"> '+
                        '<div class="modal-content">'+
                            '<h4>Advanced Search</h4>'+
                            '<div class="divider"></div>'+
                            '<div class="row">'+

                                '<div class="col s12">'+
                                    '<div class="row">'+
                                        '<div class="input-field col s8">'+
                                            '<input id="search-name" type="text" name="name">'+
                                            '<label for="search-name">Search Item Name</label>'+
                                        '</div>'+
                                    '</div>'+

                                    '<div class="row">'+
                                        '<div class="col s8">'+
                                            '<input class="filled-in" type="checkbox" id="type-boar" name="boar" value="boar" />'+
                                            '<label for="type-boar">Boar</label>'+

                                            '<input class="filled-in" type="checkbox" id="type-sow" name="sow" value="sow" />'+
                                            '<label for="type-sow">Sow</label>'+

                                            '<input class="filled-in" type="checkbox" id="type-gilt" name="gilt" value="gilt" />'+
                                            '<label for="type-gilt">Gilt</label>'+

                                            '<input class="filled-in" type="checkbox" id="type-semen" name="semen" value="semen" />'+
                                            '<label for="type-semen">Semen</label>'+
                                        '</div>'+
                                    '</div>'+

                                    '<div class="row">'+
                                        '<div class="col s3">'+
                                            '<label for="min-price">Minimum Price</label>'+
                                            '<input type="number" value='+minPrice+' min="'+minPrice+'" name="minPrice"/>'+
                                        '</div>'+
                                        '<div class="col s3">'+
                                            '<label for="max-price">Maximum Price</label>'+
                                            '<input type="number" value="'+maxPrice+'" max="'+maxPrice+'" name="maxPrice"/>'+
                                        '</div>'+
                                    '</div>'+

                                    '<div class="row">'+
                                        '<div class="col s3">'+
                                            '<label for="min-price">Minimum Quantity</label>'+
                                            '<input type="number" value="'+minQuantity+'" min="'+minQuantity+'" name="minQuantity"/>'+
                                        '</div>'+
                                        '<div class="col s3">'+
                                            '<label for="max-price">Maximum Quantity</label>'+
                                            '<input type="number" value="'+maxQuantity+'" max="'+maxQuantity+'" name="maxQuantity"/>'+
                                        '</div>'+
                                    '</div>'+

                                    '<div class="row">'+
                                        '<div class="col s3">'+
                                            '<label for="min-price">Minimum ADG</label>'+
                                            '<input type="number" value="'+minADG+'" min="'+minADG+'" name="minADG"/>'+
                                        '</div>'+
                                        '<div class="col s3">'+
                                            '<label for="max-price">Maximum ADG</label>'+
                                            '<input type="number" value="'+maxADG+'" max="'+maxADG+'" name="maxADG"/>'+
                                        '</div>'+
                                    '</div>'+

                                    '<div class="row">'+
                                        '<div class="col s3">'+
                                            '<label for="min-price">Minimum FCR</label>'+
                                            '<input type="number" value="'+minFCR+'" min="'+minFCR+'" name="minFCR"/>'+
                                        '</div>'+
                                        '<div class="col s3">'+
                                            '<label for="max-price">Maximum FCR</label>'+
                                            '<input type="number" value="'+maxFCR+'" max="'+maxFCR+'" name="maxFCR"/>'+
                                        '</div>'+
                                    '</div>'+

                                    '<div class="row">'+
                                        '<div class="col s3">'+
                                            '<label for="min-price">Minimum Backfat Thickness</label>'+
                                            '<input type="number" value="'+minBackfatThickness+'" min="'+minBackfatThickness+'" name="minBackfatThickness"/>'+
                                        '</div>'+
                                        '<div class="col s3">'+
                                            '<label for="max-price">Maximum Backfat Thickness</label>'+
                                            '<input type="number" value="'+maxBackfatThickness+'" max="'+maxBackfatThickness+'" name="maxBackfatThickness"/>'+
                                        '</div>'+
                                    '</div>'+

                                '</div>'+

                            '</div>'+
                        '</div>'+
                        '<div class="modal-footer">'+
                            '<button type="submit" class="btn waves-effect waves-light" name="advance-search" v-on:click="advancedSearchMethod($e)">Search</button>'+
                        '</div>'+
                    '</form>'+
                '</div>',

})

// Component for displaying the product information
Vue.component('product-modal', {
    props: [],
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

})

Vue.component('modal-trigger', {
    template:'<a href="#product-modal" class="modal-trigger" v-on:click="modalData">See more information</a>'


})

var vm = new Vue({
    el: '#app-products',
    data: {
        toggled: false
    },
    methods: {
        advancedSearchMethod: function(e){

        }
    }
})
