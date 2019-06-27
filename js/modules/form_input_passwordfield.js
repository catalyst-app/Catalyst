class PasswordField extends HTMLElement {
	constructor(properties) {
		super();

		// decide if the HTML was created beforehand (e.g. from server) or without attributed (e.g. document.createElement)
		if (properties != undefined) {
			this.properties = properties;
		} else if (this.getAttribute("data-properties") != null) {
			this.properties = JSON.parse(this.getAttribute("data-properties"));
		} else {
			throw new Error("Element created without properties.");
		}

		window.log(this.constructor.name, "Constructing an object to represent "+this.properties.distinguisher);

		// var host = this.attachShadow({ mode: "open" });
		this.appendChild((() => {
			var $$a = document.createElement('div');
			$$a.setAttribute('class', 'input-field col s12');
			var $$b = this.element = document.createElement('input');
			$$b.id = this.properties.formDistinguisher + '-input-' + this.properties.distinguisher;
			$$b.name = this.properties.distinguisher;
			$$b.type = 'password';
			$$b.setAttribute('autocomplete', this.properties.autocomplete);
			$$b.required = this.properties.required;
			$$b.autofocus = this.properties.primary;
			$$b.setAttribute('minlength', this.properties.minlength);
			$$b.setAttribute('class', 'form-field');
			$$a.appendChild($$b);
			var $$c = this.label = new FormLabel(this.properties).children[0];
			$$a.appendChild($$c);
			var $$d = this.helperText = new FormLabelHelperSpan(this.properties).children[0];
			$$a.appendChild($$d);
			return $$a;
		})());

		this.addEventListener("input", this.verify.bind(this, true), {passive: true});
	}

	/**
	 * @param string errorMessage
	 * @param bool passive
	 */
	markError(errorMessage, passive) {
		window.log(this.properties.distinguisher, "Marking with error message "+errorMessage, true);

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
	 * Encrypt string based on server keys and whatnot specified in transit encryption inifo
	 * Base64 is kept for BC
	 * @return string
	 */
	getAggregationValue() {
		return encryptString(btoa(this.getValue()));
	}

	/**
	 * @param bool passive If the form is actively verifying the content (and thus toasts/etc should show) or
	 *	 false if verify is being called from input
	 * @return bool
	 */
	verify(passive=false) {
		let value = this.getValue();
		window.log(this.properties.distinguisher, "Verifying with value "+JSON.stringify("•".repeat(value.length)));

		if (value.length) {
			if (value.length < this.properties.minlength) {
				window.log(this.properties.distinguisher, "Value length "+value.length+" is below minimum length "+this.properties.minlength, true);
				this.markError(this.properties.errors.belowMinLength, passive);
				return false;
			}
		} else {
			if (this.properties.required) {
				window.log(this.properties.distinguisher, "Required but empty value", true);
				this.markError(this.properties.errors.requiredButMissing, passive);
				return false;
			}
		}
		window.log(this.properties.distinguisher, "Verification successful");

		this.element.classList.remove("invalid", "marked-invalid");

		return true;
	}
}
