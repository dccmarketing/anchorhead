<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @link       http://slushman.com
 * @since      1.0.0
 * @package    Anchorhead
 * @subpackage Anchorhead/classes
 * @author     Slushman <chris@slushman.com>
 */
class Anchorhead_Activator {

	/**
	 * Runs on plugin activation.
	 *
	 * Gets the options list and set them in the database with the
	 * default values specified there.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'classes/class-admin.php';

		$opts 		= array();
		$options 	= Anchorhead_Admin::get_options_list();

		foreach ( $options as $option ) {

			$opts[ $option[0] ] = $option[2];

		}

		update_option( 'anchorhead-options', $opts );

	} // activate()

} // class
