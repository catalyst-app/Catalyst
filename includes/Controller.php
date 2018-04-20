<?php

namespace Catalyst;

use \Catalyst\{Email, HTTPCode};
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Exception;
use \ReflectionClass;
use \Throwable;

/**
 * Generic controller which handles things like error logging, autoloading, etc.
 */
class Controller {
	const DEVEL = true;
	const VERSION = "0.0.1~alpha";
	const APRIL_FOOLS = true;

	/**
	 * Current commit
	 * @var null|string
	 */
	protected static $commit = null;

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
		} elseif ($name === 'QRcode') {
			require_once __DIR__.DIRECTORY_SEPARATOR."phpqrcode.php";
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
			if (array_key_exists("file", $row) && $row["file"] == realpath(__FILE__)) {
				continue;
			}
			$item = "";
			if (array_key_exists("file", $row)) {
				$item .= basename($row["file"])."";
			}
			if (array_key_exists("line", $row)) {
				$item .= ":".$row["line"];
			}
			$item .= " called ";
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
						$item .= serialize($arg).", ";
					} catch (Exception $e) {
						$item .= "non-serializable".", ";
					}
				}
				$item .= ")";
			} else {
				$item .= "(...)";
			}
			$result[] = $item;
		}

		return $result;
	}

	/**
	 * Sends an email/discord webhook reporting the error
	 * 
	 * @param string $subj Subject message (include halt or similar)
	 * @param string $errco Error code
	 * @param int $errno Error number/code
	 * @param string $errstr Error string/message
	 * @param string $errfile File the error occured in
	 * @param int $errline Line the error occured on
	 * @param string $trackingId Unique ID for the error
	 */
	public static function sendErrorNotice(string $subj, string $errco, int $errno, string $errstr, string $errfile, int $errline, string $trackingId) : void {
		ob_start();
		$destinations = [];
		if (!array_key_exists("SERVER_NAME", $_SERVER) || $_SERVER["SERVER_NAME"] == "localhost") { // default to local reporting
			$destinations = ["discord","email","telegram"];
		} else {
			$destinations = ["discord","email","telegram"];
		}
		if (in_array("email", $destinations)) {
			$trace = self::getTrace();
			Email::sendEmail(
				[["error_logs@catalystapp.co","Error Log"]],
				$subj." occured in ".$errfile." at ".$errline.": ".$errstr,
				'<p><strong>Error code:</strong> '.$errco.' ('.$errno.')</p>'.
				'<p><strong>Error string:</strong> '.htmlspecialchars($errstr).'</p>'.
				'<p><strong>Error file:</strong> '.htmlspecialchars($errfile).'</p>'.
				'<p><strong>Error line:</strong> '.$errline.'</p>'.
				'<p><strong>Tracking ID:</strong> '.$trackingId.'</p>'.
				'<p><strong>Site User:</strong> '.((isset($_SESSION) && array_key_exists("user", $_SESSION)) ? $_SESSION['user']->getUsername() : 'not logged in').'</p>'.
				'<p><strong>Beta User:</strong> '.(array_key_exists('PHP_AUTH_USER', $_SERVER) ? $_SERVER['PHP_AUTH_USER'] : 'unknown').'</p>'.
				'<p><strong>Trace:</strong></p>'.
				'<p>'.implode('</p><p>',array_map("htmlspecialchars",$trace)).'</p>'.
				'<p><strong>Dump:</strong> <pre>'.htmlspecialchars(serialize([$_SERVER,$_SESSION])).'</pre></p>',
				"Error code: ".$errco." (".$errno.")\r\n\r\n".
				"Error string: ".$errstr."\r\n\r\n".
				"Error file: ".$errfile."\r\n\r\n".
				"Error line: ".$errline."\r\n\r\n".
				"Tracking ID: ".$trackingId."\r\n\r\n".
				'Site User: '.((isset($_SESSION) && array_key_exists("user", $_SESSION)) ? $_SESSION['user']->getUsername() : 'not logged in')."\r\n\r\n".
				'Beta User: '.(array_key_exists('PHP_AUTH_USER', $_SERVER) ? $_SERVER['PHP_AUTH_USER'] : 'unknown')."\r\n\r\n".
				"Trace: \r\n\r\n".
				implode("\r\n\r\n",$trace)."\r\n\r\n".
				"Dump: ".serialize([$_SERVER,$_SESSION]),
				Email::ERROR_LOG_EMAIL,
				Email::ERROR_LOG_PASSWORD
			);
		}

		$reflectedClass = new ReflectionClass(Secrets::class);
		$constants = $reflectedClass->getConstants();

		foreach ($constants as $value) {
			$errstr = str_replace($value, "{REDACTED}", $errstr);
		}

		if (in_array("discord", $destinations)) {
			$trace = self::getTrace(false);
			$traceEmbeds = [];
			foreach ($trace as $row) {
				$traceEmbeds[] = [
					"name" => "Trace:",
					"value" => $row
				];
			}

			try {
				file_get_contents("https://discordapp.com/api/webhooks/".Secrets::DISCORD_BUG_WEBHOOK_TOKEN, false, stream_context_create([
					"http" => [
						"method" => "POST",
						"ignore_errors" => true,
						"header" => "Content-Type: application/x-www-form-urlencoded",
						"content" => json_encode([
							"content" => $subj." occured",
							"embeds" => [
								[
									"title" => "An error occured",
									"fields" => array_merge([
										[
											"name" => 'Error code',
											"value" => $errco.' ('.$errno.')'
										],
										[
											"name" => 'Error string',
											"value" => str_replace("<br>", "\r\n", $errstr)
										],
										[
											"name" => 'Error file',
											"value" => basename($errfile)
										],
										[
											"name" => 'Error line',
											"value" => $errline
										],
										[
											"name" => 'Tracking ID',
											"value" => $trackingId
										],
										[
											"name" => 'Site User',
											"value" => ((isset($_SESSION) && array_key_exists("user", $_SESSION)) ? $_SESSION['user']->getUsername() : 'not logged in')
										],
										[
											"name" => 'Beta User',
											"value" => (array_key_exists('PHP_AUTH_USER', $_SERVER) ? $_SERVER['PHP_AUTH_USER'] : 'unknown')
										],
									], $traceEmbeds),
									"description" => "Please see the embed fields"
								]
							]
						])
					]
				]));
			} catch (Exception $e) {} // couldnt reach discord
		}
		if (in_array("telegram", $destinations)) {
			try {
				$trace = self::getTrace(false);
				$telegramStr = $subj." occured\n\n";
				$telegramStr .= "<b>Code:</b> ".$errco." (".$errno.")\n";
				$telegramStr .= "<b>Error:</b> ".str_replace("<br>", "\r\n", $errstr)."\n";
				$telegramStr .= "<b>File:</b> ".basename($errfile)."\n";
				$telegramStr .= "<b>Line:</b> ".$errline."\n";
				$telegramStr .= "<b>Tracking ID:</b> ".$trackingId."\n";
				$telegramStr .= "<b>Site User:</b> ".((isset($_SESSION) && array_key_exists("user", $_SESSION)) ? $_SESSION['user']->getUsername() : 'not logged in')."\n";
				$telegramStr .= "<b>Beta User:</b> ".(array_key_exists('PHP_AUTH_USER', $_SERVER) ? $_SERVER['PHP_AUTH_USER'] : 'unknown')."\n";
				$telegramStr .= "<b>Trace:</b>\n";
				foreach ($trace as $row) {
					$telegramStr .= $row .= "\n";
				}
				file_get_contents("https://api.telegram.org/bot".Secrets::TELEGRAM_TOKEN."/sendMessage?chat_id=".Secrets::TELEGRAM_CHAT."&parse_mode=HTML&text=".urlencode($telegramStr), false, stream_context_create([
					"http" => [
						"ignore_errors" => true,
					]
				]));
			} catch (Exception $e) {} // cant react telegram
		}
		ob_end_clean();
	}

	/**
	 * Will send 500 and JSON error IF an endpoint is being accessed
	 * 
	 * @param int $errno Error number/code
	 * @param string $errstr Error string/message
	 * @param string $errfile File the error occured in
	 * @param int $errline Line the error occured on
	 * @param string $trackingId Unique tracking ID
	 */
	public static function send500Error(int $errno, string $errstr, string $errfile, int $errline, string $trackingId) : void {
		if (!headers_sent()) {
			HTTPCode::set(500);
			if (Endpoint::isEndpoint()) {
				Response::sendErrorResponse(99999, ErrorCodes::ERR_99999, ["tracking_id" => $trackingId]);
			}
		}
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
		file_put_contents(REAL_ROOTDIR."error_log", json_encode(func_get_args(), JSON_PRETTY_PRINT)."\0\x\07\n", FILE_APPEND);
		$trackingId = ((string)time())."-".$errno."-".md5($errstr.$errfile.$errline)."-".uniqid();
		switch ($errno) {
			case E_ERROR:
				self::sendErrorNotice("Halting E_ERROR", "E_ERROR", $errno, $errstr, $errfile, $errline, $trackingId);
				break;
			case E_WARNING:
				self::sendErrorNotice("Halting E_WARNING", "E_WARNING", $errno, $errstr, $errfile, $errline, $trackingId);
				break;
			case E_PARSE:
				self::sendErrorNotice("Halting E_PARSE", "E_PARSE", $errno, $errstr, $errfile, $errline, $trackingId);
				break;
			case E_NOTICE:
				self::sendErrorNotice("E_NOTICE", "E_NOTICE", $errno, $errstr, $errfile, $errline, $trackingId);
				return true;
			case E_CORE_ERROR:
				self::sendErrorNotice("Halting E_CORE_ERROR", "E_CORE_ERROR", $errno, $errstr, $errfile, $errline, $trackingId);
				break;
			case E_CORE_WARNING:
				self::sendErrorNotice("Halting E_CORE_WARNING", "E_CORE_WARNING", $errno, $errstr, $errfile, $errline, $trackingId);
				break;
			case E_COMPILE_ERROR:
				self::sendErrorNotice("Halting E_COMPILE_ERROR", "E_COMPILE_ERROR", $errno, $errstr, $errfile, $errline, $trackingId);
				break;
			case E_COMPILE_WARNING:
				self::sendErrorNotice("Halting E_COMPILE_WARNING", "E_COMPILE_WARNING", $errno, $errstr, $errfile, $errline, $trackingId);
				break;
			case E_USER_ERROR:
				self::sendErrorNotice("Halting E_USER_ERROR", "E_USER_ERROR", $errno, $errstr, $errfile, $errline, $trackingId);
				break;
			case E_USER_WARNING:
				self::sendErrorNotice("Halting E_USER_WARNING", "E_USER_WARNING", $errno, $errstr, $errfile, $errline, $trackingId);
				break;
			case E_USER_NOTICE:
				self::sendErrorNotice("E_USER_NOTICE (API misuse?)", "E_USER_NOTICE", $errno, $errstr, $errfile, $errline, $trackingId);
				return true;
			case E_STRICT:
				self::sendErrorNotice("E_STRICT", "E_STRICT", $errno, $errstr, $errfile, $errline, $trackingId);
				return true;
			case E_RECOVERABLE_ERROR:
				self::sendErrorNotice("Halting E_RECOVERABLE_ERROR", "E_RECOVERABLE_ERROR", $errno, $errstr, $errfile, $errline, $trackingId);
				break;
			case E_DEPRECATED:
				self::sendErrorNotice("E_DEPRECATED", "E_DEPRECATED", $errno, $errstr, $errfile, $errline, $trackingId);
				return true;
			case E_USER_DEPRECATED:
				self::sendErrorNotice("E_USER_DEPRECATED", "E_USER_DEPRECATED", $errno, $errstr, $errfile, $errline, $trackingId);
				return true;
			default:
				self::sendErrorNotice("Halting unknown (".$errno.")", "unknown ('.$errno.')", $errno, $errstr, $errfile, $errline, $trackingId);
				break;
		}
		self::send500Error($errno, $errstr, $errfile, $errline, $trackingId);
		die("</div></p></ul></div></p></ul></div></p></ul></div></p></ul></div></p></ul></div></p></ul></div></p></ul></div></p></ul></div></p></ul></div></p></ul></div></p></ul></div></p></ul></div></p></ul></div></p></ul></div></p></ul></div></p></ul>An unknown error has occured.  This has been reported to the development team and we are working hard to fix it!  Error ID: ".$trackingId);
		return true;
	}

	/**
	 * Handles a PHP exception, will just trigger a USER_ERROR for the above handleError to deal with
	 * 
	 * @param Throwable|null $e Exception/error to be handled
	 */
	public static function handleException(?Throwable $e) : void {
		if (is_null($e)) {
			return;
		}
		self::handleError(E_ERROR, $e->getMessage(), $e->getFile(), $e->getLine());
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
	 * Return if the platform is in april fools mode
	 * 
	 * @return bool If it is april fools, or similar pranks should be pulled
	 */
	public static function isAprilFools() : bool {
		return self::APRIL_FOOLS;
	}

	/**
	 * Return the current version
	 * 
	 * @return string Version identifier
	 */
	public static function getVersion() : string {
		return self::VERSION;
	}

	/**
	 * Get the commit
	 * 
	 * @return string commit short hash
	 */
	public static function getCommit() : string {
		if (is_null(self::$commit)) {
			self::$commit = trim(`git log -1 --pretty="%h"`);
		}
		return self::$commit;
	}
}
