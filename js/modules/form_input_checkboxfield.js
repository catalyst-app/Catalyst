class CheckboxField extends HTMLElement {
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
			var $$a = document.createElement('p');
			$$a.setAttribute('class', this.properties.size + ' small-margin');
			var $$b = document.createElement('label');
			$$b.setAttribute('for', this.properties.formDistinguisher + '-input-' + this.properties.distinguisher);
			$$a.appendChild($$b);
			var $$c = this.element = document.createElement('input');
			$$c.type = 'checkbox';
			$$c.id = this.properties.formDistinguisher + '-input-' + this.properties.distinguisher;
			$$c.setAttribute('class', 'filled-in');
			$$c.setAttribute('autocomplete', 'off');
			$$c.setAttribute('class', 'filled-in');
			$$c.checked = this.properties.valueIsPrefilled ? this.properties.value : false;
			$$c.required = this.properties.required;
			$$b.appendChild($$c);
			var $$d = document.createElement('span');
			$$d.setAttribute('class', 'raw-inline-markdown');
			$$b.appendChild($$d);
			$$d.appendChildren(this.properties.label);
			renderMarkdownArea($$d);
			var $$f = document.createElement('span');
			$$f.setAttribute('class', 'red-text' + (this.properties.required ? '' : ' hide'));
			$$d.appendChild($$f);
			var $$g = document.createTextNode('\xA0*');
			$$f.appendChild($$g);
			var $$h = this.errorSpan = document.createElement('span');
			$$h.setAttribute('class', 'hide red-text');
			$$d.appendChild($$h);
			var $$i = document.createTextNode('(error text)');
			$$h.appendChild($$i);
			return $$a;
		})());

		this.addEventListener("change", this.verify.bind(this, true), {passive: true});
	}

	/**
	 * @param string errorMessage
	 * @param bool passive
	 */
	markError(errorMessage, passive) {
		window.log(this.properties.distinguisher, "Marking with error message "+errorMessage, true);

		this.element.classList.add("invalid", "marked-invalid");
		
		this.errorSpan.innerText = '\xA0(' + errorMessage + ')';
		this.errorSpan.classList.remove("hide");

		if (!passive) {
			M.escapeToast(errorMessage);
			this.element.focus();
		}
	}

	/**
	 * @return bool
	 */
	getValue() {
		return this.element.checked;
	}

	/**
	 * The value to actually be sent to the server
	 * @return string
	 */
	getAggregationValue() {
		// needed for stringification
		return this.getValue() ? "true" : "false";
	}

	/**
	 * @param bool passive If the form is actively verifying the content (and thus toasts/etc should show) or
	 *     false if verify is being called from input
	 * @return bool
	 */
	verify(passive=false) {
		let value = this.getValue();
		window.log(this.properties.distinguisher, "Verifying with value "+JSON.stringify(value));

		if (!value) {
			if (this.properties.required) {
				window.log(this.properties.distinguisher, "Required but not checked", true);
				this.markError(this.properties.errors.requiredButMissing, passive);
				return false;
			}
		}
		window.log(this.properties.distinguisher, "Verification successful");

		this.element.classList.remove("invalid", "marked-invalid");
		this.errorSpan.classList.add("hide");

		return true;
	}
}
