<?php
/**
 * Class Plugin_Test. Tests the root plugin setup.
 *
 * @package BH_WC_Checkout_Rate_Limiter
 * @author     BrianHenryIE <BrianHenryIE@gmail.com>
 */

namespace BH_WC_Checkout_Rate_Limiter;

use BH_WC_Checkout_Rate_Limiter\Includes\BH_WC_Checkout_Rate_Limiter;

/**
 * Verifies the plugin has been instantiated and added to PHP's $GLOBALS variable.
 */
class Plugin_Integration_Test extends \Codeception\TestCase\WPTestCase {

	/**
	 * Test the main plugin object is added to PHP's GLOBALS and that it is the correct class.
	 */
	public function test_plugin_instantiated() {

		$this->assertArrayHasKey( 'bh_wc_checkout_rate_limiter', $GLOBALS );

		$this->assertInstanceOf( BH_WC_Checkout_Rate_Limiter::class, $GLOBALS['bh_wc_checkout_rate_limiter'] );
	}

}
