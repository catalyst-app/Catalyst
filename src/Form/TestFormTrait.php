<?php

namespace Catalyst\Form;

use \Catalyst\API\ErrorCodes;
use \Catalyst\Form\Field\{AutocompleteValues,EmailField,TextField,PasswordField,CaptchaField};
use \Catalyst\Form\Form;

trait TestFormTrait {
	/**
	 * This form will test many inputs for the browser
	 */
	public static function getTestForm() : Form {
		$form = new Form();

		$form->setDistinguisher(self::getDistinguisherFromFunctionName(__FUNCTION__)); // get-dash-case from camelCase
		$form->setMethod(Form::POST);
		$form->setEndpoint("internal/test/form/");
		$form->setButtonText("LOGIN");
		$form->setPrimary(true);

		$textField = new TextField();
		$textField->setDistinguisher("text-field-test-one");
		$textField->setLabel("Required test field with pattern ^([a-g][a-g]?[a-g]?|[h-j]+)$, max length 10, disallowed 'abc','def'");
		$textField->setMaxLength(10);
		$textField->setDisallowed(["abc","def"]);
		$textField->setRequired(true);
		$textField->setPattern('^([a-g][a-g]?[a-g]?|[h-j]+)$');
		$textField->addError(99800, ErrorCodes::ERR_99800);
		$textField->setMissingErrorCode(99800);
		$textField->addError(99801, ErrorCodes::ERR_99801);
		$textField->setInvalidErrorCode(99801);
		$textField->addError(99802, ErrorCodes::ERR_99802);
		$form->addField($textField);

		$textField2 = new TextField();
		$textField2->setDistinguisher("text-field-test-two");
		$textField2->setLabel("Non-required test field with pattern ^([a-g][a-g]?[a-g]?|[h-j]+)$, max length 10, disallowed 'abc','def'");
		$textField2->setMaxLength(10);
		$textField2->setDisallowed(["abc","def"]);
		$textField2->setRequired(false);
		$textField2->setPattern('^([a-g][a-g]?[a-g]?|[h-j]+)$');
		$textField2->addError(99803, ErrorCodes::ERR_99803);
		$textField2->setMissingErrorCode(99803);
		$textField2->addError(99804, ErrorCodes::ERR_99804);
		$textField2->setInvalidErrorCode(99804);
		$textField2->addError(99805, ErrorCodes::ERR_99805);
		$form->addField($textField2);

		$emailField = new EmailField();
		$emailField->setDistinguisher("test-email-no-req");
		$emailField->setLabel("Email but it's not required");
		$emailField->setRequired(false);
		$emailField->addError(99809, ErrorCodes::ERR_99809);
		$emailField->setMissingErrorCode(99809);
		$emailField->addError(99810, ErrorCodes::ERR_99810);
		$emailField->setInvalidErrorCode(99810);
		$form->addField($emailField);

		$emailField2 = new EmailField();
		$emailField2->setDistinguisher("test-email-req");
		$emailField2->setLabel("Email but it's required");
		$emailField2->setRequired(true);
		$emailField2->addError(99812, ErrorCodes::ERR_99812);
		$emailField2->setMissingErrorCode(99812);
		$emailField2->addError(99813, ErrorCodes::ERR_99813);
		$emailField2->setInvalidErrorCode(99813);
		$form->addField($emailField2);

		$captchaField = new CaptchaField();
		$captchaField->setDistinguisher("test-captcha");
		$captchaField->setRequired(true);
		$captchaField->setSiteKey(CaptchaField::DEBUG_CAPTCHA_KEY);
		$captchaField->setSecretKey(CaptchaField::DEBUG_CAPTCHA_SECRET);
		$captchaField->addError(99806, ErrorCodes::ERR_99806);
		$captchaField->setMissingErrorCode(99806);
		$captchaField->addError(99807, ErrorCodes::ERR_99807);
		$captchaField->setInvalidErrorCode(99807);
		$form->addField($captchaField);

		return $form;
	}
}
