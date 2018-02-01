<?php

define("DEVEL",true);
define("VERSION","0.0.1~alpha");

spl_autoload_register(function ($name) {
	if (strpos($name, 'Catalyst\\') === 0) {
		$unNamespaced = str_replace('Catalyst\\', '', $name);
		$toPath = str_replace('\\', DIRECTORY_SEPARATOR, $unNamespaced).".php";
		if (file_exists(__DIR__.DIRECTORY_SEPARATOR.$toPath)) {
			require_once __DIR__.DIRECTORY_SEPARATOR.$toPath;
		}
	}
});

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
