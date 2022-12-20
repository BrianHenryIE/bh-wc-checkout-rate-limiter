<?php


namespace BrianHenryIE\Checkout_Rate_Limiter\WooCommerce;

use BrianHenryIE\Checkout_Rate_Limiter\Settings_Interface;
use BrianHenryIE\ColorLogger\ColorLogger;
use Psr\Log\NullLogger;
use \Exception;

/**
 * @coversDefaultClass \BrianHenryIE\Checkout_Rate_Limiter\WooCommerce\Ajax
 */
class Ajax_WPUnit_Test extends \Codeception\TestCase\WPTestCase {

	/**
	 * @covers ::__construct
	 *
	 * @throws Exception
	 */
	public function test_instantiate() {
		$settings = $this->makeEmpty( Settings_Interface::class );
		$logger   = new ColorLogger();

		$sut = new Ajax( $settings, $logger );

		$this->assertInstanceOf( Ajax::class, $sut );
	}

	/**
	 * @covers ::rate_limit_checkout
	 *
	 * @throws Exception
	 */
	public function test_not_enabled() {
		$settings = $this->makeEmpty(
			Settings_Interface::class,
			array(
				'is_enabled' => false,
			)
		);
		$logger   = new ColorLogger();

		$sut = new Ajax( $settings, $logger );

		$sut->rate_limit_checkout();
	}

	/**
	 * @covers ::rate_limit_checkout
	 *
	 * @throws Exception
	 */
	public function test_not_configured() {
		$settings = $this->makeEmpty(
			Settings_Interface::class,
			array(
				'is_enabled'               => true,
				'get_checkout_rate_limits' => array(),
			)
		);
		$logger   = new ColorLogger();

		$sut = new Ajax( $settings, $logger );

		$sut->rate_limit_checkout();
	}

	/**
	 * @covers ::rate_limit_checkout
	 *
	 * @throws Exception
	 */
	public function test_simple_non_blocked_ip() {

		$settings = $this->makeEmpty(
			Settings_Interface::class,
			array(
				'is_enabled'               => true,
				'get_checkout_rate_limits' => array(
					'60' => '1', // once per 60 seconds.
				),
			)
		);
		$logger   = new ColorLogger();

		$sut = new Ajax( $settings, $logger );

		add_filter( 'wp_doing_ajax', '__return_true' );
		add_filter(
			'wp_die_ajax_handler',
			function() {
				throw new \Exception( 'prevent die' );
			}
		);

		// Generate a fake IP for this unit test, to avoid any possible collisions.
		$_SERVER['HTTP_X_REAL_IP'] = wp_rand( 0, 255 ) . '.' . wp_rand( 0, 255 ) . '.' . wp_rand( 0, 255 ) . '.' . wp_rand( 0, 255 );

		$sut->rate_limit_checkout();

		// TODO: What to assert here?
	}


	/**
	 * @covers ::rate_limit_checkout
	 *
	 * @throws Exception
	 */
	public function test_simple_blocked_ip() {

		$settings = $this->makeEmpty(
			Settings_Interface::class,
			array(
				'is_enabled'               => true,
				'get_checkout_rate_limits' => array(
					'60' => '1', // once per 60 seconds.
				),
			)
		);
		$logger   = new ColorLogger();

		$sut = new Ajax( $settings, $logger );

		add_filter( 'wp_doing_ajax', '__return_true' );
		add_filter(
			'wp_die_ajax_handler',
			function() {
				throw new \Exception( 'Success: Exception thrown to prevent die().' );
			}
		);

		// Generate a fake IP for this unit test, to avoid any possible collisions.
		$_SERVER['HTTP_X_REAL_IP'] = wp_rand( 0, 255 ) . '.' . wp_rand( 0, 255 ) . '.' . wp_rand( 0, 255 ) . '.' . wp_rand( 0, 255 );

		$sut->rate_limit_checkout();

		$alloptions = wp_load_alloptions();
		$transients = array_filter(
			$alloptions,
			function ( $value, $key ) {
				return 0 === strpos( $key, '_transient' );
			},
			ARRAY_FILTER_USE_BOTH
		);

		array_filter(
			$alloptions,
			function ( $value, $key ) {
				return false !== strpos( $key, 'limit' );
			},
			ARRAY_FILTER_USE_BOTH
		);

		// Without sleeping, the following exception occurs:
		// @see WpOop\TransientCache\CachePool::set()
		// new CacheException($message, 0, $e);
		// "Could not write value for key "checkout8.104.172.231--60" to cache: set_transient() failed with key "checkout/checkout8.104.172.231--60" with TTL 60s".

		sleep( 1 );

		// TODO: Use a more specific Exception.
		$this->expectException( Exception::class );

		$sut->rate_limit_checkout();

	}


}
