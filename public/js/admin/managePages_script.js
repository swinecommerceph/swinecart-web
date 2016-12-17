$(document).ready(function(){


    $('.delete-content-trigger').click(function(e){
        e.preventDefault();
        // console.log($('#delete-content-form > input').attr('name','_token').attr('value'));
        //$('#delete-content-token').attr('value', $('#delete-content-form').attr('data'));
        $('#delete-content-id').attr('value', $(this).attr('data'));
    });

    $('.edit-content-trigger').click(function(e){
        e.preventDefault();
        // console.log($('#delete-content-form > input').attr('name','_token').attr('value'));
        //$('#delete-content-token').attr('value', $('#delete-content-form').attr('data'));
        $('#edit-content-id').attr('value',  $(this).attr('data'));
    });




});
