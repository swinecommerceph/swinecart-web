'use strict';

$( document ).ready(function() {

    $('.card').click(function(){
        var values = $(this).attr('data-values').split('|');
        console.log(values);

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
