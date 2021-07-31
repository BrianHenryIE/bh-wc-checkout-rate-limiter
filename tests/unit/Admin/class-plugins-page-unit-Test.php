<?php
/**
 *
 *
 * @package BH_WC_Checkout_Rate_Limiter
 * @author  BrianHenryIE <BrianHenryIE@gmail.com>
 */

namespace BrianHenryIE\Checkout_Rate_Limiter\Includes;

use BrianHenryIE\Checkout_Rate_Limiter\Admin\Plugins_Page;
use BrianHenryIE\Checkout_Rate_Limiter\API\Settings_Interface;
use BrianHenryIE\ColorLogger\ColorLogger;
use Psr\Log\NullLogger;

/**
 * Class Plugin_WP_Mock_Test
 *
 * @covers BrianHenryIE\Checkout_Rate_Limiter\Admin\Plugins_Page
 */
class Plugins_Page_Unit_Test extends \Codeception\Test\Unit {

	protected function _before() {
		\WP_Mock::setUp();
	}

	// This is required for `'times' => 1` to be verified.
	protected function _tearDown() {
		parent::_tearDown();
		\WP_Mock::tearDown();
	}

	public function test_action_links() {

		$settings = $this->makeEmpty(
			Settings_Interface::class,
			array()
		);
		$logger   = new ColorLogger();

		$sut = new Plugins_Page( $settings, $logger );

		\WP_Mock::userFunction(
			'admin_url',
			array(
				'args'   => array( 'admin.php?page=wc-settings&tab=checkout&section=checkout-rate-limiting' ),
				'return' => 'admin.php?page=wc-settings&tab=checkout&section=checkout-rate-limiting',
			)
		);

		$result = $sut->action_links( array() );

		$this->assertEquals( '<a href="admin.php?page=wc-settings&tab=checkout&section=checkout-rate-limiting">Settings</a>', $result[0] );

	}

}
