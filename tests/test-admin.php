<?php
/**
 * Class AnchorheadAdminTest
 *
 * @package
 */

/**
 * Sample test case.
 */
class Anchorhead_Admin_Test extends WP_UnitTestCase {

	public $admin;
	public $options;

	public function setUp() {

		parent::setUp();
		$this->admin 	= new Anchorhead_Admin();
		$this->options 	= $this->admin->get_options_list();

	}

	function test_admin_is_admin_class() {

		$this->assertInstanceOf( Anchorhead_Admin::class, $this->admin );

	}

	function test_get_options_list_count() {

		$opts[] = array( 'scroll-speed', 'number', '' );
		$opts[] = array( 'scroll-type', 'select', '' );

		$this->assertCount( 2, $this->options );

	}

}
