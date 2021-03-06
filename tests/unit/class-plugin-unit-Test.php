<?php
/**
 * Tests for the root plugin file.
 *
 * @package BH_WC_Checkout_Rate_Limiter
 * @author  BrianHenryIE <BrianHenryIE@gmail.com>
 */

namespace BrianHenryIE\Checkout_Rate_Limiter;

use BrianHenryIE\Checkout_Rate_Limiter\API\Settings_Interface;
use BrianHenryIE\Checkout_Rate_Limiter\Includes\BH_WC_Checkout_Rate_Limiter;
use Psr\Log\LoggerInterface;

/**
 * Class Plugin_WP_Mock_Test
 */
class Plugin_Unit_Test extends \Codeception\Test\Unit {

	protected function setup() : void {
		parent::setUp();
		\WP_Mock::setUp();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	/**
	 * Verifies the plugin does not output anything to screen.
	 */
	public function test_plugin_include_no_output() {

		// Prevents code-coverage counting, and removes the need to define the WordPress functions that are used in that class.
		\Patchwork\redefine(
			array( BH_WC_Checkout_Rate_Limiter::class, '__construct' ),
			function( Settings_Interface $settings, LoggerInterface $logger ) {}
		);

		$plugin_root_dir = dirname( __DIR__, 2 ) . '/src';

		\WP_Mock::userFunction(
			'plugin_dir_path',
			array(
				'args'   => array( \WP_Mock\Functions::type( 'string' ) ),
				'return' => $plugin_root_dir . '/',
			)
		);

		\WP_Mock::userFunction(
			'plugin_basename',
			array(
				'args'   => array( \WP_Mock\Functions::type( 'string' ) ),
				'return' => 'bh-wc-checkout-rate-limiter/bh-wc-checkout-rate-limiter.php',
			)
		);

		\WP_Mock::userFunction(
			'register_activation_hook'
		);

		\WP_Mock::userFunction(
			'register_deactivation_hook'
		);

		// bh-wp-logger related mocks.
		\WP_Mock::userFunction(
			'get_option',
			array(
				'args'   => array( 'bh_wc_checkout_rate_limiter_log_level', 'info' ),
				'return' => 'info',
			)
		);

		\WP_Mock::userFunction(
			'is_admin',
			array(
				'return' => false,
			)
		);

		\WP_Mock::userFunction(
			'get_current_user_id'
		);

		\WP_Mock::userFunction(
			'wp_normalize_path',
			array(
				'return_arg' => true,
			)
		);

		\WP_Mock::userFunction(
			'get_option',
			array(
				'args'   => array( 'active_plugins' ),
				'return' => array( 'woocommerce/woocommerce.php' ),
			)
		);

		\WP_Mock::userFunction(
			'did_action',
			array(
				'return' => false,
			)
		);

		\WP_Mock::userFunction(
			'add_action',
			array(
				'return' => false,
			)
		);

		ob_start();

		include $plugin_root_dir . '/bh-wc-checkout-rate-limiter.php';

		$printed_output = ob_get_contents();

		ob_end_clean();

		$this->assertEmpty( $printed_output );

	}

}
