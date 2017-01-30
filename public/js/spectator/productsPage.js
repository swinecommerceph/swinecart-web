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

Vue.component('advanced-search', {
    template: '<div id="search-filter-modal" class="modal modal-fixed-footer">'+
                    '<form action="home/product/advancedsearch" method="GET" class="spectator_product_advanced_search">'+
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
                            '<button type="submit" class="btn waves-effect waves-light" name="advance-search">Search</button>'+
                        '</div>'+
                    '</form>'+
                '</div>'
})

var vm = new Vue({
    el: '#app-products',
    data: {}
})
