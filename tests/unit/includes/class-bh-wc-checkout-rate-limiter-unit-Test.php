<?php
/**
 * @package BH_WC_Checkout_Rate_Limiter_Unit_Name
 * @author  BrianHenryIE <BrianHenryIE@gmail.com>
 */

namespace BrianHenryIE\Checkout_Rate_Limiter\Includes;

use BrianHenryIE\Checkout_Rate_Limiter\Admin\Plugins_Page;
use BrianHenryIE\Checkout_Rate_Limiter\Admin\Admin;
use BrianHenryIE\Checkout_Rate_Limiter\WooCommerce\Ajax;
use BrianHenryIE\Checkout_Rate_Limiter\API\Settings_Interface;
use Psr\Log\NullLogger;
use BrianHenryIE\Checkout_Rate_Limiter\WooCommerce\Settings_Advanced;
use WP_Mock\Matcher\AnyInstance;

/**
 * Class BH_WC_Checkout_Rate_Limiter_Unit_Test
 */
class BH_WC_Checkout_Rate_Limiter_Unit_Test extends \Codeception\Test\Unit {

	protected function _before() {
		\WP_Mock::setUp();
	}

	// This is required for `'times' => 1` to be verified.
	protected function _tearDown() {
		parent::_tearDown();
		\WP_Mock::tearDown();
	}

	/**
	 * @covers BrianHenryIE\Checkout_Rate_Limiter\Includes\BH_WC_Checkout_Rate_Limiter::set_locale
	 */
	public function test_set_locale_hooked() {

		\WP_Mock::expectActionAdded(
			'plugins_loaded',
			array( new AnyInstance( I18n::class ), 'load_plugin_textdomain' )
		);

		$settings = $this->makeEmpty( Settings_Interface::class );
		$logger   = new NullLogger();

		new BH_WC_Checkout_Rate_Limiter( $settings, $logger );
	}

	/**
	 * @covers BrianHenryIE\Checkout_Rate_Limiter\Includes\BH_WC_Checkout_Rate_Limiter::define_woocommerce_ajax_hooks
	 */
	public function test_woocommerce_ajax_hooks() {

		\WP_Mock::expectActionAdded(
			'wc_ajax_checkout',
			array( new AnyInstance( Ajax::class ), 'rate_limit_checkout' ),
			0
		);

		$settings = $this->makeEmpty( Settings_Interface::class );
		$logger   = new NullLogger();

		new BH_WC_Checkout_Rate_Limiter( $settings, $logger );
	}

	/**
	 * @covers BrianHenryIE\Checkout_Rate_Limiter\Includes\BH_WC_Checkout_Rate_Limiter::define_woocommerce_settings_hooks
	 */
	public function test_woocommerce_settings_hooks() {

		\WP_Mock::expectFilterAdded(
			'woocommerce_get_sections_advanced',
			array( new AnyInstance( Settings_Advanced::class ), 'add_section' ),
		);

		\WP_Mock::expectFilterAdded(
			'woocommerce_get_settings_advanced',
			array( new AnyInstance( Settings_Advanced::class ), 'settings' ),
			10,
			2
		);

		\WP_Mock::expectActionAdded(
			'woocommerce_admin_field_attempts_per_interval',
			array( new AnyInstance( Settings_Advanced::class ), 'print_attempts_per_interval_settings_field' ),
		);

		$settings = $this->makeEmpty( Settings_Interface::class );
		$logger   = new NullLogger();

		new BH_WC_Checkout_Rate_Limiter( $settings, $logger );
	}

	/**
	 * @covers BrianHenryIE\Checkout_Rate_Limiter\Includes\BH_WC_Checkout_Rate_Limiter::define_plugins_page_hooks
	 */
	public function test_plugins_page_hooks() {

		$plugin_basename = 'bh-wc-checkout-rate-limiter/bh-wc-checkout-rate-limiter.php';

		\WP_Mock::expectFilterAdded(
			"plugin_action_links_{$plugin_basename}",
			array( new AnyInstance( Plugins_Page::class ), 'action_links' )
		);

		$settings = $this->makeEmpty(
			Settings_Interface::class,
			array(
				'get_plugin_basename' => 'bh-wc-checkout-rate-limiter/bh-wc-checkout-rate-limiter.php',
			)
		);
		$logger   = new NullLogger();

		new BH_WC_Checkout_Rate_Limiter( $settings, $logger );
	}


	/**
	 * @covers BrianHenryIE\Checkout_Rate_Limiter\Includes\BH_WC_Checkout_Rate_Limiter::define_admin_hooks
	 */
	public function test_admin_hooks() {

		\WP_Mock::expectActionAdded(
			'admin_init',
			array( new AnyInstance( Admin::class ), 'add_setup_notice' ),
		);

		\WP_Mock::expectActionAdded(
			'plugins_loaded',
			array( new AnyInstance( Admin::class ), 'init_notices' ),
		);

		$settings = $this->makeEmpty( Settings_Interface::class );
		$logger   = new NullLogger();

		new BH_WC_Checkout_Rate_Limiter( $settings, $logger );
	}

}

