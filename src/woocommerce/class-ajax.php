<?php
/**
 * Code to run on WooCommerce AJAX checkout.
 *
 * When "Place Order" is clicked, this should record the user's IP address and check they have not placed too many
 * orders recently.
 *
 * @author     BrianHenryIE <BrianHenryIE@gmail.com>
 * @link       https://BrianHenryIE.com
 * @since      1.0.0
 * @package brianhenryie/bh-wc-checkout-rate-limiter
 */

namespace BrianHenryIE\Checkout_Rate_Limiter\WooCommerce;

use BrianHenryIE\Checkout_Rate_Limiter\RateLimit\Rate;
use BrianHenryIE\Checkout_Rate_Limiter\Settings_Interface;
use BrianHenryIE\Checkout_Rate_Limiter\WP_Rate_Limiter\WordPress_Rate_Limiter;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

/**
 * Hooked on wc_ajax_checkout earlier than WooCommerce's own processing code.
 *
 * @see WordPress_RateLimiter
 *
 * Class Ajax
 * @package brianhenryie/bh-wc-checkout-rate-limiter
 */
class Ajax {

	use LoggerAwareTrait;

	/**
	 * The plugin's settings.
	 *
	 * @var Settings_Interface
	 */
	protected Settings_Interface $settings;

	/**
	 * Instantiate.
	 *
	 * @param Settings_Interface $settings The plugin settings.
	 * @param LoggerInterface    $logger PSR logger.
	 */
	public function __construct( Settings_Interface $settings, LoggerInterface $logger ) {
		$this->settings = $settings;
		$this->setLogger( $logger );
	}

	/**
	 * On rate limit exceeded, return a 429 JSON error to the client.
	 * On success, function returns so the checkout can be processed as normal.
	 *
	 * No `Retry-After` header is added, since this is targeted at the WooCommerce AJAX checkout.
	 *
	 * @hooked wc_ajax_checkout
	 */
	public function rate_limit_checkout(): void {

		if ( ! $this->settings->is_enabled() ) {
			$this->logger->debug( 'Not enabled / no limits set' );
			return;
		}

		$limits = $this->settings->get_checkout_rate_limits();

		if ( empty( $limits ) ) {
			$this->logger->debug( 'No limits set' );
			return;
		}

		$ip_address = \WC_Geolocation::get_ip_address();

		foreach ( $limits as $interval => $allowed_access_count ) {

			$this->logger->debug( "Checking {$ip_address} rate limit {$allowed_access_count} per {$interval} seconds." );

			$rate = Rate::custom( $allowed_access_count, $interval );

			$rate_limiter = new WordPress_Rate_Limiter( $rate, 'checkout' );

			try {
				$status = $rate_limiter->limitSilently( $ip_address );
			} catch ( \RuntimeException $e ) {
				$this->logger->error(
					'Rate Limiter encountered an error when storing the access count.',
					array(
						'exception' => $e,
					)
				);
				// The behaviour here on an error is to NOT rate-limit.
				return;
			}

			/**
			 * TODO: Log the $_REQUEST data (but remove credit card details).
			 *
			 * @see WC_Checkout::get_posted_data()
			 */
			if ( $status->limitExceeded() ) {

				$this->logger->notice(
					"{$ip_address} blocked with {$status->getRemainingAttempts()} remaining attempts for rate limit {$allowed_access_count} per {$interval} seconds.",
					array(
						'interval'             => $interval,
						'allowed_access_count' => $allowed_access_count,
						'status'               => $status,
						'ip_address'           => $ip_address,
					)
				);

				// No real point adding headers here.
				wp_send_json_error( null, 429 );
			} else {

				$this->logger->debug(
					"{$ip_address} allowed with {$status->getRemainingAttempts()} remaining attempts for rate limit {$allowed_access_count} per {$interval} seconds.",
					array(
						'interval'             => $interval,
						'allowed_access_count' => $allowed_access_count,
						'status'               => $status,
					)
				);
			}
		}

	}

}
