( function( $ ) {
	"use strict";

	// to cache the search.
	var searchSuggestions;
	var searchCache = {};

	// track the currect AJAX request.
	var sameAjaxRequest;

	$( "#nds-advanced-search-form #nds-search-box" ).autocomplete({
		delay: 300,
		source: function( request, response ) {

			// https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/RegExp.
			var matcher = new RegExp( $.ui.autocomplete.escapeRegex( request.term ), "i" );

			// check if the data is already cached.
			if ( request.term in searchCache ) {

				// to debug uncomment the following lines.
				//console.log("Data for search term found in cache");
				//console.log( searchCache[request.term] );

				// return suggestions using the cache object.
				response( searchCache[request.term] );

				// exit and avoid an ajax call as we can use data that was cached in earlier ajax calls.
				return;
			}

			// when the search term is not cached.
			sameAjaxRequest = $.ajax ({
					url: params.ajaxurl, // domain/wp-admin/admin-ajax.php
					type: "POST",
					dataType: "json",
					data: {
						action: "nds_advanced_search_autosuggest",
						ajaxRequest: "yes",
						term: request.term
					}
				})

				// on success.
				.done( function( data, textStatus, jqXHR ) {

					if ( jqXHR === sameAjaxRequest && null !== data && "undefined" !== typeof( data ) ) {

						searchSuggestions = $.grep( data, function( item ) {
							return matcher.test( item );
						});

						// return the suggestions for the search term.
						response( searchSuggestions );

						// cache the search term with its data in a hash map.
						searchCache[request.term] = searchSuggestions;

						// to debug uncomment the following lines.
						//console.log( "Data for search term not found in cache. Added to cache ...");
						//console.log( searchCache );
					}
				})

				// on failure.
				.fail( function( xhr, status, errorThrown ) {

					$( "#nds-search-box" ).css( "background-color", "yellow" );
					$( "#nds-search-box" ).val( "An error occurred ..." );
				})

				// after all this time?
				.always( function( xhr, status ) {
				});
		},
		minLength: 3
	});
})( jQuery );
