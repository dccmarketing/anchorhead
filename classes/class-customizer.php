<?php
/**
 * Anchorhead Customizer
 *
 * Contains methods for customizing the theme customization screen.
 *
 * @link 		https://codex.wordpress.org/Theme_Customization_API
 * @since 		1.0.0
 * @package  	Anchorhead
 */
class Anchorhead_Customizer {

	/**
	 * Constructor
	 */
	public function __construct() {}

	/**
	 * Registers custom controls with the Customizer.
	 *
	 * @param 		WP_Customize_Manager 		$wp_customize 		Theme Customizer object.
	 */
	public function register_controls( $wp_customize ) {

		// Register custom controls here

	} // register_controls()

	/**
	 * Registers custom panels for the Customizer
	 *
	 * @see			add_action( 'customize_register', $func )
	 * @link 		http://ottopress.com/2012/how-to-leverage-the-theme-customizer-in-your-own-themes/
	 * @since 		1.0.0
	 *
	 * @param 		WP_Customize_Manager 		$wp_customize 		Theme Customizer object.
	 */
	public function register_panels( $wp_customize ) {

		//

	} // register_panels()

	/**
	 * Registers custom sections for the Customizer
	 *
	 * Existing sections:
	 *
	 * Slug 				Priority 		Title
	 *
	 * title_tagline 		20 				Site Identity
	 * colors 				40				Colors
	 * header_image 		60				Header Image
	 * background_image 	80				Background Image
	 * nav 					100 			Navigation
	 * widgets 				110 			Widgets
	 * static_front_page 	120 			Static Front Page
	 * default 				160 			all others
	 *
	 * @see			add_action( 'customize_register', $func )
	 * @link 		http://ottopress.com/2012/how-to-leverage-the-theme-customizer-in-your-own-themes/
	 * @since 		1.0.0
	 *
	 * @param 		WP_Customize_Manager 		$wp_customize 		Theme Customizer object.
	 */
	public function register_sections( $wp_customize ) {

		// Anchorhead Section
		$wp_customize->add_section( 'anchorhead',
			array(
				'active_callback' 	=> '',
				'capability'  		=> 'edit_theme_options',
				'description'  		=> esc_html__( 'Options for the Anchorhead plugin', 'anchorhead' ),
				'panel' 			=> '',
				'priority'  		=> 10,
				'theme_supports'  	=> '',
				'title'  			=> esc_html__( 'Anchorhead', 'anchorhead' ),
			)
		);

	} // register_sections()

	/**
	 * Registers controls/fields for the Customizer
	 *
	 * Note: To enable instant preview, we have to actually write a bit of custom
	 * javascript. See live_preview() for more.
	 *
	 * Note: To use active_callbacks, don't add these to the selecting control, it appears these conflict:
	 * 		'transport' => 'postMessage'
	 * 		$wp_customize->get_setting( 'field_name' )->transport = 'postMessage';
	 *
	 * @see			add_action( 'customize_register', $func )
	 * @link 		http://ottopress.com/2012/how-to-leverage-the-theme-customizer-in-your-own-themes/
	 * @since 		1.0.0
	 *
	 * @param 		WP_Customize_Manager 		$wp_customize 		Theme Customizer object.
	 */
	public function register_fields( $wp_customize ) {

		// Float Picker Field
		$wp_customize->add_setting(
			'float_picker',
			array(
				'capability' 		=> 'edit_theme_options',
				'default'  			=> 'none',
				'transport' 		=> 'postMessage',
				'type' 				=> 'theme_mod'
			)
		);
		$wp_customize->add_control(
			'float_picker',
			array(
				'active_callback' 	=> '',
				'choices' 			=> array(
					'none' 			=> esc_html__( 'No Float', 'anchorhead' ),
					'left' 			=> esc_html__( 'Float Left', 'anchorhead' ),
					'right' 		=> esc_html__( 'Float Right', 'anchorhead' ),
				),
				'description' 		=> esc_html__( '', 'anchorhead' ),
				'label'  			=> esc_html__( 'Float Picker', 'anchorhead' ),
				'priority' 			=> 10,
				'section'  			=> 'anchorhead',
				'settings' 			=> 'float_picker',
				'type' 				=> 'radio'
			)
		);
		$wp_customize->get_setting( 'float_picker' )->transport = 'postMessage';

		// Table of Contents Title Field
		$wp_customize->add_setting(
			'toc_title',
			array(
				'capability' 		=> 'edit_theme_options',
				'default'  			=> '',
				'sanitize_callback' => 'sanitize_text_field',
				'transport' 		=> 'postMessage',
				'type' 				=> 'theme_mod'
			)
		);
		$wp_customize->add_control(
			'toc_title',
			array(
				'active_callback' 	=> '',
				'description' 		=> esc_html__( '', 'anchorhead' ),
				'label'  			=> esc_html__( 'Table of Contents Title', 'anchorhead' ),
				'priority' 			=> 10,
				'section'  			=> 'anchorhead',
				'settings' 			=> 'toc_title',
				'type' 				=> 'text'
			)
		);
		$wp_customize->get_setting( 'toc_title' )->transport = 'postMessage';

	} // register_fields()

