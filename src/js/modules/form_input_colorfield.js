class ColorField extends HTMLElement {
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
		    var $$b = document.createElement('div');
		    $$b.setAttribute('class', 'color-field col s12');
		    $$a.appendChild($$b);
		    var $$c = this.colorBlock = document.createElement('div');
		    $$c.setAttribute('class', 'chosen-color btn');
		    $$c.setStyles({ backgroundColor: this.properties.valueIsPrefilled ? '#' + this.properties.value : getComputedStyle(document.documentElement).getPropertyValue('--main-color').trim() });
		    $$b.appendChild($$c);
		    var $$d = document.createElement('div');
		    $$d.setAttribute('class', 'color-input-wrapper');
		    $$b.appendChild($$d);
		    var $$e = document.createElement('div');
		    $$e.setAttribute('class', 'input-field col s12');
		    $$d.appendChild($$e);
		    var $$f = this.element = document.createElement('input');
		    $$f.readonly = 'readonly';
		    $$f.disabled = 'disabled';
		    $$f.type = 'text';
		    $$f.setAttribute('class', 'active');
		    $$f.id = this.properties.formDistinguisher + '-input-' + this.properties.distinguisher;
		    $$f.setAttribute('autocomplete', 'off');
		    $$f.value = this.properties.valueIsPrefilled ? '#' + this.properties.value : getComputedStyle(document.documentElement).getPropertyValue('--main-color').trim();
		    $$e.appendChild($$f);
			var $$g = this.label = new FormLabel(this.properties).children[0];
			$$g.classList.add("active");
			$$e.appendChild($$g);
			var $$h = this.helperText = new FormLabelHelperSpan(this.properties).children[0];
			$$e.appendChild($$h);
			var $$i = this.modal = document.createElement('div');
			$$i.setAttribute('class', 'color-picker-modal modal bottom-sheet');
			$$a.appendChild($$i);
			var $$j = document.createElement('div');
			$$j.setAttribute('class', 'modal-content');
			$$i.appendChild($$j);
			var $$k = document.createElement('h3');
			$$j.appendChild($$k);
			var $$l = document.createTextNode('Color');
			$$k.appendChild($$l);
			var $$m = document.createElement('h5');
			$$j.appendChild($$m);
			var $$n = document.createTextNode('Choose a color');
			$$m.appendChild($$n);
			var $$o = this.modalSwatchContainer = document.createElement('div');
			$$o.setAttribute('class', 'row');
			$$j.appendChild($$o);
			$$o.appendChildren(Object.keys(this.properties.colorMap).map((key) => {
				var $$q = document.createElement('div');
				$$q.setAttribute('data-category-colors', this.properties.colorMap[key]);
				$$q.setStyles({ backgroundColor: this.properties.valueIsPrefilled ? '#' + this.properties.value : getComputedStyle(document.documentElement).getPropertyValue('--main-color').trim() });
				$$q.setAttribute('class', 'color-swatch col l2 m3 s12');
				return $$q;
			}));
			return $$a;
		})());

		this.modal = window.M.Modal.init(this.modal);

		this.addEventListener("click", this.open.bind(this), {passive: true});
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
		return this.element.value.substr(1);
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

		if (value.length) {
			if (!(new RegExp('^[a-f0-9]{6}$')).test(value)) {
				window.log(this.properties.distinguisher, "Pattern ^[a-f0-9]{6}$ (hardcoded into class) does not match value "+value, true);
				this.markError(this.properties.errors.invalidColor, passive);
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

	/**
	 * Helper function for when a color is selected
	 */
	selectColor(hex) {
		window.log(this.properties.distinguisher, "Changed color to #"+hex)

		this.colorBlock.style.backgroundColor = "#"+hex;
		this.element.value = "#"+hex;

		document.documentElement.style.setProperty("--main-color", "#"+hex);
		document.documentElement.style.setProperty("--main-color-quarter-alpha", "#"+hex+"44");
	}

	/**
	 * Open the modal/picker
	 */
	open(event) {
		if (this.modal.el.contains(event.target)) {
			window.log(this.properties.distinguisher, "Click was inside the modal – not trying to open");
			return;
		}

		if (event.target.classList.contains("modal-overlay")) {
			window.log(this.properties.distinguisher, "Click was on the modal overlay – not trying to open");
			return;
		}

		if (this.modal.isOpen) {
			window.log(this.properties.distinguisher, "Not opening modal as the modal already seems to be open");
			return;
		}

		window.log(this.properties.distinguisher, "Opening modal");

		this.modalSwatchContainer.innerHTML = "";

		// remove stray event listeners
		var tempModalSwatchContainer = this.modalSwatchContainer.cloneNode(false);
		this.modalSwatchContainer.parentNode.replaceChild(tempModalSwatchContainer, this.modalSwatchContainer);
		this.modalSwatchContainer = tempModalSwatchContainer;

		this.modalSwatchContainer.appendChildren(Object.keys(this.properties.colorMap).map((key) => {
			var $$q = document.createElement('div');
			$$q.setAttribute('data-category-colors', this.properties.colorMap[key]);
			$$q.setAttribute('data-color', key);
			$$q.setStyles({ backgroundColor: "#"+key });
			$$q.setAttribute('class', 'color-swatch col l2 m3 s12');
			return $$q;
		}));

		var secondSwatchClickHandler = (event) => {
			if (!event.target.classList.contains("color-swatch")) {
				window.log(this.properties.distinguisher, "Got a modal click, but not on a swatch :(");
				this.modalSwatchContainer.addEventListener(secondSwatchClickHandler, { once: true });
				return;
			}

			var hex = event.target.getAttribute("data-color");

			this.selectColor(hex);

			this.modal.close();
		};

		var initialSwatchClickHandler = (event) => {
			if (!event.target.classList.contains("color-swatch")) {
				window.log(this.properties.distinguisher, "Got a modal click, but not on a swatch :(");
				this.modalSwatchContainer.addEventListener(initialSwatchClickHandler, { once: true });
				return;
			}

			var hex = event.target.getAttribute("data-color");

			this.selectColor(hex);

			window.log(this.properties.distinguisher, "Junking current swatches and creating array for this color");

			this.modalSwatchContainer.innerHTML = "";

			this.modalSwatchContainer.appendChildren(this.properties.colorMap[hex].map((val) => {
				var $$q = document.createElement('div');
				$$q.setAttribute('data-color', val);
				$$q.setStyles({ backgroundColor: "#"+val });
				$$q.setAttribute('class', 'color-swatch col l2 m3 s12');
				return $$q;
			}));

			window.log(this.properties.distinguisher, "Attaching stage 2 event listener");

			this.modalSwatchContainer.addEventListener("click", secondSwatchClickHandler, { once: true });
		};

		this.modalSwatchContainer.addEventListener("click", initialSwatchClickHandler, { once: true });

		this.modal.open();
	}
}
