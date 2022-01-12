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

use BrianHenryIE\Checkout_Rate_Limiter\RateLimit\Psr16RateLimiter;
use BrianHenryIE\Checkout_Rate_Limiter\RateLimit\Rate;
use BrianHenryIE\Checkout_Rate_Limiter\WpOop\TransientCache\CachePool;
use BrianHenryIE\Checkout_Rate_Limiter\WpOop\TransientCache\CachePoolFactory;
use Exception;

/**
 * Class WordPress_RateLimiter
 *
 * @package BrianHenryIE\Checkout_Rate_Limiter\API\RateLimiter
 */
class WordPress_RateLimiter extends Psr16RateLimiter {

	/**
	 * WordPress_RateLimiter constructor.
	 *
	 * TODO: $key_prefix is currently being used to prefix the transient name in two places, i.e. twice / double prefixed.
	 *
	 * @param string $key_prefix The cache pool name and keyprefix.
	 */
	public function __construct( Rate $rate, string $key_prefix = '' ) {

		global $wpdb;
		$factory   = new CachePoolFactory( $wpdb );
		$psr_cache = $factory->createCachePool( $key_prefix );

		parent::__construct( $rate, $psr_cache, $key_prefix );
	}

	/**
	 * The key includes the interval so multiple intervals:incidents can be counted against the one identifier.
	 * e.g. it can happen five times in one minute but no more then ten times in one hour.
	 *
	 * The default key implementation, and instances such as IPv6 as keys, uses `:` character which is reserved
	 * by wp-oop/transient-cache.
	 *
	 * @see https://github.com/wp-oop/transient-cache/blob/94b21321867dfb82eda7fe2ab962895c939f446d/src/CachePool.php#L38
	 *
	 * @since 1.0.1
	 *
	 * @param string $identifier The identifier: who/what is the thing being rate limited.
	 * @return string
	 * @throws Exception If escaping the key fails.
	 */
	protected function key( string $identifier ): string {
		$parent_key = parent::key( $identifier );
		return $this->escape_key( $parent_key );
	}

	/**
	 * Replaces all reserved characters with hyphens.
	 *
	 * @since 1.0.1
	 *
	 * @param string $key Key to escape.
	 * @return string
	 * @throws Exception Escaping function preg_replace can return null.
	 */
	protected function escape_key( string $key ): string {
		$reserved_characters = CachePool::RESERVED_KEY_SYMBOLS;
		$key                 = preg_replace( '#[' . preg_quote( $reserved_characters, '\\' ) . ']#', '-', $key );

		if ( is_null( $key ) ) {
			throw new Exception( "Error escaping key: {$key}" );
		}

		return $key;
	}
}
