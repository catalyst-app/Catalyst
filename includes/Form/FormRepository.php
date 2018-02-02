<?php

namespace Catalyst\Form;

/**
 * Simply a repository of forms for the site.  May be split up later, if needed
 */
class FormRepository {
	/**
	 * Get the form used to add a user to the mailing list.
	 * 
	 * See /About for form usage
	 * 
	 * @return Form Form for adding a user to the mailing list
	 */
	public static function getEmailListAdditionForm() : Form {
		$form = new Form();
	}
}

