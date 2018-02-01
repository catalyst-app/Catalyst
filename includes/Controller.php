<?php

define("DEVEL",true);
define("VERSION","0.0.1~alpha");

spl_autoload_register(function ($name) {
	if (strpos($name, 'Catalyst\\') === 0) {
		$unNamespaced = str_replace('Catalyst\\', '', $name);
		$toPath = str_replace('\\', DIRECTORY_SEPARATOR, $unNamespaced).".php";
		if (file_exists(__DIR__.DIRECTORY_SEPARATOR.$toPath)) {
			require_once __DIR__.DIRECTORY_SEPARATOR.$toPath;
namespace Catalyst;

use \Catalyst\{Email, HTTPCode};
use \Catalyst\API\{Endpoint, Response};
use \Exception;

/**
 * Generic controller which handles things like error logging, autoloading, etc.
 */
class Controller {
	const DEVEL = true;
	const VERSION = "0.0.1~alpha";

		}
	}
});

	/**
	 * Return if the platform is in development mode
	 * 
	 * @return bool If development mode is on
	 */
	public static function isDevelMode() : bool {
		return self::DEVEL;
	}

	/**
	 * Return the current version
	 * 
	 * @return string Version identifier
	 */
	public static function getVersion() : string {
		return self::VERSION;
	}
}

// LEGACY
require_once __DIR__."/Secrets.php";
require_once __DIR__."/Database/Connector.inc.php";

require_once __DIR__."/vendor/autoload.php";

if (!session_id()) {
	ini_set("session.cookie_lifetime", 24*60*60);
	ini_set("session.gc_maxlifetime", 24*60*60);
	ini_set("session.name", "catalyst");
	session_start();
}
