let className = "Catalyst\\Form\\Field\\TextField";
window.log("Form input handlers", "Registering "+className);

if (!window.hasOwnProperty("formInputHandlers")) {
	window.formInputHandlers = {};
}

let VALID = 0;
let INVALID = 1;
let MISSING = 2;

class TextField extends HTMLElement {
	constructor(properties) {
		super();

		this.test = "TEST";

		// decide if the HTML was created beforehand (e.g. from server) or without attributed (e.g. document.createElement)
		if (properties != undefined) {
			this.properties = properties;
		} else {
			this.properties = JSON.parse(this.getAttribute("data-properties"));
		}

		window.log(this.constructor.name, "Constructing an object to represent "+this.properties.distinguisher);

		// BC
		this.id = this.properties.formDistinguisher + '-input-' + this.properties.distinguisher;

		// var host = this.attachShadow({ mode: "open" });
		this.appendChild((() => {
			let optionalAttributes = {};
			if (this.properties.maxlength) {
				optionalAttributes['maxlength'] = this.properties.maxlength;
			}
			let className = 'form-field';
			if (this.properties.required || this.properties.primary) {
				className += ' active';
			}
			return (() => {
				var $$a = document.createElement('div');
				$$a.setAttribute('class', 'input-field col s12');
				var $$b = this.element = document.createElement('input');
				$$b.id = this.properties.formDistinguisher + '-input-' + this.properties.distinguisher + '-element';
				$$b.type = 'text';
				$$b.setAttribute('autocomplete', this.properties.autocomplete);
				$$b.setAttribute('pattern', this.properties.pattern);
				$$b.value = this.properties.value;
				$$b.required = this.properties.required;
				$$b.autofocus = this.properties.primary;
				$$b.setAttribute('class', className);
				$$b.setAttributes(optionalAttributes);
				$$a.appendChild($$b);
				var $$c = new FormLabel(this.properties).children[0];
				$$a.appendChild(this.label = $$c);
				var $$d = new FormLabelHelperSpan(this.properties).children[0];
				$$a.appendChild(this.helperText = $$d);
				return $$a;
			}).call(this);
		}).call(this));

		this.element.addEventListener("input", this.verify.bind(this, true), {passive: true});
	}

	/**
	 * @param string errorMessage
	 * @param bool passive
	 */
	markError(errorMessage, passive) {
		window.log(this.properties.distinguisher, "Marking with error error message "+errorMessage, true);

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
	 * @return string
	 */
	getAggregationValue() {
		return this.getValue();
	}

	/**
	 * @param bool passive If the form is actively verifying the content (and thus toasts/etc should show) or
	 *	 false if verify is being called from input
	 * @return bool
	 */
	verify(passive=false) {
		let value = this.getValue();
		window.log(this.properties.distinguisher, "Verifying with value "+JSON.stringify(value));

		if (value.length) {
			if (this.properties.maxlength && value.length > this.properties.maxlength) {
				window.log(this.properties.distinguisher, "Value length "+value.length+" exceeds maximum length "+this.properties.maxlength, true);
				this.markError(this.properties.invalidError, passive);
				return false;
			}
			if (!(new RegExp(this.properties.pattern)).test(value)) {
				window.log(this.properties.distinguisher, "Pattern "+this.properties.pattern+" does not match value", true);
				this.markError(this.properties.invalidError, passive);
				return false;
			}
			if (this.properties.disallowed.includes(value)) {
				window.log(this.properties.distinguisher, "Value is included within the list of explicitly disallowed values", true);
				this.markError(this.properties.invalidError, passive);
				return false;
			}
		} else {
			if (this.properties.required) {
				window.log(this.properties.distinguisher, "Required but empty value", true);
				this.markError(this.properties.missingError, passive);
				return false;
			}
		}
		window.log(this.properties.distinguisher, "Verification successful");

		this.element.classList.remove("invalid", "marked-invalid");

		return true;
	}
}

window.formInputHandlers[className] = TextField;

window.customElements.define("text-field", TextField);
