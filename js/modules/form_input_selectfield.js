<?php
header("Content-Type: application/javascript; charset=UTF-8", true);

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

define("NO_SESSION", true);

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\Form\Field\SelectField;
?>
{
	<?php require_once REAL_ROOTDIR."js/modules/form_input_header.js"; ?>
	
	const className = <?= json_encode(SelectField::class) ?>;

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

			this.wrapper = this.element.parentNode;
			this.helperText = document.querySelector("span.helper-text[for="+this.id+"]");

			this.required = this.element.getAttribute("required") === "required";

			this.options = JSON.parse(this.element.getAttribute("data-option-keys"));

			// remove to prevent duplicates for BC instantiation methods
			window.log(this.id, "Adding this.verify as a listener for change events if it was not already");this.verify.bind(this, true), 
			this.element.addEventListener("change", this.verify.bind(this, true), {passive: true});
		}

		/**
		 * @param errorType one of MISSING or INVALID
		 * @param bool passive
		 */
		markError(errorType, passive) {
			if (errorType == MISSING) {
				var errorMessage = this.helperText.getAttribute("data-missing-error");
				window.log(this.id, "Marking with error type MISSING, error message "+errorMessage, true);
			} else if (errorType == INVALID) {
				var errorMessage = this.helperText.getAttribute("data-invalid-error");
				window.log(this.id, "Marking with error type INVALID, error message "+errorMessage, true);
			} else {
				throw "Invalid error type passed to "+className+".markError ("+errorType+")";
			}

			this.wrapper.classList.add("invalid", "marked-invalid");
			this.helperText.setAttribute("data-error", errorMessage);

			if (!passive) {
				M.escapeToast(errorMessage);
				this.element.focus();
			}
		}

		/**
		 * @return string
		 */
		getValue() {
			return this.element.value;
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
			let value = this.getValue();
			window.log(this.id, "Verifying with value "+JSON.stringify(value));

			if (value.length) {
				if (!this.options.includes(value)) {
					window.log(this.id, "Value is not within the list of possible options (how ???)", true);
					this.markError(INVALID, passive);
					return false;
				}
			} else {
				if (this.required) {
					window.log(this.id, "Required but empty value", true);
					this.markError(MISSING, passive);
					return false;
				}
			}
			window.log(this.id, "Verification successful");

			this.wrapper.classList.remove("invalid", "marked-invalid");

			return true;
		}
	}
};
