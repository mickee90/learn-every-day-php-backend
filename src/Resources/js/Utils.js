"use strict";

function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance"); }

function _iterableToArray(iter) { if (Symbol.iterator in Object(iter) || Object.prototype.toString.call(iter) === "[object Arguments]") return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = new Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var Utils =
/*#__PURE__*/
function () {
  function Utils() {
    _classCallCheck(this, Utils);

    console.log('Utils');
  }

  _createClass(Utils, null, [{
    key: "validateItems",
    value: function validateItems(items) {
      return items.every(function (item) {
        return item;
      });
    }
  }, {
    key: "validateObjectItems",
    value: function validateObjectItems(items) {
      var _iteratorNormalCompletion = true;
      var _didIteratorError = false;
      var _iteratorError = undefined;

      try {
        for (var _iterator = items[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
          var item = _step.value;
          if (item.required && !item.value) return false;
        }
      } catch (err) {
        _didIteratorError = true;
        _iteratorError = err;
      } finally {
        try {
          if (!_iteratorNormalCompletion && _iterator.return != null) {
            _iterator.return();
          }
        } finally {
          if (_didIteratorError) {
            throw _iteratorError;
          }
        }
      }

      return true;
    }
  }, {
    key: "prepareInputs",
    value: function prepareInputs(inputs) {
      return _toConsumableArray(inputs).map(function (input) {
        var value = input['id'] && input['id'] === 'ck_editor' ? CKEDITOR.instances.ck_editor.getData() : input['value'];
        return {
          name: input['name'],
          value: value,
          required: input['required']
        };
      });
    }
  }, {
    key: "getInputsByForm",
    value: function getInputsByForm(form) {
      return _toConsumableArray(form).map(function (input) {
        var value = input['id'] && input['id'] === 'ck_editor' ? CKEDITOR.instances.ck_editor.getData() : input['value'];
        return {
          name: input['name'],
          value: value,
          required: input['required']
        };
      });
    }
  }, {
    key: "setStyleOnElms",
    value: function setStyleOnElms(elements, style_name, style_value) {
      elements.forEach(function (elm) {
        return elm.style[style_name] = style_value;
      });
      return this;
    }
  }, {
    key: "addClass",
    value: function addClass(element, class_name) {
      if (NodeList.prototype.isPrototypeOf(element)) {
        element.forEach(function (elem) {
          return elem.classList.add(class_name);
        });
      } else if (HTMLCollection.prototype.isPrototypeOf(element)) {
        console.log('htmlcollector');
      } else {
        element.classList.add(class_name);
      }

      return this;
    }
  }, {
    key: "removeClass",
    value: function removeClass(element, class_name) {
      if (NodeList.prototype.isPrototypeOf(element)) {
        element.forEach(function (elem) {
          return elem.classList.remove(class_name);
        });
      } else if (HTMLCollection.prototype.isPrototypeOf(element)) {
        console.log('htmlcollector');
      } else {
        element.classList.remove(class_name);
      }

      return this;
    }
  }, {
    key: "toggleClass",
    value: function toggleClass(element, class_name) {
      if (NodeList.prototype.isPrototypeOf(element)) {
        element.forEach(function (elem) {
          return elem.classList.toggle(class_name);
        });
      } else if (HTMLCollection.prototype.isPrototypeOf(element)) {
        console.log('htmlcollector');
      } else {
        element.classList.toggle(class_name);
      }

      return this;
    }
    /*
    static getInputsByForm(form) {
    	console.log('hej');
    	return [...form.elements].map((input) => {
    		let value = (input['id'] && input['id'] === 'ck_editor') ? CKEDITOR.instances.ck_editor.getData() : input['value'];
    		return {
    			name: input['name'],
    			value: value,
    			required: input['required']
    		}
    	});
    	console.log('hej1');
    }
    */

  }]);

  return Utils;
}();

window.Utils = Utils;