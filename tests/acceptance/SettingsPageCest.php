<?php

class SettingsPageCest {


	/**
	 * Login.
	 *
	 * @param AcceptanceTester $I The Codeception actor class.
	 */
	public function _before( AcceptanceTester $I ) {
		$I->loginAsAdmin();

	}

	/**
	 * Verify the settings link is present on the WooCommerce / Settings / Payments page.
	 *
	 * @param AcceptanceTester $I The Codeception actor class.
	 */
	public function testPluginsPageForName( AcceptanceTester $I ) {

		$I->amOnAdminPage( 'admin.php?page=wc-settings&tab=checkout' );

		$I->canSee( 'Rate Limiting' );
	}


}
