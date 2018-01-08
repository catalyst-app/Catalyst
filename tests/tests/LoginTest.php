<?php

namespace Redacted\Tests;

class LoginTest extends RedactedWebdriverTestCase {
	use HasCaptchaTrait;
	use FooterTestTrait;
	use TrailingSlashTestTrait;

	public static $testing = "\Redacted\Database\User\Login";

	public static function loadURL() {
		$GLOBALS["driver"]->get($GLOBALS["base"]."Login");
	}

	public function testLoginTitleIsCorrect() {
		$this->assertEquals("Login | Redacted", $GLOBALS["driver"]->getTitle());

		$this->checkConsole();
	}

	public function testMarkCaptchaCorrect() {
		$this->assertEquals("", $GLOBALS["driver"]->executeScript("return window.grecaptcha.getResponse();"));

		$this->getCaptcha();

		$this->assertNotEquals("", $GLOBALS["driver"]->executeScript("return window.grecaptcha.getResponse();"));
	}

	public function testInvalidFormMethod() {
		$result = json_decode(file_get_contents($GLOBALS["base"]."Login/handler.php", false, stream_context_create([
			"http" => [
				"ignore_errors" => true
			]
		])));

		// error occured
		$this->assertTrue($result->error, serialize($result));

		// HTTP code stuff
		$this->assertContains("405 Method Not Allowed", $http_response_header[0], serialize($result));
		$this->assertEquals(405, $result->http_code, serialize($result));
		$this->assertEquals(405, $result->error_code, serialize($result));
	}

	public function testMissingUsername() {
		$http_response_header=[];
		$result = $this->post($GLOBALS["base"]."Login/handler.php", [], $http_response_header);

		// error occured
		$this->assertTrue($result->error, serialize($result));

		// HTTP code stuff
		$this->assertContains("401 Bad Request", $http_response_header[0], serialize($result));
		$this->assertEquals(401, $result->http_code, serialize($result));
		$this->assertEquals(self::$testing::USERNAME_INVALID, $result->error_code, serialize($result));
	}

	public function testMissingPassword() {
		$http_response_header=[];
		$result = $this->post($GLOBALS["base"]."Login/handler.php", [
			"username" => TEST_USER // from Secrets
		], $http_response_header);

		// error occured
		$this->assertTrue($result->error, serialize($result));

		// HTTP code stuff
		$this->assertContains("401 Bad Request", $http_response_header[0], serialize($result));
		$this->assertEquals(401, $result->http_code, serialize($result));
		$this->assertEquals(self::$testing::PASSWORD_INVALID, $result->error_code, serialize($result));
	}

	public function testMissingCaptcha() {
		$http_response_header=[];
		$result = $this->post($GLOBALS["base"]."Login/handler.php", [
			"username" => TEST_USER, // from Secrets,
			"password" => base64_encode(TEST_PASS)
		], $http_response_header);

		// error occured
		$this->assertTrue($result->error, serialize($result));

		// HTTP code stuff
		$this->assertContains("401 Bad Request", $http_response_header[0], serialize($result));
		$this->assertEquals(401, $result->http_code, serialize($result));
		$this->assertEquals(self::$testing::CAPTCHA_INVALID, $result->error_code, serialize($result));
	}

	public function testInvalidUsername() {
		$http_response_header=[];
		$result = $this->post($GLOBALS["base"]."Login/handler.php", [
			"username" => ".",
			"password" => base64_encode(TEST_PASS),
			"captcha" => $GLOBALS["driver"]->executeScript("return window.grecaptcha.getResponse();")
		], $http_response_header);

		// error occured
		$this->assertTrue($result->error, serialize($result));

		// HTTP code stuff
		$this->assertContains("401 Bad Request", $http_response_header[0], serialize($result));
		$this->assertEquals(401, $result->http_code, serialize($result));
		$this->assertEquals(self::$testing::USERNAME_INVALID, $result->error_code, serialize($result));
	}

	public function testEmptyCaptcha() {
		$http_response_header=[];
		$result = $this->post($GLOBALS["base"]."Login/handler.php", [
			"username" => TEST_USER,
			"password" => base64_encode(TEST_PASS),
			"captcha" => ""
		], $http_response_header);

		// error occured
		$this->assertTrue($result->error, serialize($result));

		// HTTP code stuff
		$this->assertContains("401 Bad Request", $http_response_header[0], serialize($result));
		$this->assertEquals(401, $result->http_code, serialize($result));
		$this->assertEquals(self::$testing::CAPTCHA_INVALID, $result->error_code, serialize($result));
	}

