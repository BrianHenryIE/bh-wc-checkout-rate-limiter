<?php

namespace BrianHenryIE\Checkout_Rate_Limiter\WooCommerce;

use BrianHenryIE\Checkout_Rate_Limiter\API\Settings_Interface;
use BrianHenryIE\ColorLogger\ColorLogger;
use Psr\Log\LogLevel;

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

	/**
	 * Version 1.2.0 introduces sensible defaults.
	 *
	 * @covers ::settings
	 */
	public function test_defaults(): void {

		$settings = $this->makeEmpty( Settings_Interface::class );
		$logger   = new ColorLogger();

		$sut = new Settings_Payments( $settings, $logger );

		\WP_Mock::userFunction(
			'admin_url',
			array(
				'return_arg' => true,
			)
		);

		$result = $sut->settings( array(), 'checkout-rate-limiting' );

		// Twice per 60 seconds.
		$this->assertEquals( 60, $result['bh_wc_checkout_rate_limiter_allowed_attempts_per_interval_1']['default']['interval'] );
		$this->assertEquals( 2, $result['bh_wc_checkout_rate_limiter_allowed_attempts_per_interval_1']['default']['attempts'] );

		// Three times in two minutes.
		$this->assertEquals( 120, $result['bh_wc_checkout_rate_limiter_allowed_attempts_per_interval_2']['default']['interval'] );
		$this->assertEquals( 3, $result['bh_wc_checkout_rate_limiter_allowed_attempts_per_interval_2']['default']['attempts'] );

		// Five times in five minutes.
		$this->assertEquals( 300, $result['bh_wc_checkout_rate_limiter_allowed_attempts_per_interval_3']['default']['interval'] );
		$this->assertEquals( 5, $result['bh_wc_checkout_rate_limiter_allowed_attempts_per_interval_3']['default']['attempts'] );

		// Log level: Notice.
		$this->assertEquals( LogLevel::NOTICE, $result['bh_wc_checkout_rate_limiter_log_level']['default'] );
	}

}
