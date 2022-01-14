<?php
/**
 *
 *
 * @package brianhenryie/bh-wc-checkout-rate-limiter
 * @author  BrianHenryIE <BrianHenryIE@gmail.com>
 */

namespace BrianHenryIE\Checkout_Rate_Limiter\API;

/**
 * @coversDefaultClass \BrianHenryIE\Checkout_Rate_Limiter\API\Settings
 */
class Settings_Unit_Test extends \Codeception\Test\Unit {

	protected function setup() : void {
		parent::setUp();
		\WP_Mock::setUp();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	/**
	 * @covers ::get_plugin_basename
	 */
	public function test_basename(): void {

		$sut = new Settings();

		$expected = 'bh-wc-checkout-rate-limiter/bh-wc-checkout-rate-limiter.php';

		$actual = $sut->get_plugin_basename();

		$this->assertEquals( $expected, $actual );
	}

	/**
	 * @covers ::get_plugin_slug
	 */
	public function test_slug(): void {

		$sut = new Settings();

		$expected = 'bh-wc-checkout-rate-limiter';

		$actual = $sut->get_plugin_slug();

		$this->assertEquals( $expected, $actual );
	}

	/**
	 * @covers ::get_plugin_name
	 */
	public function test_name(): void {

		$sut = new Settings();

		$expected = 'Checkout Rate Limiter';

		$actual = $sut->get_plugin_name();

		$this->assertEquals( $expected, $actual );
	}

	/**
	 * Test get_log_level fetches the correct option, with a default value of "info".
	 *
	 * @covers ::get_log_level
	 */
	public function test_log_level(): void {

		\WP_Mock::userFunction(
			'get_option',
			array(
				'args'   => array(
					'bh_wc_checkout_rate_limiter_log_level',
					'info',
				),
				'return' => 'bh-wc-checkout-rate-limiter',
				'times'  => 1,
			)
		);

		$sut = new Settings();

		$sut->get_log_level();
	}
}
