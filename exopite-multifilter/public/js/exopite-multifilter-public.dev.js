;(function ( $, window, document, undefined ) {
    'use strict';

    /*
     * ToDo:
     * - http://javascriptcompressor.com/
     */

    // Plugin definition.
    $.fn.exopiteMultifilter = function( options ) {

        var defaults = {
            loadingFinishedText: 'No More Posts Available',     //Text to show when loading is finished.Default "No More Posts Available"
        }

        var _getSelectedTaxonomies = function getSelectedTaxonomies( taxonomies_terms, $taxonomies ) {

            // Get selected taxonomies
            $taxonomies.each(function( index, el ){
                var taxonomy = $( el ).data( 'taxonomy' );

                taxonomies_terms[taxonomy] = {};

                var terms = [];
                $( el ).find( '.active' ).each(function( index, elx ) {

                    terms.push( $( elx ).data( 'term' ) );
                });
                taxonomies_terms[taxonomy] = terms;

            });

            return taxonomies_terms;
        };

        var _getPaged = function getPaged( $this ) {
            var url = $this.attr( 'href' );
            var regex = /pages?\/(\d+)/g;
            var matches = regex.exec( url );

            return ( matches ) ? matches[1] : 1;
        };

        var _getUrlVars = function getUrlVars() {
            var vars = {};
            window.location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
                vars[key] = value;
            });
            return vars;
        }

        var _animate = function animate( $what, when, success, base, dataJSON ) {
            switch( when ) {
                case 'out':
                    $what.stop(true, false).slideUp( 500 ).css({ opacity: 0, transition: 'opacity .5s' });
                    break;
                case 'in':
                    $what.stop(true, false).css({ opacity: 1, transition: 'opacity .5s', height: 'auto' }).slideDown( 500 ).promise().then(function(){
                        if ( success ) {
                            if ( typeof wp.hooks !== 'undefined' ) wp.hooks.doAction( 'ema-success-animation-end', base, dataJSON );
                        }
                    });
                    break;
            }

        };

        var _getSelector = $.proxy(function(){

            return this.selector;

        }, this);

        return this.each(function( i, el ) {

            // Item specific
            var base = el;
            // Extend our default options with those provided.
            // Note that the first argument to extend is an empty
            // object – this is to keep from overriding our "defaults" object.
            var settings = $.extend({}, defaults, options );
            var elements = {
                filterItems: $( base ).find( '.exopite-multifilter-filter-item' ),
                nowLoading: $( base ).find( '.exopite-multifilter-now-loading' ),
                taxonomies: $( base ).find( '.exopite-multifilter-filter-taxonomy' ),
                searchButton: $( base ).find( '.exopite-multifilter-filter-reset-search' ).find( 'button[type="submit"]' ),
                searchField: $( base ).find( '.exopite-multifilter-filter-reset-search' ).find( 'form' ).find( 'input' ),
                reset: $( base ).find( '.exopite-multifilter-filter-reset' ),
                itemsContainer: $( base ).find( '.exopite-multifilter-items' ),
                pagination: $( base ).find( '.exopite-multifilter-paginations' ),
                loading: $( base ).find( '.exopite-multifilter-now-loading' ),
            };
            var loading = false;
            var loaded = false;
            var paged = 1;
            var search = '';
            var dataJSON;

            /**
             * On pagionation click, Check if top is higher then viewport, if yes, scroll to top.
             *
             * @param  {object} that        - this from the calling function
             * @param  {object} dataJSON    - settings from PHP
             * @return {object} that
             */
            var _checkTop = function( that, dataJSON ) {
                if ( base !== that || dataJSON.pagination != 'pagination' ) return;

                var offset = $( that ).offset().top;

                if ( ( offset - $(window).scrollTop() ) > 0 ) return;

                if ( $( '#wpadminbar' ).length ) {
                    offset -= $( '#wpadminbar' ).height();
                }

                offset -= $( 'header nav' ).height();

                $( 'html, body' ).animate( {scrollTop: ( offset ) }, 300 );

                return that;
            };

            var _init = function _init() {

                dataJSON = $( base ).data('ajax');
                $( base ).attr( "data-index", i );

                if ( typeof wp.hooks !== 'undefined' ) {

                    // Scroll wrapper top if not visible (before remove animate out or after)
                    wp.hooks.addAction( 'ema-before-send', _checkTop, 10 );
                    //wp.hooks.addAction( 'ema-success-end', _checkTop, 10 );

                }

            };

            var _getItems = function getItems() {

                // Do not override taxonomies and terms if filter is not visible
                if ( dataJSON.display_filter ) {
                    var taxonomies_terms = {};

                    // Get selected taxonomies
                    dataJSON.taxonomies_terms = _getSelectedTaxonomies( taxonomies_terms, elements.taxonomies );
                }

                // Do not override search if not visible
                if( elements.searchField.length ) {
                    dataJSON.search = search;
                }
                dataJSON.paged = paged;

                // Store current session state
                if ( dataJSON.store_session ) {
                    localStorage.setItem( _getSelector() + i, JSON.stringify( {
                        taxonomies_terms : dataJSON.taxonomies_terms,
                        paged : paged,
                        search : search
                    } ) );
                }

                $.ajax({
                    cache: false,
                    type: "POST",
                    url: wp_ajax.ajax_url,
                    data: {
                        action: 'exopite_multifilter_get_posts',
                        json: JSON.stringify( dataJSON )
                    },
                    beforeSend: function() {

                        loading = true;

                        // Add hook before AJAX sent
                        // Can be used e.g. refresh masonry container, etc...
                        // https://gist.github.com/JoeSz/6aa061ff48eaf1af658d3adf9d71ec37
                        if ( typeof wp.hooks !== 'undefined' ) wp.hooks.doAction( 'ema-before-send', base, dataJSON );

                        if ( dataJSON.pagination == 'pagination' || paged == 1 ) {
                            _animate( elements.itemsContainer, 'out', false, null, null );
                        }

                        if ( dataJSON.pagination != 'infinite' ) {
                            _animate( elements.pagination, 'out', false, null, null );
                        }

                        _animate( elements.loading, 'in', false, null, null );


                    },
                    success: function( response ){

                        if ( typeof wp.hooks !== 'undefined' ) wp.hooks.doAction( 'ema-success-top', base, dataJSON );

                        _animate( elements.loading, 'out', false, null, null );

                        var html = $(response);
                        var articles = html.find('article');
                        var hasNoPosts = html.filter('.no-posts-found');

                        // If response contain articles
                        if ( articles.length > 0 ) {

                            var pagination = html.filter('.exopite-multifilter-paginations').children(":first");
                            var pagedUrl = elements.itemsContainer.data( 'page' ) + 'page/' + paged + '/';

                            if ( dataJSON.pagination == 'pagination' || paged == 1 ) {

                                // Overwrite container HTML with new items
                                elements.itemsContainer.html( articles );

                                if ( dataJSON.style == 'masonry' ) {

                                    // On masonry we do not need animation, should be handeled by masonry
                                    $(elements.itemsContainer).css({ opacity: 1, height: 'auto', display: 'flex' });

                                } else {

                                    _animate( elements.itemsContainer, 'in' );

                                }

                                // Hook and event
                                // Useful: eg. handeling masonry refresh (soms masonry required inserted elements too)
                                if ( typeof wp.hooks !== 'undefined' ) wp.hooks.doAction( 'ema-success-items-paginated', base, articles );
                                $( base ).trigger( 'success-items-paginated.exopiteMultifilter', [base, articles] );

                            } else {
                                // infinite or readmore

                                // Add page numbers
                                if ( dataJSON.display_page_number ) {

                                    var pageNumber = $( '<div class="page-number" data-page="' + pagedUrl + '">' + paged + '</div>' );
                                    articles = pageNumber.add( articles );

                                } else if( dataJSON.update_paged ) {

                                    // Update page number in first inserted article data-page attribute
                                    // This is need for update page in browser URL on scroll
                                    articles.filter( 'article' ).first().attr( 'data-page', pagedUrl );

                                }

                                // Apped new items to container
                                elements.itemsContainer.append( articles );

                                if ( typeof wp.hooks !== 'undefined' ) wp.hooks.doAction( 'ema-success-items-appended', base, articles );
                                $( base ).trigger( 'success-items-appended.exopiteMultifilter', [base, articles] );

                            }

                            if ( typeof wp.hooks !== 'undefined' ) wp.hooks.doAction( 'ema-success', base, articles );
                            $( base ).trigger( 'success-ajax.exopiteMultifilter', [base, articles] );

                            // Update url in browser URL field
                            if( dataJSON.update_paged && pagedUrl != window.location ){
                               changeBrowserUrl( pagedUrl );
                            }

                            // Insert pagination if not infinite
                            if ( dataJSON.pagination != 'infinite' ) {
                                elements.pagination.html( pagination );
                                _animate( elements.pagination, 'in', true, base, dataJSON );
                            }

                        } else {

                            if ( dataJSON.pagination == 'pagination' || paged == 1 ) {
                                elements.itemsContainer.empty();
                            }

                            elements.pagination.html( hasNoPosts );
                            _animate( elements.itemsContainer, 'in', false, null, null );
                            _animate( elements.pagination, 'in', false, null, null );

                            loaded = true;

                            setTimeout(function() {

                                elements.pagination.fadeOut('fast');

                            }, 3000);

                        }

                        if ( $( base ).find( '.nothing-more' ).length ) {

                               setTimeout(function() {

                                elements.pagination.fadeOut('fast');

                            }, 3000);

                        }

                        if ( typeof wp.hooks !== 'undefined' ) wp.hooks.doAction( 'ema-success-end', base, dataJSON );

                        loading = false;

                    },
                    error: function( xhr, status, error ) {

                        if ( typeof wp.hooks !== 'undefined' ) wp.hooks.doAction( 'ema-error', base );

                        console.log( 'Error: ' + xhr.responseText );
                        console.log( 'Error: ' + xhr );
                        console.log( 'Error: ' + status );
                        console.log( 'Error: ' + error );

                        loading = false;

                    }
                }); // AJAX
            };

            var _checkBottom = function() {

                if( ! loading && ! loaded && dataJSON.pagination == 'infinite' && $(window).scrollTop() >= $( elements.itemsContainer ).children().last().offset().top + $( elements.itemsContainer ).children().last().outerHeight() - window.innerHeight) {

                    paged++;
                    if ( typeof wp.hooks !== 'undefined' ) wp.hooks.doAction( 'ema-bottom-reached', base );
                    _getItems();

                }

            };

            var _onScroll = Exopite.throttle(function() {

                if ( typeof dataJSON !== "undefined" ) {

                    if ( dataJSON.pagination == 'infinite' ) {
                        _checkBottom();
                    }

                    if ( dataJSON.update_paged ) {
                        $( '[data-page]' ).each(function ( idx, el ) {
                            var link = $( el ).data( 'page' );
                            if ( isElementInViewport( el ) ) {
                                if ( window.location.href == link ) return;
                                changeBrowserUrl( link );
                            }
                        });
                    }

                }

            }, 100);

            $(document).on("scroll", _onScroll );

            var _reset = function reset( action ) {

                loaded = false;
                paged = 1;
                search = '';

                if ( action != 'search' ) {
                    elements.searchField.val( '' );
                }

                switch( action ) {
                    case 'search':
                        search = elements.searchField.val();
                        // no break;
                    case 'reset':
                        elements.filterItems.removeClass( 'active' );
                        break;
                }

            };

            // Click on search
            elements.searchButton.on('click', function(event) {
                event.preventDefault();
                if ( loading ) return;

                if ( elements.searchField.val() !== '' ) {
                    _reset( 'search' );
                    if ( typeof wp.hooks !== 'undefined' ) wp.hooks.doAction( 'ema-search', base, elements.searchField.val() );
                    _getItems();

                }

            });

            // Click on reset
            elements.reset.on('click', function(event) {
                event.preventDefault();
                if ( loading ) return;

                _reset( 'reset' );
                if ( typeof wp.hooks !== 'undefined' ) wp.hooks.doAction( 'ema-reset', base );
                _getItems();

            });

            // Click on filter
            elements.filterItems.on('click', function(event) {

                event.preventDefault();
                if ( loading ) return;

                if ( typeof wp.hooks !== 'undefined' ) wp.hooks.doAction( 'ema-filter-selected', base );

                if ( dataJSON.multi_selectable === true ) {
                    _reset();
                    $( this ).toggleClass( 'active' );
                } else {
                    var is_active = $( this ).hasClass( 'active' );
                    _reset( 'reset' );
                    if ( ! is_active ) $( this ).addClass( 'active' );
                }

                _getItems();

            });

            // Click on pagination/readmore
            elements.pagination.on('click', '.page-numbers, .btn-readmore', function(event) {
                event.preventDefault();
                if ( loading ) return;

                paged = _getPaged( $( this ) );
                if ( typeof wp.hooks !== 'undefined' ) wp.hooks.doAction( 'ema-pagination', base );
                _getItems();

            });

            _init();

            // https://stackoverflow.com/questions/7171099/how-to-replace-url-parameter-with-javascript-jquery/27791249#27791249
            var _replaceUrlParam = function replaceUrlParam(url, paramName, paramValue){
                var pattern = new RegExp('(\\?|\\&)('+paramName+'=).*?(&|$)')
                var newUrl=url
                if(url.search(pattern)>=0){
                    newUrl = url.replace(pattern,'$1$2' + paramValue + '$3');
                }
                else{
                    newUrl = newUrl + (newUrl.indexOf('?')>0 ? '&' : '?') + paramName + '=' + paramValue
                }
                return newUrl
            };

            /**
             * This function will restore or load from URL the previous state of the filters, pagination or search.
             * Get previous state from local storage or from URL.
             */
            var _loadOnStart = function loadOnStart() {

                /*
                 * load from URL or localstorage
                 *
                 * - URL only with ID definied
                 * - URL priority and override items in localstorage
                 *
                 */

                var obj = null;

                // Do not run if do not need to.
                var _run = false;
                var tryLocalStorage = true;

                if ( dataJSON.load_from_url ) {

                    // Get multifilter from URL
                    var multifilterURL = _getUrlVars()["multifilter"];

                    // if multifilter in URL definied
                    if( multifilterURL && multifilterURL !== "null" && multifilterURL !== "undefined" ) {

                        // Get this ID
                        var thisID = $( base ).attr('id');

                        // for load from URL, ID is required
                        if( thisID && thisID !== "null" && thisID !== "undefined" ) {

                            // Get object from JSON from URL
                            var multifilterObj = JSON.parse( decodeURI( multifilterURL ) );

                            // Get params for this instance
                            var multifilterThis =  multifilterObj[ thisID ];

                            // If params for this instance exist
                            if( multifilterThis && multifilterThis !== "null" && multifilterThis !== "undefined" ) {

                                // Do not load from local storage
                                tryLocalStorage = false;
                                obj = multifilterThis;

                                /*
                                 * URL housekeeping
                                 * remove processed element from URL, so if user reload the page, do not override what already selected is.
                                 * Test cases:

                                ?multifilter={"test":{"taxonomies_terms":{"demo-category":["template"],"demo-tag":["template"]},"paged":1,"search":""}}

                                ?multifilter={"test":{"taxonomies_terms":{"demo-category":["template"],"demo-tag":["template"]},"paged":1,"search":""}}&something=else

                                ?multifilter={"test":{"taxonomies_terms":{"demo-category":["template"],"demo-tag":["template"]},"paged":1,"search":""},"testx":{"taxonomies_terms":{"demo-category":["template"],"demo-tag":["template"]},"paged":1,"search":""}}

                                 */
                                // remove this instance params from multifilter object
                                delete multifilterObj[ thisID ];
                                // create a string from object
                                var URLparam = JSON.stringify( multifilterObj );
                                // rempace multifilter with this string
                                var newURL = _replaceUrlParam( window.location.href, 'multifilter', URLparam );
                                // remove multifilter if empty
                                newURL = newURL.replace(/multifilter={}(\&?)/i, '');
                                // remove last "?" if no more URL parameter exist ("?"" is the last char)
                                if ( newURL.charAt( newURL.length - 1) == '?') newURL = newURL.substr(0, newURL.length - 1);

                                // Remove this insance from URL params
                                window.history.replaceState(null, null, newURL );

                            }

                        }

                    }

                }

                if ( dataJSON.store_session && tryLocalStorage ) {

                    // Get JSON object stored is localstorage.
                    obj = JSON.parse( localStorage.getItem( _getSelector() + i ) );

                }

                // If no previousy stored item(s) or in URL for this instance, then return.
                if( ! obj || obj === "null" || obj === "undefined" ) return;

                // Set paged
                if ( dataJSON.pagination == 'pagination' ) paged = obj.paged;

                /*
                 * Set search
                 * Search override selected filters
                 * http://stackoverflow.com/questions/6295665/checking-undefined-value-not-working/6302462#6302462
                 */
                if( obj.search !== '' && obj.search !== "undefined" && obj.search !== undefined ) {

                    search = obj.search;
                    elements.searchField.val( search );
                    _run = true;

                } else {

                    // Loop through all taxonomy filer in "this" item.
                    elements.taxonomies.each(function( index, taxnomy ) {

                        // Check if this is the current taxonomy.
                        var current_taxonomy = Object.keys( obj.taxonomies_terms )[index];
                        if ( $( taxnomy ).data( 'taxonomy' ) == current_taxonomy ) {

                            // If array is empty, return. This means, we do not have selected items.
                            if ( obj.taxonomies_terms[current_taxonomy].length < 1 ) return true;

                            // Loop trough terms in current taxonomy.
                            $( taxnomy ).find( '.exopite-multifilter-filter-item' ).each(function( index, term ) {

                                /*
                                 * If this term is stored in our previously selected term,
                                 * then mark active and active _run.
                                 */
                                if( $.inArray( $( term ).data( 'term' ) ,obj.taxonomies_terms[current_taxonomy] ) !== -1 ) {
                                    $( term ).addClass( 'active' );
                                    _run = true;
                                }
                            });

                        }
                    });

                }

                if ( typeof wp.hooks !== 'undefined' ) wp.hooks.doAction( 'ema-load-session', base );

                // If this is not the first page, and/or any filter is seleted, this get items.
                if ( paged > 1 || _run ) _getItems();
            };

            if ( typeof dataJSON !== "undefined" ) {
                if ( dataJSON.store_session || dataJSON.load_from_url ) _loadOnStart();
            }


        });

    };

    $(function() {

        $( '.exopite-multifilter-container' ).not('ajax-disabled').exopiteMultifilter();

    });

})( jQuery, window, document );
/*
 * ToDo:
 * - make animation in after AJAX -> .on
 *  try turn off if masonry
 */
