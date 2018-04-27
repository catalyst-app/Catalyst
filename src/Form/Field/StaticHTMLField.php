<?php

namespace Catalyst\Form\Field;

/**
 * A sttic html field
 */
class StaticHTMLField extends AbstractField {
	/**
	 * The HTML to display
	 * @var string
	 */
	protected $html = "";

	/**
	 * Set the HTML to display to a new value
	 * 
	 * @param string $html New HTML
	 */
	public function setHtml(string $html) : void {
		$this->html = $html;
	}

	/**
	 * Return the field's HTML to display
	 * 
	 * Acts as a getter, but also satiates AbstractField's requriements, which is why its down here
	 * 
	 * @return string The field's HTML
	 */
	public function getHtml() : string {
		return $this->html;
	}

	/**
	 * Full JS validation code, including if statement and all
	 * 
	 * None for this field, as it is just static HTML
	 * 
	 * @return string The JS to validate the field
	 */
	public function getJsValidator() : string {
		return '';
	}

	/**
	 * Return JS code to store the field's value in $formDataName
	 * 
	 * None for this field, as it is just static HTML
	 * 
	 * @param string $formDataName The name of the FormData variable
	 * @return string Code to use to store field in $formDataName
	 */
	public function getJsAggregator(string $formDataName) : string {
		return '';
	}

	/**
	 * Nothing to be done, as it is just static HTML
	 */
	public function checkServerSide(?array &$requestArr=null) : void {
		return;
	}
}
