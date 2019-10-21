$(document).ready(function(){

    $('select').material_select();
  
    // prevent the dropdown from instantly closing upon clicking
    // Materialize bug?
    $('#sort-by').on('click', function (event) {
      event.stopPropagation();
    });

    var chips = '';

    // Setup Elasticsearch
    /* var client = new $.es.Client({
        hosts: window.elasticsearchHost
    }); */

    $('#search-results').width($('#search-field').width());

    // Find all ticked checkboxes for producing chips
    if($('input:checked').length > 0) chips += 'Filtered by: ';
    
    $('input:checked').each(function(){
        console.log('something checked');
        console.log($(this));

        if ($(this).attr('data-type')) {
          console.log('checked: type')
          chips += `
              <div class="chip" style="text-transform:capitalize;">
                Type: ${ $(this).attr('data-type') }
                <i class="close material-icons" data-checkbox-id="${ $(this).attr('id') }" >close</i>
              </div>
            `
        }
        else if ($(this).attr('data-breed')) {
          console.log('checked: breed')
          chips += `
              <div class="chip" style="text-transform:capitalize;">
                Type: ${ $(this).attr('data-breed') }
                <i class="close material-icons" data-checkbox-id="${ $(this).attr('id')}" >close</i>
              </div>          
            `
        }
        else if ($(this).attr('data-breeder')) {
          console.log('checked: breeder')
          chips += `
              <div class="chip" style="text-transform:capitalize;">
                Type: ${ $(this).attr('data-breeder') }
                <i class="close material-icons" data-checkbox-id="${ $(this).attr('id')}" >close</i>
              </div>          
            `
        }

    });

    // Append chip to #chip-container
    $('#chip-container').append(chips);

    // For Filter Container Pushpin
    $('#filter-container #collapsible-container').pushpin({
        top: $('#filter-container').offset().top,
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
        console.log('something checked');
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
