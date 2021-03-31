<?php

namespace BrianHenryIE\Checkout_Rate_Limiter\API\RateLimiter;

use BrianHenryIE\Checkout_Rate_Limiter\Psr\SimpleCache\CacheInterface;
use BrianHenryIE\Checkout_Rate_Limiter\RateLimit\Rate;
use BrianHenryIE\Checkout_Rate_Limiter\RateLimit\Status;

/**
 * @covers BrianHenryIE\Checkout_Rate_Limiter\API\RateLimiter\Psr16RateLimiter
 */
class Psr16_RateLimiter_Test extends \Codeception\TestCase\WPTestCase {

	/**
	 * @covers BrianHenryIE\Checkout_Rate_Limiter\API\RateLimiter\Psr16RateLimiter::__construct
	 */
	public function test_instantiate() {

		$cache = $this->makeEmpty( CacheInterface::class );

		$sut = new Psr16RateLimiter( $cache );

		$this->assertInstanceOf( Psr16RateLimiter::class, $sut );

	}

	/**
	 * @covers BrianHenryIE\Checkout_Rate_Limiter\API\RateLimiter\Psr16RateLimiter::limitSilently
	 */
	public function test_limit_silently() {
		$cache = $this->makeEmpty(
			CacheInterface::class,
			array(
				'get' => array( 'time', 'data' ),
			)
		);

		$sut = new Psr16RateLimiter( $cache );

		$ip_address = '127.0.0.1';
		$rate       = Rate::custom( 3, 60 );

		$status = $sut->limitSilently( $ip_address, $rate );

		$this->assertInstanceOf( Status::class, $status );

	}


}
