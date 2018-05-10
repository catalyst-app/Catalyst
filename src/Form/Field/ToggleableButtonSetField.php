<?php

namespace Catalyst\Form\Field;

use \Catalyst\Form\Form;

/**
 * Represents a set of sets of buttons field
 */
class ToggleableButtonSetField extends ToggleableButtonsField {
	/**
	 * buttons, in form label => [key, title, tooltip]
	 * 
	 * @var array
	 */
	protected $buttons = [];

	/**
	 * Get the input's button set
	 * 
	 * @return array Current maxmimum
	 */
	public function getButtons() : array {
		return $this->buttons;
	}

	/**
	 * Set the buttons
	 * 
	 * @param array $buttons New buttons
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

		$str .= '<strong>';
		$str .= htmlspecialchars($this->getLabel());
		$str .= '</strong>';

		$str .= '</p>';

		$str .= '<div';
		$str .= ' class="toggle-btn-set-container col s12"';
		$str .= ' id="'.htmlspecialchars($this->getId()).'"';
		$str .= '>';

		foreach ($this->getButtons() as $key => $buttonSet) {
			$str .= '<p';
			$str .= ' class="no-bottom-margin col s12">';

			$str .= htmlspecialchars($key);

			$str .= ' (<a href="#" class="toggle-btn-invert-btn">Invert</a>)';

			$str .= '</p>';

			$str .= '<div';
			$str .= ' class="toggle-btn-set-container col s12"';
			$str .= ' id="'.htmlspecialchars($this->getId()).'"';
			$str .= '>';

			foreach ($buttonSet as $button) {
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
		}

		$str .= '</div>';

		return $str;
	}
}
