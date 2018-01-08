<?php

namespace Redacted\Tests;

class HomeTest extends RedactedWebdriverTestCase {
	use FooterTestTrait;
	use TrailingSlashTestTrait;

	public static function loadURL() {
		$GLOBALS["driver"]->get($GLOBALS["base"]);
	}

	public function testHomeTitleIsCorrect() {
		$this->assertEquals("Home | Redacted", $GLOBALS["driver"]->getTitle());

		$this->checkConsole();
	}
}
