<?php

namespace BrianHenryIE\Checkout_Rate_Limiter\API\RateLimiter;

use BrianHenryIE\Checkout_Rate_Limiter\RateLimit\Rate;
use BrianHenryIE\Checkout_Rate_Limiter\RateLimit\Status;

class WordPress_RateLimiter_WP_Unit_Test extends \Codeception\TestCase\WPTestCase {

	/**
	 * Instantiating was failing. Turned out to be a missing "/" in the autoload-classmap.
	 *
	 * @covers BrianHenryIE\Checkout_Rate_Limiter\API\RateLimiter\WordPress_RateLimiter::__construct
	 */
	public function test_constructor() {

		$sut = new WordPress_RateLimiter();

		$this->assertInstanceOf( Psr16RateLimiter::class, $sut );

	}

	/**
	 * The Psr16RateLimiter was generating cache keys that WpOop\TransientCache did not like.
	 *
	 * @see https://github.com/wp-oop/transient-cache/blob/94b21321867dfb82eda7fe2ab962895c939f446d/src/CachePool.php#L38
	 * @see Psr16RateLimiter::key()
	 *
	 * @coversNothing
	 */
	public function test_happy_path_use() {

		$sut = new WordPress_RateLimiter();

		$rate = Rate::custom( 3, 60 );

		$status = $sut->limitSilently( '127.0.0.1', $rate );

		$this->assertInstanceOf( Status::class, $status );

	}


}
