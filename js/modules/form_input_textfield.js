<?php
header("Content-Type: application/javascript; charset=UTF-8", true);

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

define("NO_SESSION", true);

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\Form\Field\TextField;
?>
{
	<?php require_once REAL_ROOTDIR."js/modules/form_input_header.js"; ?>
	
	const className = <?= json_encode(TextField::class) ?>;

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

			this.label = document.querySelector("label[for="+this.id+"]");
			this.helperText = document.querySelector("span.helper-text[for="+this.id+"]");

			this.required = this.element.getAttribute("required") === "required";

			this.pattern = this.element.getAttribute("pattern");
			this.maxLength = this.element.getAttribute("maxlength");
			this.disallowed = JSON.parse(this.element.getAttribute("data-disallowed"));

			// remove to prevent duplicates for BC instantiation methods
			window.log(this.id, "Adding this.verify as a listener for input events if it was not already");
			this.element.removeEventListener("input", this.verify.bind(this, true));
			this.element.addEventListener("input", this.verify.bind(this, true));
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

			this.element.classList.add("invalid", "marked-invalid");
			this.label.classList.add("active");
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
		 * @param bool passive If the form is actively verifying the content (and thus toasts/etc should show) or
		 *     false if verify is being called from input
		 * @return bool
		 */
		verify(passive=false) {
			let value = this.getValue();
			window.log(this.id, "Verifying with value "+JSON.stringify(value));

			if (value.length) {
				if (this.maxLength && value.length > this.maxLength) {
					window.log(this.id, "Value length "+value.length+" exceeds maximum length "+this.maxLength, true);
					this.markError(INVALID, passive);
					return false;
				}
				if (!(new RegExp(this.pattern)).test(value)) {
					window.log(this.id, "Pattern "+this.pattern+" does not match value", true);
					this.markError(INVALID, passive);
					return false;
				}
				if (this.disallowed.includes(value)) {
					window.log(this.id, "Value is included within the list of explicitly disallowed values", true);
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

			this.element.classList.remove("invalid", "marked-invalid");

			return true;
		}
	}
};
