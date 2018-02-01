<?php

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

	/**
	 * Autoloads classes, will be registered with spl_autoload_register
	 * 
	 * @param string $name Name of class to attempt to load
	 */
	public static function loadClass(string $name) : void {
		if (strpos($name, 'Catalyst\\') === 0) {
			$unNamespaced = str_replace('Catalyst\\', '', $name);
			$toPath = str_replace('\\', DIRECTORY_SEPARATOR, $unNamespaced).".php";
			if (file_exists(__DIR__.DIRECTORY_SEPARATOR.$toPath)) {
				require_once __DIR__.DIRECTORY_SEPARATOR.$toPath;
			}
		}
	}

	/**
	 * Generate a pretty trace - MAY CONTAIN SENSITIVE INFO
	 * 
	 * @param bool $includeParams Whether or not to include function args
	 * @return string[] pretty traceback
	 */
	public static function getTrace(bool $includeParams=true) : array {
		$trace = (new Exception())->getTrace();

		$result = [];
		foreach ($trace as $row) {
			$item = "";
			if (array_key_exists("line", $row)) {
				$item .= "(".$row["line"].") ";
			}
			if (array_key_exists("file", $row)) {
				$item .= $row["file"].", ";
			}
			if (array_key_exists("class", $row)) {
				$item .= $row["class"];
			}
			if (array_key_exists("type", $row)) {
				$item .= $row["type"];
			}
			if (array_key_exists("function", $row)) {
				$item .= $row["function"];
			}
			if (array_key_exists("args", $row) && $includeParams) {
				$item .= "(";
				foreach ($row["args"] as $key => $arg) {
					try {
						$item .= $key." ".serialize($arg);
					} catch (Exception $e) {
						$item .= $key.": non-serializable";
					}
				}
				$item .= ")";
			}
			$result[] = $item;
		}

		return $result;
	}

	/**
	 * Sends an email reporting the error
	 * 
	 * @param string $subj Subject message (include halt or similar)
	 * @param string $errco Error code
	 * @param int $errno Error number/code
	 * @param string $errstr Error string/message
	 * @param string $errfile File the error occured in
	 * @param int $errline Line the error occured on
	 */
	public static function sendErrorEmail(string $subj, string $errco, int $errno, string $errstr, string $errfile, int $errline) {
		Email::sendEmail(
			[["error_logs@catalystapp.co","Error Log"]],
			$subj." occured in ".$errfile." at ".$errline.": ".$errstr,
			'<p><strong>Error code:</strong> '.$errco.' ('.$errno.')</p>'.
			'<p><strong>Error string:</strong> '.htmlspecialchars($errstr).'</p>'.
			'<p><strong>Error file:</strong> '.htmlspecialchars($errfile).'</p>'.
			'<p><strong>Error line:</strong> '.$errline.'</p>'.
			'<p><strong>Trace:</strong></p>'.
			'<p>'.implode('</p><p>',array_map("htmlspecialchars",self::getTrace())).'</p>'.
			'<p><strong>Dump:</strong> <pre>'.htmlspecialchars(serialize($_SERVER)).'</pre></p>',
			"Error code: ".$errco." (".$errno.")\r\n\r\n".
			"Error string: ".$errstr."\r\n\r\n".
			"Error file: ".$errfile."\r\n\r\n".
			"Error line: ".$errline."\r\n\r\n".
			"Trace: \r\n\r\n".
			implode("\r\n\r\n",self::getTrace())."\r\n\r\n".
			"Dump: ".serialize($_SERVER),
			Email::ERROR_LOG_EMAIL,
			Email::ERROR_LOG_PASSWORD
		);
	}

	/**
	 * set_error_handler function
	 * 
	 * @param int $errno Error number/code
	 * @param string $errstr Error string/message
	 * @param string $errfile File the error occured in
	 * @param int $errline Line the error occured on
	 * @return bool Whether or not the handler worked
	 */
	public static function handleError(int $errno, string $errstr, string $errfile, int $errline) : bool {
		switch ($errno) {
			case E_ERROR:
				self::sendErrorEmail("Halting E_ERROR", "E_ERROR", $errno, $errstr, $errfile, $errline);
				HTTPCode::set(500);
				if (Endpoint::isApi()) {
					Response::sendErrorResponse(99999, "An unknown error occured", [$errno,$errstr,$errfile,$errline]);
				}
				die("An unknown fatal error has occured.  This has been reported to the developer team and we are working hard to fix it!");
			case E_WARNING:
				self::sendErrorEmail("Halting E_WARNING", "E_WARNING", $errno, $errstr, $errfile, $errline);
				HTTPCode::set(500);
				if (Endpoint::isApi()) {
					Response::sendErrorResponse(99999, "An unknown error occured", [$errno,$errstr,$errfile,$errline]);
				}
				die("An unknown error has occured.  This has been reported to the developer team and we are working hard to fix it!");
			case E_PARSE:
				self::sendErrorEmail("Halting E_PARSE", "E_PARSE", $errno, $errstr, $errfile, $errline);
				HTTPCode::set(500);
				if (Endpoint::isApi()) {
					Response::sendErrorResponse(99999, "An unknown error occured", [$errno,$errstr,$errfile,$errline]);
				}
				die("An unknown error has occured.  This has been reported to the developer team and we are working hard to fix it!");
			case E_NOTICE:
				self::sendErrorEmail("E_NOTICE", "E_NOTICE", $errno, $errstr, $errfile, $errline);
				return true;
			case E_CORE_ERROR:
				self::sendErrorEmail("Halting E_CORE_ERROR", "E_CORE_ERROR", $errno, $errstr, $errfile, $errline);
				HTTPCode::set(500);
				if (Endpoint::isApi()) {
					Response::sendErrorResponse(99999, "An unknown error occured", [$errno,$errstr,$errfile,$errline]);
				}
				die("An unknown error has occured.  This has been reported to the developer team and we are working hard to fix it!");
			case E_CORE_WARNING:
				self::sendErrorEmail("Halting E_CORE_WARNING", "E_CORE_WARNING", $errno, $errstr, $errfile, $errline);
				HTTPCode::set(500);
				if (Endpoint::isApi()) {
					Response::sendErrorResponse(99999, "An unknown error occured", [$errno,$errstr,$errfile,$errline]);
				}
				die("An unknown error has occured.  This has been reported to the developer team and we are working hard to fix it!");
			case E_COMPILE_ERROR:
				self::sendErrorEmail("Halting E_COMPILE_ERROR", "E_COMPILE_ERROR", $errno, $errstr, $errfile, $errline);
				HTTPCode::set(500);
				if (Endpoint::isApi()) {
					Response::sendErrorResponse(99999, "An unknown error occured", [$errno,$errstr,$errfile,$errline]);
				}
				die("An unknown error has occured.  This has been reported to the developer team and we are working hard to fix it!");
			case E_COMPILE_WARNING:
				self::sendErrorEmail("Halting E_COMPILE_WARNING", "E_COMPILE_WARNING", $errno, $errstr, $errfile, $errline);
				HTTPCode::set(500);
				if (Endpoint::isApi()) {
					Response::sendErrorResponse(99999, "An unknown error occured", [$errno,$errstr,$errfile,$errline]);
				}
				die("An unknown error has occured.  This has been reported to the developer team and we are working hard to fix it!");
			case E_USER_ERROR:
				self::sendErrorEmail("Halting E_USER_ERROR", "E_USER_ERROR", $errno, $errstr, $errfile, $errline);
				HTTPCode::set(500);
				if (Endpoint::isApi()) {
					Response::sendErrorResponse(99999, "An unknown error occured", [$errno,$errstr,$errfile,$errline]);
				}
				die("An unknown error has occured.  This has been reported to the developer team and we are working hard to fix it!");
			case E_USER_WARNING:
				self::sendErrorEmail("Halting E_USER_WARNING", "E_USER_WARNING", $errno, $errstr, $errfile, $errline);
				HTTPCode::set(500);
				if (Endpoint::isApi()) {
					Response::sendErrorResponse(99999, "An unknown error occured", [$errno,$errstr,$errfile,$errline]);
				}
				die("An unknown error has occured.  This has been reported to the developer team and we are working hard to fix it!");
			case E_USER_NOTICE:
				self::sendErrorEmail("E_USER_NOTICE (API misuse?)", "E_USER_NOTICE", $errno, $errstr, $errfile, $errline);
				return true;
			case E_STRICT:
				self::sendErrorEmail("E_STRICT", "E_STRICT", $errno, $errstr, $errfile, $errline);
				return true;
			case E_RECOVERABLE_ERROR:
				self::sendErrorEmail("Halting E_RECOVERABLE_ERROR", "E_RECOVERABLE_ERROR", $errno, $errstr, $errfile, $errline);
				HTTPCode::set(500);
				if (Endpoint::isApi()) {
					Response::sendErrorResponse(99999, "An unknown error occured", [$errno,$errstr,$errfile,$errline]);
				}
				die("An unknown error has occured.  This has been reported to the developer team and we are working hard to fix it!");
			case E_DEPRECATED:
				self::sendErrorEmail("E_DEPRECATED", "E_DEPRECATED", $errno, $errstr, $errfile, $errline);
				return true;
			case E_USER_DEPRECATED:
				self::sendErrorEmail("E_USER_DEPRECATED", "E_USER_DEPRECATED", $errno, $errstr, $errfile, $errline);
				return true;
			default:
				self::sendErrorEmail("Halting unknown (".$error_code.")", "unknown ('.$errno.')", $errno, $errstr, $errfile, $errline);
				HTTPCode::set(500);
				if (Endpoint::isApi()) {
					Response::sendErrorResponse(99999, "An unknown error occured", [$errno,$errstr,$errfile,$errline]);
				}
				die("An unknown error has occured.  This has been reported to the developer team and we are working hard to fix it!");
		}
		return true;
	}

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

spl_autoload_register("\\Catalyst\\Controller::loadClass");

// Choose what error handling we want to do

if (Controller::isDevelMode()) {
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
} else {
	error_reporting(0);
	ini_set('display_errors', 0);
	ini_set('display_startup_errors', 0);
}

set_error_handler("\\Catalyst\\Controller::handleError", E_ALL);

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
