<?php

namespace BrianHenryIE\Checkout_Rate_Limiter\WooCommerce;

use BrianHenryIE\Checkout_Rate_Limiter\API\Settings_Interface;
use BrianHenryIE\ColorLogger\ColorLogger;

/**
 * @coversDefaultClass \BrianHenryIE\Checkout_Rate_Limiter\WooCommerce\Settings_Payments
 */
class Settings_Payments_Unit_Test extends \Codeception\Test\Unit {

	/**
	 * @covers ::add_section
	 */
	public function test_add_section(): void {

		$settings = $this->makeEmpty( Settings_Interface::class );
		$logger   = new ColorLogger();

		$sut = new Settings_Payments( $settings, $logger );

		$result = $sut->add_section( array() );

		$this->assertArrayHasKey( 'checkout-rate-limiting', $result );
		$this->assertContains( 'Rate Limiting', $result );
	}
}
