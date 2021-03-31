<?php
/**
 * Implementation of nikolaposa/rate-limit to allow using a PSR-16 cache for storage.
 *
 * @see https://github.com/nikolaposa/rate-limit
 * @see https://www.php-fig.org/psr/psr-16/
 *
 * I think this should maybe be part of the RateLimit library, hence the PHPCS rules are disabled and the licence
 * is MIT.
 *
 * @license    MIT
 * @author     BrianHenryIE <BrianHenryIE@gmail.com>
 * @link       https://BrianHenryIE.com
 */

declare(strict_types=1);

namespace BrianHenryIE\Checkout_Rate_Limiter\API\RateLimiter;

use BrianHenryIE\Checkout_Rate_Limiter\Psr\SimpleCache\CacheInterface;
use BrianHenryIE\Checkout_Rate_Limiter\Psr\SimpleCache\InvalidArgumentException;
use BrianHenryIE\Checkout_Rate_Limiter\RateLimit\Exception\LimitExceeded;
use BrianHenryIE\Checkout_Rate_Limiter\RateLimit\RateLimiter;
use BrianHenryIE\Checkout_Rate_Limiter\RateLimit\SilentRateLimiter;
use BrianHenryIE\Checkout_Rate_Limiter\RateLimit\Rate;
use BrianHenryIE\Checkout_Rate_Limiter\RateLimit\Status;
use function time;

/**
 * Class Psr16RateLimiter
 *
 * @package BrianHenryIE\Checkout_Rate_Limiter\API\RateLimiter
 */
class Psr16RateLimiter implements RateLimiter, SilentRateLimiter {

	protected CacheInterface $psrCache;

	/** @var string */
	protected $keyPrefix;

	public function __construct( CacheInterface $psrCache, string $keyPrefix = '' ) {

		$this->keyPrefix = $keyPrefix;
		$this->psrCache  = $psrCache;
	}

	public function limit( string $identifier, Rate $rate ): void {
		$key = $this->key( $identifier, $rate->getInterval() );

		$current = $this->getCurrentCount( $key );

		if ( $current >= $rate->getOperations() ) {
			throw LimitExceeded::for( $identifier, $rate );
		}

		$this->updateCounter( $key, $rate->getInterval() );
	}

	public function limitSilently( string $identifier, Rate $rate ): Status {
		$key = $this->key( $identifier, $rate->getInterval() );

		$current = $this->updateCounter( $key, $rate->getInterval() );

		return Status::from(
			$identifier,
			$current,
			$rate->getOperations(),
			time() + $rate->getInterval()
		);
	}

	/**
	 * The key includes the interval so multiple intervals:incidents can be counted against the one identifier.
	 * e.g. it can happen five times in one minute but no more then ten times in one hour.
	 *
	 * @param string $identifier
	 * @param int    $interval
	 * @return string
	 */
	protected function key( string $identifier, int $interval ): string {
		return "{$this->keyPrefix}{$identifier}:$interval";
	}

	/**
	 * Return a count of unexpired records for the key.
	 *
	 * @param string $key
	 * @return int
	 */
	protected function getCurrentCount( string $key ): int {
		$stored_values = $this->getCurrentStoredCounter( $key );

		return count( $stored_values );
	}

	/**
	 * @param string $key
	 * @return array<int, array{key: string, created_at: int, expires_at: int, interval :int}>
	 */
	protected function getCurrentStoredCounter( string $key ): array {

		try {
			$stored_values = $this->psrCache->get( $key, array() );
		} catch ( InvalidArgumentException $e ) {
			$stored_values = array();
		}

		foreach ( $stored_values as $created_time => $value ) {

			if ( isset( $value['expires_at'] ) && $value['expires_at'] < time() ) {
				unset( $stored_values[ $created_time ] );
			}
		}

		return $stored_values;
	}

	protected function updateCounter( string $key, int $interval ): int {

		$stored_values = $this->getCurrentStoredCounter( $key );

		$created_time = time();
		$expires_at   = $created_time + $interval;

		$stored_values[ $created_time ] = array(
			'key'          => $key,
			'created_time' => $created_time,
			'expires_at'   => $expires_at,
			'interval'     => $interval,
		);

		$this->psrCache->set( $key, $stored_values, $interval );

		return count( $stored_values );
	}

}
