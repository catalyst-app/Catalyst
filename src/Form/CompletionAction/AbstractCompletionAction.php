<?php

namespace Catalyst\Form\CompletionAction;

/**
 * Represents an action which can be performed after form submission
 * @abstract
 */
abstract class AbstractCompletionAction {
	/**
	 * Get JavaScript to run on success
	 * 
	 * @return string JS code for success
	 */
	abstract public function getJs() : string;
}

