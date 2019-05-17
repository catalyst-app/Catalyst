<?php

namespace Catalyst\Form\Field;

use \Catalyst\Form\Form;
use \InvalidArgumentException;

/**
 * Literally wraps a field in an element, basic passthrough...thats all
 */
class WrappedField extends AbstractField {
	use LabelTrait;

	/**
	 * Array of fields
	 * @var AbstractField|null
	 */
	protected $field = null;
	/**
	 * classes to wrap the field in
	 * @var string
	 */
	protected $wrapperClasses = "";

	/**
	 * Get the field to wrap
	 * 
	 * @return AbstractField|null
	 */
	public function getField() : ?AbstractField {
		return $this->field;
	}

	/**
	 * Set the field to wrap
	 * 
	 * @param AbstractField $field
	 */
	public function setField(AbstractField $field) : void {
		$this->field = $field;
	}

	/**
	 * Get the classes to wrap the field with
	 * 
	 * @return string
	 */
	public function getWrapperClasses() : string {
		return $this->wrapperClasses;
	}

	/**
	 * Set the wrapper classes to a new value
	 * 
	 * @param string $wrapperClasses
	 */
	public function setWrapperClasses(string $wrapperClasses) : void {
		$this->wrapperClasses = $wrapperClasses;
	}

	/**
	 * Copied from AbstractField, sets internal field's Form
	 * 
	 * @param Form $form
	 */
	public function setForm(Form $form) : void {
		if (is_null($this->getField())) {
			throw new InvalidArgumentException(basename(__CLASS__)." not given a field to wrap");
		}
		$this->form = $form;
		$this->getField()->setForm($form);
	}

	/**
	 * Return the field's HTML input
	 * 
	 * @return string The HTML to display
	 */
	public function getHtml() : string {
		if (is_null($this->getField())) {
			throw new InvalidArgumentException(basename(__CLASS__)." not given a field to wrap");
		}
		$str = '';

		$str .= '<div';
		$str .= ' class="'.htmlspecialchars($this->getWrapperClasses()).'">';

		$str .= $this->getField()->getHtml();

		$str .= '</div>';

		return $str;
	}

	/**
	 * Full JS validation code, including if statement and all
	 * 
	 * @return string The JS to validate the field
	 */
	public function getJsValidator() : string {
		if (is_null($this->getField())) {
			throw new InvalidArgumentException(basename(__CLASS__)." not given a field to wrap");
		}
		return $this->getField()->getJsValidator();
	}

	/**
	 * Return JS code to store the field's value in $formDataName
	 * 
	 * @param string $formDataName The name of the FormData variable
	 * @return string Code to use to store field in $formDataName
	 */
	public function getJsAggregator(string $formDataName) : string {
		if (is_null($this->getField())) {
			throw new InvalidArgumentException(basename(__CLASS__)." not given a field to wrap");
		}
		return $this->getField()->getJsAggregator($formDataName);
	}

	/**
	 * Return JS code which should be added in the main onload closure
	 * 
	 * @return string
	 */
	public function getJsOnload() : string {
		if (is_null($this->getField())) {
			throw new InvalidArgumentException(basename(__CLASS__)." not given a field to wrap");
		}
		return $this->getField()->getJsOnload();
	}

	/**
	 * Check the field's forms on the servers side
	 * 
	 * @param array $requestArr Array to find the form data in
	 */
	public function checkServerSide(?array &$requestArr=null) : void {
		if (is_null($this->getField())) {
			throw new InvalidArgumentException(basename(__CLASS__)." not given a field to wrap");
		}
		$this->getField()->checkServerSide($requestArr);
	}
}
