class TimeField extends HTMLElement {
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
			$$b.setAttribute('class', 'timepicker ' + (this.properties.primary || this.properties.valueIsPrefilled ? ' active' : ''));
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

		this.pickerInstance = M.Timepicker.init(this.element, {
			onSelect: this.verify.bind(this, true),
			onCloseEnd: this.verify.bind(this, true),
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
			this.element.value = M.Timepicker._addLeadingZero(this.pickerInstance.hours)+':'+M.Timepicker._addLeadingZero(this.pickerInstance.minutes)+" "+this.pickerInstance.amOrPm;;
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
			if (!/^(0?[1-9]|1[0-2]):[0-5][0-9]\s(A|P)M$/.test(value)) {
				window.log(this.properties.distinguisher, "Pattern ^(0?[1-9]|1[0-2]):[0-5][0-9]\s(A|P)M$ does not match value", true);
				this.markError(this.properties.errors.invalidTime, passive);
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
