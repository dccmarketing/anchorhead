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
	 * An array of headings on pages.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $headings    An array of headings on pages.
	 */
	private $headings = array();

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

		wp_enqueue_script( ANCHORHEAD_SLUG . 'smooth-scroll', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/anchorhead-public.min.js', array(), ANCHORHEAD_VERSION, false );

	} // enqueue_scripts()

	/**
	 * Adds anchor tags after each heading in the content.
	 *
	 * @exits 		If $content is empty.
	 * @exits 		If not in the loop.
	 * @exits 		If there are no headings.
	 * @exits 		If The current page is not in the headings array.
	 * @param 		mixed 		$content 		The current content
	 * @return  	mixed 						Content with the anchor links
	 */
	public function add_anchors( $content ) {

		if ( '' === trim( $content ) ) { return $content; }
		if ( ! in_the_loop() ) { return $content; }
		if ( empty( $this->headings ) ) { return $content; }

		$thispageID = get_the_ID();

		if ( in_array( $thispageID, $this->headings ) ) { return $content; }

		$offset = 0;
		$blocks = $this->get_block_elements_count( $content );

		foreach ( $this->headings[$thispageID]['h2'] as $heading ) {

			$new_content 	= $this->get_new_content( $heading, $blocks );
			$start 			= $this->get_starting_point( $heading, $offset, $blocks );
			$content 		= substr_replace( $content, $new_content, $start, 0 );
			$offset 		= strlen( $new_content ) + $offset;

			unset( $new_content );
			unset( $start );

		} // foreach

		return $content;

	} // add_anchors()

	/**
	 * Adds links to the anchor tags in the content.
	 *
	 * @exits 		If $content is empty.
	 * @exits 		If not in the loop.
	 * @exits 		If there are no headings.
	 * @exits 		If The current page is not in the headings array.
	 * @param 		mixed 		$content 		The current content
	 * @return  	mixed 						Content with the anchor links
	 */
	public function add_menu( $content ) {

		if ( '' === trim( $content ) ) { return $content; }
		if ( ! in_the_loop() ) { return $content; }
		if ( empty( $this->headings ) ) { return $content; }

		$thispageID = get_the_ID();

		if ( in_array( $thispageID, $this->headings ) ) { return $content; }

		$float = get_theme_mod( 'float_picker' );
		$title = get_theme_mod( 'toc_title' );

		$return = '';
		$return .= '<ul class="ah-menu" data-float="' . esc_attr( $float ) . '">';
		$return .= '<h3 class="toc-title">';

		if ( ! empty( $title ) ) {

			$return .= esc_html( $title );

		}

		$return .= '</h3>';



		foreach ( $this->headings[$thispageID]['h2'] as $heading ) {

			$return .= '<li>';
			$return .= '<a data-scroll href="#' . $heading['anchortext'] . '">';
			$return .= $heading['text'];
			$return .= '</a>';
			$return .= '</li>';

		} // foreach

		$return .= '</ul>';

		return $return . $content;

	} // add_menu()

	/**
	 * Populates the headings class variable with headings from the content.
	 *
	 * @exits 		If $content is empty.
	 * @exits 		If not in the loop.
	 * @exits 		If this page is set to not show the anchor menu.
	 * @exits 		If no H2 elements were found.
	 * @param 		mixed 		$content 		The current content
	 * @return 		mixed 		$content 		The current content
	 */
	public function find_headings( $content ) {

		if ( '' === trim( $content ) ) { return $content; }
		if ( ! in_the_loop() ) { return $content; }

		global $post;

		$meta = get_post_custom( $post->ID );

		if ( array_key_exists( 'show-anchors', $meta ) && empty( $meta['show-anchors'][0] ) ) { return $content; }

		$dom 	= new DOMDocument();
		$h2arr 	= array();
		$pageID = get_the_ID();

		$dom->loadHTML( $content );

		$h2arr = $dom->getElementsByTagName( 'h2' );

		if ( 0 >= $h2arr->length ) { return $content; }

		foreach ( $h2arr as $item ) {

			$text 				= esc_html( trim( $item->nodeValue ) ); // the text used in the heading
			$textlength 		= strlen( $text ); // the length of the text
			$textbegin 			= strpos( $content, '>' . $text . '</h2>' ); // heading beginning position within the content string

			if ( ! $textbegin && 0 === strpos( $content, '<h2>' ) ) {

				$pos 		= strpos( $content, '<h2>' );
				$textbegin 	= 3;

			}

			if ( empty( $textlength ) || empty( $textbegin ) ) { continue; }

			$info['anchortext'] = sanitize_title( $text );
			$info['textbegin'] 	= $textbegin;
			$info['text'] 		= $text;
			$info['textend'] 	= $textbegin + $textlength + 5;
			$info['textlength'] = $textlength;
			$info['closetag'] 	= $textbegin + $textlength + 1;

			$this->headings[$pageID]['h2'][] = $info;

			unset( $text );
			unset( $textlength );
			unset( $textbegin );

		}

		unset( $dom );

		return $content;

	} // find_headings()

	/**
	 * Returns the count of all the block-level elements in the content.
	 *
	 * @exits 		If the content is empty.
	 * @exits 		If no spaces are found within the block elements threshhold.
	 * @param 		mixed 		$content 		The content.
	 * @return 		int 						The block element count.
	 */
	private function get_block_elements_count( $content ) {

		if ( empty( $content ) ) { return 0; }

		$subpos = strpos( $content, ' ', $this->options['block-elements-threshhold'] );

		if ( FALSE === $subpos ) { return; }

		$trimmed 	= substr( $content, 0, $subpos );
		$dom 		= new DOMDocument();

		$dom->loadHTML( $trimmed );

		$count 		= '';
		$elements 	= array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'li', 'div', 'pre', 'hr', 'blockquote', 'address', 'article', 'aside', 'canvas', 'dd',' dl', 'fieldset', 'figcaption', 'figure',' footer' ,'form', 'header', 'hgroup', 'main', 'nav', 'noscript', 'output', 'section', 'table', 'tfoot', 'video'  );

		foreach ( $elements as $element ) {

			$els 	= $dom->getElementsByTagName( $element );
			$count 	= $count + $els->length;

			unset( $els );

		}

		return $count;

	} // get_block_elements_count()

	/**
	 * Returns the headings array.
	 *
	 * Can optionally return just the headings array for that page.
	 *
	 * @param 		string 		$page 		The page slug.
	 * @return 		array 					Array of headings.
	 */
	public function get_headings( $page = '' ) {

		if ( empty( $page ) ) {

			return $this->headings;

		}

		return $this->headings[$page];

	} // get_headings()

	/**
	 * Returns the appropriate new content to add.
	 *
	 * @exits 		If $heading is empty.
	 * @param 		array 		$heading 		Array of heading info.
	 * @return 		mixed 						The new content.
	 */
	private function get_new_content( $heading, $blocks ) {

		if ( empty( $heading ) ) { return; }

		$thresh = $this->get_threshhold( $blocks );

		if ( $thresh > $heading['textbegin'] ) {

			$new_content = ' class="inline-heading"';

		} else {

			$new_content = '<span id="' . $heading['anchortext'] . '"></span><a class="ah-top" data-scroll href="#" role="link">Back to top</a></h2>';

		}

		return $new_content;

	} // get_new_content()

	/**
	 * Returns the new starting point for the heading.
	 *
	 * @exits 		If $heading is empty.
	 * @exits 		If $offset is empty.
	 * @param 		array 		$heading 		Array of heading info.
	 * @param 		int 		$offset 		The offset.
	 * @param 		int 		$blocks 		The number of block elements in the content.
	 * @return 		int 						The new starting position.
	 */
	private function get_starting_point( $heading, $offset, $blocks ) {

		if ( empty( $heading ) ) { return; }

		$thresh = $this->get_threshhold( $blocks );

		if ( $thresh > $heading['textbegin'] ) {

			$start = $heading['textbegin'] + $offset;

		} else {

			$start = $heading['closetag'] + $offset;

		}

		return $start;

	} // get_starting_point()

	/**
	 * Returns the character count for the top-link-threshhold.
	 *
	 * Each block-level element adds 75 characters - equivalent to one line of characters
	 *
	 * @todo 		Add to the top-link-threshhold as the menu gets longer.
	 *
	 * @param 		int 		$blocks 		The quantity of block elements.
	 * @return 		int 						The threshhold.
	 */
	private function get_threshhold( $blocks ) {

		//return 500;

		return $this->options['top-link-threshhold'] + ( $blocks * 75 );

	} // get_threshhold()

	/**
	 * Sets the class variable $options
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
