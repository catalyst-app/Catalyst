<?php

namespace Catalyst\Artist;

use \Catalyst\Form\CompletionAction\ConcreteRedirectCompletionAction;
use \Catalyst\Form\Form;

/**
 * undelete artist page form
 */
trait UndeleteArtistPageFormTrait {
	/**
	 * Get the form used to un-delete an artist page
	 * 
	 * @return Form
	 */
	public static function getUndeleteArtistPageForm() : Form {
		$form = new Form();

		$form->setDistinguisher(self::getDistinguisherFromFunctionName(__FUNCTION__)); // get-dash-case from camelCase
		$form->setMethod(Form::POST);
		$form->setEndpoint("internal/artist/undelete/");
		$form->setButtonText("REACTIVATE");
		$form->setPrimary(false);

		$completionAction = new ConcreteRedirectCompletionAction();
		$completionAction->setRedirectUrl("Artist/Edit");
		$form->setCompletionAction($completionAction);

		return $form;
	}
}
