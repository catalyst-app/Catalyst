<?php

namespace Catalyst\Form\Field;

/**
 * Used by fields which contains labels
 */
trait LabelTrait {
	/**
	 * self explanatory
	 * 
	 * @var string
	 */
	protected $label = "";
	/**
	 * Helper text, shown below the field
	 * 
	 * @var string
	 */
	protected $helperText = "";

	/**
	 * Get the current label
	 * 
	 * @return string the label
	 */
	public function getLabel() : string {
		return $this->label;
	}

	/**
	 * Set the label
	 * 
	 * @param string $label The new label
	 */
	public function setLabel(string $label) : void {
		$this->label = $label;
	}

	/**
	 * Get the current helper text
	 * 
	 * @return string the helper text
	 */
	public function getHelperText() : string {
		return $this->helperText;
	}

	/**
	 * Set the helper text
	 * 
	 * @param string $helperText The new helper text
	 */
	public function setHelperText(string $helperText) : void {
		$this->helperText = $helperText;
	}

	/**
	 * Get the HTML for the label
	 * 
	 * @return string
	 */
	protected function getLabelHtml(bool $escapeLabelText=true) : string {
		$str = '';
		
		$str .= '<label';
		$str .= ' for="'.htmlspecialchars($this->getId()).'"';
		$str .= '>';
		if ($escapeLabelText) {
			$str .= htmlspecialchars($this->getLabel());
		} else {
			$str .= $this->getLabel();
		}
		if ($this->isRequired()) {
			$str .= '<span class="red-text">';
			$str .= '&nbsp;*';
			$str .= '</span>';
		}
		$str .= '</label>';

		$str .= '<span';
		$str .= ' for="'.htmlspecialchars($this->getId()).'"';
		$str .= ' class="helper-text"';
		$str .= ' data-error="'.htmlspecialchars($this->getErrorMessage($this->getInvalidErrorCode())).'"';
		$str .= '>';

		$str .= htmlspecialchars($this->getHelperText());

		$str .= '</span>';

		return $str;
	}
}
