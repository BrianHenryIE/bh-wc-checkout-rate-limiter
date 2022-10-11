<?php
/**
 * Class Plugin_Test. Tests the root plugin setup.
 *
 * @package brianhenryie/bh-wc-checkout-rate-limiter
 * @author     BrianHenryIE <BrianHenryIE@gmail.com>
 */

namespace BrianHenryIE\Checkout_Rate_Limiter;

use \Exception;

/**
 * Verifies the plugin has been instantiated and added to PHP's $GLOBALS variable.
 */
class Plugin_Integration_Test extends \Codeception\TestCase\WPTestCase {

	public function test_activation_hook() {
		$network_wide = false;

		$plugin_basename = 'bh-wc-checkout-rate-limiter/bh-wc-checkout-rate-limiter.php';

		$option = 'bh_wc_checkout_rate_limiter_activated_time';

		add_filter(
			"pre_option_{$option}",
			function( $pre_value, $option, $default ) {
				throw new Exception( 'Success: return early if the activation method is running.' );
			},
			10,
			3
		);

		$this->expectException( Exception::class );

		do_action( "activate_{$plugin_basename}", $network_wide );
	}

}
