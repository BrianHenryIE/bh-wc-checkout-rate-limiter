<?php

namespace BrianHenryIE\Checkout_Rate_Limiter\API\RateLimiter;

use BrianHenryIE\Checkout_Rate_Limiter\RateLimit\Psr16RateLimiter;
use BrianHenryIE\Checkout_Rate_Limiter\RateLimit\Rate;
use BrianHenryIE\Checkout_Rate_Limiter\RateLimit\Status;

class WordPress_RateLimiter_WP_Unit_Test extends \Codeception\TestCase\WPTestCase {

	/**
	 * Instantiating was failing. Turned out to be a missing "/" in the autoload-classmap.
	 *
	 * @covers \BrianHenryIE\Checkout_Rate_Limiter\API\RateLimiter\WordPress_RateLimiter::__construct
	 */
	public function test_constructor() {

		$rate = Rate::perMinute( 2 );

		$sut = new WordPress_RateLimiter( $rate );

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

		$rate = Rate::perMinute( 2 );

		$sut = new WordPress_RateLimiter( $rate );

		$rate = Rate::custom( 3, 60 );

		$status = $sut->limitSilently( '127.0.0.1' );

		$this->assertInstanceOf( Status::class, $status );
	}

	/**
	 * PHP Fatal error:  Uncaught RuntimeException: set_transient() failed with key "checkout/checkout83.116.144.11-60" with TTL 60s in /.../wp-content/plugins/bh-wc-checkout-rate-limiter/strauss/wp-oop/transient-cache/src/CachePool.php:321
	 *
	 * @see CachePool::setTransient()
	 */
	public function test_reserved_characters() {

		$this->markTestIncomplete();

		$rate         = Rate::custom( 3, 60 );
		$rate_limiter = new WordPress_RateLimiter( $rate, 'checkout' );

		$ip_address = '83.116.144.11';

		$status = $rate_limiter->limitSilently( $ip_address );

	}

}
