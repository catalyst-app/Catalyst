class CaptchaField extends HTMLElement {
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
			var $$a = this.element = document.createElement('div');
			$$a.id = this.properties.formDistinguisher + '-input-' + this.properties.distinguisher;
			$$a.setAttribute('class', 'g-recaptcha col s12 form-field');
			return $$a;
		})());

		setTimeout(() => {
			window.log(this.properties.distinguisher, "Actually rendering CAPTCHA, deferred from onload");

			if (window.devMode) {
				console.time("Render CAPTCHA "+this.properties.distinguisher);
			}
			this.widgetId = grecaptcha.render(this.element, {
				"sitekey": this.properties.siteKey,
				"callback": this.successCallback.bind(this),
				"expired-callback": this.expiredCallback.bind(this),
				"error-callback": this.errorCallback.bind(this)
			});
			if (window.devMode) {
				console.timeEnd("Render CAPTCHA "+this.properties.distinguisher);
			}
		}, 1000);
	}

	/**
	 * @param string errorMessage
	 * @param bool passive
	 */
	markError(errorMessage, passive) {
		window.log(this.properties.distinguisher, "Marking with error message "+errorMessage, true);

		this.element.classList.remove("valid");
		this.element.classList.add("invalid", "marked-invalid");
		this.element.setAttribute("data-error", errorMessage);

		grecaptcha.reset(this.widgetId);

		if (!passive) {
			M.escapeToast(errorMessage);
			this.element.focus();
		}
	}

	/**
	 * @return string
	 */
	getValue() {
		return grecaptcha.getResponse(this.widgetId);
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
		let value = this.getValue();
		window.log(this.properties.distinguisher, "Verifying with value "+JSON.stringify(value));

		if (!value.length) {
			window.log(this.properties.distinguisher, "CAPTCHA value is missing or invalid (no way to tell)", true);
			this.markError(this.properties.errors.requiredButMissing, passive);
			return false;
		}

		window.log(this.properties.distinguisher, "Verification successful");

		this.element.classList.add("valid");
		this.element.classList.remove("invalid");

		return true;
	}

	/**
	 * The callback grecaptcha executes on successful solve
	 */
	successCallback() {
		return new Promise((resolve, reject) => {
			window.log(this.properties.distinguisher, "CAPTCHA solved successfully");
			this.element.classList.add("valid");
			this.element.classList.remove("invalid");

			return resolve();
		});
	}

	/**
	 * The callback grecaptcha executes when the recaptcha expires
	 * (Happens after a short time in the background)
	 */
	expiredCallback() {
		return new Promise((resolve, reject) => {
			this.markError(this.properties.errors.expired), true;

			return resolve();
		});
	}

	/**
	 * The callback grecaptcha executes when the recaptcha has an unknown error
	 * (Docs are not clear; network error and other stuff?)
	 */
	errorCallback() {
		return new Promise((resolve, reject) => {
			this.markError(this.properties.errors.unknownError);

			return resolve();
		});
	}
}
