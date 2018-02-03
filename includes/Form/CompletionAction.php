<?php

namespace Catalyst\Form;

/**
 * Represents an action which can be performed based
 * @abstract
 */
abstract class CompletionAction {
	/**
	 * Get JavaScript to run on success
	 * 
	 * @return string JS code for success
	 */
	abstract public function getJs() : string;
}

