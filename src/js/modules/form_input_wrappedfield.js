class WrappedField extends HTMLElement {
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
			$$a.setAttribute('class', this.properties._wrapperClasses);
			var $$b = this.field = new (window.customElements.get(this.properties._wrappedComponentName))(this.properties);
			$$a.appendChild($$b);
			return $$a;
		})());
	}

	/**
	 * @param string errorMessage
	 * @param bool passive
	 */
	markError(errorMessage, passive) {
		this.field.markError(errorMessage, passive);
	}

	getValue() {
		return this.field.getValue();
	}

	/**
	 * The value to actually be sent to the server
	 * @return string
	 */
	getAggregationValue() {
		return this.field.getAggregationValue();
	}

	/**
	 * @param bool passive If the form is actively verifying the content (and thus toasts/etc should show) or
	 *	 false if verify is being called from input
	 * @return bool
	 */
	verify(passive=false) {
		return this.field.verify(passive);
	}
}
