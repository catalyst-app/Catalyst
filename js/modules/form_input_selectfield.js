class SelectField extends HTMLElement {
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

		// BC
		this.id = this.properties.formDistinguisher + '-input-' + this.properties.distinguisher;

		this.appendChild((() => {
			var $$a = this.wrapper = document.createElement('div');
			$$a.setAttribute('class', 'input-field col s12');
			var $$b = this.element = document.createElement('select');
			$$b.id = this.properties.formDistinguisher + '-input-' + this.properties.distinguisher;
			$$b.name = this.properties.distinguisher;
			$$b.setAttribute('autocomplete', this.properties.autocomplete);
			$$b.required = this.properties.required;
			$$b.setAttribute('class', 'form-field');
			$$a.appendChild($$b);
			var $$c = document.createElement('option');
			$$c.value = '';
			$$c.selected = this.properties.value == null || this.properties.value == '';
			$$b.appendChild($$c);
			$$b.appendChildren(Object.keys(this.properties).forEach(function (key) {
			    return function () {
			        var $$e = document.createElement('option');
			        $$e.value = key;
			        $$e.selected = this.properties.value == key;
			        $$e.appendChildren(this.properties.options[key]);
			        return $$e;
			    }.call(this);
			}));
			var $$g = this.label = new FormLabel(this.properties).children[0];
			$$a.appendChild($$g);
			var $$h = this.helperText = new FormLabelHelperSpan(this.properties).children[0];
			$$a.appendChild($$h);
			return $$a;
		})());

		this.element.addEventListener("change", this.verify.bind(this, true), {passive: true});
	}

	/**
	 * @param string errorMessage
	 * @param bool passive
	 */
	markError(errorMessage, passive) {
		window.log(this.properties.distinguisher, "Marking with error error message "+errorMessage, true);

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

	getAggregationValue = this.getValue;

	/**
	 * @param bool passive If the form is actively verifying the content (and thus toasts/etc should show) or
	 *     false if verify is being called from input
	 * @return bool
	 */
	verify(passive=false) {
		let value = this.getValue();
		window.log(this.properties.distinguisher, "Verifying with value "+JSON.stringify(value));

		if (value.length) {
			if (!this.properties.options.hasOwnProperty(value)) {
				window.log(this.properties.distinguisher, "Value is not within the list of possible options (how ???)", true);
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

		this.wrapper.classList.remove("invalid", "marked-invalid");

		return true;
	}
}

// BC
{
	window.log("Form input handlers", "Registering SelectField");

	if (!window.hasOwnProperty("formInputHandlers")) {
		window.formInputHandlers = {};
	}
	window.formInputHandlers["Catalyst\\Form\\Field\\SelectField"] = SelectField;
}

window.customElements.define("select-field", SelectField);
