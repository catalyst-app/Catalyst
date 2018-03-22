<?php

namespace Catalyst\Artist;

use \Catalyst\Form\CompletionAction\ConcreteRedirectCompletionAction;
use \Catalyst\Form\Field\JSConfirmField;
use \Catalyst\Form\Form;

/**
 * delete artist page form
 */
trait DeleteArtistPageFormTrait {
	/**
	 * Get the form used to delete an artist page
	 * 
	 * @return Form
	 */
	public static function getDeleteArtistPageForm() : Form {
		$form = new Form();

		$form->setDistinguisher(self::getDistinguisherFromFunctionName(__FUNCTION__)); // get-dash-case from camelCase
		$form->setMethod(Form::POST);
		$form->setEndpoint("internal/artist/delete/");
		$form->setButtonText("DELETE");
		$form->setPrimary(false);

		$completionAction = new ConcreteRedirectCompletionAction();
		$completionAction->setRedirectUrl("Dashboard");
		$form->setCompletionAction($completionAction);

		$confirmField = new JSConfirmField();
		$confirmField->setDistinguisher("confirm");
		$confirmField->setRequired(true);
		$confirmField->setPrompt("Are you sure you want to delete your page?  This will PERMANENTLY delete all commission types and other information.");
		$form->addField($confirmField);

		return $form;
	}
}
