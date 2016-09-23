<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://slushman.com
 * @since      1.0.0
 *
 * @package    Anchorhead
 * @subpackage Anchorhead/classes
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Anchorhead
 * @subpackage Anchorhead/classes
 * @author     Slushman <chris@slushman.com>
 */
class Anchorhead_Public {

	/**
	 * The page metadata.
	 *
	 * @since 		1.0.0
	 * @access 		private
	 * @var 		array 			$meta 		The page metadata.
	 */
	private $meta;

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
	 * Registers all the WordPress hooks and filters for this class.
	 */
	public function hooks() {

		add_action( 'wp_enqueue_scripts', 		array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', 		array( $this, 'enqueue_scripts' ) );
		add_filter( 'wp_print_footer_scripts', 	array( $this, 'start_smooth_scroll' ), 99 );
		add_action( 'template_redirect', 		array( $this, 'set_meta' ), 10, 2 );

	} // hooks()

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( ANCHORHEAD_SLUG . '-public', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/anchorhead-public.css', array(), ANCHORHEAD_VERSION, 'all' );

	} // enqueue_styles()

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( ANCHORHEAD_SLUG, plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/anchorhead-public.min.js', array(), ANCHORHEAD_VERSION, true );

		$data['contentSelector'] 	= $this->options['content-selector'];
		$data['float'] 				= get_theme_mod( 'float_picker' );
		$data['threshhold'] 		= $this->options['top-link-threshhold'];
		$data['tocTitle'] 			= get_theme_mod( 'toc_title' );

		if ( isset( $this->meta['hide-anchors'] ) ) {

			$data['hide'] = $this->meta['hide-anchors'][0];

		}

		wp_localize_script( ANCHORHEAD_SLUG, 'ahsets', $data );

	} // enqueue_scripts()

	/**
	 * Sets the class variable $meta.
	 */
	public function set_meta() {

		global $post;

		if ( empty( $post ) ) { return; }

		$this->meta = get_post_custom( $post->ID );

	} // set_meta()

	/**
	 * Sets the class variable $options.
	 */
	private function set_options() {

		$this->options = get_option( ANCHORHEAD_SLUG . '-options' );

	} // set_options()

	/**
	 * Initiates smooth-scroll.js
	 *
	 * @exits 		If scroll-type or scroll-speed options are not set.
	 * @return 		mixed 		Script ouptut
	 */
	public function start_smooth_scroll() {

		if ( ! isset( $this->options['scroll-type'] ) || ! isset( $this->options['scroll-speed'] ) ) { return; }

		?><script>smoothScroll.init({
			easing: '<?php echo esc_html( $this->options['scroll-type'] ); ?>',
			speed: '<?php echo esc_html( $this->options['scroll-speed'] ); ?>'
		});</script><?php

	} // start_smooth_scroll()

} // class
