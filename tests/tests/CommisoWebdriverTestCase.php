<?php

namespace Redacted\Tests;

abstract class RedactedWebdriverTestCase extends \PHPUnit\Framework\TestCase {
	public static function loadURL() {
		new \RuntimeException("loadURL is not defined");
	}

	public static function setUpBeforeClass() {
		static::loadURL();
	}

	public static function tearDownAfterClass() {
		$GLOBALS["driver"]->get("about:blank");
	}

	protected function checkConsole() {
		$log = $GLOBALS["driver"]->manage()->getLog("browser");
		foreach ($log as $message) {
			foreach ($GLOBALS["ignoreConsole"] as $ignore) {
				if (preg_match($ignore, $message["message"])) {
					continue 2;
				}
			}
			$this->fail($message["level"].":".$message["source"]." ".$message["message"]);
		}
	}

	protected function post(string $url, array $parts, &$headers) {
		$boundary = "------".microtime(true);
		$r = json_decode(file_get_contents($url, false, stream_context_create([
			"http" => [
				"ignore_errors" => true,
				"method" => "POST",
				"header" => "Content-Type: multipart/form-data;boundary=".$boundary,
				"content" => implode("", array_map(function($a, $b) use ($boundary) {
					return "--".$boundary."\r\n".
							 "Content-Disposition: form-data; name=\"".$a."\"\r\n\r\n".
							 $b."\r\n";
				}, array_keys($parts), $parts))."--".$boundary."--"
			]
		])));

		// JSON returned
		$this->assertNotSame(false, $r);
		$this->assertNotNull($r);

		$headers = $http_response_header;
		return $r;
	}
}
