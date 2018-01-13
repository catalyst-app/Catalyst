<?php

namespace Catalyst\Tests;

trait HasCaptchaTrait {
	static $alertNotClosed=false;
	static $testCaptchaSecret="6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe";

	public function getCaptcha() {
		$GLOBALS["driver"]->executeScript('$(".g-recaptcha iframe").attr("id", "captchaToBeClicked");');
		$captcha = $GLOBALS["driver"]->switchTo()->frame($GLOBALS["driver"]->findElement(\Facebook\WebDriver\WebDriverBy::id("captchaToBeClicked")));
		$captcha->findElement(\Facebook\WebDriver\WebDriverBy::className("recaptcha-checkbox-checkmark"))->click();
		$GLOBALS["driver"]->switchTo()->defaultContent();

		if (self::$testCaptchaSecret != static::$testing::CAPTCHA_SECRET) {
			echo ("     Test captcha is not in use; cannot automatically mark.  Attempting to mark.\n");

			if ($GLOBALS["driver"]->executeScript("return window.grecaptcha.getResponse();") != "") {
				return;
			}

			if ($GLOBALS["captchaAlertNotClosed"]) {
				$this->markTestSkipped("Last captcha was not manually solved, unlikely for this to be");
				return;
			}

			$GLOBALS["driver"]->executeScript('alert("'.__METHOD__.' Test captcha is not in use; cannot automatically mark.  Please re-solve.  Waiting up to 10 seconds for this dialog to be accepted.");');

			$alertClosed = false;
			for ($i=0; $alertClosed || $i < 20; $i++) { 
				usleep(500000);
				try {
					$GLOBALS["driver"]->switchTo()->alert()->getText();
					continue;
				} catch (\Exception $e) {
					$alertClosed=true;
				}
				$response = $GLOBALS["driver"]->executeScript("return window.grecaptcha.getResponse();");
				if ($response != "") {
					$this->assertNotEquals("", $response);
					return;
				}
			}
			try {
				$GLOBALS["driver"]->switchTo()->alert()->dismiss();
			} catch (\Exception $e) {}
			$GLOBALS["captchaAlertNotClosed"] = true;
			$this->markTestSkipped("Captcha was not manually solved.");
			return;
		}

		for ($i=0; $i < 8; $i++) { 
			usleep(500000);
			$response = $GLOBALS["driver"]->executeScript("return window.grecaptcha.getResponse();");
			if ($response != "") {
				$this->assertNotEquals("", $response);
				return;
			}
		}
	}
}
