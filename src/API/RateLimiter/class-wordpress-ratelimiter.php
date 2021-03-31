<?php
/**
 * Rate limiter that uses WordPress's transients (which will be superseded by any object cache plugin) for storage.
 *
 * Extends the Psr16 RateLimiter and uses Anton Ukhanev (XedinUnknown)'s PSR-16 interface for WordPress transients.
 *
 * @see https://github.com/wp-oop/transient-cache
 * @see https://github.com/nikolaposa/rate-limit
 *
 * @author     BrianHenryIE <BrianHenryIE@gmail.com>
 * @link       https://BrianHenryIE.com
 * @since      1.0.0
 * @package    BH_WC_Checkout_Rate_Limiter
 */

namespace BrianHenryIE\Checkout_Rate_Limiter\API\RateLimiter;

use BrianHenryIE\Checkout_Rate_Limiter\WpOop\TransientCache\CachePoolFactory;

/**
 * Class WordPress_RateLimiter
 *
 * @package BrianHenryIE\Checkout_Rate_Limiter\API\RateLimiter
 */
class WordPress_RateLimiter extends Psr16RateLimiter {

	/**
	 * WordPress_RateLimiter constructor.
	 *
	 * TODO: $key_prefix is currently being used to prefix the transient name in two place, i.e. twice / double prefixed.
	 *
	 * @param string $key_prefix The cache pool name and keyprefix.
	 */
	public function __construct( string $key_prefix = '' ) {

		global $wpdb;
		$factory   = new CachePoolFactory( $wpdb );
		$psr_cache = $factory->createCachePool( $key_prefix );

		parent::__construct( $psr_cache, $key_prefix );
	}

}
