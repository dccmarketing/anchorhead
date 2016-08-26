<?php

/**
 * Provides the markup for a set of radios
 *
 * $atts:
 *	class 			Class applied to the label wrapping each radio input.
 *	description 	Description of the set for the legend tag.
 * 	name 			The name of the field.
 * 	selections 		The individual items to create radios.
 * 		label 		The text label of this item.
 * 		value 		The value of this item.
 *  value 			The saved value.
 *  wrap-tag 		Optional tag name to wrap each radio in, ie: 'p', 'span', 'li', etc.
 *
 * @package    Anchorhead
 */
$defaults['class'] 			= '';
$defaults['description'] 	= __( '', 'anchorhead' );
$defaults['name'] 			= '';
$defaults['selections'] 	= array();
$defaults['value'] 			= '';
$defaults['wrap-tag'] 		= '';
$atts 						= wp_parse_args( $atts, $defaults );

?><fieldset class="wrap-radios" role="radiogroup" >
	<legend class="description"><?php echo wp_kses( $atts['description'], array( 'code' => array() ) ); ?></legend><?php

	foreach ( $atts['selections'] as $selection ) {

		$label = ( is_array( $selection ) ? $selection['label'] : $selection );
		$value = ( is_array( $selection ) ? $selection['value'] : sanitize_title( $selection ) );

		if ( ! empty( $atts['wrap-tag'] ) ) {

			echo '<' . esc_attr( $atts['wrap-tag'] ) . '>';

		}



		?><label class="<?php echo esc_attr( $atts['class'] ); ?>" for="<?php echo esc_attr( $value ); ?>">
			<input <?php

				checked( $value, $atts['value'], true );

				?>id="<?php echo esc_attr( $value ); ?>"
				name="<?php echo esc_attr( $atts['name'] ); ?>"
				type="radio"
				value="<?php echo esc_attr( $value ); ?>" />
			<span class=""><?php

				echo wp_kses( $label, array( 'code' => array() ) );

			?></span>
		</label><?php

		if ( ! empty( $atts['wrap-tag'] ) ) {

			echo '</' . esc_attr( $atts['wrap-tag'] ) . '>';

		}

	} // foreach

?></fieldset>
