class MarkdownField extends HTMLElement {
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
			var firstMarkdownField = true;
			if (this.parentNode.getElementsByTagName('markdown-field') > 1) {
				if (this.parentNode.getElementsByTagName('markdown-field')[0] != this) {
					var firstMarkdownField = false;
				}
			}
			return (() => {
				var $$a = document.createElement('div');
				var $$b = document.createElement('p');
				$$b.setAttribute('class', 'col s12 no-bottom-margin' + (firstMarkdownField ? '' : ' hide'));
				$$a.appendChild($$b);
				var $$c = document.createTextNode('Catalyst supports a modified version of Markdown in fields like this.  Please read ');
				$$b.appendChild($$c);
				var $$d = document.createElement('a');
				$$d.setAttribute('href', document.body.parentNode.getAttribute('data-rootdir') + 'Markdown');
				$$b.appendChild($$d);
				var $$e = document.createTextNode('this page');
				$$d.appendChild($$e);
				var $$f = document.createTextNode(' for help.');
				$$b.appendChild($$f);
				var $$g = document.createElement('div');
				$$g.setAttribute('class', 'col s12');
				$$a.appendChild($$g);
				var $$h = document.createElement('div');
				$$h.setAttribute('class', 'row');
				$$g.appendChild($$h);
				var $$i = document.createElement('div');
				$$i.setAttribute('class', 'input-field col s12 m6');
				$$h.appendChild($$i);
				var $$j = this.element = document.createElement('textarea');
				$$j.setAttribute('autocomplete', this.properties.autocomplete);
				$$j.setAttribute('class', 'markdown-field materialize-textarea form-field');
				$$j.required = this.properties.required;
				$$j.autofocus = this.properties.primary;
				$$j.id = this.properties.formDistinguisher + '-input-' + this.properties.distinguisher;
				$$j.name = this.properties.distinguisher;
				$$i.appendChild($$j);
				$$j.appendChildren(this.properties.value == null ? '' : this.properties.value);
				var $$l = this.label = new FormLabel(this.properties).children[0];
				$$i.appendChild($$l);
				var $$m = this.helperText = new FormLabelHelperSpan(this.properties).children[0];
				$$i.appendChild($$m);
				var $$n = this.preview = document.createElement('div');
				$$n.setAttribute('class', 'col s12 m6 markdown-target markdown-preview raw-markdown');
				$$n.id = '-preview-' + this.properties.formDistinguisher + '-input-' + this.properties.distinguisher;
				$$h.appendChild($$n);
				return $$a;
			})();
		})());

		M.textareaAutoResize(this.element);

		this.addEventListener("input", this.verify.bind(this, true), {passive: true});
		this.addEventListener("input", this.render.bind(this), {passive: true});
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

		if (this.required) {
			if (!value.length) {
				window.log(this.properties.distinguisher, "Required but empty value", true);
				this.markError(this.properties.errors.requiredButMissing, passive);
				return false;
			}
		}

		window.log(this.properties.distinguisher, "Verification successful");

		this.element.classList.remove("invalid", "marked-invalid");

		return true;
	}

	/**
	 * Render the field
	 */
	render() {
		window.log(this.properties.distinguisher, "Clearing existing render timeout");
		clearTimeout(this.timeout);

		window.log(this.properties.distinguisher, "Setting render timeout for 200ms");

		this.timeout = setTimeout((function() {
			window.log(this.properties.distinguisher, "rendering");
			this.preview.classList.replace("rendered-markdown", "raw-markdown");
			this.preview.textContent = this.getValue();

			renderMarkdownArea(this.preview);
		}).bind(this), 200);
	}
}
