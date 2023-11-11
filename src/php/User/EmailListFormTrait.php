<?php

namespace Catalyst\User;

use \Catalyst\API\ErrorCodes;
use \Catalyst\Form\CompletionAction\AutoClosingModalCompletionAction;
use \Catalyst\Form\Field\{AutocompleteValues, CaptchaField, CheckboxField, EmailField, TextField};
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
	public static function getEmailListAdditionForm(): Form {
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
		$form->addField($informationRequestField);

		$captchaField = new CaptchaField();
		$captchaField->setDistinguisher("captcha");
		$captchaField->setRequired(true);
		$captchaField->setSiteKey(Secrets::get("EMAIL_LIST_CAPTCHA_SITE"));
		$captchaField->setSecretKey(Secrets::get("EMAIL_LIST_CAPTCHA_SECRET"));
		$form->addField($captchaField);

		return $form;
	}
}
