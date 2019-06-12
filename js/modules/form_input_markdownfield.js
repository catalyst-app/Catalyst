<?php
header("Content-Type: application/javascript; charset=UTF-8", true);

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

define("NO_SESSION", true);

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\Form\Field\MarkdownField;
?>
{
	<?php require_once REAL_ROOTDIR."js/modules/form_input_header.js"; ?>
	
	const className = <?= json_encode(MarkdownField::class) ?>;

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

			this.preview = this.element.parentNode.nextSibling;

			this.required = element.getAttribute("required") === "required";

			// remove to prevent duplicates for BC instantiation methods
			window.log(this.id, "Adding this.verify as a listener for input events if it was not already");
			this.element.removeEventListener("input", this.verify.bind(this, true));
			this.element.addEventListener("input", this.verify.bind(this, true));

			window.log(this.id, "Adding this.render as a listener for input events if it was not already");
			this.element.removeEventListener("input", this.render.bind(this));
			this.element.addEventListener("input", this.render.bind(this));
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

			if (this.required) {
				if (!value.length) {
					window.log(this.id, "Required but empty value", true);
					this.markError(MISSING, passive);
					return false;
				}
			}

			window.log(this.id, "Verification successful");

			this.element.classList.remove("invalid", "marked-invalid");

			return true;
		}

		render() {
			window.log(this.id, "Clearing existing render timeout");
			clearTimeout(this.timeout);

			window.log(this.id, "Setting render timeout for 200ms");

			this.timeout = setTimeout((function() {
				window.log(this.id, "rendering");
				this.preview.classList.remove("rendered-markdown");
				this.preview.classList.add("raw-markdown");
				this.preview.textContent = this.getValue();
				renderMarkdownArea(this.preview);
			}).bind(this), 200);
		}
	}
};
