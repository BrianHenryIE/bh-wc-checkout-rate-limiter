<?php
/**
 * Loads all required classes
 *
 * Uses classmap, PSR4 & wp-namespace-autoloader.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           brianhenryie/bh-wc-checkout-rate-limiter
 *
 * @see https://github.com/pablo-sg-pacheco/wp-namespace-autoloader/
 */

namespace BrianHenryIE\Checkout_Rate_Limiter;

use BrianHenryIE\Checkout_Rate_Limiter\Alley_Interactive\Autoloader\Autoloader;

require_once __DIR__ . '/vendor-prefixed/autoload.php';

Autoloader::generate(
	'BrianHenryIE\Checkout_Rate_Limiter',
	__DIR__ . '/src',
)->register();
