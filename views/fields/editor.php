<?php

/**
 * Provides the markup for any WP Editor field
 *
 * @package 	Anchorhead
 * @see 		https://developer.wordpress.org/reference/functions/wp_editor/
 */
$defaults['description'] 	= esc_html__( '', 'anchorhead' );
$defaults['id'] 			= '';
$defaults['label'] 			= esc_html__( '', 'anchorhead' );
$defaults['settings'] 		= array();
$defaults['value'] 			= '';
$atts 						= wp_parse_args( $atts, $defaults );

if ( ! empty( $atts['label'] ) ) :

	?><label for="<?php echo esc_attr( $atts['id'] ); ?>"><?php echo wp_kses( $atts['label'], array( 'code' => array() ) ); ?>: </label><?php

endif;

wp_editor( $atts['value'], $atts['id'], $atts['settings'] );

?><span class="description"><?php echo wp_kses( $atts['description'], array( 'code' => array() ) ); ?></span>
