<?php
header("Content-Type: application/javascript; charset=UTF-8", true);
?>
// https://tc39.github.io/ecma262/#sec-array.prototype.includes
Object.defineProperty(Array.prototype, 'includes', {
	value: function(searchElement, fromIndex) {
		if (this == null) {
			throw new TypeError('"this" is null or not defined');
		}
  
		// 1. Let O be ? ToObject(this value).
		var o = Object(this);

		// 2. Let len be ? ToLength(? Get(O, "length")).
		var len = o.length >>> 0;
  
		// 3. If len is 0, return false.
		if (len === 0) {
			return false;
		}
  
		// 4. Let n be ? ToInteger(fromIndex).
		//    (If fromIndex is undefined, this step produces the value 0.)
		var n = fromIndex | 0;
  
		// 5. If n ≥ 0, then
		//  a. Let k be n.
		// 6. Else n < 0,
		//  a. Let k be len + n.
		//  b. If k < 0, let k be 0.
		var k = Math.max(n >= 0 ? n : len - Math.abs(n), 0);
  
		function sameValueZero(x, y) {
			return x == y || (typeof x === 'number' && typeof y === 'number' && isNaN(x) && isNaN(y));
		}
  
		// 7. Repeat, while k < len
		while (k < len) {
			// a. Let elementK be the result of ? Get(O, ! ToString(k)).
			// b. If SameValueZero(searchElement, elementK) is true, return true.
			if (sameValueZero(o[k], searchElement)) {
				return true;
			}
		  // c. Increase k by 1. 
		  k++;
		}
  
		// 8. Return false
		return false;
	}
});

// https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/startsWith#Polyfill
if (!String.prototype.startsWith) {
	Object.defineProperty(String.prototype, 'startsWith', {
		value: function(search, pos) {
			pos = !pos || pos < 0 ? 0 : +pos;
			return this.substring(pos, pos + search.length) === search;
		}
	});
}

// Adapted from https://github.com/treycordova/nativejsx
// used for nativejsx-generated code
if (typeof HTMLElement.prototype.appendChildren !== 'function') {
  HTMLElement.prototype.appendChildren = function (children) {
    children = Array.isArray(children) ? children : [children];
    children.forEach(function (child) {
      if (child instanceof HTMLElement) {
        this.appendChild(child);
      } else if (child || typeof child === 'string') {
        this.appendChild(document.createTextNode(child.toString()));
      }
    }.bind(this));
  };
}
if (typeof HTMLElement.prototype.setAttributes !== 'function') {
  HTMLElement.prototype.setAttributes = function (attributes) {
    var isPlainObject = Object.prototype.toString.call(attributes) === '[object Object]' &&
      typeof attributes.constructor === 'function' &&
      Object.prototype.toString.call(attributes.constructor.prototype) === '[object Object]' &&
      attributes.constructor.prototype.hasOwnProperty('isPrototypeOf');

    if (isPlainObject) {
      for (var key in attributes) {
        this.setAttribute(key, attributes[key]);
      }
    } else {
      throw new DOMException('Failed to execute \'setAttributes\' on \'Element\': ' + Object.prototype.toString.call(attributes) + ' is not a plain object.');
    }
  };
}
if (typeof HTMLElement.prototype.setStyles !== 'function') {
  HTMLElement.prototype.setStyles = function (styles) {
    for (var style in styles) this.style[style] = styles[style];
  };
}