	/**
	 * Enqueue scripts for the Customizer.
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( ANCHORHEAD_SLUG . '-customizer', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/anchorhead-customizer.min.js', array( 'jquery', 'customize-preview' ), ANCHORHEAD_VERSION, true );

	} // enqueue_scripts()

	/**
	 * This will generate a line of CSS for use in header output. If the setting
	 * ($mod_name) has no defined value, the CSS will not be output.
	 *
	 * @access 		public
	 * @since 		1.0.0
	 *
	 * @param 		string 		$selector 		CSS selector
	 * @param 		string 		$style 			The name of the CSS *property* to modify
	 * @param 		string 		$mod_name 		The name of the 'theme_mod' option to fetch
	 * @param 		string 		$prefix 		Optional. Anything that needs to be output before the CSS property
	 * @param 		string 		$postfix 		Optional. Anything that needs to be output after the CSS property
	 * @param 		bool 		$echo 			Optional. Whether to print directly to the page (default: true).
	 *
	 * @return 		string 						Returns a single line of CSS with selectors and a property.
	 */
	public function generate_css( $selector, $style, $mod_name, $prefix = '', $postfix = '', $echo = true ) {

		$return = '';
		$mod 	= get_theme_mod( $mod_name );

		if ( ! empty( $mod ) ) {

			$return = sprintf('%s { %s:%s; }',
				$selector,
				$style,
				$prefix . $mod . $postfix
			);

			if ( $echo ) {

				echo $return;

			}

		}

		return $return;

	} // generate_css()

	/**
	 * This will output the custom WordPress settings to the live theme's WP head.
	 *
	 * Used by hook: 'wp_head'
	 *
	 * @access 		public
	 * @see 		add_action( 'wp_head', $func )
	 * @since 		1.0.0
	 */
	public function header_output() {

		?><!-- Anchorhead CSS -->
		<style type="text/css"><?php

			// pattern:
			// $this->generate_css( 'selector', 'style', 'mod_name', 'prefix', 'postfix', true );
			//
			// background-image example:
			// $this->generate_css( '.class', 'background-image', 'background_image_example', 'url(', ')' );


		?></style><!-- Anchorhead CSS --><?php

	} // header_output()

	/**
	 * Adds a link on the plugin settings page to the Customizer section.
	 *
	 * @since 		1.0.0
	 *
	 * @param 		array 		$links 		The current array of links
	 * @return 		array 					The modified array of links
	 */
	public function link_to_customizer( $links ) {

		$query['autofocus[section]'] 	= 'anchorhead';
 		$panel_link 					= add_query_arg( $query, admin_url( 'customize.php' ) );

		$links[] = sprintf( '<a href="%s">%s</a>', $panel_link, esc_html__( 'Settings', 'anchorhead' ) );

		return $links;

	} // link_to_customizer()

	/**
	 * Loads files for Custom Controls.
	 */
	public function load_customize_controls() {

		$files = array();

		//$files[] = 'float-picker.php';

		if ( empty( $files ) ) { return; }

		foreach ( $files as $file ) {

			include( plugin_dir_path( dirname( __FILE__ ) ) . 'views/controls/' . $file );

		}

	} // load_customize_controls()

} // class
