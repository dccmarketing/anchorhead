<?php

/**
 * The plugin bootstrap file.
 *
 * Sets plugin constants and loads the auto-loader class and the main plugin class.
 *
 * @link              http://slushman.com
 * @since             1.0.0
 * @package           Anchorhead
 *
 * @wordpress-plugin
 * Plugin Name:       Anchorhead
 * Plugin URI:        http://slushman.com/anchorhead
 * Description:       Adds anchor links to the h2 heading in a page/post.
 * Version:           1.2
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
define( 'ANCHORHEAD_VERSION', '1.2' );
define( 'ANCHORHEAD_SLUG', 'anchorhead' );
define( 'ANCHORHEAD_FILE', plugin_basename( __FILE__ ) );

/**
 * Activation/Deactivation Hooks
 */
register_activation_hook( __FILE__, array( 'Anchorhead_Activator', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Anchorhead_Deactivator', 'deactivate' ) );

/**
 * Load Autoloader
 */
require plugin_dir_path( __FILE__ ) . '/classes/class-autoloader.php';

/**
 * Initializes each class and adds the hooks action in each to init.
 */
function anchorhead_init() {

	$obj_admin 		= new Anchorhead_Admin();
	$obj_customizer = new Anchorhead_Customizer();
	$obj_i18n 		= new Anchorhead_i18n();
	$obj_mb_anchors = new Anchorhead_Metabox_Hideanchors();
	$obj_public 	= new Anchorhead_Public();

	add_action( 'init', array( $obj_admin, 'hooks' ) );
	add_action( 'init', array( $obj_customizer, 'hooks' ) );
	add_action( 'init', array( $obj_i18n, 'hooks' ) );
	add_action( 'init', array( $obj_mb_anchors, 'hooks' ) );
	add_action( 'init', array( $obj_public, 'hooks' ) );

} // anchorhead_init()

add_action( 'plugins_loaded', 'anchorhead_init' );
