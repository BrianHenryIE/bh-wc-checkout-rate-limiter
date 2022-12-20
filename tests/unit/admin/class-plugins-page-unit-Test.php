<?php
/**
 *
 *
 * @package brianhenryie/bh-wc-checkout-rate-limiter
 * @author  BrianHenryIE <BrianHenryIE@gmail.com>
 */

namespace BrianHenryIE\Checkout_Rate_Limiter\WP_Includes;

use BrianHenryIE\Checkout_Rate_Limiter\Admin\Plugins_Page;
use BrianHenryIE\Checkout_Rate_Limiter\Settings_Interface;
use BrianHenryIE\ColorLogger\ColorLogger;
use Psr\Log\NullLogger;

/**
 * Class Plugin_WP_Mock_Test
 *
 * @covers BrianHenryIE\Checkout_Rate_Limiter\Admin\Plugins_Page
 */
class Plugins_Page_Unit_Test extends \Codeception\Test\Unit {

	protected function setup() : void {
		parent::setUp();
		\WP_Mock::setUp();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
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

		$result = $sut->action_links( array(), '', array(), '' );

		$this->assertEquals( '<a href="admin.php?page=wc-settings&tab=checkout&section=checkout-rate-limiting">Settings</a>', $result[0] );

	}

}
