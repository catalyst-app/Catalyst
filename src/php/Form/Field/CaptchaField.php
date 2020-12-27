<?php

namespace Catalyst\Form\Field;

use \Catalyst\Controller;
use \Catalyst\Form\Form;
use \Exception;
use \InvalidArgumentException;
use \LogicException;

/**
 * A Google ReCaptcha v2 captcha
 */
class CaptchaField extends AbstractField {
	/**
	 * The site key
	 * @var string
	 */
	protected $siteKey = "";
	/**
	 * The captcha secret key
	 * @var string
	 */
	protected $secretKey = "";

	const DEBUG_CAPTCHA_KEY = '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI';
	const DEBUG_CAPTCHA_SECRET = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe';

	/**
	 * Get the current site key, or debug/testing one if the site is in development mode
	 * 
	 * @return string Site key
	 */
	public function getSiteKey() : string {
		if (Controller::isDevelMode()) {
			return self::DEBUG_CAPTCHA_KEY;
		}
		return $this->siteKey;
	}

	/**
	 * Get the current site key, or debug/testing one if the site is in development mode
	 * 
	 * @param string $siteKey New site key
	 */
	public function setSiteKey(string $siteKey) : void {
		$this->siteKey = $siteKey;
	}

	/**
	 * Get the current secret key, or debug/testing one if the site is in development mode
	 * 
	 * @return string Secret key
	 */
	protected function getSecretKey() : string {
		if (Controller::isDevelMode()) {
			return self::DEBUG_CAPTCHA_SECRET;
		}
		return $this->secretKey;
	}

	/**
	 * Set the secret key to a new value
	 * 
	 * @param string $secretKey New secret key
	 */
	public function setSecretKey(string $secretKey) : void {
		$this->secretKey = $secretKey;
	}

	/**
	 * @return string the name of the web component tag
	 */
	public static function getWebComponentName() : string {
		return "captcha-field";
	}

	/**
	 * Get the DESCRIPTIVE error message types and default messages
	 * @return string[]
	 */
	protected function getDefaultErrorMessages() : array {
		return [
			"requiredButMissing" => "Please verify that you are not a robot",
			"unknownError" => "Please check your network connection.  If this error persists, contact support",
			"expired" => "Please re-solve the CAPTCHA, you took too long to submit",
			"verificationFailed" => "Please try again.  If this error persists, contact support",
		] + parent::getDefaultErrorMessages();
	}

	/**
	 * @return array Properties for the created field element
	 */
	public function getProperties() : array {
		return [
			"formDistinguisher" => $this->getForm()->getDistinguisher(),
			"distinguisher" => $this->getDistinguisher(),
			"siteKey" => $this->getSiteKey(),
			"errors" => $this->getErrorMessages(),
		];
	}

	/**
	 * Return the field's HTML input
	 * 
	 * @return string The HTML to display
	 */
	public function getHtml() : string {
		return $this->getWebComponentHtml();
	}

	/**
	 * Full JS validation code, including if statement and all
	 * 
	 * @return string The JS to validate the field
	 */
	public function getJsValidator() : string {
		return 'if (!document.getElementById('.json_encode($this->getId()).').parentNode.verify()) { return; }';
	}

	/**
	 * Return JS code to store the field's value in $formDataName
	 * 
	 * @param string $formDataName The name of the FormData variable
	 * @return string Code to use to store field in $formDataName
	 */
	public function getJsAggregator(string $formDataName) : string {
		return $formDataName.'.append('.json_encode($this->getDistinguisher()).', document.getElementById('.json_encode($this->getId()).').parentNode.getAggregationValue());';
	}

	/**
	 * Verify the ReCaptcha v2 (provided dev mode is disabled)
	 * 
	 * @param array $requestArr Array to find the form data in
	 * @throws LogicException Secret key is not set
	 * @throws RuntimeException Response was not valid whatsoever
	 */
	public function checkServerSide(?array &$requestArr=null) : void {
		if (is_null($requestArr)) {
			if ($this->getForm()->getMethod() == Form::POST) {
				$requestArr = &$_POST;
			} else {
				$requestArr = &$_GET;
			}
		}
		if (empty($this->getSecretKey())) {
			throw new LogicException("CaptchaField must have a secret key set");
		}
		if (!array_key_exists($this->getDistinguisher(), $requestArr)) {
			$this->throwError("requiredButMissing");
		}
		if (empty($requestArr[$this->getDistinguisher()])) {
			$this->throwError("requiredButMissing");
		}
		if (Controller::isDevelMode()) {
			return; // we don't verify captchas in dev mode (potentially offline/excessive)
		}
		$opts = [
			"http" => [
				"method"  => "POST",
				"header"  => "Content-type: application/x-www-form-urlencoded",
				"content" => http_build_query([
					"secret" => $this->getSecretKey(),
					"response" => $requestArr[$this->getDistinguisher()],
					"remoteip" => $_SERVER["REMOTE_ADDR"]
				])
			]
		];
		$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify", false, stream_context_create($opts));
		if ($response === false) {
			throw new RuntimeException("Unable to query reCAPTCHA.");
		}
		if (!json_decode($response)->success) {
			$this->throwError("verificationFailed");
		}
	}
}
