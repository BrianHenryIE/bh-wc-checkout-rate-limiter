<?php
/**
 * @package BH_WC_Checkout_Rate_Limiter_Unit_Name
 * @author  BrianHenryIE <BrianHenryIE@gmail.com>
 */

namespace BH_WC_Checkout_Rate_Limiter\Includes;

use BH_WC_Checkout_Rate_Limiter\Admin\Admin;
use BH_WC_Checkout_Rate_Limiter\Frontend\Frontend;
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
	 * @covers BH_WC_Checkout_Rate_Limiter::set_locale
	 */
	public function test_set_locale_hooked() {

		\WP_Mock::expectActionAdded(
			'plugins_loaded',
			array( new AnyInstance( I18n::class ), 'load_plugin_textdomain' )
		);

		new BH_WC_Checkout_Rate_Limiter();
	}

	/**
	 * @covers BH_WC_Checkout_Rate_Limiter::define_admin_hooks
	 */
	public function test_admin_hooks() {

		\WP_Mock::expectActionAdded(
			'admin_enqueue_scripts',
			array( new AnyInstance( Admin::class ), 'enqueue_styles' )
		);

		\WP_Mock::expectActionAdded(
			'admin_enqueue_scripts',
			array( new AnyInstance( Admin::class ), 'enqueue_scripts' )
		);

		new BH_WC_Checkout_Rate_Limiter();
	}

	/**
	 * @covers BH_WC_Checkout_Rate_Limiter::define_frontend_hooks
	 */
	public function test_frontend_hooks() {

		\WP_Mock::expectActionAdded(
			'wp_enqueue_scripts',
			array( new AnyInstance( Frontend::class ), 'enqueue_styles' )
		);

		\WP_Mock::expectActionAdded(
			'wp_enqueue_scripts',
			array( new AnyInstance( Frontend::class ), 'enqueue_scripts' )
		);

		new BH_WC_Checkout_Rate_Limiter();
	}

}
