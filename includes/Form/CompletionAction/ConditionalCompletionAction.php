<?php

namespace Catalyst\Form\CompletionAction;

/**
 * Redirects to a given URL
 */
class ConditionalCompletionAction extends AbstractCompletionAction {
	/**
	 * Conditions and associated actions
	 * 
	 * Format: [condition, action]
	 * 
	 * @var mixed[][]
	 */
	protected $conditions = [];
	/**
	 * Action to run on else
	 * 
	 * @var AbstractCompletionAction|null
	 */
	protected $else = null;

	/**
	 * Create a new ConditionalCompletionAction
	 * 
	 * @param array $conditions Conditions
	 */
	public function __construct(array $conditions=[]) {
		array_map(function ($condition) {
			$this->addCondition(...$condition);
		}, $conditions);
	}

	/**
	 * Add a condition
	 * 
	 * @param string $condition Condition to test for the action
	 * @param AbstractCompletionAction $action Action to perform if the condition is true
	 */
	public function addCondition(string $condition, AbstractCompletionAction $action) : void {
		$this->conditions[] = [$condition, $action];
	}

	/**
	 * Get the conditions
	 * 
	 * @return array The action's array of conditions
	 */
	public function getConditions() : array {
		return $this->conditions;
	}

	/**
	 * Set the conditions
	 * 
	 * @param array $conditions The new array to use
	 */
	public function setConditions(array $conditions) : void {
		$this->conditions = [];
		array_map(function ($condition) {
			$this->addCondition(...$condition);
		}, $conditions);
	}

	/**
	 * Get the condition to run on else
	 * 
	 * @return AbstractCompletionAction Action to run if none of the conditions are true
	 */
	public function getElse() : ?AbstractCompletionAction {
		return $this->else;
	}

	/**
	 * Set the condition to run on else
	 * 
	 * @param AbstractCompletionAction $action Action to run if none of the conditions are true
	 */
	public function setElse(AbstractCompletionAction $action) : void {
		$this->else = $action;
	}

	/**
	 * Get JavaScript to run on success
	 * 
	 * @return string JS code for success
	 */
	public function getJs() : string {
		if (count($this->getConditions()) + !is_null($this->getElse()) == 0) {
			return '';
		} else if (count($this->getConditions()) + !is_null($this->getElse()) == 1) {
			if (!is_null($this->getElse())) {
				return $this->getElse()->getJs();
			} else {
				return $this->getConditions()[0][1]->getJs();
			}
		}
		$str = '';
		$str .= 'window.log('.json_encode(__CLASS__).', '.json_encode("checking ".count($this->getConditions())." conditions").');';
		for ($i=0; $i < count($this->getConditions()); $i++) { 
			if ($i == 0) {
				$str .= 'if';
			} else {
				$str .= 'else if';
			}
			$str .= '('.$this->getConditions()[$i][0].')';
			$str .= '{';
			$str .= 'window.log('.json_encode(__CLASS__).', '.json_encode("condition ".$i." (".$this->getConditions()[$i][0].") is true, invoking result").');';
			$str .= $this->getConditions()[$i][1]->getJs();
			$str .= '}';
		}
		if (!is_null($this->getElse())) {
			$str .= 'else';
			$str .= '{';
			$str .= 'window.log('.json_encode(__CLASS__).', '.json_encode("no conditions passed, invoking else").');';
			$str .= $this->getElse()->getJs();
			$str .= '}';
		}
		return $str;
	}
}

