/**
 * Exopite Multifilter: starts the slick carousel for the "carousel" style.
 *
 * Was part of the bundled slick.js (1.8.1) before. The settings come from the
 * data-carousel attribute of .exopite-multifilter-container.
 */
( function( $ ) {
	'use strict';

	/**
	 * Positive integer from a setting, or the fallback.
	 * slidesToScroll / slidesToShow 0 or NaN made slick loop endlessly (getNavigableIndexes).
	 *
	 * @param {*}      value    Setting value.
	 * @param {number} fallback Fallback value.
	 * @return {number} Integer >= 1.
	 */
	function toPositiveInt( value, fallback ) {
		const number = parseInt( value, 10 );
		return ( isNaN( number ) || number < 1 ) ? fallback : number;
	}

	/**
	 * "false" (string) is false, everything else is true.
	 *
	 * @param {*} value Setting value.
	 * @return {boolean} The flag.
	 */
	function toFlag( value ) {
		return value !== 'false' && value !== false;
	}

	$( function() {

		$( '.exopite-multifilter-items.slick-carousel' ).each( function( idx ) {

			const data = $( this ).parent( '.exopite-multifilter-container' ).data( 'carousel' );

			if ( typeof data === 'undefined' ) {
				return;
			}

			this.id = 'slick-carousel' + idx;

			$( this ).slick( {
				autoplay: toFlag( data.autoplay ),
				arrows: toFlag( data.arrows ),
				autoplaySpeed: toPositiveInt( data.autoplay_speed, 3000 ),
				infinite: toFlag( data.infinite ),
				speed: toPositiveInt( data.speed, 300 ),
				pauseOnHover: toFlag( data.pause_on_hover ),
				dots: toFlag( data.dots ),
				adaptiveHeight: toFlag( data.adaptive_height ),
				mobileFirst: toFlag( data.mobile_first ),
				slidesPerRow: toPositiveInt( data.slides_per_row, 1 ),
				slidesToShow: toPositiveInt( data.slides_to_show, 1 ),
				slidesToScroll: toPositiveInt( data.slides_to_scroll, 1 ),
				useTransform: toFlag( data.use_transform ),
			} );

		} );

	} );

}( jQuery ) );
