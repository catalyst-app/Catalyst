class FormLabelHelperSpan extends HTMLElement {
	constructor(properties) {
		super();

		// decide if the HTML was created beforehand (e.g. from server) or without attributed (e.g. document.createElement)
		if (properties != undefined) {
			this.properties = properties;
		} else {
			this.properties = JSON.parse(this.getAttribute("data-properties"));
		}

		window.log(this.constructor.name, "Constructing a label helper span object to represent "+this.properties.distinguisher);

		this.appendChild((() => {
			var $$a = document.createElement('span');
			$$a.setAttribute('for', this.properties.formDistinguisher + '-input-' + this.properties.distinguisher + '-element');
			$$a.setAttribute('class', 'helper-text');
			$$a.appendChildren(this.properties.helperText);
			return $$a;
		}).call(this));
	}

	connectedCallback() {
		throw "FormLabel should NEVER be added to the DOM.  It is for templating only.  If you wish to use it, create an instance and add .children[0] (the standard label element itself) to your DOM.";
	}
}

window.customElements.define("form-label-helper-span", FormLabelHelperSpan);
