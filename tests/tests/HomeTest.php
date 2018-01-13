<?php

namespace Catalyst\Tests;

class HomeTest extends CatalystWebdriverTestCase {
	use FooterTestTrait;
	use TrailingSlashTestTrait;

	public static function loadURL() {
		$GLOBALS["driver"]->get($GLOBALS["base"]);
	}

	public function testHomeTitleIsCorrect() {
		$this->assertEquals("Home | Catalyst", $GLOBALS["driver"]->getTitle());

		$this->checkConsole();
	}
}
