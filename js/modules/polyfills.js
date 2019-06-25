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

// https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/forEach#Polyfill
if (!Array.prototype.forEach) {
  Array.prototype.forEach = function(callback/*, thisArg*/) {
    var T, k;

    if (this == null) {
      throw new TypeError('this is null or not defined');
    }

    // 1. Let O be the result of calling toObject() passing the
    // |this| value as the argument.
    var O = Object(this);

    // 2. Let lenValue be the result of calling the Get() internal
    // method of O with the argument "length".
    // 3. Let len be toUint32(lenValue).
    var len = O.length >>> 0;

    // 4. If isCallable(callback) is false, throw a TypeError exception. 
    // See: http://es5.github.com/#x9.11
    if (typeof callback !== 'function') {
      throw new TypeError(callback + ' is not a function');
    }

    // 5. If thisArg was supplied, let T be thisArg; else let
    // T be undefined.
    if (arguments.length > 1) {
      T = arguments[1];
    }

    // 6. Let k be 0.
    k = 0;

    // 7. Repeat while k < len.
    while (k < len) {
      var kValue;

      // a. Let Pk be ToString(k).
      //    This is implicit for LHS operands of the in operator.
      // b. Let kPresent be the result of calling the HasProperty
      //    internal method of O with argument Pk.
      //    This step can be combined with c.
      // c. If kPresent is true, then
      if (k in O) {

        // i. Let kValue be the result of calling the Get internal
        // method of O with argument Pk.
        kValue = O[k];

        // ii. Call the Call internal method of callback with T as
        // the this value and argument list containing kValue, k, and O.
        callback.call(T, kValue, k, O);
      }
      // d. Increase k by 1.
      k++;
    }
    // 8. return undefined.
  };
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

