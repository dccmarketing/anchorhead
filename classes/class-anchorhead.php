<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://slushman.com
 * @since      1.0.0
 *
 * @package    Anchorhead
 * @subpackage Anchorhead/classes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Anchorhead
 * @subpackage Anchorhead/classes
 * @author     Slushman <chris@slushman.com>
 */
class Anchorhead {

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Plugin_Name 	$_instance 		Instance singleton.
	 */
	protected static $_instance;

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Anchorhead_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The data sanitizer.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Anchorhead_Sanitize    $sanitize    Sanitizes data.
	 */
	protected $sanitize;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_customizer_hooks();
		$this->define_metabox_hooks();

	} // __construct()

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Anchorhead_Loader. Orchestrates the hooks of the plugin.
	 * - Anchorhead_i18n. Defines internationalization functionality.
	 * - Anchorhead_Admin. Defines all hooks for the admin area.
	 * - Anchorhead_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		$this->loader = new Anchorhead_Loader();

	} // load_dependencies()

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Anchorhead_Admin();

		$this->loader->action( 'admin_enqueue_scripts', 	$plugin_admin, 'enqueue_styles' );
		$this->loader->action( 'customize_preview_init', 	$plugin_admin, 'enqueue_styles' );
		$this->loader->action( 'admin_enqueue_scripts', 	$plugin_admin, 'enqueue_scripts' );

	} // define_admin_hooks()

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_customizer_hooks() {

		$plugin_customizer = new Anchorhead_Customizer();

		$this->loader->action( 'customize_register', 		$plugin_customizer, 'register_controls' );
		$this->loader->action( 'customize_register', 		$plugin_customizer, 'register_panels' );
		$this->loader->action( 'customize_register', 		$plugin_customizer, 'register_sections' );
		$this->loader->action( 'customize_register', 		$plugin_customizer, 'register_fields' );
		$this->loader->action( 'wp_head', 					$plugin_customizer, 'header_output' );
		$this->loader->action( 'customize_register', 		$plugin_customizer, 'load_customize_controls', 0 );
		$this->loader->action( 'customize_preview_init', 	$plugin_customizer, 'enqueue_scripts', 0 );
		$this->loader->action( 'plugin_action_links_' . ANCHORHEAD_FILE, $plugin_customizer, 'link_to_customizer' );

	} // define_customizer_hooks()

	/**
	 * Register all of the hooks related to metaboxes
	 *
	 * @since 		1.0.0
	 * @access 		private
	 */
	private function define_metabox_hooks() {

		$metaboxes = array( 'Showanchors' );

		if ( empty( $metaboxes ) ) { return; }

		foreach ( $metaboxes as $box ) {

			$class 	= 'Anchorhead_Metabox_' . $box;
			$box 	= new $class();

			$this->loader->action( 'add_meta_boxes', 		$box, 'add_metaboxes', 10, 2 );
			$this->loader->action( 'add_meta_boxes', 		$box, 'set_meta', 10, 2 );
			$this->loader->action( 'save_post', 			$box, 'validate_meta', 10, 2 );
			$this->loader->action( 'edit_form_after_title', $box, 'promote_metaboxes', 10, 1 );

		}

	} // define_metabox_hooks()

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Anchorhead_Public();

		$this->loader->action( 'wp_enqueue_scripts', 	$plugin_public, 'enqueue_styles' );
		$this->loader->action( 'wp_enqueue_scripts', 	$plugin_public, 'enqueue_scripts' );
		$this->loader->filter( 'wp_footer', 			$plugin_public, 'start_smooth_scroll' );
		$this->loader->filter( 'the_content', 			$plugin_public, 'find_headings' , 50 );
		$this->loader->filter( 'the_content', 			$plugin_public, 'add_anchors' , 60 );
		$this->loader->filter( 'the_content', 			$plugin_public, 'add_menu' , 70 );

	} // define_public_hooks()

	/**
	 * Get instance of main class
	 *
	 * @since 		1.0.0
	 * @return 		Anchorhead
	 */
	public static function get_instance() {

		if ( empty( self::$_instance ) ) {

			self::$_instance = new self;

		}

		return self::$_instance;

	} // get_instance()

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Anchorhead_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Anchorhead_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Anchorhead_i18n();

		$this->loader->action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	} // set_locale()

} // class
