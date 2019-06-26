<?php

namespace Catalyst\Form\Field;

/**
 * A static html field
 */
class StaticHTMLField extends AbstractField {
	/**
	 * The HTML to display
	 * @var string
	 */
	protected $staticHtml = "";

	/**
	 * @return string The name of the web component tag
	 */
	public static function getWebComponentName() : string {
		return "static-html-field";
	}

	/**
	 * Set the HTML to display to a new value
	 * 
	 * @param string $staticHtml New HTML
	 */
	public function setStaticHtml(string $staticHtml) : void {
		$this->staticHtml = $staticHtml;
	}

	/**
	 * Return the field's static HTML to display
	 * 
	 * @return string The field's HTML
	 */
	public function getStaticHtml() : string {
		return $this->staticHtml;
	}

	/**
	 * @return array Properties for the created field element
	 */
	public function getProperties() : array {
		return [
			"formDistinguisher" => $this->getForm()->getDistinguisher(),
			"distinguisher" => "static-html-".hash("sha256", $this->getStaticHtml()),
			"html" => $this->getStaticHtml(),
			"errors" => $this->getErrorMessages(),
		];
	}

	/**
	 * Return the field's HTML input
	 * 
	 * @return string The HTML to display
	 */
	public function getHtml() : string {
		return $this->getWebComponentHtml();
	}

	/**
	 * Full JS validation code, including if statement and all
	 * 
	 * None for this field, as it is just static
	 * 
	 * @return string The JS to validate the field
	 */
	public function getJsValidator() : string {
		return 'if (!document.getElementById('.json_encode($this->getId()).').verify()) { return; }';
	}

	/**
	 * Return JS code to store the field's value in $formDataName
	 * 
	 * None for this field, as it is just a static thing, but here for consistency
	 * 
	 * @param string $formDataName The name of the FormData variable
	 * @return string Code to use to store field in $formDataName
	 */
	public function getJsAggregator(string $formDataName) : string {
		return $formDataName.'.append('.json_encode($this->getDistinguisher()).', document.getElementById('.json_encode($this->getId()).').getAggregationValue());';
	}

	/**
	 * Nothing to be done, as it is just a static thing, but here for consistency
	 */
	public function checkServerSide(?array &$requestArr=null) : void {
		return;
	}
}
