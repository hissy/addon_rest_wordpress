<?php
defined('C5_EXECUTE') or die(_("Access Denied."));

class RestWordpressPackage extends Package {

	protected $pkgHandle = 'rest_wordpress';
	protected $appVersionRequired = '5.6.3';
	protected $pkgVersion = '0.1.1';
	
	public function getPackageDescription() {
		return t("REST WordPress");
	}
	
	public function getPackageName() {
		return t("REST WordPress");
	}
	
	public function install() {
		$pkg = parent::install();
		$ci = new ContentImporter();
		$ci->importContentFile($pkg->getPackagePath() . '/config/install.xml');
	}
	
	public function on_start() {
		ini_set('include_path', DIR_PACKAGES . '/rest_wordpress/libraries/3rdparty' . PATH_SEPARATOR . ini_get('include_path'));
		Loader::library('3rdparty/Zend/Oauth/Consumer','rest_wordpress');
		Loader::library('3rdparty/Zend/Crypt/Hmac','rest_wordpress');
	}

}