	public function testInvalidCaptcha() {
		if (self::$testCaptchaSecret == self::$testing::CAPTCHA_SECRET) {
			$this->markTestSkipped("Test captcha is in use");
			return;
		}

		$http_response_header=[];
		$result = $this->post($GLOBALS["base"]."Login/handler.php", [
			"username" => TEST_USER,
			"password" => base64_encode(TEST_PASS),
			"captcha" => "nah"
		], $http_response_header);

		// error occured
		$this->assertTrue($result->error, serialize($result));

		// HTTP code stuff
		$this->assertContains("401 Bad Request", $http_response_header[0], serialize($result));
		$this->assertEquals(401, $result->http_code, serialize($result));
		$this->assertEquals(self::$testing::CAPTCHA_INVALID, $result->error_code, serialize($result));
	}

	public function testIncorrectUsername() {
		$GLOBALS["driver"]->executeScript("window.grecaptcha.reset();");
		$this->getCaptcha();
		$http_response_header=[];
		$result = $this->post($GLOBALS["base"]."Login/handler.php", [
			"username" => "u".microtime(true),
			"password" => base64_encode(TEST_PASS),
			"captcha" => $GLOBALS["driver"]->executeScript("return window.grecaptcha.getResponse();")
		], $http_response_header);

		// error occured
		$this->assertTrue($result->error, serialize($result));

		// HTTP code stuff
		$this->assertContains("401 Bad Request", $http_response_header[0], serialize($result));
		$this->assertEquals(401, $result->http_code, serialize($result));
		$this->assertEquals(self::$testing::USERNAME_INVALID, $result->error_code, serialize($result));
	}

	public function testInvalidPassword() {
		$GLOBALS["driver"]->executeScript("window.grecaptcha.reset();");
		$this->getCaptcha();
		$http_response_header=[];
		$result = $this->post($GLOBALS["base"]."Login/handler.php", [
			"username" => TEST_USER,
			"password" => "no",
			"captcha" => $GLOBALS["driver"]->executeScript("return window.grecaptcha.getResponse();")
		], $http_response_header);

		// error occured
		$this->assertTrue($result->error, serialize($result));

		// HTTP code stuff
		$this->assertContains("401 Bad Request", $http_response_header[0], serialize($result));
		$this->assertEquals(401, $result->http_code, serialize($result));
		$this->assertEquals(self::$testing::PASSWORD_INVALID, $result->error_code, serialize($result));
	}

	public function testValidLogin() {
		$GLOBALS["driver"]->executeScript("window.grecaptcha.reset();");
		$this->getCaptcha();

		$http_response_header=[];
		$result = $this->post($GLOBALS["base"]."Login/handler.php", [
			"username" => TEST_USER,
			"password" => base64_encode(TEST_PASS),
			"captcha" => $GLOBALS["driver"]->executeScript("return window.grecaptcha.getResponse();")
		], $http_response_header);

		// error is false
		$this->assertFalse($result->error, serialize($result));

		// HTTP code stuff
		$this->assertContains("200 OK", $http_response_header[0], serialize($result));
		$this->assertEquals(200, $result->http_code, serialize($result));
	}

	public function testSuspendedLogin() {
		$GLOBALS["driver"]->executeScript("window.grecaptcha.reset();");
		$this->getCaptcha();

		$http_response_header=[];
		$result = $this->post($GLOBALS["base"]."Login/handler.php", [
			"username" => SUSPENDED_USER,
			"password" => base64_encode(SUSPENDED_PASS),
			"captcha" => $GLOBALS["driver"]->executeScript("return window.grecaptcha.getResponse();")
		], $http_response_header);

		// error is false
		$this->assertTrue($result->error, serialize($result));

		// HTTP code stuff
		$this->assertContains("401 Bad Request", $http_response_header[0], serialize($result));
		$this->assertEquals(401, $result->http_code, serialize($result));
		$this->assertEquals(self::$testing::ACCOUNT_DISABLED, $result->error_code, serialize($result));
	}

	public function testDeactivatedLogin() {
		$GLOBALS["driver"]->executeScript("window.grecaptcha.reset();");
		$this->getCaptcha();

		$http_response_header=[];
		$result = $this->post($GLOBALS["base"]."Login/handler.php", [
			"username" => DEACTIVATED_USER,
			"password" => base64_encode(DEACTIVATED_PASS),
			"captcha" => $GLOBALS["driver"]->executeScript("return window.grecaptcha.getResponse();")
		], $http_response_header);

		// error is false
		$this->assertTrue($result->error, serialize($result));

		// HTTP code stuff
		$this->assertContains("401 Bad Request", $http_response_header[0], serialize($result));
		$this->assertEquals(401, $result->http_code, serialize($result));
		$this->assertEquals(self::$testing::ACCOUNT_DISABLED, $result->error_code, serialize($result));
	}
}
