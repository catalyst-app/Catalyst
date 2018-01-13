<?php

namespace Redacted\Tests;

class RegisterTest extends RedactedWebdriverTestCase {
	use HasCaptchaTrait;
	use FooterTestTrait;
	use TrailingSlashTestTrait;

	public static $testing = "\Catalyst\Database\User\Register";

	public static function loadURL() {
		$GLOBALS["driver"]->get($GLOBALS["base"]."Register");
	}

	public function testLoginTitleIsCorrect() {
		$this->assertEquals("Register | Redacted", $GLOBALS["driver"]->getTitle());

		$this->checkConsole();
	}

	public function testMarkCaptchaCorrect() {
		$this->assertEquals("", $GLOBALS["driver"]->executeScript("return window.grecaptcha.getResponse();"));

		$this->getCaptcha();

		$this->assertNotEquals("", $GLOBALS["driver"]->executeScript("return window.grecaptcha.getResponse();"));
	}

	public function testInvalidFormMethod() {
		$result = json_decode(file_get_contents($GLOBALS["base"]."Register/handler.php", false, stream_context_create([
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

	public function testEmptyPost() {
		$http_response_header=[];
		$result = $this->post($GLOBALS["base"]."Register/handler.php", [], $http_response_header);

		// error occured
		$this->assertTrue($result->error, serialize($result));

		// HTTP code stuff
		$this->assertContains("401 Bad Request", $http_response_header[0], serialize($result));
		$this->assertEquals(401, $result->http_code, serialize($result));
		// THIS IS EXPECTED: if the post array is empty it signifies too large a request
		$this->assertEquals(self::$testing::PICTURE_INVALID, $result->error_code, serialize($result));
	}

	public function testInvalidUsername() {
		$http_response_header=[];
		$result = $this->post($GLOBALS["base"]."Register/handler.php", [
			"username" => "a"
		], $http_response_header);

		// error occured
		$this->assertTrue($result->error, serialize($result));

		// HTTP code stuff
		$this->assertContains("401 Bad Request", $http_response_header[0], serialize($result));
		$this->assertEquals(401, $result->http_code, serialize($result));
		$this->assertEquals(self::$testing::USERNAME_INVALID, $result->error_code, serialize($result));
	}

	public function testMissingPassword() {
		$http_response_header=[];
		$result = $this->post($GLOBALS["base"]."Register/handler.php", [
			"username" => microtime(true)
		], $http_response_header);

		// error occured
		$this->assertTrue($result->error, serialize($result));

		// HTTP code stuff
		$this->assertContains("401 Bad Request", $http_response_header[0], serialize($result));
		$this->assertEquals(401, $result->http_code, serialize($result));
		$this->assertEquals(self::$testing::PASSWORD_INVALID, $result->error_code, serialize($result));
	}

	public function testUnmatchedPassword() {
		$http_response_header=[];
		$result = $this->post($GLOBALS["base"]."Register/handler.php", [
			"username" => microtime(true),
			"password" => base64_encode("aaaaaaaaa")
		], $http_response_header);

		// error occured
		$this->assertTrue($result->error, serialize($result));

		// HTTP code stuff
		$this->assertContains("401 Bad Request", $http_response_header[0], serialize($result));
		$this->assertEquals(401, $result->http_code, serialize($result));
		$this->assertEquals(self::$testing::PASSWORDS_UNEQUAL, $result->error_code, serialize($result));
	}

	public function testInvalidlyMatchedPassword() {
		$http_response_header=[];
		$result = $this->post($GLOBALS["base"]."Register/handler.php", [
			"username" => microtime(true),
			"password" => base64_encode("aaaaaaaaa"),
			"passwordconfirm" => base64_encode("bbbbbbbb")
		], $http_response_header);

		// error occured
		$this->assertTrue($result->error, serialize($result));

		// HTTP code stuff
		$this->assertContains("401 Bad Request", $http_response_header[0], serialize($result));
		$this->assertEquals(401, $result->http_code, serialize($result));
		$this->assertEquals(self::$testing::PASSWORDS_UNEQUAL, $result->error_code, serialize($result));
	}

	public function testInvalidEmail() {
		$http_response_header=[];
		$result = $this->post($GLOBALS["base"]."Register/handler.php", [
			"username" => microtime(true),
			"password" => base64_encode("aaaaaaaaa"),
			"passwordconfirm" => base64_encode("aaaaaaaaa"),
			"email" => "a"
		], $http_response_header);

		// error occured
		$this->assertTrue($result->error, serialize($result));

		// HTTP code stuff
		$this->assertContains("401 Bad Request", $http_response_header[0], serialize($result));
		$this->assertEquals(401, $result->http_code, serialize($result));
		$this->assertEquals(self::$testing::EMAIL_INVALID, $result->error_code, serialize($result));
	}

	public function testInvalidNickname() {
		$http_response_header=[];
		$result = $this->post($GLOBALS["base"]."Register/handler.php", [
			"username" => microtime(true),
			"password" => base64_encode("aaaaaaaaa"),
			"passwordconfirm" => base64_encode("aaaaaaaaa"),
			"email" => "test@example.com",
			"nickname" => "a"
		], $http_response_header);

		// error occured
		$this->assertTrue($result->error, serialize($result));

		// HTTP code stuff
		$this->assertContains("401 Bad Request", $http_response_header[0], serialize($result));
		$this->assertEquals(401, $result->http_code, serialize($result));
		$this->assertEquals(self::$testing::NICKNAME_INVALID, $result->error_code, serialize($result));
	}

	public function testMoreTests() {
		$this->markTestIncomplete("Not completed - needs further tests");
	}
}
