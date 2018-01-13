<?php

namespace Catalyst\Tests;

trait TrailingSlashTestTrait {
	public function testUrlContainsTrailingSlash() {
		$this->assertRegExp("/\/$/", $GLOBALS["driver"]->getCurrentURL());

		$this->checkConsole();
	}

	/**
	 * @depends testUrlContainsTrailingSlash
	 */
	public function testUrlAddsTrailingSlash() {
		$GLOBALS["driver"]->get(strrev(substr(strrev($GLOBALS["driver"]->getCurrentURL()), 1)));
		$this->assertRegExp("/\/$/", $GLOBALS["driver"]->getCurrentURL());

		$this->checkConsole();
	}
}
