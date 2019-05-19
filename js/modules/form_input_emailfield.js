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
			this.label = document.querySelector("label[for="+this.id+"]")
			this.helperText = document.querySelector("span.helper-text[for="+this.id+"]");

			this.required = element.getAttribute("required") === "required";
		}

		/**
		 * @param errorType one of MISSING or INVALID
		 */
		markError(errorType) {
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

			M.escapeToast(errorMessage);

			this.element.focus();
		}

		/**
		 * @return string
		 */
		getValue() {
			return this.element.value;
		}

		/**
		 * @return bool
		 */
		verify() {
			let value = this.getValue();
			window.log(this.id, "Verifying with value "+JSON.stringify(value));

			if (this.required) {
				if (!value.length) {
					window.log(this.id, "Required but empty value", true);
					this.markError(MISSING);
					return false;
				}
			}
			if (value.length) {
				if (value.length > <?= json_encode(EmailField::MAX_LENGTH) ?>) {
					window.log(this.id, "Value length "+value.length+" exceeds maximum length "+<?= json_encode(EmailField::MAX_LENGTH) ?>, true);
					this.markError(INVALID);
					return false;
				}
				if (!(new RegExp(<?= json_encode(EmailField::PATTERN) ?>)).test(value)) {
					window.log(this.id, "Pattern "+<?= json_encode(EmailField::PATTERN) ?>+" does not match value", true);
					this.markError(INVALID);
					return false;
				}
			}
			window.log(this.id, "Verification successful");
			return true;
		}
	}
};
