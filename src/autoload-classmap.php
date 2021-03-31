<?php
/**
 * To autoload the Psr16RateLimiter class.
 *
 * @link              https://BrianHenryIE.com
 * @since             1.0.0
 * @package           BH_WC_Checkout_Rate_Limiter
 */

use BrianHenryIE\Checkout_Rate_Limiter\API\RateLimiter\Psr16RateLimiter;

return array(
	Psr16RateLimiter::class => __DIR__ . '/API/RateLimiter/Psr16RateLimiter.php',
);
