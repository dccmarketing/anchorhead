/**
 * customizer.js
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

	var api = wp.customize;

	// Table of Contents Title.
	api( 'toc_title', function( value ) {
		value.bind( function( to ) {
			$( '.toc-title' ).text( to );
		} );
	} );

	// Table of Content float position.
	api( 'float_picker', function( value ) {
		value.bind( function( to ) {
			$( '.ah-menu' ).attr( 'data-float', to );
		} );
	} );

} )( jQuery );
