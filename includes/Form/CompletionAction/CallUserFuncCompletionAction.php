<?php

namespace Catalyst\Form\CompletionAction;

/**
 * Calls a function with data as parameter
 */
class CallUserFuncCompletionAction extends AbstractCompletionAction {
	/**
	 * Function to call
	 * 
	 * @var string
	 */
	protected $func = 'console.log';

	/**
	 * Create a new CallUserFuncCompletionAction
	 * 
	 * @param string $func The function to call
	 */
	public function __construct(string $func='') {
		$this->setFunc($func);
	}

	/**
	 * Get the func
	 * 
	 * @return string function which will be called
	 */
	public function getFunc() : string {
		return $this->func;
	}

	/**
	 * Set the function
	 * 
	 * @param string $func New function
	 */
	public function setFunc(string $func) : void {
		$this->func = $func;
	}

	/**
	 * Get JavaScript to run on success
	 * 
	 * @return string JS code for success
	 */
	public function getJs() : string {
		$str = '';
		$str .= $this->getFunc().'(data);';
		return $str;
	}
}

