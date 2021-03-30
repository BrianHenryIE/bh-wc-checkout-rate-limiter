<?php
/**
 *
 *
 * @package BH_WC_Checkout_Rate_Limiter
 * @author  BrianHenryIE <BrianHenryIE@gmail.com>
 */

namespace BH_WC_Checkout_Rate_Limiter\Includes;

/**
 * Class Plugin_WP_Mock_Test
 *
 * @covers \BH_WC_Checkout_Rate_Limiter\Includes\I18n
 */
class I18n_Unit_Test extends \Codeception\Test\Unit {

	protected function _before() {
		\WP_Mock::setUp();
	}

	// This is required for `'times' => 1` to be verified.
	protected function _tearDown() {
		parent::_tearDown();
		\WP_Mock::tearDown();
	}

	/**
	 * Verify load_plugin_textdomain is correctly called.
	 *
	 * @covers I18n::load_plugin_textdomain
	 */
	public function test_load_plugin_textdomain() {

		global $plugin_root_dir;

		\WP_Mock::userFunction(
			'load_plugin_textdomain',
			array(
				'args'   => array(
					'bh-wc-checkout-rate-limiter',
					false,
					$plugin_root_dir . '/languages/',
				)
			)
		);
	}
}
