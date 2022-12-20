<?php
/**
 * Tests Psr\SimpleCache is prefixed.
 *
 * @package brianhenryie/bh-wc-checkout-rate-limiter
 * @author     BrianHenryIE <BrianHenryIE@gmail.com>
 */

namespace BrianHenryIE\Checkout_Rate_Limiter;

class Issue_16_Integration_Test extends \Codeception\TestCase\WPTestCase {

	public function test_interface_is_prefixed() {

		$this->assertTrue( interface_exists( \BrianHenryIE\Checkout_Rate_Limiter\Psr\SimpleCache\CacheInterface::class ) );
	}

}
