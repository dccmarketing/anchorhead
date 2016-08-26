<?php
/**
 * Class Anchorhead__Sanitize_Test
 *
 * @package
 */

/**
 * Sample test case.
 */
class Anchorhead__Sanitize_Test extends WP_UnitTestCase {

	public $sanitizer;

	/**
	 * Sets up the test data for the test class.
	 */
	public function setUp() {
		parent::setUp();
		$this->sanitizer = new Anchorhead_Sanitize();
	}

	/**
	 * Is $this->sanitizer an instance of the Anchorhead_Sanitize class?
	 */
	function test_admin_is_admin_class() {
		$this->assertInstanceOf( Anchorhead_Sanitize::class, $this->sanitizer );
	}

	/**
	 * If the clean method does not receive the type parameter, it should
	 * return an instance of the WP_Error class.
	 */
	function test_get_error_without_type() {
		$result = $this->sanitizer->clean( 'four', '' );
		$this->assertInstanceOf( WP_Error::class, $result );
	}

	/**
	 * If the clean method does not receive the type parameter, it should
	 * return a specific message in the WP_Error class.
	 */
	function test_type_error_message() {
		$result 	= $this->sanitizer->clean( 'four', '' );
		$message 	= $result->get_error_message();
		$this->assertEquals( 'Specify the data type to sanitize.', $message );
	}

	/**
	 * Test clean, positives.
	 */
	function test_clean_positives() {

		$tests[] = array( 0, 'radio', 0 );
		$tests[] = array( 1, 'radio', 1 );
		$tests[] = array( '1', 'radio', '1' );
		$tests[] = array( 'none', 'radio', 'none' );
		$tests[] = array( 'left', 'radio', 'left' );

		$tests[] = array( '1', 'select', '1' );
		$tests[] = array( 1, 'select', 1 );
		$tests[] = array( 'US', 'select', 'US' );

		$tests[] = array( '0', 'number', '0' );
		$tests[] = array( 0, 'number', 0 );
		$tests[] = array( '1', 'number', '1' );
		$tests[] = array( 1, 'number', 1 );
		$tests[] = array( '999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999', 'number', '9223372036854775807' );
		$tests[] = array( 01000, 'number', 512 );
		$tests[] = array( '08', 'number', '8' );
		$tests[] = array( '09', 'number', '9' );
		$tests[] = array( '-99', 'number', '-99' );
		$tests[] = array( -99, 'number', -99 );
		$tests[] = array( 1.00, 'number', 1 );
		$tests[] = array( 0.00, 'number', 0 );
		$tests[] = array( '0,00', 'number', '0' );

		$tests[] = array( 0, 'checkbox', 0 );
		$tests[] = array( 1, 'checkbox', 1 );
		$tests[] = array( null, 'checkbox', 0 );

		$tests[] = array( 'test@test.com', 'email', 'test@test.com' );
		$tests[] = array( ' test@test.com', 'email', 'test@test.com' );
		$tests[] = array( 'test@test.com ', 'email', 'test@test.com' );

		// $tests[] = array( '', 'range', '' );
		// $tests[] = array( '', 'hidden', '' );
		// $tests[] = array( '', 'month', '' );
		// $tests[] = array( '', 'text', '' );
		// $tests[] = array( '', 'color', '' );
		// $tests[] = array( '', 'file', '' );
		// $tests[] = array( '', 'tel', '' );
		// $tests[] = array( '', 'textarea', '' );
		// $tests[] = array( '', 'url', '' );
		// $tests[] = array( '', 'date', '' );
		// $tests[] = array( '', 'datetime', '' );
		// $tests[] = array( '', 'datetime-local', '' );
		// $tests[] = array( '', 'time', '' );
		// $tests[] = array( '', 'week', '' );

		if ( empty( $tests ) ) { return; }

		foreach ( $tests as $test ) {

			$result = $this->sanitizer->clean( $test[0], $test[1] );

			$this->assertEquals( $test[2], $result );

		}

	}

	/**
	 * Test clean, negatives.
	 */
	function test_clean_negatives() {

		$tests[] = array( 'a', 'number', 0 );

		//
		// $1.00
		// 1/2
		// 1E2
		// 1E02
		// 1E+02
		// -1.00
		// -$1.00
		// -1/2
		// -1E2
		// -1E02
		// -1E+02
		// 1/0
		// 0/0
		// -2147483648/-1
		// -9223372036854775808/-1
		// 0..0
		// .
		// 0.0.0
		// 0,00
		// 0,,0
		// ,
		// 0,0,0
		// 0.0/0
		// 1.0/0.0
		// 0.0/0.0
		// 1,0/0,0
		// 0,0/0,0
		// --1
		// -
		// -.
		// -,
		// NaN
		// Infinity
		// -Infinity
		// INF
		// 1#INF
		// -1#IND
		// 1#QNAN
		// 1#SNAN
		// 1#IND
		// 0x0
		// 0xffffffff
		// 0xffffffffffffffff
		// 0xabad1dea
		// 1,000.00
		// 1 000.00
		// 1'000.00
		// 1,000,000.00
		// 1 000 000.00
		// 1'000'000.00
		// 1.000,00
		// 1 000,00
		// 1'000,00
		// 1.000.000,00
		// 1 000 000,00
		// 1'000'000,00
		// 2.2250738585072011e-308
		//
		//
		//
		// $tests[] = array( 'a', 'checkbox', FALSE );
		// $tests[] = array( '146248957639486512987365', 'checkbox', FALSE );
		// $tests[] = array( '-99', 'checkbox', FALSE );
		// $tests[] = array( '-99.99', 'checkbox', FALSE );
		// $tests[] = array( '3.14', 'checkbox', FALSE );

		// $tests[] = array( 'a', 'email', 'email_too_short' );
		//
		//
		// $tests[] = array( '', 'range', '' );
		// $tests[] = array( '', 'hidden', '' );
		// $tests[] = array( '', 'month', '' );
		// $tests[] = array( '', 'text', '' );
		// $tests[] = array( '', 'color', '' );
		// $tests[] = array( '', 'email', '' );
		// $tests[] = array( '', 'file', '' );
		// $tests[] = array( '', 'tel', '' );
		// $tests[] = array( '', 'textarea', '' );
		// $tests[] = array( '', 'url', '' );
		// $tests[] = array( '', 'radio', '' );
		// $tests[] = array( '', 'select', '' );
		// $tests[] = array( '', 'date', '' );
		// $tests[] = array( '', 'datetime', '' );
		// $tests[] = array( '', 'datetime-local', '' );
		// $tests[] = array( '', 'time', '' );
		// $tests[] = array( '', 'week', '' );
		// $tests[] = array( '', 'number', '' );

		if ( empty( $tests ) ) { return; }

		foreach ( $tests as $test ) {

			$result = $this->sanitizer->clean( $test[0], $test[1] );

			$this->assertEquals( $test[2], $result );

		}

	}

	function test_clean_not_equals() {

		$tests = array();

		$tests[] = array( '999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999', 'number', '999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999' );
		$tests[] = array( 01000, 'number', 1000 );
		$tests[] = array( '0,00', 'number', '0,00' );
		$tests[] = array( '123456789012345678901234567890123456789', 'number', '123456789012345678901234567890123456789' );
		$tests[] = array( 123456789012345678901234567890123456789, 'number', 123456789012345678901234567890123456789 );

		if ( empty( $tests ) ) { return; }

		foreach ( $tests as $test ) {

			$result = $this->sanitizer->clean( $test[0], $test[1] );

			$this->assertNotEquals( $test[2], $result );

		}

	}

}
