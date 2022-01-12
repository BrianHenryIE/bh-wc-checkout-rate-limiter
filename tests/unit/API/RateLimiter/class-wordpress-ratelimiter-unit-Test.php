<?php
/**
 *
 *
 * @package BH_WC_Checkout_Rate_Limiter
 * @author  BrianHenryIE <BrianHenryIE@gmail.com>
 */

namespace BrianHenryIE\Checkout_Rate_Limiter\API\RateLimiter;

use ReflectionClass;

/**
 * Class Plugin_WP_Mock_Test
 *
 * @covers BrianHenryIE\Checkout_Rate_Limiter\API\RateLimiter\WordPress_RateLimiter
 */
class WordPress_RateLimiter_Unit_Test extends \Codeception\Test\Unit {

	/**
	 * Caused a fatal error when IPv6 : character was used, which is disallowed by wp-oop/transient-cache.
	 */
	public function test_reserved_characters() {

		$class  = new ReflectionClass( WordPress_RateLimiter::class );
		$method = $class->getMethod( 'escape_key' );
		$method->setAccessible( true );

		$sut = $this->makeEmptyExcept( WordPress_RateLimiter::class, 'escape_key' );

		$result = $method->invokeArgs( $sut, array( '2603:3006:1095:c000:28ab:2cde:6b0f:c6eb' ) );

		$this->assertEquals( '2603-3006-1095-c000-28ab-2cde-6b0f-c6eb', $result );

	}
}
