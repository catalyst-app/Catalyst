<?php

namespace Catalyst\User;

use \Catalyst\API\ErrorCodes;
use \Catalyst\Form\CompletionAction\ConcreteRedirectCompletionAction;
use \Catalyst\Form\Field\{AutocompleteValues, CaptchaField, StaticHTMLField, TextField};
use \Catalyst\Form\Form;
use \Catalyst\{Secrets,Tokens};

/**
 * Rmail verification form
 */
trait EmailVerificationFormTrait {
	/**
	 * Verifies an email address token
	 * 
	 * See /EmailVerification
	 * @return Form Form for verifying a new email
	 */
	public static function getEmailVerificationForm() : Form {
		$form = new Form();

		$form->setDistinguisher(self::getDistinguisherFromFunctionName(__FUNCTION__)); // get-dash-case from camelCase
		$form->setMethod(Form::POST);
		$form->setEndpoint("internal/email_verification/");
		$form->setButtonText("VERIFY");
		$form->setPrimary(true);

		$completionAction = new ConcreteRedirectCompletionAction();
		$completionAction->setRedirectUrl("Dashboard");
		$form->setCompletionAction($completionAction);

		$tokenField = new TextField();
		$tokenField->setDistinguisher("token");
		$tokenField->setLabel("Token");
		$tokenField->setRequired(true);
		if (array_key_exists("email_token", $_SESSION) && 
			preg_match('/'.Tokens::EMAIL_VERIFICATION_TOKEN_REGEX.'/', $_SESSION["email_token"])) {
			$tokenField->setPrefilledValue($_SESSION["email_token"]);
			$tokenField->setHelperText("This field was populated with the link from your e-mail!");
		}
		$tokenField->setMaxLength(Tokens::EMAIL_VERIFICATION_TOKEN_LENGTH);
		$tokenField->setPattern(Tokens::EMAIL_VERIFICATION_TOKEN_REGEX);
		$tokenField->setAutocompleteAttribute(AutocompleteValues::OFF);
		$tokenField->addError(90401, ErrorCodes::ERR_90401);
		$tokenField->setMissingErrorCode(90401);
		$tokenField->addError(90402, ErrorCodes::ERR_90402);
		$tokenField->setInvalidErrorCode(90402);
		$form->addField($tokenField);

		$captchaField = new CaptchaField();
		$captchaField->setDistinguisher("captcha");
		$captchaField->setRequired(true);
		$captchaField->setSiteKey("6LdGBEEUAAAAAMHsFHz4BRvEnIq1NMuuU_Keo7nn");
		$captchaField->setSecretKey(Secrets::EMAIL_VERIFICATION_CAPTCHA_SECRET);
		$captchaField->addError(90403, ErrorCodes::ERR_90403);
		$captchaField->setMissingErrorCode(90403);
		$captchaField->addError(90404, ErrorCodes::ERR_90404);
		$captchaField->setInvalidErrorCode(90404);
		$form->addField($captchaField);

		return $form;
	}
}
