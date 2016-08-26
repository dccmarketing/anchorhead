<?php

/**
 * Provides the markup for a select field
 *
 * @package    Anchorhead
 */
$defaults['aria'] 			= __( '', 'anchorhead' );
$defaults['blank'] 			= __( '- Select -', 'anchorhead' );
$defaults['class'] 			= 'widefat';
$defaults['description'] 	= __( '', 'anchorhead' );
$defaults['id'] 			= '';
$defaults['label'] 			= __( '', 'anchorhead' );
$defaults['name'] 			= '';
$defaults['selections'] 	= array();
$defaults['value'] 			= '';
$atts 						= wp_parse_args( $atts, $defaults );

if ( ! empty( $atts['label'] ) ) :

	?><label for="<?php echo esc_attr( $atts['id'] ); ?>"><?php echo wp_kses( $atts['label'], array( 'code' => array() ) ); ?>: </label><?php

endif;

?><select
	aria-label="<?php echo wp_kses( $atts['aria'], array( 'code' => array() ) ); ?>"
	class="<?php echo esc_attr( $atts['class'] ); ?>"
	id="<?php echo esc_attr( $atts['id'] ); ?>"
	name="<?php echo esc_attr( $atts['name'] ); ?>"><?php

if ( ! empty( $atts['blank'] ) ) {

	?><option value><?php echo wp_kses( $atts['blank'], array( 'code' => array() ) ); ?></option><?php

}

if ( ! empty( $atts['selections'] ) ) {

	foreach ( $atts['selections'] as $selection ) {

		$label = ( is_array( $selection ) ? $selection['label'] : $selection );
		$value = ( is_array( $selection ) ? $selection['value'] : sanitize_title( $selection ) );

		?><option
			value="<?php echo esc_attr( $value ); ?>" <?php
			selected( $atts['value'], $value ); ?>><?php

			echo wp_kses( $label, array( 'code' => array() ) );

		?></option><?php

	} // foreach

}

?></select>
<span class="description"><?php echo wp_kses( $atts['description'], array( 'code' => array() ) ); ?></span>
