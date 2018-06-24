<?php

namespace Catalyst;

use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\Database\{AbstractDatabaseModel, AbstractDatabaseRowModel};
use \Catalyst\Email\Email;
use \Catalyst\HTTPCode;
use \Catalyst\Images\{DBImage, Image};
use \Catalyst\Page\{UniversalFunctions, Values};
use \Exception;
use \LogicException;
use \PDO;
use \ReflectionClass;
use \Throwable;
use \WhichBrowser\Parser;

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
			require_once __DIR__.DIRECTORY_SEPARATOR."vendor_qr".DIRECTORY_SEPARATOR."phpqrcode.php";
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
		if (!class_exists("\\Catalyst\\Secrets") || !class_exists("\\PHPMailer\\PHPMailer\\PHPMailer")) {
			return;
		}
		ob_start();
		$destinations = [];
		if (!array_key_exists("SERVER_NAME", $_SERVER) || $_SERVER["SERVER_NAME"] == "localhost") { // default to local reporting
			$destinations = ["discord","email","telegram"];
		} else {
			$destinations = ["discord","email","telegram"];
		}
		
		$ua = "unknown";
		if (php_sapi_name() == "cli") {
			$ua = "CLI";
		} else {
			$ua = [new Parser(getallheaders()), new Parser(getallheaders(), ['detectBots' => false])];
			if ($ua[0]->toString() == $ua[1]->toString()) {
				$ua = $ua[0]->toString();
			} else {
				$ua = $ua[0]->toString()." (pretending to be ".$ua[1]->toString().")";
			}
		}

		if (in_array("email", $destinations)) {
			$trace = self::getTrace();
			Email::sendEmail(
				[["error_logs@catalystapp.co","Error Log"]],
				$subj." on ".(php_sapi_name() == "cli" ? "cli" : (array_key_exists("HTTP_HOST", $_SERVER) ? $_SERVER["HTTP_HOST"] : "unknown"))." occured in ".$errfile." at ".$errline.": ".$errstr,
				'<p><strong>Error code:</strong> '.$errco.' ('.$errno.')</p>'.
				'<p><strong>Error string:</strong> '.htmlspecialchars($errstr).'</p>'.
				'<p><strong>Error file:</strong> '.htmlspecialchars($errfile).'</p>'.
				'<p><strong>Error line:</strong> '.$errline.'</p>'.
				'<p><strong>Tracking ID:</strong> '.$trackingId.'</p>'.
				'<p><strong>Destination Script:</strong> '.(array_key_exists("PHP_SELF", $_SERVER) ? $_SERVER["PHP_SELF"] : "unknown").'</p>'.
				'<p><strong>Site User:</strong> '.((isset($_SESSION) && array_key_exists("user", $_SESSION)) ? $_SESSION['user']->getUsername() : 'not logged in').'</p>'.
				'<p><strong>Referrer:</strong> '.htmlspecialchars(array_key_exists("HTTP_REFERER", $_SERVER) ? $_SERVER["HTTP_REFERER"] : "unknown").'</p>'.
				"<p><strong>IP Address:</strong> ".htmlspecialchars(array_key_exists("REMOTE_ADDR", $_SERVER) ? $_SERVER["REMOTE_ADDR"] : "unknown")."</p>".
				'<p><strong>User agent:</strong> '.htmlspecialchars($ua)."</p>".
				'<p><strong>Host:</strong> '.htmlspecialchars(php_sapi_name() == "cli" ? "cli" : (array_key_exists("HTTP_HOST", $_SERVER) ? $_SERVER["HTTP_HOST"] : "unknown")).'</p>'.
				'<p><strong>Trace:</strong></p>'.
				'<p>'.implode('</p><p>',array_map("htmlspecialchars",$trace)).'</p>'.
				'<p><strong>Dump:</strong> <pre>'.htmlspecialchars(serialize([$_SERVER,isset($_SESSION) ? $_SESSION : null])).'</pre></p>',
				"Error code: ".$errco." (".$errno.")\r\n\r\n".
				"Error string: ".$errstr."\r\n\r\n".
				"Error file: ".$errfile."\r\n\r\n".
				"Error line: ".$errline."\r\n\r\n".
				"Tracking ID: ".$trackingId."\r\n\r\n".
				'Destination Script: '.(array_key_exists("PHP_SELF", $_SERVER) ? $_SERVER["PHP_SELF"] : "unknown")."\r\n\r\n".
				'Referrer: '.(array_key_exists("HTTP_REFERER", $_SERVER) ? $_SERVER["HTTP_REFERER"] : "unknown")."\r\n\r\n".
				'Site User: '.((isset($_SESSION) && array_key_exists("user", $_SESSION)) ? $_SESSION['user']->getUsername() : 'not logged in')."\r\n\r\n".
				"IP Address: ".(array_key_exists("REMOTE_ADDR", $_SERVER) ? $_SERVER["REMOTE_ADDR"] : "unknown")."\r\n\r\n".
				'User agent: '.($ua)."\r\n\r\n".
				'Host: '.(php_sapi_name() == "cli" ? "cli" : (array_key_exists("HTTP_HOST", $_SERVER) ? $_SERVER["HTTP_HOST"] : "unknown"))."\r\n\r\n".
				"Trace: \r\n\r\n".
				implode("\r\n\r\n",$trace)."\r\n\r\n".
				"Dump: ".serialize([$_SERVER,isset($_SESSION) ? $_SESSION : null]),
				Email::ERROR_LOG_EMAIL,
				Email::ERROR_LOG_PASSWORD,
				Email::ERROR_LOG_SMIME_PATH,
				Email::ERROR_LOG_SMIME_PASSWORD
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
											"name" => 'Destination Script',
											"value" => (array_key_exists("PHP_SELF", $_SERVER) ? $_SERVER["PHP_SELF"] : "unknown"),
										],
										[
											"name" => 'Referrer',
											"value" => (array_key_exists("HTTP_REFERER", $_SERVER) ? $_SERVER["HTTP_REFERER"] : "unknown"),
										],
										[
											"name" => "IP Address",
											"value" => substr(array_key_exists("REMOTE_ADDR", $_SERVER) ? $_SERVER["REMOTE_ADDR"] : "unknown", 0, -4)."XXXX",
										],
										[
											"name" => 'User agent',
											"value" => ($ua),
										],
										[
											"name" => 'Host',
											"value" => (php_sapi_name() == "cli" ? "cli" : (array_key_exists("HTTP_HOST", $_SERVER) ? $_SERVER["HTTP_HOST"] : "unknown")),
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
				$telegramStr .= "<b>Error:</b> ".htmlspecialchars(str_replace("<br>", "\r\n", $errstr))."\n";
				$telegramStr .= "<b>File:</b> ".basename($errfile)."\n";
				$telegramStr .= "<b>Line:</b> ".$errline."\n";
				$telegramStr .= "<b>Tracking ID:</b> ".$trackingId."\n";
				$telegramStr .= '<b>Destination Script:</b> '.htmlspecialchars(array_key_exists("PHP_SELF", $_SERVER) ? $_SERVER["PHP_SELF"] : "unknown")."\n";
				$telegramStr .= '<b>Referrer:</b> '.htmlspecialchars(array_key_exists("HTTP_REFERER", $_SERVER) ? $_SERVER["HTTP_REFERER"] : "unknown")."\n";
				$telegramStr .= "<b>Site User:</b> ".htmlspecialchars((isset($_SESSION) && array_key_exists("user", $_SESSION)) ? $_SESSION['user']->getUsername() : 'not logged in')."\n";
				$telegramStr .= "<b>IP Address:</b> ".substr(array_key_exists("REMOTE_ADDR", $_SERVER) ? $_SERVER["REMOTE_ADDR"] : "unknown", 0, -4)."XXXX"."\n";
				$telegramStr .= '<b>User agent:</b> '.htmlspecialchars($ua)."\n";
				$telegramStr .= '<b>Host:</b> '.htmlspecialchars(php_sapi_name() == "cli" ? "cli" : (array_key_exists("HTTP_HOST", $_SERVER) ? $_SERVER["HTTP_HOST"] : "unknown"))."\n";
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
		}
		if (php_sapi_name() === 'cli') {
			echo "An unexpected error occured!  Information:\n".
				"  ERRNO:      ".$errno."\n".
				"  ERRSTR:     ".$errstr."\n".
				"  ERRFILE:    ".$errfile."\n".
				"  ERRLINE:    ".$errline."\n".
				"  TRACK_ID:    ".$trackingId."\n".
				"  ARGV:   ".implode(" | ", $_SERVER["argv"])."\n";
			flush();
			exit(1); // non-zero error codes ye
		}
		ob_clean(); // clean out output buffer, as we're about to show errors
		if (Endpoint::isEndpoint()) {
			if (!headers_sent()) {
				Response::sendErrorResponse(99999, ErrorCodes::ERR_99999, ["tracking_id" => $trackingId]);
			} else {
				trigger_error("API ENDPOINT HEADERS (SOMEHOW) ALREADY SENT BEFORE ERROR!", E_USER_NOTICE);
			}
		} else {
			// we're actually in JS
			if (array_key_exists("SCRIPT_FILENAME", $_SERVER) && strpos(strrev($_SERVER["SCRIPT_FILENAME"]), strrev(".js")) === 0) {
				echo ';alert("An unexpected error occured.  Tracking ID: '.$trackingId.'");window.onerror('.json_encode($trackingId).', "???", -1);';
				ob_flush();
				flush();
				die();
			}
			
			// check constants are set
			if (!defined("PAGE_COLOR")) {
				define("PAGE_COLOR", Values::DEFAULT_COLOR);
			}
			if (!defined("PAGE_TITLE")) {
				define("PAGE_TITLE", "Error!!1");
			}
			if (!defined("PAGE_KEYWORD")) {
				define("PAGE_KEYWORD", "error");
			}

			if (headers_sent()) { // assume we already sent header, despite our OB attempts
				echo '</div><div a=""></div></div></div></p></img></div></div><h3>An unexpected error occured.  Tracking ID: '.$trackingId.'</h3></body></html>';
			} else {
				if (!defined("REAL_ROOTDIR")) {
					define("REAL_ROOTDIR", __DIR__.'/../'); // we can do this safely because we're only using stuff from this script, so no fuckery will be included
				}

				require Values::HEAD_INC;
				
				echo '<div class="section center row">';
				echo UniversalFunctions::createHeading('An unexpected error occured</h3>');
				echo '<p class="flow-text no-bottom-margin">Tracking ID: <strong>'.$trackingId.'</strong></p>';
				echo '<p class="flow-text no-top-margin">If you would please e-mail <a href="mailto:bugs@catalystapp.co">bugs@catalystapp.co</a> with the above tracking ID, what you were doing, and any other relevant information.</p>';
				
				if (self::isDevelMode()) {
					echo '<div class="code-block">';
					echo '<p><strong>Development mode information:</strong></p>';
					echo '<p></p>';
					echo '<p>ERRNO: '.htmlspecialchars($errno).'</p>';
					echo '<p>ERRSTR: '.htmlspecialchars($errstr).'</p>';
					echo '<p>ERRFILE: '.htmlspecialchars($errfile).'</p>';
					echo '<p>ERRLINE: '.htmlspecialchars($errline).'</p>';
					echo '<p>TRACK_ID: '.htmlspecialchars($trackingId).'</p>';
					echo '<p></p>';
					echo '<p>Common mistakes (in self-hosting):</p>';
					echo '<p>SECRETS DEFINED: '.(class_exists("\\Catalyst\\Secrets") ? "YES" : "NO").'</p>';
					$version = explode(".", phpversion());
					$vid = ($version[0] * 10000 + $version[1] * 100);
					echo '<p>PHP VERSION (>=7.2): '.($vid >= 70200 ? "YES" : "NO").'</p>';
					echo '<p>PDO exists: '.(class_exists("\\PDO") ? "YES" : "NO").'</p>';
					echo '<p>MySQL PDO: '.(in_array("mysql", PDO::getAvailableDrivers()) ? "YES" : "NO").'</p>';
					echo '<p>COMPOSER IS UPDATED: '.(class_exists("\\PHPMailer\\PHPMailer\\PHPMailer") ? "YES (still ensure upgraded)" : "NO").'</p>';
					echo '</div>';

					echo '<div class="code-block">';
					echo '<p><strong>Server/host information:</strong></p>';
					echo '<p></p>';
					echo '<p>Host: '.htmlspecialchars(array_key_exists("HTTP_HOST", $_SERVER) ? $_SERVER["HTTP_HOST"] : "unknown").'</p>';
					echo '<p>Device: '.htmlspecialchars(array_key_exists("HTTP_USER_AGENT", $_SERVER) ? $_SERVER["HTTP_USER_AGENT"] : "unknown").'</p>';
					echo '<p>Cookies: '.htmlspecialchars(array_key_exists("HTTP_COOKIE", $_SERVER) ? $_SERVER["HTTP_COOKIE"] : "unknown").'</p>';
					echo '<p>Scheme: '.htmlspecialchars(array_key_exists("REQUEST_SCHEME", $_SERVER) ? $_SERVER["REQUEST_SCHEME"] : "unknown").'</p>';
					echo '<p>Protocol: '.htmlspecialchars(array_key_exists("SERVER_PROTOCOL", $_SERVER) ? $_SERVER["SERVER_PROTOCOL"] : "unknown").'</p>';
					echo '<p>Method: '.htmlspecialchars(array_key_exists("REQUEST_METHOD", $_SERVER) ? $_SERVER["REQUEST_METHOD"] : "unknown").'</p>';
					echo '<p>Requested at: '.htmlspecialchars(array_key_exists("REQUEST_TIME_FLOAT", $_SERVER) ? $_SERVER["REQUEST_TIME_FLOAT"] : "unknown").'</p>';
					echo '<p>TLS: '.htmlspecialchars(array_key_exists("HTTPS", $_SERVER) && $_SERVER["HTTPS"] == "on" ? "YES" : "NO").'</p>';
					echo '<p>Referrer: '.htmlspecialchars(array_key_exists("HTTP_REFERER", $_SERVER) ? $_SERVER["HTTP_REFERER"] : "unknown").'</p>';
					echo '<p>X_Requester: '.htmlspecialchars(array_key_exists("HTTP_X_REQUESTED_WITH", $_SERVER) ? $_SERVER["HTTP_X_REQUESTED_WITH"] : "unknown").'</p>';
					echo '<p>Remote: '.htmlspecialchars(array_key_exists("REMOTE_ADDR", $_SERVER) ? $_SERVER["REMOTE_ADDR"] : "unknown").':'.htmlspecialchars(array_key_exists("REMOTE_PORT", $_SERVER) ? $_SERVER["REMOTE_PORT"] : "unknown").'</p>';
					echo '<p>argv: '.htmlspecialchars(array_key_exists("ARGV", $_SERVER) ? $_SERVER["ARGV"] : "unknown").'</p>';
					echo '<p></p>';
					echo '<p>PHP Version: '.htmlspecialchars(phpversion()).'</p>';
					echo '<p>Catalyst Version: '.htmlspecialchars(Controller::getVersion()." (".self::getCommit().")").'</p>';
					echo '</div>';
				}
				
				echo '<img title="Credit: SammyTheTanuki" class="center col s10 offset-s1 m6 offset-m3" src="'.REAL_ROOTDIR.'img/oops.png" />';
				echo '</div>';
				
				require Values::FOOTER_INC;
			}
			ob_flush();
			flush();
			die();
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
		error_log(json_encode(func_get_args(), JSON_PRETTY_PRINT)."\0\x\07\n");
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
	 * Check that Secrets exists and contains what it should
	 */
	public static function verifySecretsIntegrity() : void {
		if (!file_exists(__DIR__."/Secrets.php")) {
			throw new LogicException("src/Secrets.php does not exist");
		}
		if (!class_exists("\\Catalyst\\Secrets")) {
			throw new LogicException("Secrets class does not exist");
		}
		if (!defined("\\Catalyst\\Secrets::DB_PASSWORD")) {
			throw new LogicException("Secrets::DB_PASSWORD is not defined");
		}
		if (!defined("\\Catalyst\\Secrets::NO_REPLY_PASSWORD")) {
			throw new LogicException("Secrets::NO_REPLY_PASSWORD is not defined");
		}
		if (!defined("\\Catalyst\\Secrets::ERROR_LOG_PASSWORD")) {
			throw new LogicException("Secrets::ERROR_LOG_PASSWORD is not defined");
		}
		if (!defined("\\Catalyst\\Secrets::LOGIN_CAPTCHA_SECRET")) {
			throw new LogicException("Secrets::LOGIN_CAPTCHA_SECRET is not defined");
		}
		if (!defined("\\Catalyst\\Secrets::REGISTER_CAPTCHA_SECRET")) {
			throw new LogicException("Secrets::REGISTER_CAPTCHA_SECRET is not defined");
		}
		if (!defined("\\Catalyst\\Secrets::EMAIL_VERIFICATION_CAPTCHA_SECRET")) {
			throw new LogicException("Secrets::EMAIL_VERIFICATION_CAPTCHA_SECRET is not defined");
		}
		if (!defined("\\Catalyst\\Secrets::EMAIL_LIST_CAPTCHA_SECRET")) {
			throw new LogicException("Secrets::EMAIL_LIST_CAPTCHA_SECRET is not defined");
		}
		if (!defined("\\Catalyst\\Secrets::DISCORD_BACKUP_WEBHOOK_TOKEN")) {
			throw new LogicException("Secrets::DISCORD_BACKUP_WEBHOOK_TOKEN is not defined");
		}
		if (!defined("\\Catalyst\\Secrets::DISCORD_BUG_WEBHOOK_TOKEN")) {
			throw new LogicException("Secrets::DISCORD_BUG_WEBHOOK_TOKEN is not defined");
		}
		if (!defined("\\Catalyst\\Secrets::TELEGRAM_TOKEN")) {
			throw new LogicException("Secrets::TELEGRAM_TOKEN is not defined");
		}
		if (!defined("\\Catalyst\\Secrets::TELEGRAM_CHAT")) {
			throw new LogicException("Secrets::TELEGRAM_CHAT is not defined");
		}
		if (!defined("\\Catalyst\\Secrets::RSA_PRIVATE")) {
			throw new LogicException("Secrets::RSA_PRIVATE is not defined");
		}
		if (!defined("\\Catalyst\\Secrets::RSA_PUBLIC")) {
			throw new LogicException("Secrets::RSA_PUBLIC is not defined");
		}
		if (!defined("\\Catalyst\\Secrets::ERROR_LOG_SMIME_PASSWORD")) {
			throw new LogicException("Secrets::ERROR_LOG_SMIME_PASSWORD is not defined");
		}
		if (!defined("\\Catalyst\\Secrets::NO_REPLY_SMIME_PASSWORD")) {
			throw new LogicException("Secrets::NO_REPLY_SMIME_PASSWORD is not defined");
		}
	}

	/**
	 * Called via register_shutdown_function, should flush OB
	 */
	public static function shutdown() : void {
		AbstractDatabaseModel::writeAllUpdates();
		AbstractDatabaseRowModel::writeAllDeletions();
		DBImage::writeAllChanges();
		Image::writePendingOperationQueues();
		if (php_sapi_name() !== 'cli') {
			ob_end_flush();
		}
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
