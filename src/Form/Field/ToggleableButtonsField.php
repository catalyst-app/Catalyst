<?php

namespace Catalyst\Form\Field;

use \Catalyst\Form\Form;

/**
 * Represents a set of buttons field
 */
class ToggleableButtonsField extends AbstractField {
	use LabelTrait, SupportsPrefilledValueTrait;
	/**
	 * buttons, in form [key, title, tooltip]
	 * 
	 * @var string[][]
	 */
	protected $buttons = [];

	/**
	 * Get the input's button set
	 * 
	 * @return string[][] Current maxmimum
	 */
	public function getButtons() : array {
		return $this->buttons;
	}

	/**
	 * Set the buttons
	 * 
	 * @param string[][] $buttons New buttons
	 */
	public function setButtons(array $buttons) : void {
		$this->buttons = $buttons;
	}

	/**
	 * Return the field's HTML input
	 * 
	 * @return string The HTML to display
	 */
	public function getHtml() : string {
		$str = '';
		$str .= '<p';
		$str .= ' class="no-bottom-margin col s12">';

		$str .= htmlspecialchars($this->getLabel());

		$str .= ' (<a href="#" class="toggle-btn-invert-btn">Invert</a>)';

		$str .= '</p>';

		$str .= '<div';
		$str .= ' class="toggle-btn-set-container col s12"';
		$str .= ' id="'.htmlspecialchars($this->getId()).'"';
		$str .= '>';

		foreach ($this->getButtons() as $button) {
			$str .= '<div';
			$str .= ' class="';
			$str .= 'btn toggle-btn-set-button toggle-btn tooltipped ';

			if ($this->isFieldPrefilled() && in_array($button[0], $this->getPrefilledValue())) {
				$str .= 'on';
			} else {
				$str .= 'off';
			}

			$str .= '"';
			$str .= ' data-key="'.htmlspecialchars($button[0]).'"';
			$str .= ' data-tooltip="'.htmlspecialchars($button[2]).'"';
			$str .= ' data-position="bottom"';
			$str .= ' data-delay="10"';
			$str .= '>';

			$str .= htmlspecialchars($button[1]);

			$str .= '</div>';
		}

		$str .= '</div>';
		return $str;
	}

	/**
	 * Full JS validation code, including if statement and all
	 * 
	 * @return string The JS to validate the field
	 */
	public function getJsValidator() : string {
		return '';
	}

	/**
	 * Return JS code to store the field's value in $formDataName
	 * 
	 * @param string $formDataName The name of the FormData variable
	 * @return string Code to use to store field in $formDataName
	 */
	public function getJsAggregator(string $formDataName) : string {
		return $formDataName.'.append('.json_encode($this->getDistinguisher()).', JSON.stringify($('.json_encode("#".$this->getId()." .toggle-btn-set-button.toggle-btn.on").').get().map(function(e) {return $(e).attr("data-key");})));';
	}

	/**
	 * Check the field's forms on the servers side
	 * 
	 * @param array $requestArr Array to find the form data in
	 */
	public function checkServerSide(?array &$requestArr=null) : void {
		if (is_null($requestArr)) {
			if ($this->getForm()->getMethod() == Form::POST) {
				$requestArr = &$_POST;
			} else {
				$requestArr = &$_GET;
			}
		}
		if (!array_key_exists($this->getDistinguisher(), $requestArr)) {
			$requestArr[$this->getDistinguisher()] = [];
		}
		if ($this->isRequired()) {
			if (empty($requestArr[$this->getDistinguisher()])) {
				$this->throwMissingError();
			}
		} else {
			if (empty($requestArr[$this->getDistinguisher()])) {
				return; // not required and empty, don't do further checks
			}
		}
		if (json_decode($requestArr[$this->getDistinguisher()]) === false || !is_array(json_decode($requestArr[$this->getDistinguisher()]))) {
			$this->throwInvalidError();
		}
		$requestArr[$this->getDistinguisher()] = json_decode($requestArr[$this->getDistinguisher()]);
		foreach ($requestArr[$this->getDistinguisher()] as $item) {
			if (!is_string($item)) {
				$this->throwInvalidError();
			}
			if (is_array(array_values($this->getButtons())[0])) {
				$allButtons = array_merge(...array_values($this->getButtons()));
				if (!in_array($item, array_column($allButtons, 0))) {
					$this->throwInvalidError();
				}
			} else {
				echo json_encode($this->getButtons());
				if (!in_array($item, array_column($this->getButtons(), 0))) {
					$this->throwInvalidError();
				}
			}
		}
	}
}
