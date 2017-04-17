$(document).ready(function(){
    $('select').material_select();

    $('#this_user_information_trigger').click(function(e){
        e.preventDefault();
        spectator_layout.show_spectator_information();
    });

});
