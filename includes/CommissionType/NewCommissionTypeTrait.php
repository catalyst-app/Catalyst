<?php

namespace Catalyst\CommissionType;

use \Catalyst\Form\Form;

/*
name <255
blurb <255
desc (md)
sort=0
base cost <64
base usd (num, step=2, min=0, max=1000000)
attrs
bool phys addy
bool quotes
bool requests
bool trades
bool commissions
modifier groups creator
	modifiers:
		name <60
		price <64
		usd (0-100000, .01 step)
	sorting
payments
	type < 64
	addy long
	instructions md
	sorting
stages
	label
	sorting
 */

/**
 * trait which contains the new commission type form
 */
trait NewCommissionTypeTrait {
	/**
	 * Get the form used to create a new commission type
	 * 
	 * @return Form
	 */
	public static function getNewCommissionTypeForm() : Form {
		$form = new Form();

		$nameField = new TextField();

		return $form;
	}
}
