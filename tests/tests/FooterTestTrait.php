<?php

namespace Redacted\Tests;

trait FooterTestTrait {
	// Because we render this in the browser we can trap extra <div> and such
	public function testFooterIsOutermost() {
		$this->assertNotNull($GLOBALS["driver"]->findElement(\Facebook\WebDriver\WebDriverBy::cssSelector("body > footer")));
	
		$this->checkConsole();
	}
	
	public function testFooterContainsContainerAndTwoParagraphs() {
		$this->assertCount(2, $GLOBALS["driver"]->findElements(\Facebook\WebDriver\WebDriverBy::cssSelector("footer > .container > p")));
	
		$this->checkConsole();
	}
}
