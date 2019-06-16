<?php
header("Content-Type: application/javascript; charset=UTF-8", true);

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

define("NO_SESSION", true);

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\Form\Field\HiddenInputField;
?>
{
	<?php require_once REAL_ROOTDIR."js/modules/form_input_header.js"; ?>
	
	const className = <?= json_encode(HiddenInputField::class) ?>;

	window.log("Form input handlers", "Registering "+className);

	window.formInputHandlers[className] = class {
		constructor(element) {
			this.fieldType = className;

			window.log(className, "Constructing an object to represent #"+element.id);

			if (!(element instanceof HTMLElement)) {
				throw "Provided element to "+className+" constructor is not a HTMLElement";
			}
			if (element.getAttribute("data-field-type") !== className) {
				throw "Provided element to "+className+" constructor does not have a data-field-type of "+className;
			}

			this.element = element;
			
			this.id = this.element.id;

			this.hiddenInputId = this.element.getAttribute("data-hidden-input-id");
			this.hiddenInput = document.getElementById(this.hiddenInputId);
		}

		/**
		 * @param errorType one of MISSING or INVALID
		 * @param bool passive
		 */
		markError(errorType, passive) {
			if (errorType == MISSING) {
				window.log(this.id, "Showing message for error type MISSING and throwing exception", true);
				M.escapeToast(this.element.getAttribute("data-missing-error"));
			} else if (errorType == INVALID) {
				window.log(this.id, "Unable to mark with error type INVALID due to form type and logic", true);
				M.escapeToast("An unexpected error occurred.")
			} else {
				throw "Invalid error type passed to "+className+".markError ("+errorType+")";
			}

			if (!passive) {
				throw className+" – the actual page does not have the element ID "+this.hiddenInputId;
			}
		}

		/**
		 * @return string
		 */
		getValue() {
			return this.hiddenInput.value;
		}

		/**
		 * @return string
		 */
		getAggregationValue() {
			return this.getValue();
		}

		/**
		 * @param bool passive If the form is actively verifying the content (and thus toasts/etc should show) or
		 *     false if verify is being called from input
		 * @return bool
		 */
		verify(passive=false) {
			window.log(this.id, "Verifying hidden input field exists");

			if (this.hiddenInput == null) {
				window.log(this.id, "Element with ID "+this.hiddenInputId+" does not exist", true);
				this.markError(MISSING);
				return false;
			}
			
			window.log(this.id, "Verification successful");
			return true;
		}
	}
};
