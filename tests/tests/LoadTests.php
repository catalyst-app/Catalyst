<?php

require_once __DIR__.'/CatalystWebdriverTestCase.php';
require_once __DIR__.'/FooterTestTrait.php';
require_once __DIR__.'/HasCaptchaTrait.php';
require_once __DIR__.'/TrailingSlashTestTrait.php';

require_once __DIR__.'/../../includes/init.php';
$GLOBALS["base"] = "http://localhost/Catalyst/";
$GLOBALS["ignoreConsole"] = ["/".preg_quote("favicon.ico - Failed to load resource:", "/")."/"];

$GLOBALS["captchaAlertNotClosed"] = false;

$capabilities = \Facebook\WebDriver\Remote\DesiredCapabilities::chrome();
$GLOBALS["driver"] = \Facebook\WebDriver\Remote\RemoteWebDriver::create('http://localhost:4444/wd/hub', $capabilities);

register_shutdown_function(function() {
	$GLOBALS["driver"]->quit();
});
