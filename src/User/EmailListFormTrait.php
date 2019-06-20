<?php

namespace Catalyst\User;

use \Catalyst\API\ErrorCodes;
use \Catalyst\Form\CompletionAction\AutoClosingModalCompletionAction;
use \Catalyst\Form\Field\{AutocompleteValues,CaptchaField,CheckboxField,EmailField,TextField};
use \Catalyst\Form\Form;
use \Catalyst\Secrets;

/**
 * Email list form
 */
trait EmailListFormTrait {

	/**
	 * Get the form used to add a user to the mailing list.
	 * 
	 * See /About for form usage
	 * @return Form Form for adding a user to the mailing list
	 */
	public static function getEmailListAdditionForm() : Form {
		$form = new Form();

		$form->setDistinguisher(self::getDistinguisherFromFunctionName(__FUNCTION__)); // get-dash-case from camelCase
		$form->setMethod(Form::POST);
		$form->setEndpoint("internal/email_list/");
		$form->setButtonText("ADD");
		$form->setPrimary(false);
		$form->setResetOnSuccess(true);

		$completionAction = new AutoClosingModalCompletionAction();
		$completionAction->setContents("You have been added to the email list!");
		$completionAction->setDelay(10);
		$form->setCompletionAction($completionAction);

		$emailField = new EmailField();
		$emailField->setDistinguisher("email");
		$emailField->setLabel("Email");
		$emailField->setRequired(true);
		$emailField->setAutocompleteAttribute(AutocompleteValues::EMAIL);
		$form->addField($emailField);

		$contextField = new TextField();
		$contextField->setDistinguisher("context");
		$contextField->setLabel("Name and other information");
		$contextField->setRequired(true);
		$contextField->setAutocompleteAttribute(AutocompleteValues::NICKNAME);
		$form->addField($contextField);

		$informationRequestField = new CheckboxField();
		$informationRequestField->setDistinguisher("request-info");
		$informationRequestField->setLabel("I would like a staff member to personally contact me with additional information about our services");
		$informationRequestField->setRequired(false);
		$informationRequestField->addError(90005, ErrorCodes::ERR_90005);
		$informationRequestField->setMissingErrorCode(90005);
		$informationRequestField->setInvalidErrorCode(90005);
		$form->addField($informationRequestField);

		$captchaField = new CaptchaField();
		$captchaField->setDistinguisher("captcha");
		$captchaField->setRequired(true);
		$captchaField->setSiteKey("6LdaGlcUAAAAAE0HWwoFT4Y81ifwLV6nCsvQobk4");
		$captchaField->setSecretKey(Secrets::EMAIL_LIST_CAPTCHA_SECRET);
		$captchaField->addError(90006, ErrorCodes::ERR_90006);
		$captchaField->setMissingErrorCode(90006);
		$captchaField->addError(90007, ErrorCodes::ERR_90007);
		$captchaField->setInvalidErrorCode(90007);
		$form->addField($captchaField);

		return $form;
	}
}
