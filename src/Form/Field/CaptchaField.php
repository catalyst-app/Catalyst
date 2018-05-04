<?php

namespace Catalyst\Form\Field;

use \Catalyst\Controller;
use \Catalyst\Form\Form;
use \Catalyst\Page\Values;
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

	/**
	 * Get the current site key, or debug/testing one if the site is in development mode
	 * 
	 * @return string Site key
	 */
	public function getSiteKey() : string {
		if (Controller::isDevelMode()) {
			return Values::DEBUG_CAPTCHA_KEY;
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
			return Values::DEBUG_CAPTCHA_SECRET;
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
	 * Return the field's HTML input
	 * 
	 * @return string The HTML to display
	 */
	public function getHtml() : string {
		if (!$this->isRequired()) {
			throw new LogicException("CaptchaField must be required");
		}
		if (empty($this->getSiteKey())) {
			throw new InvalidArgumentException("CaptchaField must have a site key set");
		}
		$str = '';
		$str .= '<div';
		$str .= ' class="g-recaptcha col s12"';
		$str .= ' data-sitekey="'.htmlspecialchars($this->getSiteKey()).'"';
		$str .= ' id="'.htmlspecialchars($this->getId()).'"';
		$str .= ' data-expired-callback="markCaptchaInvalid"';
		$str .= ' data-callback="markCaptchaValid"';
		$str .= ' data-error="'.htmlspecialchars($this->getErrorMessage($this->getInvalidErrorCode())).'"';
		$str .= '></div>';

		return $str;
	}

	/**
	 * Full JS validation code, including if statement and all
	 * 
	 * @return string The JS to validate the field
	 */
	public function getJsValidator() : string {
		$str = '';
		$str .= 'if (grecaptcha.getResponse() == "") {';
		$str .= 'window.log('.json_encode(basename(__CLASS__)).', "captcha - grecaptcha.getResponse() returned an empty string.  Invoking markCaptchaInvalid.", true);';
		$str .= 'markCaptchaInvalid();';
		$str .= Form::CANCEL_SUBMISSION_JS;
		$str .= '}';

		return $str;
	}

	/**
	 * Return JS code to store the field's value in $formDataName
	 * 
	 * @param string $formDataName The name of the FormData variable
	 * @return string Code to use to store field in $formDataName
	 */
	public function getJsAggregator(string $formDataName) : string {
		return $formDataName.'.append('.json_encode($this->getDistinguisher()).',grecaptcha.getResponse());';
	}

	/**
	 * Verify the ReCaptcha v2
	 * 
	 * @param array $requestArr Array to find the form data in
	 * @throws LogicException Field is not required
	 */
	public function checkServerSide(?array &$requestArr=null) : void {
		if (is_null($requestArr)) {
			if ($this->getMethod() == Form::POST) {
				$requestArr = $_POST;
			} else {
				$requestArr = $_GET;
			}
		}
		if (!$this->isRequired()) {
			throw new LogicException("CaptchaField must be required");
		}
		if (empty($this->getSecretKey())) {
			throw new InvalidArgumentException("CaptchaField must have a secret key set");
		}
		if (Controller::isDevelMode()) {
			return; // dont verify captchas in dev mode
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
		if (!json_decode($response)->success) {
			$this->throwInvalidError();
		}
	}
}
