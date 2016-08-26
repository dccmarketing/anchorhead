<?php

/**
 * The plugin bootstrap file
 *
 * @link              http://slushman.com
 * @since             1.0.0
 * @package           Anchorhead
 *
 * @wordpress-plugin
 * Plugin Name:       Anchorhead
 * Plugin URI:        http://slushman.com/anchorhead
 * Description:       Adds anchor links to the h2 heading in a page/post.
 * Version:           1.1
 * Author:            Slushman
 * Author URI:        http://slushman.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       anchorhead
 * Domain Path:       /assets/languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) { die; }

/**
 * Define constants
 */
define( 'ANCHORHEAD_VERSION', '1.1' );
define( 'ANCHORHEAD_SLUG', 'anchorhead' );
define( 'ANCHORHEAD_FILE', plugin_basename( __FILE__ ) );

/**
 * Activation/Deactivation Hooks
 */
register_activation_hook( __FILE__, array( 'Anchorhead_Activator', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Anchorhead_Deactivator', 'deactivate' ) );

/**
 * Autoloader function
 *
 * Will search both plugin root and includes folder for class
 *
 * @param string $class_name
 */
if ( ! function_exists( 'anchorhead_autoloader' ) ) :

	function anchorhead_autoloader( $class_name ) {

		$class_name = str_replace( 'Anchorhead_', '', $class_name );
		$lower 		= strtolower( $class_name );
		$file      	= 'class-' . str_replace( '_', '-', $lower ) . '.php';
		$base_path 	= plugin_dir_path( __FILE__ );
		$paths[] 	= $base_path . $file;
		$paths[] 	= $base_path . 'classes/' . $file;

		/**
		 * plugin_name_autoloader_paths filter
		 */
		$paths = apply_filters( 'anchorhead-autoloader-paths', $paths );

		foreach ( $paths as $path ) :

			if ( is_readable( $path ) && file_exists( $path ) ) {

				require_once( $path );
				return;

			}

		endforeach;

	} // anchorhead_autoloader()

endif;

spl_autoload_register( 'anchorhead_autoloader' );

if ( ! function_exists( 'anchorhead_init' ) ) :

	/**
	 * Function to initialize plugin
	 */
	function anchorhead_init() {

		anchorhead()->run();

	}

	add_action( 'plugins_loaded', 'anchorhead_init' );

endif;

if ( ! function_exists( 'anchorhead' ) ) :

	/**
	 * Function wrapper to get instance of plugin
	 *
	 * @return Anchorhead
	 */
	function anchorhead() {

		return Anchorhead::get_instance();

	}

endif;
