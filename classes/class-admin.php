<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://slushman.com
 * @since      1.0.0
 *
 * @package    Anchorhead
 * @subpackage Anchorhead/classes
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Anchorhead
 * @subpackage Anchorhead/classes
 * @author     Slushman <chris@slushman.com>
 */
class Anchorhead_Admin {

	/**
	 * The plugin options.
	 *
	 * @since 		1.0.0
	 * @access 		private
	 * @var 		string 			$options 		The plugin options.
	 */
	private $options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->set_options();

	} // __construct()

	/**
	 * Adds a settings page link to a menu
	 *
	 * @link 		https://codex.wordpress.org/classesistration_Menus
	 * @since 		1.0.0
	 * @return 		array 			Array of menu hooks
	 */
	public static function add_menu() {

		$menu_hooks = array();

		// Top-level page
		// add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );

		// Submenu Page
		// add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function);

		$menu_hooks[] = add_submenu_page(
			'options-general.php',
			apply_filters( ANCHORHEAD_SLUG . '-settings-page-title', esc_html__( 'Anchorhead Settings', 'anchorhead' ) ),
			apply_filters( ANCHORHEAD_SLUG . '-settings-menu-title', esc_html__( 'Anchorhead', 'anchorhead' ) ),
			'manage_options',
			ANCHORHEAD_SLUG . '-settings',
			array( $this, 'page_options' )
		);

		//echo '<pre>'; print_r( $menu_hooks ); echo '</pre>';

		return $menu_hooks;

	} // add_menu()

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( ANCHORHEAD_SLUG, plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/anchorhead-admin.css', array(), ANCHORHEAD_VERSION, 'all' );

	} // enqueue_styles()

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( ANCHORHEAD_SLUG, plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/anchorhead-admin.min.js', array( 'jquery' ), ANCHORHEAD_VERSION, true );

	} // enqueue_scripts()

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts_customizer() {

		//wp_enqueue_script( ANCHORHEAD_SLUG, plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/anchorhead-admin.js', array( 'jquery' ), ANCHORHEAD_VERSION, false );

	} // enqueue_scripts_customizer()

	/**
	 * Creates a checkbox field
	 *
	 * @param 	array 		$args 			The arguments for the field
	 *
	 * @return 	string 						The HTML field
	 */
	public function field_checkbox( $args ) {

		$defaults['class'] 			= '';
		$defaults['description'] 	= '';
		$defaults['label'] 			= '';
		$defaults['name'] 			= ANCHORHEAD_SLUG . '-options[' . $args['id'] . ']';
		$defaults['value'] 			= 0;

		/**
		 * anchorhead-field-checkbox-options-defaults filter
		 *
		 * @param 	array 	$defaults 		The default settings for the field
		 */
		$defaults 	= apply_filters( ANCHORHEAD_SLUG . '-field-checkbox-options-defaults', $defaults );
		$atts 		= wp_parse_args( $args, $defaults );

		if ( ! empty( $this->options[$atts['id']] ) ) {

			$atts['value'] = $this->options[$atts['id']];

		}

		include( plugin_dir_path( dirname( __FILE__ ) ) . 'views/fields/checkbox.php' );

	} // field_checkbox()

	/**
	 * Creates a text field
	 *
	 * @param 	array 		$args 			The arguments for the field
	 *
	 * @return 	string 						The HTML field
	 */
	public function field_editor( $args ) {

		$defaults['description'] 	= '';
		$defaults['settings'] 		= array();
		$defaults['value']			= '';

		/**
		 * plugin-name-field-text-options-defaults filter
		 *
		 * @param 	array 	$defaults 		The default settings for the field
		 */
		$defaults 	= apply_filters( PLUGIN_NAME_SLUG . '-field-editor-options-defaults', $defaults );
		$atts 		= wp_parse_args( $args, $defaults );

		if ( ! empty( $this->options[$atts['id']] ) ) {

			$atts['value'] = $this->options[$atts['id']];

		}

		include( plugin_dir_path( dirname( __FILE__ ) ) . 'views/fields/editor.php' );

	} // field_editor()

	/**
	 * Creates a set of radios field
	 *
	 * @param 	array 		$args 			The arguments for the field
	 *
	 * @return 	string 						The HTML field
	 */
	public function field_radios( $args ) {

		$defaults['class'] 			= '';
		$defaults['description'] 	= '';
		$defaults['label'] 			= '';
		$defaults['name'] 			= ANCHORHEAD_SLUG . '-options[' . $args['id'] . ']';
		$defaults['value'] 			= 0;

		/**
		 * anchorhead-field-radios-options-defaults filter
		 *
		 * @param 	array 	$defaults 		The default settings for the field
		 */
		$defaults 	= apply_filters( ANCHORHEAD_SLUG . '-field-radios-options-defaults', $defaults );
		$atts 		= wp_parse_args( $args, $defaults );

		if ( ! empty( $this->options[$atts['id']] ) ) {

			$atts['value'] = $this->options[$atts['id']];

		}

		include( plugin_dir_path( dirname( __FILE__ ) ) . 'views/fields/radios.php' );

	} // field_radios()

	/**
	 * Creates a select field
	 *
	 * Note: label is blank since its created in the Settings API
	 *
	 * @param 	array 		$args 			The arguments for the field
	 *
	 * @return 	string 						The HTML field
	 */
	public function field_select( $args ) {

		$defaults['aria'] 			= '';
		$defaults['blank'] 			= '';
		$defaults['class'] 			= '';
		$defaults['context'] 		= '';
		$defaults['description'] 	= '';
		$defaults['label'] 			= '';
		$defaults['name'] 			= ANCHORHEAD_SLUG . '-options[' . $args['id'] . ']';
		$defaults['selections'] 	= array();
		$defaults['value'] 			= '';

		/**
		 * anchorhead-field-select-options-defaults filter
		 *
		 * @param 	array 	$defaults 		The default settings for the field
		 */
		$defaults 	= apply_filters( ANCHORHEAD_SLUG . '-field-select-options-defaults', $defaults );
		$atts 		= wp_parse_args( $args, $defaults );

		if ( ! empty( $this->options[$atts['id']] ) ) {

			$atts['value'] = $this->options[$atts['id']];

		}

		if ( empty( $atts['aria'] ) && ! empty( $atts['description'] ) ) {

			$atts['aria'] = $atts['description'];

		} elseif ( empty( $atts['aria'] ) && ! empty( $atts['label'] ) ) {

			$atts['aria'] = $atts['label'];

		}

		include( plugin_dir_path( dirname( __FILE__ ) ) . 'views/fields/select.php' );

	} // field_select()

	/**
	 * Creates a text field
	 *
	 * @param 	array 		$args 			The arguments for the field
	 *
	 * @return 	string 						The HTML field
	 */
	public function field_text( $args ) {

		$defaults['class'] 			= 'text widefat';
		$defaults['description'] 	= '';
		$defaults['label'] 			= '';
		$defaults['name'] 			= ANCHORHEAD_SLUG . '-options[' . $args['id'] . ']';
		$defaults['placeholder'] 	= '';
		$defaults['type'] 			= 'text';
		$defaults['value'] 			= '';

		/**
		 * anchorhead-field-text-options-defaults filter
		 *
		 * @param 	array 	$defaults 		The default settings for the field
		 */
		$defaults 	= apply_filters( ANCHORHEAD_SLUG . '-field-text-options-defaults', $defaults );
		$atts 		= wp_parse_args( $args, $defaults );

		if ( ! empty( $this->options[$atts['id']] ) ) {

			$atts['value'] = $this->options[$atts['id']];

		}

		include( plugin_dir_path( dirname( __FILE__ ) ) . 'views/fields/text.php' );

	} // field_text()

	/**
	 * Creates a textarea field
	 *
	 * @param 	array 		$args 			The arguments for the field
	 *
	 * @return 	string 						The HTML field
	 */
	public function field_textarea( $args ) {

		$defaults['class'] 			= 'large-text';
		$defaults['cols'] 			= 50;
		$defaults['context'] 		= '';
		$defaults['description'] 	= '';
		$defaults['label'] 			= '';
		$defaults['name'] 			= ANCHORHEAD_SLUG . '-options[' . $args['id'] . ']';
		$defaults['rows'] 			= 10;
		$defaults['value'] 			= '';

		/**
		 * anchorhead-field-textarea-options-defaults filter
		 *
		 * @param 	array 	$defaults 		The default settings for the field
		 */
		$defaults 	= apply_filters( ANCHORHEAD_SLUG . '-field-textarea-options-defaults', $defaults );
		$atts 		= wp_parse_args( $args, $defaults );

		if ( ! empty( $this->options[$atts['id']] ) ) {

			$atts['value'] = $this->options[$atts['id']];

		}

		include( plugin_dir_path( dirname( __FILE__ ) ) . 'views/fields/textarea.php' );

	} // field_textarea()

	/**
	 * Returns an array of options names, fields types, and default values
	 *
	 * Each item consists of the following:
	 * 		option name, field type, default value (used during plugin activation)
	 *
	 * @return 		array 			An array of options
	 */
	public static function get_options_list() {

		$options = array();

		$options[] = array( 'top-link-threshhold', 'number', '25' );
		$options[] = array( 'block-elements-threshhold', 'number', '500' );
		$options[] = array( 'scroll-speed', 'number', '650' );
		$options[] = array( 'scroll-type', 'select', 'easeInOutQuad' );

		return $options;

	} // get_options_list()

	/**
	 * Adds a link to the plugin settings page
	 *
	 * @since 		1.0.0
	 *
	 * @param 		array 		$links 		The current array of links
	 * @return 		array 					The modified array of links
	 */
	public function link_settings( $links ) {

		$links[] = sprintf( '<a href="%s">%s</a>', admin_url( 'options-general.php?page=anchorhead-settings' ), esc_html__( 'Settings', 'anchorhead' ) );

		return $links;

	} // link_settings()

	/**
	 * Adds links to the plugin links row
	 *
	 * @since 		1.0.0
	 *
	 * @param 		array 		$links 		The current array of row links
	 * @param 		string 		$file 		The name of the file
	 *
	 * @return 		array 					The modified array of row links
	 */
	public function link_row_meta( $links, $file ) {

		if ( $file == ANCHORHEAD_FILE ) {

			$links[] = '<a href="http://twitter.com/slushman">Twitter</a>';

		}

		return $links;

	} // link_row_meta()

	/**
	 * Includes the options page view
	 *
	 * @since 		1.0.0
	 *
	 * @return 		void
	 */
	public function page_options() {

		include( plugin_dir_path( dirname( __FILE__ ) ) . 'views/pages/settings.php' );

	} // page_options()

	/**
	 * Registers settings fields with WordPress
	 */
	public function register_fields() {

		// add_settings_field( $id, $title, $callback, $menu_slug, $section, $args );

		add_settings_field(
			'scroll-speed',
			apply_filters( ANCHORHEAD_SLUG . '-label-scroll-speed', esc_html__( 'Scroll Speed', 'anchorhead' ) ),
			array( $this, 'field_text' ),
			ANCHORHEAD_SLUG,
			ANCHORHEAD_SLUG . '-smooth-scroll',
			array(
				'class' 		=> 'text',
				'description' 	=> __( 'How fast (in milliseconds) the smooth scrolling should go to the anchor links. Zero will jump immediately.', 'anchorhead' ),
				'id' 			=> 'scroll-speed',
				'type' 			=> 'number',
				'value' 		=> '650',
			)
		);

		add_settings_field(
			'scroll-type',
			apply_filters( ANCHORHEAD_SLUG . '-label-scroll-type', esc_html__( 'Scroll Type', 'anchorhead' ) ),
			array( $this, 'field_select' ),
			ANCHORHEAD_SLUG,
			ANCHORHEAD_SLUG . '-smooth-scroll',
			array(
				'description' 	=> __( 'Learn about the different patterns and what they do at easings.net.', 'anchorhead' ),
				'id' 			=> 'scroll-type',
				'selections'	=> array(
					array( 'label' => esc_html__( 'Linear', 'anchorhead' ), 		'value' => 'Linear' ),
					array( 'label' => esc_html__( 'easeInQuad', 'anchorhead' ), 	'value' => 'easeInQuad' ),
					array( 'label' => esc_html__( 'easeInCubic', 'anchorhead' ), 	'value' => 'easeInCubic' ),
					array( 'label' => esc_html__( 'easeInQuart', 'anchorhead' ), 	'value' => 'easeInQuart' ),
					array( 'label' => esc_html__( 'easeInQuint', 'anchorhead' ), 	'value' => 'easeInQuint' ),
					array( 'label' => esc_html__( 'easeInOutQuad', 'anchorhead' ), 	'value' => 'easeInOutQuad' ),
					array( 'label' => esc_html__( 'easeInOutCubic', 'anchorhead' ), 'value' => 'easeInOutCubic' ),
					array( 'label' => esc_html__( 'easeInOutQuart', 'anchorhead' ), 'value' => 'easeInOutQuart' ),
					array( 'label' => esc_html__( 'easeInOutQuint', 'anchorhead' ), 'value' => 'easeInOutQuint' ),
					array( 'label' => esc_html__( 'easeOutQuad', 'anchorhead' ), 	'value' => 'easeOutQuad' ),
					array( 'label' => esc_html__( 'easeOutCubic', 'anchorhead' ), 	'value' => 'easeOutCubic' ),
					array( 'label' => esc_html__( 'easeOutQuart', 'anchorhead' ), 	'value' => 'easeOutQuart' ),
					array( 'label' => esc_html__( 'easeOutQuint', 'anchorhead' ), 	'value' => 'easeOutQuint' ),
				),
				'value' 		=> 'easeInOutQuad'
			)
		);

		add_settings_field(
			'top-link-threshhold',
			apply_filters( ANCHORHEAD_SLUG . '-label-top-link-threshhold', esc_html__( 'Top Link Threshhold', 'anchorhead' ) ),
			array( $this, 'field_text' ),
			ANCHORHEAD_SLUG,
			ANCHORHEAD_SLUG . '-display',
			array(
				'class' 		=> '',
				'description' 	=> __( 'How many characters from the beginning of the content will the plugin begin adding the "back to top" links to H2 headings. No value will add the links to all H2 headings.', 'anchorhead' ),
				'id' 			=> 'top-link-threshhold',
				'type' 			=> 'number',
				'value' 		=> '25',
			)
		);

		add_settings_field(
			'block-elements-threshhold',
			apply_filters( ANCHORHEAD_SLUG . '-label-block-elements-threshhold', esc_html__( 'Block Elements Threshhold', 'anchorhead' ) ),
			array( $this, 'field_text' ),
			ANCHORHEAD_SLUG,
			ANCHORHEAD_SLUG . '-display',
			array(
				'class' 		=> '',
				'description' 	=> __( 'How many characters from the beginning of the content will the plugin compensate for list items ,heading, and other block elements (items that require the entire width of the content area). This helps prevent issues with top-links appearing too close to the top of the page.', 'anchorhead' ),
				'id' 			=> 'block-elements-threshhold',
				'type' 			=> 'number',
				'value' 		=> '500',
			)
		);

	} // register_fields()

	/**
	 * Registers settings sections with WordPress
	 */
	public function register_sections() {

		// add_settings_section( $id, $title, $callback, $menu_slug );

		add_settings_section(
			ANCHORHEAD_SLUG . '-display',
			apply_filters( ANCHORHEAD_SLUG . '-section-display-title', esc_html__( 'Display Settings', 'anchorhead' ) ),
			array( $this, 'section_display' ),
			ANCHORHEAD_SLUG
		);

		add_settings_section(
			ANCHORHEAD_SLUG . '-smooth-scroll',
			apply_filters( ANCHORHEAD_SLUG . '-section-smooth-scroll-title', esc_html__( 'Smooth Scroll Settings', 'anchorhead' ) ),
			array( $this, 'section_smooth_scroll' ),
			ANCHORHEAD_SLUG
		);

	} // register_sections()

	/**
	 * Registers plugin settings
	 *
	 * @since 		1.0.0
	 */
	public function register_settings() {

		// register_setting( $option_group, $option_name, $sanitize_callback );

		register_setting(
			ANCHORHEAD_SLUG . '-options',
			ANCHORHEAD_SLUG . '-options',
			array( $this, 'validate_options' )
		);

	} // register_settings()

	/**
	 * Displays a settings section
	 *
	 * @since 		1.0.0
	 *
	 * @param 		array 		$params 		Array of parameters for the section
	 *
	 * @return 		mixed 						The settings section
	 */
	public function section_display( $params ) {

		$message = __( 'Settings related to the plugin output.', 'anchorhead' );

		include( plugin_dir_path( dirname( __FILE__ ) ) . 'views/sections/display.php' );

	} // section_display()

	/**
	 * Displays a settings section
	 *
	 * @since 		1.0.0
	 *
	 * @param 		array 		$params 		Array of parameters for the section
	 *
	 * @return 		mixed 						The settings section
	 */
	public function section_smooth_scroll( $params ) {

		$message = __( 'Settings related to smooth scrolling feature.', 'anchorhead' );

		include( plugin_dir_path( dirname( __FILE__ ) ) . 'views/sections/scroll.php' );

	} // section_smooth_scroll()

	/**
	 * Sets the class variable $options
	 */
	private function set_options() {

		$this->options = get_option( ANCHORHEAD_SLUG . '-options' );

	} // set_options()

	/**
	 * Validates saved options
	 *
	 * @since 		1.0.0
	 *
	 * @param 		array 		$input 			array of submitted plugin options
	 *
	 * @return 		array 						array of validated plugin options
	 */
	public function validate_options( $input ) {

		$valid 		= array();
		$options 	= $this->get_options_list();

		foreach ( $options as $option ) {

			$sanitizer 			= new Anchorhead_Sanitize();
			$valid[$option[0]] 	= $sanitizer->clean( $input[$option[0]], $option[1] );

			unset( $sanitizer );

		}

		return $valid;

	} // validate_options()

} // class
