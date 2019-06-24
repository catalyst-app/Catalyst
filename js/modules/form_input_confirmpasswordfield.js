class ConfirmPasswordField extends HTMLElement {
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

		this.secondaryProperties = JSON.parse(JSON.stringify(this.properties));
		this.secondaryProperties.distinguisher = "confirm-"+this.secondaryProperties.distinguisher;
		this.secondaryProperties.label = "Confirm "+this.secondaryProperties.label;

		// var host = this.attachShadow({ mode: "open" });
		this.appendChild((() => {
			let optionalAttributes = {};
			if (this.properties.maxlength) {
				optionalAttributes['maxlength'] = this.properties.maxlength;
			}
			let className = 'form-field';
			if (this.properties.value != null || this.properties.primary) {
				className += ' active';
			}
			return (() => {
				var $$a = document.createElement('div');
				var $$b = this.primaryField = new PasswordField(this.properties);
				$$a.appendChild($$b);
				var $$c = this.secondaryField = new PasswordField(this.secondaryProperties);
				$$a.appendChild($$c);
				return $$a;
			})();
		})());

		this.addEventListener("input", this.verify.bind(this, true), {passive: true});
	}

	/**
	 * @param string errorMessage
	 * @param bool passive
	 */
	markError(errorMessage, passive) {
		window.log("confirm-password-parent-"+this.properties.distinguisher, "Marking with error message "+errorMessage, true);

		this.primaryField.markError(errorMessage, passive);
		this.secondaryField.markError(errorMessage, passive);
	}

	/**
	 * @return string
	 */
	getValue() {
		return this.primaryField.getValue();
	}

	/**
	 * @return string
	 */
	getAggregationValue() {
		return this.primaryField.getAggregationValue();
	}

	/**
	 * Note that the fields do length verification themselves, we just need equiality
	 *
	 * @param bool passive If the form is actively verifying the content (and thus toasts/etc should show) or
	 *	 false if verify is being called from input
	 * @return bool
	 */
	verify(passive=false) {
		let value = this.primaryField.getValue();
		let secondaryValue = this.secondaryField.getValue();

		window.log("confirm-password-parent-"+this.properties.distinguisher, "Verifying with value "+JSON.stringify("•".repeat(value.length)));

		let pass = true;

		// check length first
		// this ensures we show the length error before confirmation error (because typing it twice then finding out its unuable is bad)
		pass = ((this.primaryField.getValue().length && this.primaryField.verify(passive)) + (this.secondaryField.getValue().length && this.secondaryField.verify(passive))) == 2;

		if (pass && value != secondaryValue) {
			window.log("confirm-password-parent-"+this.properties.distinguisher, "Fields do not match", true);
			this.markError(this.properties.errors.confirmationMismatch, passive);

			pass = false;
		}

		if (pass) {
			window.log("confirm-password-parent-"+this.properties.distinguisher, "Verification successful");
		}

		return pass;
	}
}
