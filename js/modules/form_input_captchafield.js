<?php
header("Content-Type: application/javascript; charset=UTF-8", true);

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

define("NO_SESSION", true);

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\Form\Field\CaptchaField;
?>
{
	<?php require_once REAL_ROOTDIR."js/modules/form_input_header.js"; ?>

	const className = <?= json_encode(CaptchaField::class) ?>;

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
		}

		/**
		 * @param errorType one of MISSING or INVALID
		 * @param bool passive
		 */
		markError(errorType, passive) {
			if (errorType == MISSING) {
				var errorMessage = this.element.getAttribute("data-missing-error");
				window.log(this.id, "Marking with error type MISSING, error message "+errorMessage, true);
			} else if (errorType == INVALID) {
				var errorMessage = this.element.getAttribute("data-invalid-error");
				window.log(this.id, "Marking with error type INVALID, error message "+errorMessage, true);
			} else {
				throw "Invalid error type passed to "+className+".markError ("+errorType+")";
			}

			this.element.setAttribute("data-error", errorMessage);

			this.element.classList.add("invalid");
			this.element.classList.remove("valid");

			if (!passive) {
				M.escapeToast(errorMessage);
				this.element.focus();
			}
		}

		/**
		 * @return string
		 */
		getValue() {
			return grecaptcha.getResponse();
		}

		getAggregationValue = this.getValue;

		/**
		 * @param bool passive If the form is actively verifying the content (and thus toasts/etc should show) or
		 *     false if verify is being called from input
		 * @return bool
		 */
		verify(passive=false) {
			let value = this.getValue();
			window.log(this.id, "Verifying with value "+JSON.stringify(value));

			if (!value.length) {
				window.log(this.id, "CAPTCHA value is missing or invalid (no way to tell)", true);
				this.markError(INVALID, passive);
				return false;
			}

			window.log(this.id, "Verification successful");

			this.element.classList.add("valid");
			this.element.classList.remove("invalid");

			return true;
		}

		/**
		 * Wraps the real verify, making instances as needed
		 */
		static verify() {
			window.log(className, "Static method verify called, searching for CAPTCHA with class 'g-recaptcha'");
			
			var captchas = document.getElementsByClassName("g-recaptcha");

			window.log(className, ""+captchas.length+" CAPTCHA(s) found");

			if (captchas.length == 0) {
				window.log(className, "No CAPTCHAs were found, yet static method verify was invoked.  This indicated improper usage of this method or an incorrectly declared CAPTCHA element.", true);
				return;
			}

			window.log(className, "Verifying first CAPTCHA with ID "+captchas[0].id);

			(new window.formInputHandlers[className](captchas[0])).verify();
		}
	}

	// recaptcha's callbacks don't work when directly passing static methods
	window.verifyCaptchas = window.formInputHandlers[className].verify;
};
