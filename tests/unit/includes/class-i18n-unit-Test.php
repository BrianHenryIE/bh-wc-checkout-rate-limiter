<?php
/**
 *
 *
 * @package BH_WC_Checkout_Rate_Limiter
 * @author  BrianHenryIE <BrianHenryIE@gmail.com>
 */

namespace BrianHenryIE\Checkout_Rate_Limiter\Includes;

/**
 * Class Plugin_WP_Mock_Test
 *
 * @covers BrianHenryIE\Checkout_Rate_Limiter\Includes\I18n
 */
class I18n_Unit_Test extends \Codeception\Test\Unit {

	protected function setup() : void {
		parent::setUp();
		\WP_Mock::setUp();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	/**
	 * Verify load_plugin_textdomain is correctly called.
	 *
	 * @covers BrianHenryIE\Checkout_Rate_Limiter\Includes\I18n::load_plugin_textdomain
	 */
	public function test_load_plugin_textdomain() {

		\WP_Mock::userFunction(
			'plugin_basename',
			array(
				'args'   => array(
					\WP_Mock\Functions::type( 'string' ),
				),
				'return' => 'bh-wc-checkout-rate-limiter',
				'times'  => 1,
			)
		);

		\WP_Mock::userFunction(
			'load_plugin_textdomain',
			array(
				'times' => 1,
				'args'  => array(
					'bh-wc-checkout-rate-limiter',
					false,
					'bh-wc-checkout-rate-limiter/languages/',
				),
			)
		);

		$i18n = new I18n();
		$i18n->load_plugin_textdomain();

	}
}
