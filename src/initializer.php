<?php

define("EXEC_START_TIME", microtime(true));

require_once __DIR__.DIRECTORY_SEPARATOR.'Controller.php';

spl_autoload_register("\\Catalyst\\Controller::loadClass");

use \Catalyst\Controller;
use \Catalyst\User\User;

// Choose what error handling we want to do
if (Controller::isDevelMode()) {
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	ini_set('scream.enabled', 1);
} else {
	error_reporting(0);
	ini_set('display_errors', 0);
	ini_set('display_startup_errors', 0);
	ini_set('scream.enabled', 0);
}

if (php_sapi_name() !== 'cli') {
	ob_start();

	header("X-Catalyst-Version: ".Controller::getVersion()." (".Controller::getCommit().")", true);
}

register_shutdown_function("\\Catalyst\\Controller::shutdown");
set_error_handler("\\Catalyst\\Controller::handleError", E_ALL);
set_exception_handler("\\Catalyst\\Controller::handleException");

require_once __DIR__."/vendor/autoload.php";

Controller::verifySecretsIntegrity();

if (!session_id() && !defined("NO_SESSION")) {
	ini_set("session.name", "catalyst");
	ini_set("session.gc_maxlifetime", 24*60*60);
	ini_set("session.cookie_lifetime", 24*60*60);
	ini_set("session.cookie_httponly", true);
	if (array_key_exists("SERVER_NAME", $_SERVER) && $_SERVER["SERVER_NAME"] != "localhost") {
		ini_set("session.cookie_secure", true);
	}
	session_start([
		"name" => "catalyst",
		"gc_maxlifetime" => 24*60*60,
		"cookie_lifetime" => 24*60*60,
		"cookie_httponly" => true,
		"cookie_secure" => array_key_exists("SERVER_NAME", $_SERVER) && $_SERVER["SERVER_NAME"] != "localhost",
	]);
}

// remove pending_user if not actively logging in
if (!defined("NO_SESSION") && User::isPending2FA()) {
	if (strpos($_SERVER["SCRIPT_NAME"], "/api/internal/totp_login/") === false && 
		strpos($_SERVER["SCRIPT_NAME"], "/Login/TOTP/") === false &&
		strpos($_SERVER["SCRIPT_NAME"], "/css/") === false &&
		strpos($_SERVER["SCRIPT_NAME"], "/js/") === false) {
		unset($_SESSION["pending_user"]);
	}
}

if (User::isLoggedIn() && !$_SESSION["user"]->isApprovedBetaTester()) {
	trigger_error("Your account, ".$_SESSION["user"]->getNickname().", has not been activated.  The administrators have been notified and will do this as soon as possible.", E_USER_ERROR)
	unset($_SESSION["user"]);
}
