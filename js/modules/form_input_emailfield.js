<?php
header("Content-Type: application/javascript; charset=UTF-8", true);

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

define("NO_SESSION", true);

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\Form\Field\EmailField;
?>
{
	<?php require_once REAL_ROOTDIR."js/modules/form_input_header.js"; ?>
	
	const className = <?= json_encode(EmailField::class) ?>;

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

			this.id = element.id;

			this.element = element;
			this.label = document.querySelector("label[for="+this.id+"]");
			this.helperText = document.querySelector("span.helper-text[for="+this.id+"]");

			this.required = element.getAttribute("required") === "required";

			// remove to prevent duplicates for BC instantiation methods
			this.element.removeEventListener("input", this.verify.bind(this));
			this.element.addEventListener("input", this.verify.bind(this));
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
			}

			this.element.focus();
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

			if (this.required) {
				if (!value.length) {
					window.log(this.id, "Required but empty value", true);
					this.markError(MISSING, passive);
					return false;
				}
			}
			if (value.length) {
				if (value.length > <?= json_encode(EmailField::MAX_LENGTH) ?>) {
					window.log(this.id, "Value length "+value.length+" exceeds maximum length "+<?= json_encode(EmailField::MAX_LENGTH) ?>, true);
					this.markError(INVALID, passive);
					return false;
				}
				if (!(new RegExp(<?= json_encode(EmailField::PATTERN) ?>)).test(value)) {
					window.log(this.id, "Pattern "+<?= json_encode(EmailField::PATTERN) ?>+" does not match value", true);
					this.markError(INVALID, passive);
					return false;
				}
			}

			window.log(this.id, "Verification successful");

			this.element.classList.remove("invalid", "marked-invalid");

			return true;
		}
	}
};
