"use strict";

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var FlashMessage =
/*#__PURE__*/
function () {
  function FlashMessage() {
    _classCallCheck(this, FlashMessage);

    this.flash_message = '';
    this.messages = [];
  }

  _createClass(FlashMessage, [{
    key: "setMessages",
    value: function setMessages() {
      var messages = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : [];
      this.messages = messages;
      return this;
    }
  }, {
    key: "addMessage",
    value: function addMessage(message) {
      this.messages = this.messages.push(message);
      return this;
    }
  }, {
    key: "execute",
    value: function execute() {
      var quick_message = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
      var messages = quick_message ? quick_message : this.messages;
      if (!Array.isArray(messages)) messages = [messages];
      var _iteratorNormalCompletion = true;
      var _didIteratorError = false;
      var _iteratorError = undefined;

      try {
        for (var _iterator = messages[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
          var message = _step.value;
          this.flash_message += "<div class='flash_message flash_message_".concat(message.type, "'>").concat(message.message, "</div>");
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

      document.querySelector("#msg").innerHTML = this.flash_message;
      this.scrollToSmoothly(0, 10);
    }
  }, {
    key: "scrollToSmoothly",
    value: function scrollToSmoothly(pos, time) {
      var currentPos = window.scrollY || window.screenTop;

      if (currentPos < pos) {
        var t = 10;

        var _loop = function _loop(i) {
          t += 10;
          setTimeout(function () {
            window.scrollTo(0, i);
          }, t / 2);
        };

        for (var i = currentPos; i <= pos; i += 10) {
          _loop(i);
        }
      } else {
        time = time || 2;
        var i = currentPos;
        var x;
        x = setInterval(function () {
          window.scrollTo(0, i);
          i -= 10;

          if (i <= pos) {
            clearInterval(x);
          }
        }, time);
      }
    }
  }]);

  return FlashMessage;
}();

window.FlashMessage = FlashMessage;