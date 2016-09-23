<?php

/**
 * Autoloader for PHP 5.3+
 *
 * @since 			1.0.0
 * @package 		Anchorhead
 * @subpackage 		Anchorhead/classes
 */
class Anchorhead_Autoloader {

	/**
	* Autoloader function
	*
	* @param 		string 			$class_name
	*/
	public static function autoloader( $class_name ) {

		if ( 0 !== strpos( $class_name, 'Anchorhead_' ) ) { return; }

		$class_name = str_replace( 'Anchorhead_', '', $class_name );
		$lower 		= strtolower( $class_name );
		$file      	= 'class-' . str_replace( '_', '-', $lower ) . '.php';
		$base_path 	= plugin_dir_path( __FILE__ );
		$paths[] 	= $base_path . $file;
		$paths[] 	= $base_path . 'classes/' . $file;

		/**
		 * anchorhead-autoloader-paths filter
		 */
		$paths = apply_filters( 'anchorhead-autoloader-paths', $paths );

		foreach ( $paths as $path ) :

			if ( is_readable( $path ) && file_exists( $path ) ) {

				require_once( $path );
				return;

			}

		endforeach;

		return FALSE;

	} // autoloader()

} // class

spl_autoload_register( 'Anchorhead_Autoloader::autoloader' );
