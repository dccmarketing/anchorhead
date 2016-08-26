<?php
/**
 * The markup for the Showanchors metabox.
 *
 * @package 		Anchorhead
 */

wp_nonce_field( ANCHORHEAD_SLUG, 'nonce_anchorhead_showanchors' );

$atts['description'] 	= __( 'Show anchor links on this page.', 'anchorhead' );
$atts['id'] 			= 'show-anchors';
$atts['name'] 			= 'show-anchors';
$atts['value'] 			= 1;

/**
 * This check is different from other fields. Only change the value for a checkbox
 * if the key exists in the meta array. Otherwise, leave it at the default value.
 * This handles a checked or unchecked default value.
 *
 * Checking for a not empty meta value while having a checked default value never
 * saves the unchecked state.
 */
if ( array_key_exists( $atts['id'], $this->meta ) ) {

	$atts['value'] = $this->meta[$atts['id']][0];

}

$atts = apply_filters( 'anchorhead-field-' . $atts['id'], $atts );

?><p><?php

include( plugin_dir_path( dirname( __FILE__ ) ) . 'fields/checkbox.php' );
unset( $atts );

?></p><?php
