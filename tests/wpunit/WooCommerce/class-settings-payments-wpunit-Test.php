<?php

namespace BrianHenryIE\Checkout_Rate_Limiter\WooCommerce;

use BrianHenryIE\Checkout_Rate_Limiter\Settings_Interface;
use BrianHenryIE\ColorLogger\ColorLogger;
use Psr\Log\NullLogger;

/**
 * @coversDefaultClass \BrianHenryIE\Checkout_Rate_Limiter\WooCommerce\Settings_Payments
 *
 * Class Ajax_WPUnit_Test
 * @package brianhenryie/bh-wc-checkout-rate-limiter
 */
class Settings_Payments_WPUnit_Test extends \Codeception\TestCase\WPTestCase {

	/**
	 * When the settings page is first loaded, the values for the rates are not yet set, causing a PHP warning:
	 *
	 * `PHP Warning: Illegal string offset 'interval' in .../WooCommerce/class-settings-advanced.php on line 196`
	 *
	 * @covers ::print_attempts_per_interval_settings_field
	 */
	public function test_first_run_empty_variables(): void {

		$settings = $this->makeEmpty( Settings_Interface::class );
		$logger   = new ColorLogger();

		$sut = new Settings_Payments( $settings, $logger );

		$value = array(
			'value'    => array(),
			'id'       => '',
			'class'    => '',
			'title'    => '',
			'desc_tip' => '',
		);

		$exception = null;
		try {
			$sut->print_attempts_per_interval_settings_field( $value );
		} catch ( \PHPUnit\Framework\Exception $e ) {
			$exception = $e;
		}

		$this->assertNull( $exception );

	}

}
