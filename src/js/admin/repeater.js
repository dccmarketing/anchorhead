/**
 * Repeaters
 */
(function( $ ) {
	'use strict';

	var addbtn = document.querySelector( '#add-repeater' );
	var repwrap = document.querySelector( '.repeaters' );
	var repeaters = repwrap.querySelectorAll( '.repeater:not(.hidden)' );
	var orig = repwrap.querySelector( '.repeater.hidden' );
	var len = repeaters.length;

	/**
	 * Returns the selected parent element.
	 *
	 * @param 		object 		el 				The selected element.
	 * @param 		string 		stopHere 		The parent's class to find.
	 * @return 		object 						The selected parent element.
	 */
	function findParent( el, stopHere ) {

		var parent = '';

		parent = el.parentNode;

		if ( ! parent.classList ){ findParent( parent, stopHere ); }

		if ( ! parent.classList.contains( stopHere ) ) { return parent; }

		return false;

	} // findParent()

	/**
	 * Removes a repeated field.
	 *
	 * @param 		object 		event 			The event object.
	 * @param 		object 		element 		The selected element.
	 * @param 		object 		parent 			The parent element.
	 * @return 		bool 						Returns false.
	 */
	function removeRepeaterField( event, element, parent ) {

		console.log( element );

		event.preventDefault();

		console.log( parent ); // why does this come back as a null when its found in the function above?

		if ( ! parent.classList.contains( 'first' ) ) {

			repwrap.removeChild( parent );

		}

		return false;

	} // removeRepeaterField()

	/**
	 * Clones the hidden field to add another repeater.
	 */
	addbtn.addEventListener( 'click', function(e){
		e.preventDefault();

		var clone = orig.cloneNode( true );

		clone.classList.remove( 'hidden' );
		repwrap.insertBefore( clone );

		return false;

	});

	for( var i = 0; i < len; i++ ) {

		var parent = repeaters[i];
		var remover = parent.querySelector( '.link-remove' );
		var hidebtn = parent.querySelector( '.btn-edit' );
		var content = parent.querySelector( '.repeater-content' );
		var title = parent.querySelector( '.title-repeater' );
		var field = parent.querySelector( '[data-title]' );
		var fieldval = field.value;

		console.log( hidebtn );

		remover.addEventListener( 'click', function( event ){
			removeRepeaterField( event, this, parent )
		});

		hidebtn.addEventListener( 'click', function( event ){
			parent.classList.toggle( 'closed' );
			$(content).slideToggle( '150' );
		});

		console.log( fieldval );

		field.addEventListener( 'keyup', function(){
			title.createTextNode( fieldval );
		});

		if ( fieldval.length > 0 ) {

			title.textContent = fieldval;

		}
		// } else {
		//
		// 	repeater.find( '.title-repeater' ).text( nhdata.repeatertitle );
		//
		// }



		// field.addEventListener( 'keyup', function( e ){
		// 	title.text( this.value );
		// });

	}

	/**
	 * Changes the title of the repeater header as you type
	 */
	// $(function(){
	//
	// 	$( '.repeater-title' ).on( 'keyup', function(){
	//
	// 		var repeater = $(this).parents( '.repeater' );
	// 		var fieldval = $(this).val();
	//
	// 		if ( fieldval.length > 0 ) {
	//
	// 			repeater.find( '.title-repeater' ).text( fieldval );
	//
	// 		} else {
	//
	// 			repeater.find( '.title-repeater' ).text( nhdata.repeatertitle );
	//
	// 		}
	//
	// 	});
	//
	// });

	/**
	 * Makes the repeaters sortable.
	 */
	$(function() {
		$(repwrap).sortable({
			cursor: 'move',
			handle: '.handle',
			items: '.repeater',
			opacity: 0.6,
		});
	});

})( jQuery );
