class DateField extends HTMLElement {
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
			$$b.type = 'text';
			$$b.setAttribute('class', 'datepicker ' + (this.properties.primary || this.properties.valueIsPrefilled ? ' active' : ''));
			$$b.id = this.properties.formDistinguisher + '-input-' + this.properties.distinguisher;
			$$b.setAttribute('autocomplete', this.properties.autocomplete);
			$$b.required = this.properties.required;
			$$b.autofocus = this.properties.primary;
			$$b.value = this.properties.valueIsPrefilled ? this.properties.value : '';
			$$a.appendChild($$b);
			var $$c = this.label = new FormLabel(this.properties).children[0];
			$$a.appendChild($$c);
			var $$d = this.helperText = new FormLabelHelperSpan(this.properties).children[0];
			$$a.appendChild($$d);
			return $$a;
		})());

		this.pickerInstance = M.Datepicker.init(this.element, {
			showDaysInNextAndPreviousMonths: true,
			onSelect: this.verify.bind(this, true),
			showClearBtn: true
		});
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
		if (this.pickerInstance.isOpen) {
			this.element.value = this.pickerInstance.toString();
			if (this.element.value.length) {
				this.label.classList.add("active");
			}
		}

		return this.element.value;
	}

	/**
	 * The value to actually be sent to the server
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
			if (!/^((Jan|Mar|May|Jul|Aug|Oct|Dec)\s(0[1-9]|[1-2][0-9]|3[0-1]),\s2[0-9]{3}|(Apr|Jun|Sep|Nov)\s(0[1-9]|[1-2][0-9]|30),\s2[0-9]{3}|Feb\s(0[1-9]|[1-2][0-8]),\s2[0-9]{3}|Feb\s29,\s2([048]00|[0-9][02468][48]|[0-9][13579][26]))$/.test(value)) {
				window.log(this.properties.distinguisher, "Pattern ^((Jan|Mar|May|Jul|Aug|Oct|Dec)\\s(0[1-9]|[1-2][0-9]|3[0-1]),\\s2[0-9]{3}|(Apr|Jun|Sep|Nov)\\s(0[1-9]|[1-2][0-9]|30),\\s2[0-9]{3}|Feb\\s(0[1-9]|[1-2][0-8]),\\s2[0-9]{3}|Feb\s29,\\s2([048]00|[0-9][02468][48]|[0-9][13579][26]))$ does not match value", true);
				this.markError(this.properties.errors.invalidDate, passive);
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
