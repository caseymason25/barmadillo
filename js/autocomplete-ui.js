/***********************************************
 *          autocomplete-ui.js                 *
 *                                             *
 *  The jquery needed to have the autocomplete *
 *  work                                       *
 ***********************************************/

$(function() {
    var cache = {};
            $("#bar_search").autocomplete({ //#bar_search is the ID of the search box
                minLength: 2,
                source: function( request, response ) {
                    var term = request.term;
                    if ( term in cache ) {
                      response( cache[ term ] );
                      return;
                    }
                    
                    $.getJSON( "/scripts/autocomplete-core.php", request, function( data, status, xhr ) {
                    cache[ term ] = data;
                    response( data );
                  });
                }
            });   
        });