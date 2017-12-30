( function( $ ) {
	"use strict";

	// HashMap for searched keys and their post titles.
	var searchCache = {};

	// track the currect AJAX request.
	var sameAjaxRequest;

	// get cached post titles from the params object of wp_localize_script.
	var cachedPostTitles = ( false !== params.cached_post_titles && params.cached_post_titles.length ) ? params.cached_post_titles : false;

	$( "#nds-advanced-search-form #nds-search-box" ).autocomplete({
		delay: 300,
		source: function( request, response ) {

			// https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/RegExp.
			var matcher = new RegExp( $.ui.autocomplete.escapeRegex( request.term ), "i" );

			var searchTitlesForSuggestions = function( searchTitles ) {

				// function used to search the key in the post titles.
				var suggestions = $.grep( searchTitles, function( item ) {
									return matcher.test( item );
								});

				// cache the search term with its data in a hash map.
				cachedPostTitles = searchTitles;
				searchCache[request.term] = suggestions;

				return suggestions;
			};

			// check if the search key was already cached in the HashMap.
			if ( request.term in searchCache ) {

				// return suggestions using the cache object.
				response( searchCache[request.term] );

				// exit and avoid an ajax call as we can use data that was cached in earlier ajax calls.
				return;

			} // else check if cached post tiles exists.
			else if ( cachedPostTitles ) {

				// cachedPostTitles array may have been set in previous AJAX call or inititally by wp_localize_script.
				var searchSuggestions = searchTitlesForSuggestions( cachedPostTitles );

				// return the suggestions for the search term.
				response( searchSuggestions );

				// exit and avoid an ajax call as we can use data that was cached in earlier ajax calls.
				return;

			}

			// Else Make an AJAX Request.

			// AJAX call is made if wp_localize_script sent an empty array for post titles.
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

						// data contains the post titles sent by the AJAX handler.
						var searchSuggestions = searchTitlesForSuggestions( data );

						// return the suggestions for the search term.
						response( searchSuggestions );

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
