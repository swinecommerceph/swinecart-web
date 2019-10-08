'use strict';

var filter = {
    apply: function(){
        // URL search syntax: ?q=value&type=value[+value]*&breed=value[+value]&sort=value*
        var types = [];
        var breeds = [];
        var breeders = [];
        var filter_parameters = '?';
        var search_query = document.getElementById('search').value;
        var type_url = '';
        var breed_url = '';
        var breeder_url = '';

        // Get all checked checkboxes regarding type of product
        $('.filter-type:checked').each(function(){
            types.push($(this).attr('data-type'));
        });

        // Get all checked checkboxes regarding breed of product
        $('.filter-breed:checked').each(function(){
            breeds.push($(this).attr('data-breed'));
        });

        // Get all checked checkboxes regarding breeders
        $('.filter-breeder:checked').each(function(){
            breeders.push($(this).attr('data-breeder'));
        });


        // Check if there is search query
        if(search_query){
            filter_parameters += 'q=' + search_query;
        }

        // Join type filter values by '+' and append to filter_parameters
        if(types.length > 0){
            type_url = 'type=';
            type_url += types.join('+');
            filter_parameters += (filter_parameters.length > 1) ? '&'+type_url : type_url;
        }

        // Join breed filter values by '+' and append to filter_parameters
        if(breeds.length > 0){
            //console.log(breeds);
            breed_url = 'breed=';
            breed_url += breeds.join('+');
            filter_parameters += (filter_parameters.length > 1) ? '&'+breed_url : breed_url;
        }

        // Join breeder filter values by '+' adn append to filter_parameters
        if (breeders.length > 0){
          breeder_url = 'breeder=';
          breeder_url += breeders.join('+');
          filter_parameters += (filter_parameters.length > 1) ? '&'+breeder_url : breeder_url;
        }

        console.log('filter_parameters: ' + filter_parameters);

        // Append sort parameters to filter_parameters
        if($('select option:selected').val() == ""){
            filter_parameters += (filter_parameters.length > 1) ? '&sort=none' : 'sort=none';
        }
        else {
            filter_parameters += (filter_parameters.length > 1) ? '&sort='+$('select option:selected').val() : 'sort='+$('select option:selected').val();
        }
        window.location = config.viewProducts_url+filter_parameters;

    }
};

$(document).ready(function(){

    var chips = '';

    // Setup Elasticsearch
    /* var client = new $.es.Client({
        hosts: window.elasticsearchHost
    }); */

    $('#search-results').width($('#search-field').width());

    // Find all ticked checkboxes for producing chips
    if($('input:checked').length > 0) chips += 'Filtered by: ';
    $('input:checked').each(function(){
        if($(this).attr('data-type')){
            chips +=
                '<div class="chip" style="text-transform:capitalize;">'+
                    'Type: '+$(this).attr('data-type')+
                    '<i class="close material-icons" data-checkbox-id="'+$(this).attr('id')+'">close</i>'+
                '</div> ';
        }
        else if ($(this).attr('data-breed')) {
            chips +=
                '<div class="chip" style="text-transform:capitalize;">'+
                    'Breed: '+$(this).attr('data-breed')+
                    '<i class="close material-icons" data-checkbox-id="'+$(this).attr('id')+'">close</i>'+
                '</div> ';
        }

    });

    // Append chip to #chip-container
    console.log(chips);
    $('#chip-container').append(chips);

    // For Filter Container Pushpin
    $('#filter-container #collapsible-container').pushpin({
        // top: $('#filter-container').offset().top,
        offset: 135
    });

    $("input#search").keydown(function(e){
        // Perform GET request upon pressing the Enter key
        // or fetch suggestions from Elastic search
        // and output it on search results
        if(e.which == 13) {
            e.preventDefault();
            filter.apply();
        }
        /* else{
            setTimeout(function(){
                var searchPhrase = $('input#search').val();

                // Execute of searchPhrase is not empty
                if(searchPhrase){

                    // Query on Elasticsearch search engine
                    client.search({
                        index: 'swinecart',
                        type: 'products',
                        body:{
                            "_source": "output",
                            "suggest": {
                                "productSuggest": {
                                    "prefix": searchPhrase,
                                    "completion": {
                                        "field": "suggest",
                                        "fuzzy": {
                                        	"fuzziness": 2
                                        }
                                    }
                                }
                            }
                        }
                    }).then(function(response){
                        var options = (response.suggest) ? response.suggest.productSuggest[0].options : '';
                        var searchResultsTop = '';
                        var searchResultsBot = '';

                        if(options.length > 0){

                            for (var i = 0; i < 3; i++) {
                                searchResultsTop += '<li class="search-item">' +
                                    options[i]._source.output[1] +
                                    '</li>';

                                searchResultsBot += '<li class="search-item">' +
                                    options[i]._source.output[0] +
                                    '</li>';
                            }

                            $("#search-results ul").html(searchResultsTop + searchResultsBot);

                            $("#search-results").show();
                        }

                    }, function(error){
                        console.trace(error.message);
                    });
                }

                else $("#search-results").hide();

            }, 0);

        } */
    });

    // Redirect to designated link upon checkbox and select value change
    $("#filter-container input[type=checkbox], select").change(function(){
        filter.apply();
    });

    // Redirect to designated link upon removing chips
    $(".chip i.material-icons").click(function(e){
        e.preventDefault();
        $("#"+$(this).attr('data-checkbox-id')).prop('checked', false);
        filter.apply();
    });

    $('body').on('click', 'li.search-item', function(e){
        e.preventDefault();

        $('input#search').val($(this).html());

        $("#search-results").hide();
        filter.apply();
    });

});

//# sourceMappingURL=viewProducts.js.map
