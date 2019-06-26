class StaticHTMLField extends HTMLElement {
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

		this.id = this.properties.formDistinguisher + '-input-' + this.properties.distinguisher;
		this.innerHTML = this.properties.html;
	}

	/**
	 * @param string errorMessage
	 * @param bool passive
	 */
	markError(errorMessage, passive) {
		window.log(this.properties.distinguisher, "Marking with error message "+errorMessage, true);

		if (!passive) {
			M.escapeToast(errorMessage);
			throw 'StaticHTMLField '+this.properties.distinguisher+' has error: '+errorMessage;
		}
	}

	/**
	 * @return string
	 */
	getValue() {
		return "";
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
	 *     false if verify is being called from input
	 * @return bool
	 */
	verify(passive=false) {
		window.log(this.properties.distinguisher, "StaticHTMLField, not verifying anything");
		window.log(this.properties.distinguisher, "Verification successful");
		return true;
	}
}
