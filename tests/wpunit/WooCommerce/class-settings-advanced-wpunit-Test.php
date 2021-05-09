<?php

namespace BrianHenryIE\Checkout_Rate_Limiter\WooCommerce;

use BrianHenryIE\Checkout_Rate_Limiter\API\Settings_Interface;
use Psr\Log\NullLogger;
use \Exception;

/**
 * @covers BrianHenryIE\Checkout_Rate_Limiter\WooCommerce\Settings_Advanced
 *
 * Class Ajax_WPUnit_Test
 * @package BrianHenryIE\Checkout_Rate_Limiter\WooCommerce
 */
class Settings_Advanced_WPUnit_Test extends \Codeception\TestCase\WPTestCase {

    /**
     * When the settings page is first loaded, the values for the rates are not yet set, causing a PHP warning:
     *
     * `PHP Warning: Illegal string offset 'interval' in .../WooCommerce/class-settings-advanced.php on line 196`
     */
    public function test_first_run_empty_variables() {

        $settings = $this->makeEmpty( Settings_Interface::class );
        $logger = new NullLogger();

        $sut = new Settings_Advanced( $settings, $logger );

        $value = array(
            'value' => array(),
            'id' => '',
            'class' => '',
            'title' => '',
            'desc_tip' => '',
        );

        $exception = null;
        try {
            $sut->print_attempts_per_interval_settings_field($value);
        } catch ( \PHPUnit\Framework\Exception $e ) {
            $exception = $e;
        }

        $this->assertNull( $exception );

    }

}
